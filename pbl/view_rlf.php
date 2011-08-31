<?php
/*
 * Created on 19-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = required_param('id', PARAM_INT);// course_module ID, or
$rlfid = required_param('rlfid',PARAM_INT);

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
} else {
    error('You must specify a course_module ID or an instance ID');
}
require_login($course, true, $cm);

$context = get_context_instance(CONTEXT_MODULE, $cm->id);
//$coursecontext = get_context_instance(COURSE_CONTEXT, $course->id);
$strpbls = get_string('modulenameplural', 'pbl');
$strpbl  = get_string('modulename', 'pbl');

$navlinks = array();
$navlinks[] = array('name' => format_string($pbl->name), 'link' => "view.php?id=$id", 'type' => 'activity');
$navlinks[] = array('name' => 'RLF', 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strpbl), navmenu($course, $cm));

$rlf=get_record('pbl_rlf','id',$rlfid);
$read=record_exists('pbl_rlf_user','rlfid',$rlf->id,'userid',$USER->id);
echo '<b>RLF Name</b><br/>';
echo $rlf->description;
echo '<br/><br/><b>RLF Description</b><br/>';
echo $rlf->link;
echo '<br/><br/>';
if(!$read){
	 $options['view_rlf.php?id='.$id.'&rlfid='.$rlfid] = "Mark this RLF as";
	     echo '<center><br /><table width=200><tr><td><div><form action="?id='.$id.'" name="addtask" id="taskform" method="post"><select name="addtaskvalue" onchange="document.addtask.action = document.addtask.addtaskvalue.options[document.addtask.addtaskvalue.selectedIndex].value;document.addtask.submit(); return true;" >';

	        $options['rlf_mark.php?id='.$id.'&rlfid='.$rlfid.'&action=read'] = "READ";

	        foreach ($options as $optionkey => $optionvalue) {
	            echo '<option value ="'.$optionkey.'">'.$optionvalue.'</option>';
	        }


	        echo '</select>'.pbl_makelocalhelplinkmain ("rlfmarking", "Help with Marking the RLF (new window)", "pbl").'</form></div></td></tr></table></center>';
}
else {
	 $options['view_rlf.php?id='.$id.'&rlfid='.$rlfid] = "Mark this RLF as";
	     echo '<center><br /><table width=200><tr><td><div><form action="?id='.$id.'" name="addtask" id="taskform" method="post"><select name="addtaskvalue" onchange="document.addtask.action = document.addtask.addtaskvalue.options[document.addtask.addtaskvalue.selectedIndex].value;document.addtask.submit(); return true;" >';

	        $options['rlf_mark.php?id='.$id.'&rlfid='.$rlfid.'&action=unread'] = "UNREAD";

	        foreach ($options as $optionkey => $optionvalue) {
	            echo '<option value ="'.$optionkey.'">'.$optionvalue.'</option>';
	        }


	        echo '</select>'.pbl_makelocalhelplinkmain ("rlfmarking", "Help with Marking the RLF (new window)", "pbl").'</form></div></td></tr></table></center>';
}
print_footer($course);

?>
