<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot.'/lib/grouplib.php');
$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // pbl instance ID

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

} else if ($a) {
    if (! $pbl = get_record('pbl', 'id', $a)) {
        error('Course module is incorrect');
    }
    if (! $course = get_record('course', 'id', $pbl->course)) {
        error('Course is misconfigured');
    }
    if (! $cm = get_coursemodule_from_instance('pbl', $pbl->id, $course->id)) {
        error('Course Module ID was incorrect');
    }

} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$options['view.php?id='.$id] = "select group for which you want to create the discussion";
     echo '<center><br /><table width=200><tr><td><div><form action="?id='.$id.'" name="addtask" id="taskform" method="post"><select name="addtaskvalue" onchange="document.addtask.action = document.addtask.addtaskvalue.options[document.addtask.addtaskvalue.selectedIndex].value;document.addtask.submit(); return true;" >';
        
        
        $options['new_discuss_chat.php?id='.$id] = "chat";
        $options['new_discuss_wiki.php?id='.$id] = "wiki";
        $options['new_discuss_forum.php?id='.$id] = "forum";
        
        foreach ($options as $optionkey => $optionvalue) {
            echo '<option value ="'.$optionkey.'">'.$optionvalue.'</option>';
        }
        
        echo '</select>'.pbl_makelocalhelplinkmain ("addtask", "Help with Add Task (new window)", "pbl").'</form></div></td></tr></table></center>';
?>