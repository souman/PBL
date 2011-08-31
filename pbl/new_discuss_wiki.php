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
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
//require_capability('mod/pbl:nview', $context);
/*if (!has_capability('mod/pbl:view', $context)) {
  error( "nopermissiontoviewforum");
}
if (has_capability('mod/pbl:view', $context)) {
    echo "hi";
}
$wikied->type='wiki';
$wikied->userid=$USER->id;
$wikied->courseid=$COURSE->id;
$wikied->moduleid=$id;
$wikied->groupid=0;
$wikiid=get_records('wiki');
$maxid=0;
foreach ($wikiid as $x )
		{
			if ($x->id > $maxid)
			{
			    $maxid=$x->id;
			}
			//echo "a<br>";
			
	    }
$maxid=$maxid+1;
$wikied->discussid=$maxid;
if(!insert_record('pbl_discuss',$wikied))
{
    error("data not inserted into the pbl_discuss table");
}*/
$max_section=get_record('course','id',$COURSE->id);
if($max_section->numsections%2!=0)
{
    $max_section->numsections=$max_section->numsections+1;
}
$pbl_section=$max_section->numsections+10+$pbl->id*2+1;//now pbl_section is odd
$url=$CFG->wwwroot.'/mod/pbl/modedit.php?add=wiki&type=&course='.$COURSE->id.'&section='.$pbl_section.'&return=0&insertdb=pbl_discuss';
		redirect($url);
?>