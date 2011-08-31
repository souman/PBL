<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot.'/lib/grouplib.php');
$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$a  = optional_param('a', 0, PARAM_INT);  // pbl instance ID
$type = optional_param('type', 0, PARAM_ALPHA);
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

$max_section=get_record('course','id',$COURSE->id);
if($max_section->numsections%2!=0)
{
    $max_section->numsections=$max_section->numsections+1;
}
$pbl_section=$max_section->numsections+10+$pbl->id*2+1;//now pbl_section is odd

$url=$CFG->wwwroot.'/mod/pbl/modedit.php?add='.$type.'&type=&course='.$COURSE->id.'&section='.$pbl_section.'&return=0&insertdb=pbl_discuss';
		redirect($url);
?>