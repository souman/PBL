<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // pbl instance ID
$moduleid = optional_param('moduleid', 0, PARAM_INT);  // discuss module ID

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

$strpbls = get_string('modulenameplural', 'pbl');
$strpbl  = get_string('modulename', 'pbl');

$navlinks = array();
$navlinks[] = array('name' => $strpbls, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($pbl->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strpbl), navmenu($course, $cm));

require_login($course, true, $cm);
$sesskey=sesskey();
echo "<form action='' method='post'>";
echo "<center>";
echo "<b>Are you sure want to delete this discussion???<br/><br/></b>";
echo "<input type='submit' name='delete_yes' value='YES' id='mysubmit'>";
echo "<input type='submit' name='delete_no' value='NO' id='mysubmit'>";
echo "</center>";
echo "</div>";

if(isset($_POST['delete_yes']))
{
	if(!get_record('pbl_discuss','moduleid',$moduleid))
	{
		error("no such module available");
	}
	else
	{
		delete_records('pbl_discuss','moduleid',$moduleid);
		echo "<b>The Module is deleted from this PBL course. You will be redirected soon!!!</b>";
		redirect($CFG->wwwroot.'/course/mod.php?delete='.$moduleid.'&sesskey='.$sesskey.'&sr=1');
	}
	//echo "yes";
}
if(isset($_POST['delete_no']))
{
	echo "<b>You will be redirected soon!!!</b>";
	redirect($CFG->wwwroot.'/mod/pbl/view.php?id='.$id);
}
?>
