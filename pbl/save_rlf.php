<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $CFG;
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once("$CFG->libdir/formslib.php");
$userid = $_POST["user_id"];
$rlf->userid = $_POST["user_id"];
$rlf->courseid = $_POST["course_id"];
$rlf->description = $_POST["name"];
$rlf->pblid = $_POST["module_id"];
//$rlf->pbl = $_POST["pbl_id"];
//echo $rlf->pbl;
if($_POST["link"]=='') {
    $rlf->link=NULL;
}
else {
    $rlf->link = $_POST["link"];
}
$x = $rlf->pblid;
$groupids = get_records('groups_members','userid',$userid);

if(!insert_record('pbl_rlf',$rlf)) {
        error("error inserting data into RLF table");
}

redirect("$CFG->wwwroot/mod/pbl/view.php?id=$x")
?>
