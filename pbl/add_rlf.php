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

class simplehtml_form extends moodleform {
    function definition() {

        global $COURSE;
        $mform =& $this->_form;

//-------------------------------------------------------------------------------
    /// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

    /// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('rlfname', 'pbl'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addElement('htmleditor', 'link', get_string('rlfdes', 'pbl'));
        $mform->addElement('hidden','user_id');
		$mform->addElement('hidden','course_id');
		$mform->addElement('hidden','module_id');
//-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
         $this->add_action_buttons($cancel = false);
    }
}

$simplehtml = new simplehtml_form('save_rlf.php');
if ($fromform = $simplehtml->get_data()) {
    // we need to add code to appropriately act on and store the submitted data
    //simplehtml_form('saveform.php');
    echo $fromform->displaytext1b0;
   //redirect("$CFG->wwwroot/mod/rubrics/saveform.php?id=$id");
} else {
    //form didn't validate or this is the first display
    $site = get_site();
    print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
                   update_module_button($cm->id, $course->id, $strrubrics), navmenu($course, $cm));

    $toform['user_id'] = $USER->id;
	$toform['course_id'] = $COURSE->id;
	$toform['module_id'] = $id;
	//$toform['pbl_id'] = $pbl->id;
	$simplehtml->set_data($toform);
    $simplehtml->display();
    print_footer();
}
?>
