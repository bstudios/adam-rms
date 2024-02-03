<?php

/**
 * This file is used by every page. It is included in every page and contains the following:
 * - Database connection
 * - Twig setup
 * - Error handling
 * - Sentry error reporting
 * - Global functions ("bCMS" class)
 * - Config Variables 
 */
require_once(__DIR__ . '/../../vendor/autoload.php'); //Composer
require_once __DIR__ . '/libs/Config/Config.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\CloudFront\CloudFrontClient;
use Aws\Exception\AwsException;
use Twig\Extra\String\StringExtension;

//TWIG
$TWIGLOADER = new \Twig\Loader\FilesystemLoader([__DIR__ . '/../']);
if (getenv('ERRORS') == "true") {
    $TWIG = new \Twig\Environment($TWIGLOADER, array(
        'debug' => true,
        'auto_reload' => true,
        'charset' => 'utf-8'
    ));
    $TWIG->addExtension(new \Twig\Extension\DebugExtension());
} else {
    $TWIG = new \Twig\Environment($TWIGLOADER, array(
        'debug' => false,
        'auto_reload' => false,
        'cache' => '/tmp/',
        'charset' => 'utf-8'
    ));
}
$TWIG->addExtension(new StringExtension());

if (getenv('ERRORS') == "true") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

if (!$CONFIG['DEV']) {
    Sentry\init([
        'dsn' => $CONFIG['ERRORS_PROVIDERS_SENTRY'],
        'traces_sample_rate' => 0.1, //Capture 10% of pageloads for perforamnce monitoring
        'release' => $CONFIG['VERSION']['ENV'] ?: ($CONFIG['VERSION']['TAG'] . "." . $CONFIG['VERSION']['COMMIT']),
        'sample_rate' => 1.0,
    ]);
}

/* DATBASE CONNECTION */
try {
    $DBLIB = new MysqliDb([
        'host' => getenv('DB_HOSTNAME'),
        'username' => getenv('DB_USERNAME'), //CREATE INSERT SELECT UPDATE DELETE
        'password' => getenv('DB_PASSWORD'),
        'db' => getenv('DB_DATABASE'),
        'port' => getenv('DB_PORT') ?: 3306,
        //'prefix' => 'adamrms_',
        'charset' => 'utf8'
    ]);
} catch (Exception $e) {
    // TODO use twig for this
    if (getenv('ERRORS') == "true") {
        echo "Could not connect to database: " . $e->getMessage();
        exit;
    } else {
        echo "Could not connect to database";
        exit;
    }
}

$OLDENDDAYSCONFIGSSS = array(
    'VERSION' => ['ENV' => getenv('bCMS__VERSION') ? (strlen(getenv('bCMS__VERSION')) > 7 ? substr(getenv('bCMS__VERSION'), 0, 7) : getenv('bCMS__VERSION')) : false, 'COMMIT' => file_get_contents(__DIR__ . '/version/COMMIT.txt'), 'TAG' => file_get_contents(__DIR__ . '/version/TAG.txt'), "COMMITFULL" => file_get_contents(__DIR__ . '/version/COMMITFULL.txt')], //Version number is the first 7 characters of the commit hash for certain deployments, and for others there's a nice numerical tag.
    'AWS' => [
        'KEY' => getenv('bCMS__AWS_SERVER_KEY'),
        'SECRET' => getenv('bCMS__AWS_SERVER_SECRET_KEY'),
        'DEFAULTUPLOADS' => [
            'BUCKET' => getenv('bCMS__AWS_S3_BUCKET_NAME'),
            'ENDPOINT' => getenv('bCMS__AWS_S3_BUCKET_ENDPOINT'),
            'REGION' => getenv('bCMS__AWS_S3_BUCKET_REGION'),
            'CDNEndpoint' => getenv('bCMS__AWS_S3_CDN'),
        ],
        "CLOUDFRONT" => [
            "ENABLED" => getenv('bCMS__AWS_ACCOUNT_CLOUDFRONT_ENABLED') == "TRUE",
            "PRIVATEKEY" => str_replace('\n', "\n", str_replace('"', '', getenv('bCMS__AWS_ACCOUNT_PRIVATE_KEY'))),
            "KEYPAIRID" => getenv('bCMS__AWS_ACCOUNT_PRIVATE_KEY_ID')
        ]
    ],
    'ENABLE_DEV_DB_EDITOR' => (getenv('RUNNING_IN_DEVCONTAINER') == "devcontainer" ? true : false),
    'AUTH-PROVIDERS' => [
        "GOOGLE" => [
            'keys' => [
                'id' => getenv('bCMS__OAUTH__GOOGLEKEY'),
                'secret' => getenv('bCMS__OAUTH__GOOGLESECRET')
            ],
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email'
        ]
    ],
    'DEV' => (getenv('ERRORS') == "true" ? true : false),
);


