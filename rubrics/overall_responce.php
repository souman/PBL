<?php
/*
 * Created on 05-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot.'/lib/grouplib.php');
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
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
/// Print the page header
$strrubricss = get_string('modulenameplural', 'rubrics');
$strrubrics  = get_string('modulename', 'rubrics');

$navlinks = array();
$navlinks[] = array('name' => $strrubricss, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($rubrics->name), 'link' => '', 'type' => 'activityinstance');

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
if (has_capability('mod/rubrics:viewresults', $context)) {
echo "<center>";
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
				$rating_value=$record->value;
				echo $rating_value;
				echo '</th>';
			}
			else if($countrow!=0&&$countcolumn!=0) {
				echo '<td>';
				$record=get_record('rubrics_form','name',$courseid.'a'.$id.'rating'.$countcolumn);
				$rating_value=$record->value;
				$count_rec=count_records('rubrics_user_record','rubrics_id',$id,'row_name',$criteria_value,'value',$rating_value);
				echo $count_rec;
				echo '</td>';
			}
			else {
				echo '<td>';
				$name='criteria_name'.$countrow;
				$record=get_record('rubrics_form','name',$courseid.'a'.$id.'criteria_name'.$countrow);
				$criteria_value=$record->value;
				echo $criteria_value;
				//echo "<input type='hidden' name='criteria_name$countrow' value='$value' />";
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
	echo "</center>";
}
print_footer($course);
?>
