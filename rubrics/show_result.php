<?php
/*
 * Created on 22-Mar-2011
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
	$dst_user= $_POST[users_of_rubrics];
	echo "<center>";
	echo 'Result for '.$dst_user;
	echo "<br/>";
	echo "<br/>";
/*	$results=get_records('rubrics_user_record','dst_user',$dst_user);



	$i=0;
	$rowname=array(0=>NULL);
	$count=array(0=>NULL);
	$value=array(0=>NULL);
	for($j=0;$j<=100;$j=$j+1) {
		$rowname[$j]=NULL;
		$value[$j]=NULL;
		$count[$j]=NULL;
		}
	$flag=0;
	$counting=0;
	foreach ($results as $result) {

			if($result->rubrics_id==$id && $result->courseid==$courseid) {
				$counting++;
				for($j=0;$j<=$i;$j=$j+1) {
					if($rowname[$j]==$result->row_name && $value[$j]==$result->value){
						$flag=1;
					}
				}
				if($flag==0) {
					$rowname[$i]=$result->row_name;
					$value[$i]=$result->value;
					$count[$i]=count_records('rubrics_user_record','dst_user',$dst_user,'row_name',$result->row_name,'value',$result->value);
					$i=$i+1;
				}
				$flag=0;
		}
	}
	if( $counting==0) {
		echo "No record found";
	}
	else {
		echo "<table border='1'>";
		echo "<tr>" .
			"<td><b><font color='#055578'> &nbsp &nbsp Criteria  &nbsp &nbsp </font></b></td>".
			"<td><b><font color='#055578'> &nbsp &nbsp Rating &nbsp &nbsp </font></b></td>".
			"<td><b><font color='#055578'> &nbsp &nbsp Count &nbsp &nbsp </font></b></td>".
		"</tr>";
		for($j=0;$j<$i;$j=$j+1) {
			echo "<tr>";
				echo "<td>";
					echo $rowname[$j];
				echo "</td>";
				echo "<td>";
					echo $value[$j];
				echo "</td>";
				echo "<td>";
					echo $count[$j];
				echo "</td>";
			echo "</tr>";
		}
	}
	//$results->dst_user;
	echo "</table>";
	echo "</center>";*/
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
				echo "&nbsp".$rating_value."&nbsp";
				echo '</th>';
			}
			else if($countrow!=0&&$countcolumn!=0) {
				echo "<td align='center'>";
				$record=get_record('rubrics_form','name',$courseid.'a'.$id.'rating'.$countcolumn);
				$rating_value=$record->value;
				$count_recs=get_records('rubrics_user_record','rubrics_id',$id);
				$counting=0;
				foreach ($count_recs as $count_rec) {
					if ($count_rec->row_name==$criteria_value && $count_rec->value==$rating_value && $count_rec->dst_user==$dst_user) {
						$counting++;
					}

				}
				echo $counting;
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
else {
	error("You dont have the permission");
}
print_footer($course);
?>