$CONFIGCLASS = new Config;
$CONFIG = $CONFIGCLASS->getConfigArray();
if (count($CONFIGCLASS->CONFIG_MISSING_VALUES) > 0) {
    // Use twig for this
    throw new Exception("Missing config values: " . implode(", ", $CONFIGCLASS->CONFIG_MISSING_VALUES));
}

// Set the timezone
date_default_timezone_set($CONFIG['TIMEZONE']);

// Include the bCMS class, which contains useful functions 
require_once __DIR__ . '/libs/bCMS/bCMS.php';
$GLOBALS['bCMS'] = new bCMS;

// TODO move these functions to a class
function generateNewTag()
{
    global $DBLIB;
    //Get highest current tag
    $DBLIB->orderBy("assets_tag", "DESC");
    $DBLIB->where("assets_tag", 'A-%', 'like');
    $tag = $DBLIB->getone("assets", ["assets_tag"]);
    if ($tag) {
        if (is_numeric(str_replace("A-", "", $tag["assets_tag"]))) {
            $value = intval(str_replace("A-", "", $tag["assets_tag"])) + 1;
            if ($value <= 9999) $value = sprintf('%04d', $value);
            return "A-" . $value;
        } else return "A-0001";
    } else return "A-0001";
}
function assetFlagsAndBlocks($assetid)
{
    global $DBLIB;
    $DBLIB->where("maintenanceJobs.maintenanceJobs_deleted", 0);
    $DBLIB->where("(maintenanceJobs.maintenanceJobs_blockAssets = 1 OR maintenanceJobs.maintenanceJobs_flagAssets = 1)");
    $DBLIB->where("(FIND_IN_SET(" . $assetid . ", maintenanceJobs.maintenanceJobs_assets) > 0)");
    $DBLIB->join("maintenanceJobsStatuses", "maintenanceJobs.maintenanceJobsStatuses_id=maintenanceJobsStatuses.maintenanceJobsStatuses_id", "LEFT");
    //$DBLIB->join("users AS userCreator", "userCreator.users_userid=maintenanceJobs.maintenanceJobs_user_creator", "LEFT");
    //$DBLIB->join("users AS userAssigned", "userAssigned.users_userid=maintenanceJobs.maintenanceJobs_user_assignedTo", "LEFT");
    $DBLIB->orderBy("maintenanceJobs.maintenanceJobs_priority", "DESC");
    $jobs = $DBLIB->get('maintenanceJobs', null, ["maintenanceJobs.maintenanceJobs_id", "maintenanceJobs.maintenanceJobs_faultDescription", "maintenanceJobs.maintenanceJobs_title", "maintenanceJobs.maintenanceJobs_faultDescription", "maintenanceJobs.maintenanceJobs_flagAssets", "maintenanceJobs.maintenanceJobs_blockAssets", "maintenanceJobsStatuses.maintenanceJobsStatuses_name"]);
    $return = ["BLOCK" => [], "FLAG" => [], "COUNT" => ["BLOCK" => 0, "FLAG" => 0]];
    if (!$jobs) return $return;
    foreach ($jobs as $job) {
        if ($job["maintenanceJobs_blockAssets"] == 1) {
            $return['BLOCK'][] = $job;
            $return['COUNT']['BLOCK'] += 1;
        }
        if ($job["maintenanceJobs_flagAssets"] == 1) {
            $return['FLAG'][] = $job;
            $return['COUNT']['FLAG'] += 1;
        }
    }
    return $return;
}
function assetLatestScan($assetid)
{
    if ($assetid == null) return false;
    global $DBLIB;
    $DBLIB->orderBy("assetsBarcodesScans.assetsBarcodesScans_timestamp", "DESC");
    $DBLIB->where("assetsBarcodes.assets_id", $assetid);
    $DBLIB->where("assetsBarcodes.assetsBarcodes_deleted", 0);
    $DBLIB->join("assetsBarcodes", "assetsBarcodes.assetsBarcodes_id=assetsBarcodesScans.assetsBarcodes_id");
    $DBLIB->join("locationsBarcodes", "locationsBarcodes.locationsBarcodes_id=assetsBarcodesScans.locationsBarcodes_id", "LEFT");
    $DBLIB->join("assets", "assets.assets_id=assetsBarcodesScans.location_assets_id", "LEFT");
    $DBLIB->join("assetTypes", "assets.assetTypes_id=assetTypes.assetTypes_id", "LEFT");
    $DBLIB->join("locations", "locations.locations_id=locationsBarcodes.locations_id", "LEFT");
    $DBLIB->join("users", "users.users_userid=assetsBarcodesScans.users_userid");
    return $DBLIB->getone("assetsBarcodesScans", ["assetsBarcodesScans.*", "users.users_name1", "users.users_name2", "locations.locations_name", "locations.locations_id", "assets.assetTypes_id", "assetTypes.assetTypes_name"]);
}



