<?php  // $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of rubrics
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/rubrics
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $CFG,$USER;
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once("$CFG->libdir/formslib.php");
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
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$courseid = $course->id;
$value=$id;
$rowz= get_record('rubrics','id',$rubrics->id);
$rowno=$rowz->rowno+1;
$columnno=$rowz->columnno+1;
$countrow=1;
$countcolumn=0;
$dst_user= $_POST["users_of_rubrics"];
$src_user= $USER->username;
$record_user=new stdClass();
if (has_capability('mod/rubrics:viewrubric', $context)) {
	$rowno_chk=$rowno;
	$countrw_chk=0;

	while($rowno_chk>0) {
		$rubrics_user_record->value= $_POST["rating".$rowno_chk];
		if($rubrics_user_record->value==NULL) {
			error("You haven't selected an option");
		}
		$rowno_chk=$rowno_chk-1;
		//$countrow_chk=$countrow_chk+1;
	}
	$criteria_name=get_record('rubric_record', 'texts',$src_user.'#'.$dst_user.'#'.$courseid.'#'.$value);
	if($criteria_name->id=='') {
		$record_user->texts=$src_user.'#'.$dst_user.'#'.$courseid.'#'.$value;
		$record_user->moduleid=$value;
		insert_record('rubric_record',$record_user);
	}
	else {
	error("You have already submitted this rubric for this user");
	}
	//$courseid=$_POST["courseid"];
	$rubrics_user_record=new stdClass();

	while($rowno>0) {
		$rubrics_user_record->row_name=$_POST["criteria_name".$countrow];
		//echo $rubrics_user_record->row_name;
		//echo '<br/>';
		$rubrics_user_record->value= $_POST["rating".$countrow];
		//echo $rubrics_user_record->value;
		//echo '<br/>';
		$rubrics_user_record->src_user=$src_user;
		$rubrics_user_record->dst_user=$dst_user;
		$rubrics_user_record->rubrics_id=$value;
		$rubrics_user_record->courseid=$courseid;
		insert_record('rubrics_user_record',$rubrics_user_record);
		$rowno=$rowno-1;
		$countrow=$countrow+1;
	}
}
else {
	error("You dont have permission to view this page");
}

		redirect("$CFG->wwwroot/mod/rubrics/view.php?id=$id");
?>