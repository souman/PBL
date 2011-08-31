<?php
/*
 * Created on 05-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot.'/lib/dmllib.php');
global $CFG, $USER,$COURSE, $DB, $OUTPUT;

//require_once('simple.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or

if ($id) {
    if (! $cm = get_coursemodule_from_id('rubrics', $id)) {
        error('Course Module ID was incorrect');
    }

    if (! $course = get_record('course', 'id', $cm->course)) {
        error('Course is misconfigured');
    }

    if (! $rubrics = get_record('rubrics', 'id', $cm->instance)) {
        error('Course module is incorrect');
    }
}
require_login($course);
//echo '$course='.$rubrics->id;
add_to_log($course->id, "rubrics", "view", "view_form.php?id=$cm->id", "$rubrics->id");

/// Print the page header
$strrubricss = get_string('modulenameplural', 'rubrics');
$strrubrics  = get_string('modulename', 'rubrics');

$navlinks = array();
$navlinks[] = array('name' => $strrubricss, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($rubrics->name), 'link' => '', 'type' => 'activityinstance');
?>
<script language=\"javascript\">
function show_users() {
	document.getElementById('show_user').style.display='block';
}

function hide_users() {
	document.getElementById('hide_user').style.display='none';
}
</script>
<?
$navigation = build_navigation($navlinks);

$courseid = $course->id;
$value=$rubrics->id;
$rowz= get_record('rubrics','id',$value);
$rowno=$rowz->rowno+1;
$columnno=$rowz->columnno+1;
$countrow=0;
$countcolumn=0;
print_header_simple(format_string($rubrics->name), '', $navigation, '', '', true,
		update_module_button($cm->id, $course->id, $strrubrics), navmenu($course, $cm));

$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$context1 = get_context_instance(CONTEXT_COURSE, $course->id);
$student_role=get_record('role','name','Student');
$students = get_role_users($student_role->id, $context1);
$type=get_record('rubrics','id',$rubrics->id);
$rowname=array(0=>NULL);
$value=array(0=>NULL);
if($type->type=='question') {
	if (has_capability('mod/rubrics:viewresults', $context)) {
		echo "<center>";
		echo "<br/><br/>Select the user for whom you want to see the result<br/><br/>";
		echo "<form action='show_individual_result.php?id=$id' method='post'>";
		echo '<b>Select User</b><br/>';
		echo "<select name='users_of_rubrics'>";
		foreach ($students as $user) {
			$full_user_name=fullname($user);
			$user_name=$user->username;
			echo "<option value=$user_name>$full_user_name</option>";
		}
		echo "</select>";
		echo "</select>";
		echo "<br/>";
		echo "<br/>";
		echo "<input type='submit' value='submit' />";
		echo "</form>";
		echo "</center>";
	}
	else {
		error("You dont have permission to view the result");
	}

}
print_footer($course);

?>
