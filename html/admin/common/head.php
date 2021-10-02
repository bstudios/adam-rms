<?php
require_once __DIR__ . '/../../common/coreHead.php';
require_once __DIR__ . '/../../common/libs/Auth/main.php';

try {
    //session_set_cookie_params(0, '/', '.' . $_SERVER['SERVER_NAME']); //Fix for subdomain bug
    session_set_cookie_params(43200); //12hours
    session_start(); //Open up the session
} catch (Exception $e) {
    //Do Nothing
}

header("Content-Security-Policy: default-src 'none';" .
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://assets.adam-rms.com https://cdnjs.cloudflare.com https://static.cloudflareinsights.com    https://www.youtube.com https://*.ytimg.com;".
    //          We have laods of inline JS                                  Libs                                             CF Analytics   Training
    "style-src 'unsafe-inline' 'self' https://assets.adam-rms.com https://cdnjs.cloudflare.com https://fonts.googleapis.com;".
    //          We have loads of inline CSS                 Libs                        GFonts
    "font-src 'self' data: https://assets.adam-rms.com https://fonts.googleapis.com https://fonts.gstatic.com https://cdnjs.cloudflare.com;" .
    //                                               Loading in google fonts     more gfonts               Fonts from libs like fontawesome
    "manifest-src 'self' https://*.adam-rms.com;" .
    //          Show images on mobile devices like favicons
    "img-src 'self' data: blob: https://assets.adam-rms.com https://cdnjs.cloudflare.com https://*.adam-rms.com https://cloudflareinsights.com  https://*.ytimg.com https://*.backblazeb2.com;".
    //                                                    Uploads    Images from libs                 Images                CF Analytics                                            Training                Uploads
    "connect-src 'self' https://*.adam-rms.com https://sentry.io https://cloudflareinsights.com  https://*.backblazeb2.com;".
    //                  File uploads                      Error reporting     CF Analytics             File Uploads
    "frame-src https://www.youtube.com;".
    "object-src 'self' blob:;".
    //          Inline PDFs generated by the system
    "worker-src 'self' blob:;" .
    //          Use of camera
    "frame-ancestors 'self';");

$GLOBALS['AUTH'] = new bID;