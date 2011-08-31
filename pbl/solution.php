<?php
/*
 * Created on 15-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 *
 *
 *
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
//require_once(dirname(__FILE__).'/filelib.php');
global $CFG, $USER,$COURSE;
require_once("$CFG->libdir/filelib.php");
$id = required_param('id', PARAM_INT); //
$solutionid = required_param('solutionid', PARAM_INT); //
$type  = required_param('type', PARAM_TEXT);  //

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
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
if($type=='delete') {
	if(has_capability('mod/pbl:delete_solution', $context)) {
		$solution=get_record('pbl_solution','id',$solutionid);
		if($solution->userid==$USER->id) {
			if($solution->file_location!='') {
				echo 'here';
				fulldelete($solution->file_location);
			}
			delete_records('pbl_solution','id',$solutionid);
		}
	}
	redirect("$CFG->wwwroot/mod/pbl/view.php?id=$id");
}
if($type=='view') {
	if(has_capability('mod/pbl:view_solution', $context)) {
		$strpbls = get_string('modulenameplural', 'pbl');
		$strpbl  = get_string('modulename', 'pbl');
		$navlinks = array();
		$navlinks[] = array('name' => $strpbls, 'link' => "index.php?id=$course->id", 'type' => 'activity');
		$navlinks[] = array('name' => format_string($pbl->name), 'link' => '', 'type' => 'activityinstance');

		$navigation = build_navigation($navlinks);
		print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strpbl), navmenu($course, $cm));
		$solution=get_record('pbl_solution','id',$solutionid);
		echo '<b>Solution Name:</b><br/>';
		echo $solution->name;
		echo '<br/><br/>';
		echo '<b>Solution:</b><br/>';
		echo $solution->definition;
		echo '<br/><br/>';
		if($solution->file_location!='') {
			echo '<b>Attached File</b>';
			echo '<br/>';
			echo "<a href='file.php?id=$id&solutionid=$solutionid'>$solution->file_name</a>";
		}

	}
}

?>
