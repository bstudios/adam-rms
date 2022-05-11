<?php
require_once __DIR__ . '/../../apiHeadSecure.php';

if (!$AUTH->instancePermissionCheck(49)) finish(false);

$DBLIB->where("crewAssignments_id", $_POST['crewAssignments_id']);
$DBLIB->where("crewAssignments_deleted", 0);
$DBLIB->join("projects", "crewAssignments.projects_id=projects.projects_id", "LEFT");
$DBLIB->where("projects.instances_id", $AUTH->data['instance']['instances_id']);
$DBLIB->where("projects.projects_deleted", 0);
$assignment = $DBLIB->getone("crewAssignments", ["crewAssignments.crewAssignments_id", "crewAssignments.users_userid", "crewAssignments.crewAssignments_role", "projects.projects_id", "projects.projects_name"]);
if (!$assignment) finish(false);
else {
    $bCMS->auditLog("EDIT-CREW", "crewAssignments", $assignment['crewAssignments_id'], $AUTH->data['users_userid'],null, $assignment['projects_id']);
    if ($assignment["users_userid"] and $_POST['crewAssignments_role'] != $assignment['crewAssignments_role']) { //Only email if the role name has changed, not just role comments
        notify(20, $assignment["users_userid"], $AUTH->data['instance']['instances_id'], $AUTH->data['users_name1'] . " " . $AUTH->data['users_name2'] . " changed your role on the event " . $assignment['projects_name'] . " to be " . $bCMS->sanitizeString($_POST['crewAssignments_role']));
    }
    $DBLIB->where("crewAssignments_id", $assignment['crewAssignments_id']);
    if ($DBLIB->update("crewAssignments", ["crewAssignments_role" => $_POST['crewAssignments_role'], "crewAssignments_comment" => $_POST['crewAssignments_comment']])) finish(true);
    else finish(false);
}