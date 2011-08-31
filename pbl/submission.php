<?php
/*
 * Created on 16-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot.'/lib/grouplib.php');
$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // pbl instance ID

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

} else if ($a) {
    if (! $pbl = get_record('pbl', 'id', $a)) {
        error('Course module is incorrect');
    }
    if (! $course = get_record('course', 'id', $pbl->course)) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('pbl', $pbl->id, $course->id)) {
        error('Course Module ID was incorrect');
    }

} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$strpbls = get_string('modulenameplural', 'pbl');
$strpbl  = get_string('modulename', 'pbl');

$navlinks = array();
$navlinks[] = array('name' => $strpbls, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($pbl->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strpbl), navmenu($course, $cm));

echo '<center>';
echo 'Select the type of submission';
 $options['submission.php?id='.$id] = "Create New Submission";
     echo '<center><br /><table width=220><tr><td><div><form action="?id='.$id.'" name="addtask" id="taskform" method="post"><select name="addtaskvalue" onchange="document.addtask.action = document.addtask.addtaskvalue.options[document.addtask.addtaskvalue.selectedIndex].value;document.addtask.submit(); return true;" >';


        $options['new_submission.php?id='.$id.'&type=assignment'.'&assessmenttype=upload'] = "Advanced Uploading of Files";
        $options['new_submission.php?id='.$id.'&type=assignment'.'&assessmenttype=online'] = "Online Text";
        $options['new_submission.php?id='.$id.'&type=assignment'.'&assessmenttype=uploadsingle'] = "Upload a Single File";
        $options['new_submission.php?id='.$id.'&type=assignment'.'&assessmenttype=offline'] = "Offline Activity";

        foreach ($options as $optionkey => $optionvalue) {
            echo '<option value ="'.$optionkey.'">'.$optionvalue.'</option>';
        }

        echo '</select>'.pbl_makelocalhelplinkmain ("createsubmission", "Help with Create Submission (new window)", "pbl").'</form></div></td></tr></table></center>';
echo '</center>';
?>
