<?php
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

$context = get_context_instance(CONTEXT_MODULE, $cm->id);

$navlinks = array();
$navlinks[] = array('name' => $strrubricss, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($rubrics->name), 'link' => '', 'type' => 'activityinstance');
$navigation = build_navigation($navlinks);
$courseid = $course->id;
$value=$id;
$rubricid=$rubrics->id;
$rowz= get_record('rubrics','id',$rubricid);
$rowno=$rowz->rowno+1;
$columnno=$rowz->columnno+1;
//echo $rowno;
//echo$columnno;
$countrow=0;
$countcolumn=0;
$context = get_context_instance( CONTEXT_COURSE, $courseid );
print_header_simple(format_string($rubrics->name), '', $navigation, '', '', true,
		update_module_button($cm->id, $course->id, $strrubrics), navmenu($course, $cm));

		//echo "courseid=".$courseid;
if (has_capability('mod/rubrics:viewrubric', $context)) {
	$instanceid=get_record('course_modules','id',$id);
	$type_of_rubric=get_record('rubrics','id',$instanceid->instance);
	echo "<center>";
	echo "<br/><br/>Submit the Rubrics<br/><br/>";
	echo "<form action='update_record.php?id=$id' method='post'>";
	if($type_of_rubric->type=='peer') {
		$student_role=get_record('role','name','Student');
		$students = get_role_users($student_role->id, $context);
		echo "<b>Select User</b><br/>";
		echo	"<select name='users_of_rubrics'>";
			foreach ($students as $user) {
				$course_groupmode=get_record('course','id',$course->id);
				if($course_groupmode->groupmode==1) {
					//echo "<option value=$user_name>$user->id</option>";
					if(user_belong_to_same_group($user->id,$USER->id,$course->id)) {
						$full_user_name=fullname($user);
						$user_name=$user->username;
						echo "<option value=$user_name>$full_user_name</option>";
					}
				}
				else {
					$full_user_name=fullname($user);
					$user_name=$user->username;
					echo "<option value=$user_name>$full_user_name</option>";
				}
			}
		echo "</select>";
	}
	else if($type_of_rubric->type=='self') {
		$self_user=$USER->username;
		echo "<input type='hidden' name='users_of_rubrics' value='$self_user' />";
	}
	else if($type_of_rubric->type=='question') {
		$nul=NULL;
		echo "<input type='hidden' name='users_of_rubrics' value='$nul' />";
	}
	echo "<table  border='2' align='center'>";
	while($rowno>=0) {
    	echo '<tr>';
    	$x=$columnno;
    	while ($x>=0) {
			if($countrow==0 && $countcolumn==0){
				echo '<td>';
				echo "<b><font color='#055578'>Rubric Item</b></font>";
				echo '</td>';
			}
			else if($countrow==0) {
				echo '<th>';
				$name='rating'.$countcolumn;
				$record=get_record('rubrics_form','name',$courseid.'a'.$id.'rating'.$countcolumn);
				$value=$record->value;
				echo $value;
				echo '</th>';
			}
			else if($countrow!=0&&$countcolumn!=0) {
				echo "<td align='justify'>";
				$name='level_definition'.$countrow.'b'.$countcolumn;
				$record=get_record('rubrics_form','name',$courseid.'a'.$id.'level_definition'.$countrow.'b'.$countcolumn);
				$value=$record->value;
				$length = strlen($value);
				$value1=wordwrap($value, 20, "<br />\n");
				$rating_record=get_record('rubrics_form','name',$courseid.'a'.$id.'rating'.$countcolumn);
				$rating_value=$rating_record->value;
				echo "<input type='radio' name='rating$countrow' value='$rating_value' />";
				echo $value1;
				echo '</td>';
			}
			else {
				echo '<td>';
				$name='criteria_name'.$countrow;
				$record=get_record('rubrics_form','name',$courseid.'a'.$id.'criteria_name'.$countrow);
				$value=$record->value;
				$value1=wordwrap($value, 20, "<br />\n");
				echo $value1;
				echo "<input type='hidden' name='criteria_name$countrow' value='$value' />";
				echo '</td>';
        	}
	 		$x--;
			$countcolumn++;
    	}
	$countcolumn=0;
	echo '</tr>';

    $rowno--;
    $countrow++;
}
echo "</table>";
		echo "<br/>";
		echo "<input type='submit' value='submit' />";
		echo "</form>";
		echo "</center>";
}
else {
	error("You dont have permission to view this page");
}

//$role = $DB->get_record('role', array('shortname' => 'Student'));
//echo $context->id;

/// Finish the page
print_footer($course);

?>