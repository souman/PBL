<?php //$Id: mod_form.php,v 1.2.2.3 2009/03/19 12:23:11 mudrd8mz Exp $

/**
 * This file defines the main rubrics configuration form
 * It uses the standard core Moodle (>1.8) formslib. For
 * more info about them, please visit:
 *
 * http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * The form must provide support for, at least these fields:
 *   - name: text element of 64cc max
 *
 * Also, it's usual to use these fields:
 *   - intro: one htmlarea element to describe the activity
 *            (will be showed in the list of activities of
 *             rubrics type (index.php) and in the header
 *             of the rubrics main page (view.php).
 *   - introformat: The format used to write the contents
 *             of the intro field. It automatically defaults
 *             to HTML when the htmleditor is used and can be
 *             manually selected if the htmleditor is not used
 *             (standard formats are: MOODLE, HTML, PLAIN, MARKDOWN)
 *             See lib/weblib.php Constants and the format_text()
 *             function for more info
 */

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_rubrics_mod_form extends moodleform_mod {

    function definition() {

        global $COURSE;
        $mform =& $this->_form;

//-------------------------------------------------------------------------------
    /// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

    /// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('rubricsname', 'rubrics'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

    /// Adding the required "intro" field to hold the description of the instance
        $mform->addElement('htmleditor', 'intro', get_string('rubricsintro', 'rubrics'));
        $mform->setType('intro', PARAM_RAW);
        $mform->addRule('intro', get_string('required'), 'required', null, 'client');
        $mform->setHelpButton('intro', array('writing', 'richtext'), false, 'editorhelpbutton');

    /// Adding "introformat" field
        $mform->addElement('format', 'introformat', get_string('format'));

//-------------------------------------------------------------------------------
    /// Adding the rest of rubrics settings, spreeading all them into this fieldset
    /// or adding more fieldsets ('header' elements) if needed for better logic
       // $mform->addElement('static', 'label1', 'rubricssetting1', 'Your rubrics fields go here. Replace me!');

        $mform->addElement('header', 'rubricsfieldset', get_string('rubricsfieldset', 'rubrics'));
        //$mform->addElement('static', 'label2', 'rubricssetting2', 'Your rubricsn fields go here. Replace me!');

        $choicerows = array();
        for($i=0;$i<100;$i++) {
            $choicerows[$i]=$i+1;
		}
        $mform->addElement('select', 'rowno', get_string('rubricsrow', 'rubrics'),$choicerows);

        $choicecloumns = array();
        for($i=0;$i<10;$i++) {
            $choicecolumns[$i]=$i+1;
		}
		$mform->addElement('select', 'columnno', get_string('rubricscolumn', 'rubrics'),$choicecolumns);

		$mform->addElement('select', 'type', get_string('type', 'rubrics'), Array ("self" => "Self Assessment", "peer" => "Peer Assessment", "question" => "Questionnaire"));

		/*$chiocegroups= array();
		$chiocegroups[0]=0;
		$chiocegroups[1]=1;
		$mform->addElement('select', 'group', get_string('rubricsgroup', 'rubrics'),$chiocegroups);*/
//-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();

    }
}

?>
