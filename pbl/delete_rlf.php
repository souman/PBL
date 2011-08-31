<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $CFG, $USER,$COURSE;

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // rubrics instance ID
$rlfid = optional_param('rlfid', 0, PARAM_INT);  // RLF instance ID

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
}
require_login($course);

add_to_log($course->id, "pbl", "Delete RLF", "dlt_rlf.php?id=$cm->id", "$pbl->id");
$rlf=get_record('pbl_rlf','id',$rlfid);

if($rlf->usrid !=$USER->id) {
	error("You dont have the privilage to delete this RLF");
}

else if($rlf->moduleid !=$id) {
	error("This RLF does not belongs to this particular PBL");
}

else if($rlf->courseid !=$course->id) {
	error("This RLF does not belongs to this particular course");
}

else {
	 if (!delete_records('pbl_rlf', 'id', $rlfid)) {
		error("error in deletion of RLF");
	}
    redirect("$CFG->wwwroot/mod/pbl/view.php?id=$id");
}
?>