require_once __DIR__ . '/libs/bCMS/projectFinance.php';

// Setup the "PAGEDATA" array which is used by Twig
$PAGEDATA = array('CONFIG' => $CONFIG);

// Setup the "MAINTENANCEJOBPRIORITIES" array which is used by Twig
$GLOBALS['MAINTENANCEJOBPRIORITIES'] = [
    1 => ["class" => "danger", "id" => 1, "text" => "Emergency"],
    2 => ["class" => "danger", "id" => 2, "text" => "Business Critical"],
    3 => ["class" => "danger", "id" => 3, "text" => "Urgent"],
    4 => ["class" => "danger", "id" => 4, "text" => "Routine - High"],
    5 => ["class" => "warning", "id" => 5, "text" => "Routine - Medium", "default" => true],
    6 => ["class" => "warning", "id" => 6, "text" => "Routine - Low"],
    7 => ["class" => "warning", "id" => 7, "text" => "Monthly-cycle Maintenance"],
    8 => ["class" => "success", "id" => 8, "text" => "Annual-cycle Maintenance"],
    9 => ["class" => "success", "id" => 9, "text" => "Long Term"],
    10 => ["class" => "info", "id" => 10, "text" => "Log only"]
];
$PAGEDATA['MAINTENANCEJOBPRIORITIES'] = $GLOBALS['MAINTENANCEJOBPRIORITIES'];


// Include Twig Extensions
require_once __DIR__ . '/libs/twigExtensions.php';

// Try to open up a session cookie
try {
    session_set_cookie_params(43200); //12hours
    session_start(); //Open up the session
} catch (Exception $e) {
    //Do Nothing
}

// Include the content security policy
require_once __DIR__ . '/libs/csp.php';

// Include the Auth class
require_once __DIR__ . '/libs/Auth/main.php';
$GLOBALS['AUTH'] = new bID;
