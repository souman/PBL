<?php
/*
 * Created on 16-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
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
$strpbls = get_string('modulenameplural', 'pbl');
$strpbl  = get_string('modulename', 'pbl');

$navlinks = array();
$navlinks[] = array('name' => format_string($pbl->name), 'link' => "view.php?id=$id", 'type' => 'activity');
$navlinks[] = array('name' => 'Report', 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strpbl), navmenu($course, $cm));

 if(has_capability('mod/pbl:view_report', $context)) {
 	$usergroups=get_records('groups_members','userid',$USER->id);
 	foreach($usergroups as $usergroup) {
 		$groupnames=get_record('groups','id',$usergroup->groupid);
 		if($groupnames->courseid==$course->id) {
			echo "<a href='show_report.php?id=$id&groupid=$groupnames->id&userid=0'>Click to view report for $groupnames->name</a>";
			echo "<br/>";
 		}

 	}
 }
 print_footer($course);
?>
