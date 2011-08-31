<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $CFG, $USER,$COURSE;

//require_once('simple.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // rubrics instance ID

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
add_to_log($course->id, "rubrics", "view", "edit_form.php?id=$cm->id", "$rubrics->id");

/// Print the page header
$strrubricss = get_string('modulenameplural', 'rubrics');
$strrubrics  = get_string('modulename', 'rubrics');

$navlinks = array();
$navlinks[] = array('name' => $strrubricss, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($rubrics->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);
$rubrics = get_record('rubrics', 'id', $cm->instance);
$rubricsid=$rubrics->id;
$rowz= get_record('rubrics','id',$rubricsid);
$rowno=$rowz->rowno+1;
$columnno=$rowz->columnno+1;
$courseid = $course->id;
//echo $courseid;
$moduleid = $id;


require_once("$CFG->libdir/formslib.php");
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$countrow=0;
$countcolumn=0;
 $site = get_site();

	print_header_simple(format_string($rubrics->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strrubrics), navmenu($course, $cm));
if (has_capability('mod/rubrics:editrubric', $context)) {
	echo "<center>";
	echo "<br/><br/><b>Define Your Rubric</b><br/><br/>";
	echo "<form action='saveform.php?id=$id' method='post'>";
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
					echo '<td>';
					echo "<b><font color='#C93000'>Name/Rating of the<br/> performence level<br/></font></b>";
					$name='rating'.$countcolumn;
					$record=get_record('rubrics_form','name',$courseid.'a'.$id.'rating'.$countcolumn);
					$value=$record->value;
					echo "<input type='text' name=$name value=$value></input>";
					echo '</td>';
				}
				else if($countrow!=0&&$countcolumn!=0) {
					echo '<td>';
					$name='level_definition'.$countrow.'b'.$countcolumn;
					$record=get_record('rubrics_form','name',$courseid.'a'.$id.'level_definition'.$countrow.'b'.$countcolumn);
					$value=$record->value;
					echo "<br/>&nbsp;&nbsp;&nbsp;<textarea cols='18' rows='6' name=$name >$value</textarea> &nbsp;&nbsp;&nbsp;";
					echo '</td>';
				}
				else {
					echo '<td>';
					$name='criteria_name'.$countrow;
					$record=get_record('rubrics_form','name',$courseid.'a'.$id.'criteria_name'.$countrow);
					$value=$record->value;
					echo "<br/>&nbsp;&nbsp;&nbsp;<textarea cols='20' rows='6' name=$name>$value</textarea> &nbsp;&nbsp;&nbsp;";
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
	echo "<br/>";
	echo "<input type='submit' />";
	echo "</form>";
	echo "</center>";
}
else {
	error("You don't have permission to view this page");
}
/// Finish the page
print_footer();
?>