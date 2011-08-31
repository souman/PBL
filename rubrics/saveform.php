<?php  // $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $CFG, $USER,$COURSE;
/**
 * This page prints a particular instance of rubrics
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/rubrics
 */


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
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$value=$rubrics->id;
$rowz= get_record('rubrics','id',$value);
$rowno=$rowz->rowno+2;
$columnno=$rowz->columnno+2;
$countrow=0;
$countcolumn=0;
$courseid=$course->id;
$rubrics_form=new stdClass();
if (has_capability('mod/rubrics:editrubric', $context)) {
	while($rowno>0) {
		//echo $_POST["criteria_name".$countrow];
		if($countrow>0) {
			$rubrics_for->name=$courseid.'a'.$id.'criteria_name'.$countrow;
			$rubrics_for->value=$_POST["criteria_name".$countrow];
			$rubrics_for->courseid=$courseid;
			$rubrics_for->moduleid=$id;
			$rubrics_for->rrowid=$countrow;
			$rubrics_for->columnid=0;
			$criteria_name=get_record('rubrics_form', 'name',$courseid.'a'.$id.'criteria_name'.$countrow);
			if($criteria_name->id=='') {
				insert_record("rubrics_form",$rubrics_for);
			}
			else {
				$rubrics_for->id=$criteria_name->id;
				update_record("rubrics_form",$rubrics_for);
			}
		}
			//echo "<br>";
		$x=$columnno;
		while($x>0)	{
			if($countrow!=0 && $countcolumn!=0) {
				$rubrics_for->name=$courseid.'a'.$id.'level_definition'.$countrow.'b'.$countcolumn;
				$rubrics_for->value=$_POST["level_definition".$countrow."b".$countcolumn];
				$rubrics_for->courseid=$courseid;
				$rubrics_for->moduleid=$id;
				$rubrics_for->rrowid=$countrow;
				$rubrics_for->columnid=$countcolumn;
				//echo "level_definition".$countrow."b".$countcolumn;
				$criteria_name=get_record('rubrics_form', 'name',$courseid.'a'.$id.'level_definition'.$countrow.'b'.$countcolumn);
				if($criteria_name->id=='') {
					insert_record("rubrics_form",$rubrics_for);
				}
				else {
					$rubrics_for->id=$criteria_name->id;
					update_record("rubrics_form",$rubrics_for);
				}
			}
			if($countrow==0 && $countcolumn!=0) {
				$rubrics_for->name=$courseid.'a'.$id.'rating'.$countcolumn;
				$rubrics_for->value=$_POST["rating".$countcolumn];
				$rubrics_for->courseid=$courseid;
				$rubrics_for->moduleid=$id;
				$rubrics_for->rrowid=$countrow;
				$rubrics_for->columnid=$countcolumn;
				//echo "rating".$countcolumn;
					$criteria_name=get_record('rubrics_form', 'name',$courseid.'a'.$id.'rating'.$countcolumn);
				if($criteria_name->id=='') {
					insert_record("rubrics_form",$rubrics_for);
				}
				else {
					$rubrics_for->id=$criteria_name->id;
					update_record("rubrics_form",$rubrics_for);
				}
			}
			$x=$x-1;
			$countcolumn=$countcolumn+1;
		}
		$countcolumn=0;
		$rowno=$rowno-1;
		$countrow=$countrow+1;
	}
}
else {
	error("You don't have permission to see this page");
}
redirect("$CFG->wwwroot/mod/rubrics/view.php?id=$id");
?>
