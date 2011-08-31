<?php
/*
 * Created on 19-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);// course_module ID, or
$rlfid = required_param('rlfid',PARAM_INT);
$action= required_param('action',PARAM_TEXT);
//echo $action;
if ($id) {
    if (! $cm = get_coursemodule_from_id('pbl', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = get_record('course', 'id', $cm->course)) {
        error('Course is misconfigured');
    }

    if (! $pbl = get_record('pbl', 'id', $cm->instance)) {
        error('Course module is incorrect');
    }
} else {
    error('You must specify a course_module ID or an instance ID');
}
require_login($course, true, $cm);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$read=record_exists('pbl_rlf_user','rlfid',$rlfid,'userid',$USER->id);
//echo $read;
if(has_capability('mod/pbl:addRLF', $context))  {
	if(!$read && $action=='read'){

		$rlf_mark->userid=$USER->id;
		$rlf_mark->rlfid=$rlfid;
		//echo $rlf_mark->rlfid;
		//echo $rlf_mark->userid;
		if(!insert_record('pbl_rlf_user',$rlf_mark)) {
		error("Error inserting into rlf_user table");
		}
		redirect("$CFG->wwwroot/mod/pbl/view_rlf.php?id=$id&rlfid=$rlfid");
	}
	else if($read && $action=='unread') {
		if(!delete_records('pbl_rlf_user','userid',$USER->id,'rlfid',$rlfid)) {
		error("Error deleting in rlf_user table");
		}
		redirect("$CFG->wwwroot/mod/pbl/view_rlf.php?id=$id&rlfid=$rlfid");

	}
}
?>
