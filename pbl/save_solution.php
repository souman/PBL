<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $CFG, $USER,$COURSE;

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // rubrics instance ID

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
}
require_login($course);

add_to_log($course->id, "pbl", "Create RLF", "add_rlf.php?id=$cm->id", "$pbl->id");
$strpbls = get_string('modulenameplural', 'pbl');
$strpbl  = get_string('modulename', 'pbl');

$navlinks = array();
$navlinks[] = array('name' => $strpbls, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($pbl->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strpbl), navmenu($course, $cm));
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once("$CFG->libdir/formslib.php");

class solution_form extends moodleform {
    function definition() {

        global $COURSE;
        $mform =& $this->_form;

//-------------------------------------------------------------------------------
    /// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

    /// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('submissionname', 'pbl'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addElement('htmleditor', 'description', get_string('submissiondescription', 'pbl'));
        $mform->addElement('hidden','userid');
		$mform->addElement('hidden','courseid');
		$mform->addElement('hidden','moduleid');
		$mform->addElement('file', 'attachment', get_string('attachment', 'pbl'));
//-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
         $this->add_action_buttons($cancel = false);
    }
}

$solutionform = new solution_form();
if ($fromform = $solutionform->get_data()) {
	$solution_number=count_records('pbl_solution','pblid',$fromform->moduleid,'userid',$fromform->userid,'courseid',$fromform->courseid);
	if($solution_number>=5) {
		error("You already submitted 5 solution");
	}
	$solution_records=get_records('pbl_solution');
	$maxid=0;
	foreach($solution_records as $solution_record) {
		if ($solution_record->id>$maxid) {
			$maxid=$solution_record->id;
		}
	}
	$maxid=$maxid+1;
	//echo 'ggg';
	$destination_directory=$CFG->dataroot.'/'.$fromform->courseid.'/pbl/'.$fromform->moduleid.'/'.$fromform->userid.'/solution/'.$maxid;
	$x=$solutionform->save_files($destination_directory);
	//echo '****'.$x.'****';
	$solution->name=$fromform->name;
	$solution->definition=$fromform->description;
	$solution->courseid=$fromform->courseid;
	$solution->pblid=$fromform->moduleid;
	$solution->userid=$fromform->userid;
	if($solutionform->_upload_manager->files[attachment][name]=='') {
		$solution->file_location='';
		$solution->file_name='';
	}
	else {
		$destination_directory=$CFG->dataroot.'/'.$fromform->courseid.'/pbl/'.$fromform->moduleid.'/'.$fromform->userid.'/solution/'.$maxid.'/'.$solutionform->_upload_manager->files[attachment][name];
		$solution->file_location=$destination_directory;
		$solution->file_name=$solutionform->_upload_manager->files[attachment][name];
	}

	insert_record('pbl_solution',$solution);
   redirect("$CFG->wwwroot/mod/pbl/view.php?id=$fromform->moduleid");

  //echo ;

} else {
    //form didn't validate or this is the first display
    $site = get_site();
    print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
                   update_module_button($cm->id, $course->id, $strrubrics), navmenu($course, $cm));

    $toform['userid'] = $USER->id;
	$toform['courseid'] = $COURSE->id;
	$toform['moduleid'] = $id;
	//$toform['pbl_id'] = $pbl->id;
	$solutionform->set_data($toform);
    $solutionform->display();
    print_footer();
}
?>