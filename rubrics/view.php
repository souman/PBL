<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $CFG, $USER,$COURSE;

//require_once('simple.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
//$a  = optional_param('a', 0, PARAM_INT);  // rubrics instance ID

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
add_to_log($course->id, "rubrics", "view", "view.php?id=$cm->id", "$rubrics->id");
$context = get_context_instance(CONTEXT_COURSE, $course->id);

$strrubricss = get_string('modulenameplural', 'rubrics');
$strrubrics  = get_string('modulename', 'rubrics');

$navlinks = array();
$navlinks[] = array('name' => $strrubricss, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($rubrics->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

$courseid = $course->id;
$rubricsid = $rubrics->id;

//echo $rubricsid;

print_header_simple(format_string($rubrics->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strrubrics), navmenu($course, $cm));

if (has_capability('mod/rubrics:viewoptions', $context)) {
	if (has_capability('mod/rubrics:editrubric', $context)) {
		if(!get_records('rubrics_form','moduleid',$id)) {
			echo "<a href='edit_form.php?id=$id'>Click here to Create the Rubric</a>";
			echo '<br/>';
		}
		else {
			echo "<a href='edit_form.php?id=$id'>Click here to Edit the Rubric</a>";
			echo '<br/>';
		}
	}

	if(!get_records('rubrics_form','moduleid',$id)) {
			echo "Rubrics haven't created yet";
			echo '<br/>';
		}
		else {
			echo "<a href='view_form.php?id=$id'>Click here to View the Rubric</a>";
			echo "<br/>";
			echo "<a href='results.php?id=$id'>Click here to View the Results</a>";
		}

}
print_footer();
?>