<?php
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot.'/lib/grouplib.php');
$id = optional_param('id', 0, PARAM_INT); // course_module ID, or

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
 
/*if(isteacher($cm->course))
{
    $forumid=get_records('forum');
    $maxidobj=get_record('pbl_forum','pblid',$pbl->id);
    if($maxidobj->forumid=='')
    {
        $maxid=0;
		foreach ($forumid as $x )
		{
			//echo $x->id;
			//echo "a<br>";
			if($maxid<$x->id)
			{
				$maxid=$x->id;
			}
	    }
	    echo $maxid;
		$maxid=$maxid+1;
		$up->forumid=$maxid;*/
		echo $pbl->id;
		/*$up->courseid=$course->id;
		
		if(!insert_record('pbl_forum',$up))
		{
		    error ("can not insert into the pbl_forum table");
		}
		$pbldetails=get_records('pbl','id',$pbl->id);
		$url=$CFG->wwwroot.'/mod/pbl/modedit.php?add=forum&type=&course='.$course->id.'&section=1&return=0';
		redirect($url);
	/*}
    else
    {
        error("You have already created a forum for this problem");
    
    }
}
else
{
    error( "You don't have permission to view this page");
}*/
?>
