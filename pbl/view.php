<?php  // $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $

/**
 * This page prints a particular instance of pbl
 *
 * @author  Your Name <your@email.address>
 * @version $Id: view.php,v 1.6.2.3 2009/04/17 22:06:25 skodak Exp $
 * @package mod/pbl
 */

/// (Replace pbl with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once($CFG->dirroot.'/lib/grouplib.php');
require_once($CFG->libdir.'/blocklib.php');
require_once($CFG->libdir.'/ajax/ajaxlib.php');
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
$contextcourse= get_context_instance(CONTEXT_COURSE, $course->id);
add_to_log($course->id, "pbl", "view", "view.php?id=$cm->id", "$pbl->id");

/// Print the page header
$strpbls = get_string('modulenameplural', 'pbl');
$strpbl  = get_string('modulename', 'pbl');

$navlinks = array();
$navlinks[] = array('name' => $strpbls, 'link' => "index.php?id=$course->id", 'type' => 'activity');
$navlinks[] = array('name' => format_string($pbl->name), 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strpbl), navmenu($course, $cm));

/// Print the main part of the page


$SESSION->fromdiscussion = $CFG->wwwroot .'/course/view.php?id='. $course->id;


    if ($course->id == SITEID) {
        // This course is not a real course.
        redirect($CFG->wwwroot .'/');
    }


require_once($CFG->dirroot.'/course/moodleform_mod.php');

$sesskey=sesskey();
$max_section=get_record('course','id',$COURSE->id);
if($max_section->numsections%2!=0)
{
    $max_section->numsections=$max_section->numsections+1;
}
$pbl_section=$max_section->numsections+10;

$title=get_record('pbl','id',$pbl->id);
$PAGE = page_create_object(PAGE_COURSE_VIEW, $course->id);
$pageblocks = blocks_setup($PAGE, BLOCKS_PINNED_BOTH);

echo '<div class="course-content">';
echo "<table cellpadding='10'><tr>";
echo "<td width='15%' valign='top'>";
echo '<br/>';
echo '<table cellpadding="5"  border="1" bordercolor="#dddddd" width=100% id="sidenavigate"><tr>';
    echo "<tr>";
    $currentcontext = get_context_instance(CONTEXT_COURSE, $course->id);
        echo '<th id="blockleft" align="left">Participents</th>';
    echo "</tr>";
    echo "<tr>";
        echo "<td align='top'><a href='$CFG->wwwroot/user/index.php?contextid=$currentcontext->id'><img src=$CFG->wwwroot/mod/pbl/pix/users.gif></img>&nbsp;Peoples</a></td></tr>";
echo '</table>';

echo '<br/>';

if(has_capability('mod/pbl:edit_group', $contextcourse)) {
	echo "<table cellpadding='5' border='1' BORDERCOLOR='dddddd' width=100% id='sidenavigate'><tr>";
	    echo "<tr>";
	        echo '<th id="blockleft" align="left">Groups</th>';
	    echo "</tr>";
	    echo "<tr>";
	        echo "<td align='top'><a href='$CFG->wwwroot/group/index.php?id=$course->id'><img src=$CFG->wwwroot/mod/pbl/pix/group.gif></img>&nbsp;View/Edit Groups</a></td></tr>";
	echo '</table>';

	echo '<br/>';
}

echo "<table cellpadding='5' border='1' BORDERCOLOR='dddddd' width=100% id='sidenavigate'><tr>";
    echo "<tr>";
        echo '<th id="blockleft" align="left">Search</th>';
    echo "</tr>";
    echo "<tr>";
	echo '<td><form id="searchquery" method="get" action="'. $CFG->wwwroot .'/search/query.php"><div>'
          . '<label for="block_search_q">'. $label .'</label>'
          . '<input id="block_search_q" type="text" name="query_string" />'
          . '<input type="submit" value="Go" />'
          . '</div></form></td>';
    echo '</tr>';
echo '</table>';



echo '<br/>';

echo "<table cellpadding='5' border='1' BORDERCOLOR='dddddd' width=100% id='sidenavigate'><tr>";
    echo "<tr>";
        echo '<th id="blockleft" align="left">Add a</th>';
    echo "</tr>";
    echo "<tr>";
    echo "<td align='top'>";
    if(has_capability('mod/pbl:add_rubric', $context)) {
		echo "<a href='new_discuss.php?id=$id.&type=rubrics'><img src=$CFG->wwwroot/mod/pbl/pix/rubric.gif></img>&nbsp;Rubric</a>";
	    echo "<br/>";
    }
    if(has_capability('mod/pbl:add_submission', $context)) {
		echo "<a href='$CFG->wwwroot/mod/pbl/submission.php?id=$id'><img src=$CFG->wwwroot/mod/pbl/pix/submission.gif></img>&nbsp;Submission</a>";
		echo "<br/>";
    }
	echo "<a href='save_solution.php?id=$id'><img src=$CFG->wwwroot/mod/pbl/pix/solution.gif></img>&nbsp;Solution</a></td>";
    echo '</tr>';
echo '</table>';

echo '<br/>';

echo "<table cellpadding='5' border='1' BORDERCOLOR='dddddd' width=100% id='sidenavigate'><tr>";
    echo "<tr>";
        echo '<th id="blockleft" align="left">View</th>';
    echo "</tr>";
    echo "<tr>";
        echo "<td align='top'>";
        if(has_capability('mod/pbl:view_report', $context)) {
	        echo "<a href='report.php?id=$id'><img src=$CFG->wwwroot/mod/pbl/pix/report.gif></img>&nbsp;Report</a>";
	        echo "<br/>";
        }
        echo "<a href='$CFG->wwwroot/user/view.php?id=$USER->id&course=$course->id'><img src=$CFG->wwwroot/mod/pbl/pix/profile.gif></img>&nbsp;Profile</a></td></tr>";
echo '</table>';

echo '</td>';
echo "<td width='85%' valign='top'>";
echo '<table border="1" bordercolor="#dddddd" width=100% cellpadding="15" valign="top">';
echo '<tr>';
echo "<td>";
echo "<div id='headings'>Problem Definition</div>";
echo "<div style='color: blue'>Title:</div> ".'<b>'.$title->name.'</b>';
echo "<br>";
echo "<p><div style='color: blue'>Description:</div>";
echo $title->intro.'</p>';
echo '<br/>';
$maxidobj=get_record('pbl_forum','pblid',$pbl->id);
$id_of_forum=module_id_name('forum');
//echo $pbl->id;
if(has_capability('mod/pbl:createforum', $context)) {
    $editdir=$CFG->wwwroot.'/course/mod.php?update='.$id.'&sesskey='.$sesskey.'&sr=1';
    echo "<a href=$editdir><img src=$CFG->wwwroot/mod/pbl/pix/edit.gif >&nbsp;Edit the PBL</a>";
    echo "&nbsp; &nbsp; &nbsp;";
    if($maxidobj=='') {
        //now pbl_section is even here
        $pbl_section=$max_section->numsections+10+$pbl->id*2;
        $newforum=$CFG->wwwroot.'/mod/pbl/modedit.php?add=forum&type=&course='.$COURSE->id.'&section='.$pbl_section.'&return=0&insertdb=pbl_forum&pblid='.$pbl->id;
        echo "<a href=$newforum><img src=$CFG->wwwroot/mod/pbl/pix/edit.gif >&nbsp;Create a new forum</a>";
    }
    else  {
        $comment_id=get_record('pbl_forum','courseid',$COURSE->id,'pblid', $pbl->id);
        $updateforum=$CFG->wwwroot.'/course/mod.php?update='.$comment_id->forumid.'&sesskey='.$sesskey.'&sr=1';
        echo "<a href=$updateforum><img src=$CFG->wwwroot/mod/pbl/pix/update.gif >&nbsp;Update the forum</a>";
    }
}
echo "&nbsp; &nbsp; &nbsp;";
//$url_for_comment=$CFG->wwwroot.'/course/mod.php?update='.$id.'&sesskey='.$sesskey.'&sr=1';
    if($maxidobj->forumid!='') {
        $comment_id=get_record('pbl_forum','courseid',$COURSE->id,'pblid', $pbl->id);
        echo "<a href='comment.php?cid=$comment_id->forumid&id=$id'><img src=$CFG->wwwroot/mod/pbl/pix/comment.gif >";
        echo '&nbsp;';
        echo "Comment</a>";
    }
echo "<br/>";
echo "</td>";
echo "</tr>";
//&& isstudent($COURSE->id,$USER->id)
//echo "<br/>";
//echo "<hr />";
echo "<tr>";
echo "<td>";
if(has_capability('mod/pbl:view_discussion', $context) && isstudent($course->id, $USER->id) ) {
    echo "<div id='headings'>Discussions</div><br/>";
    $discuss_objects=get_records('pbl_discuss','courseid',$COURSE->id);

    $i=0;
    //echo $discuss_objects->type;
    foreach($discuss_objects as $discuss_object)
    {
        if($discuss_object->pblid==$pbl->id && ($discuss_object->type=='chat'||$discuss_object->type=='forum'||$discuss_object->type=='wiki')) {

            $name=$discuss_object->type;
            $module_id = module_id_name($name);
            //echo $module_id->id;
            //$discuss_module_id=get_record('course_modules','course',$course->id,'module',$module_id->id,'instance',$discuss_object->discussid);
            //echo $discuss_module_id->id;
            echo "<img src=$CFG->wwwroot/mod/$name/icon.gif >";
            echo "&nbsp;";
            $displayname=get_record($name,'id',$discuss_object->instanceid);
            echo "<a href=$CFG->wwwroot/mod/$name/view.php?id=$discuss_object->moduleid>$displayname->name</a>";
            echo '<br/>';
            //$discuss_moduleid[$i]=
        }
    }
}
if(has_capability('mod/pbl:edit_discussion', $context) )
{
    echo "<div id='headings'>Discussions</div><br/>";
    $discuss_objects=get_records('pbl_discuss','courseid',$COURSE->id);
    $i=0;
    //echo $discuss_objects->type;
    foreach($discuss_objects as $discuss_object)
    {
        if($discuss_object->pblid==$pbl->id && ($discuss_object->type=='chat'||$discuss_object->type=='forum'||$discuss_object->type=='wiki')) {

            $name=$discuss_object->type;
            $module_id = module_id_name($name);
            //echo $module_id->id;
            //$discuss_module_id=get_record('course_modules','course',$course->id,'module',$module_id->id,'instance',$discuss_object->discussid);
            //echo $discuss_module_id->id;
            echo "<img src=$CFG->wwwroot/mod/$name/icon.gif >";
            echo "&nbsp;";
            $displayname=get_record($name,'id',$discuss_object->instanceid);
            $update_url=$CFG->wwwroot.'/course/mod.php?update='.$discuss_object->moduleid.'&sesskey='.$sesskey.'&sr=0';
            $delete_url=$CFG->wwwroot.'/mod/pbl/delete_discuss.php?id='.$id.'&moduleid='.$discuss_object->moduleid;
            echo "<a href=$CFG->wwwroot/mod/$name/view.php?id=$discuss_object->moduleid>$displayname->name</a>";
            echo '&nbsp;';
            echo "<a href=$update_url><img src=$CFG->wwwroot/mod/pbl/pix/edit.gif></a>";
            echo '&nbsp;';
            echo "<a href=$delete_url><img src=$CFG->wwwroot/mod/pbl/pix/delete.gif></a>";
            echo '<br/>';
        }
    }
}
if(has_capability('mod/pbl:add_discussion', $context) && isteacher($COURSE->id,$USER->id))
{
    $options['view.php?id='.$id] = "Add new discussion";
     echo '<center><br /><table width=169><tr><td><div><form action="?id='.$id.'" name="addtask" id="taskform" method="post"><select name="addtaskvalue" onchange="document.addtask.action = document.addtask.addtaskvalue.options[document.addtask.addtaskvalue.selectedIndex].value;document.addtask.submit(); return true;" >';


        $options['new_discuss.php?id='.$id.'&type=chat'] = "chat";
        $options['new_discuss.php?id='.$id.'&type=wiki'] = "wiki";
        $options['new_discuss.php?id='.$id.'&type=forum'] = "forum";

        foreach ($options as $optionkey => $optionvalue) {
            echo '<option value ="'.$optionkey.'">'.$optionvalue.'</option>';
        }

        echo '</select>'.pbl_makelocalhelplinkmain ("addtask", "Help with Add Task (new window)", "pbl").'</form></div></td></tr></table></center>';
        /*
        if ($allertmessage['assessment']['name']) {
            echo "<br /><br /><center><b><font color=\"red\">Warning: No criteria have been set for \"".$allertmessage['assessment']['name']."\"</font></b> </center>";*/

}
echo "<br/>";
echo "</td>";
echo "</tr>";
//echo "<hr/>";
echo "<tr>";
echo "<td>";
if(has_capability('mod/pbl:addRLF', $context))  {
    echo "<div id='headings'>RLFs</div>";
    $groupids = get_records('groups_members','userid',$USER->id);
    $i=0;
    $j=0;
    $k=0;
    $rlftrack=array();
    foreach($groupids as $groupid ) {
    	//echo $groupid->groupid;

		$members=get_records('groups_members','groupid',$groupid->groupid);
		foreach($members as $member) {

			$rlfs=get_records('pbl_rlf','userid',$member->userid);
			 foreach( $rlfs as $rlf ) {
			 	if (!in_array($rlf->id, $rlftrack)) {

					$rlftrack[$i]=$rlf->id;
					$i++;
					if(record_exists('pbl_rlf_user','rlfid',$rlf->id,'userid',$USER->id)) {
						$knownrlfs[$j]=$rlf;
						$j++;
					}
					else{
						$unknownrlfs[$k]=$rlf;
						$k++;
					}
			 	}
			 }
		}
        //echo $rlf_descriptions->$rlf_descriptions; }
    }

    if($j>0)
    {
    	echo '<b id="knownrlf">Known RLFs</b><ul>';
    	foreach($knownrlfs as $rlf) {
	    	if($rlf->userid==$USER->id && $rlf->courseid == $course->id && $rlf->pblid == $id) {
				echo "<li><a href=view_rlf.php?id=$id&rlfid=$rlf->id>$rlf->description</a>&nbsp; <a href=delete_rlf.php?id=$id&rlfid=$rlf->id><img src=$CFG->wwwroot/mod/pbl/pix/delete.gif ></a></li>";
			}
			else if($rlf->courseid == $course->id && $rlf->pblid == $id) {
				echo "<li><a href=view_rlf.php?id=$id&rlfid=$rlf->id>$rlf->description</a>&nbsp;</li>";

			}
    	}
    	echo '</ul>';

    }
    if($k>0)
    {
    	echo '<b id="unknownrlf">Unknown RLFs</b><ul>';
    	foreach($unknownrlfs as $rlf) {
	    	if($rlf->userid==$USER->id && $rlf->courseid == $course->id && $rlf->pblid == $id) {
				echo "<li><a href=view_rlf.php?id=$id&rlfid=$rlf->id>$rlf->description</a>&nbsp; <a href=delete_rlf.php?id=$id&rlfid=$rlf->id><img src=$CFG->wwwroot/mod/pbl/pix/delete.gif ></a></li>";
			}
			else if($rlf->courseid == $course->id && $rlf->pblid == $id) {
				echo "<li><a href=view_rlf.php?id=$id&rlfid=$rlf->id>$rlf->description</a>&nbsp;</li>";

			}
    	}
    	echo '</ul>';
    }
 	echo "<center><a href=$CFG->wwwroot/mod/pbl/add_rlf.php?id=$id>Add RLF</a></center>";
}
echo "</tr>";
echo "</td>";
//echo '<hr/>';
echo "<tr>";
echo "<td>";
echo  "<div id='headings'>Resources</div>";
if(has_capability('mod/pbl:add_resources', $context))
{
    echo "<img src=$CFG->wwwroot/mod/pbl/pix/myfolder.gif >";
    echo "&nbsp;";
    echo "<a href=$CFG->wwwroot/blocks/file_manager/view.php?id=$COURSE->id&groupid=0>My Files</a>";
    echo '<br/>';
    $groups=get_records('groups_members','userid',$USER->id);
    foreach ($groups as $group) {
        $groupname=get_record('groups','id',$group->groupid,'courseid',$course->id);
        if($groupname->id!=NULL) {

			echo "<img src=$CFG->wwwroot/mod/pbl/pix/folder.gif >";
			echo "&nbsp;";
			echo "<a href=$CFG->wwwroot/blocks/file_manager/view.php?id=$COURSE->id&groupid=$group->groupid>$groupname->name.Files</a>";
			echo '<br/>';
		}
    }
}
if(has_capability('mod/pbl:edit_resources', $context)) {
    echo "<img src=$CFG->wwwroot/mod/pbl/pix/settings.gif >";
        echo "&nbsp;";
    echo "<a href=$CFG->wwwroot/blocks/file_manager/admin_settings.php?id=$COURSE->id&tab=files&tab2=students>Admin Settings</a>";
}
echo "</tr>";
echo "</td>";

if(has_capability('mod/pbl:view_solution', $context)) {
	$total_solutions_num=count_records('pbl_solution','pblid',$id);
	if($total_solutions_num>0) {
		echo "<tr>";
		echo "<td>";
		echo "<div id='headings'>Solutions</div>";
		//echo '<br/>';
		$i=0;
	    $solutiontrack=array();
		$solutions_num=count_records('pbl_solution','userid',$USER->id,'pblid',$id);
		if($solutions_num!=0) {
			echo'<b>My Solutions</b><ul>';
			$solutions=get_records('pbl_solution','userid',$USER->id);
			foreach($solutions as $solution) {
				if( $solution->pblid==$id) {
					if (!in_array($solution->id, $solutiontrack)) {
						$solutiontrack[$i]=$solution->id;
						$i++;
						echo '<li>';
						echo "<a href='solution.php?id=$id&solutionid=$solution->id&type=view'>$solution->name</a>";
						//echo '&nbsp;';
		            	//echo "<a href='solution.php?id=$id&solutionid=$solution->id&type=update'><img src=$CFG->wwwroot/mod/pbl/pix/edit.gif></a>";
		           		echo '&nbsp;';
		           		echo "<a href='solution.php?id=$id&solutionid=$solution->id&type=delete'><img src=$CFG->wwwroot/mod/pbl/pix/delete.gif></a>";
		            	//echo '<br/>';
						echo '</li>';
					}
				}
			}
			echo '</ul><br/>';
		}
		$other_solutions_num=0;
		$groupids = get_records('groups_members','userid',$USER->id);
		foreach($groupids as $groupid ) {
			$members=get_records('groups_members','groupid',$groupid->groupid);
			foreach($members as $member) {
				$solutions=get_records('pbl_solution','userid',$member->userid);
				 foreach( $solutions as $solution) {
				 	if($solution->pblid==$id && $solution->userid!=$USER->id) {
				 		$other_solutions_num=$other_solutions_num+1;
				 	}
				 }
			}
		}
		if($other_solutions_num>0) {
			//echo '<br/>';
			echo '<b>Other\'s Solutions<ul></b>';
			foreach($groupids as $groupid ) {
				$members=get_records('groups_members','groupid',$groupid->groupid);
				foreach($members as $member) {
					$solutions=get_records('pbl_solution','userid',$member->userid);
					foreach( $solutions as $solution) {
						if( $solution->userid!=$USER->id && $solution->pblid==$id) {
							if (!in_array($solution->id, $solutiontrack)) {
								$solutiontrack[$i]=$solution->id;
								$i++;
								echo '<li>';
								echo "<a href='solution.php?id=$id&solutionid=$solution->id&type=view'>$solution->name</a>";
								echo '</li>';
							}
						}
					}
				}
			}
			echo '</ul>';
		}

		echo "</tr>";
		echo "</td>";
	}
}
if(has_capability('mod/pbl:view_rubric', $context)) {
	$count=0;
	$rubrics=get_records('pbl_discuss','type','rubrics');
	foreach($rubrics as $rubric) {

		if($rubric->pblid==$pbl->id) {
			$count++;
		}
	}
	if($count>0) {
		echo "<tr>";
		echo "<td>";
		echo "<div id='headings'>Assessment</div>";
	}
	if($count>0) {
		echo '<b>Rubrics</b><ul>';
		foreach($rubrics as $rubric) {
			if($rubric->pblid==$pbl->id) {
				echo '<li>';
				$rubrics_name=get_record('rubrics','id',$rubric->instanceid);
					echo "<a href='$CFG->wwwroot/mod/rubrics/view.php?id=$rubric->moduleid'>$rubrics_name->name</a>";
				echo '</li>';
			}
		}
		echo '</ul>';
	}

	if($count>0) {
		echo "</tr>";
		echo "</td>";
	}
}

if(has_capability('mod/pbl:submit', $context)) {
	$count=0;
	$modules=get_records('pbl_discuss','type','assignment');
	foreach($modules as $module) {

		if($module->pblid==$pbl->id) {
			$count++;
		}
	}
	if($count>0) {
		echo "<tr>";
		echo "<td>";
		echo "<div id='headings'>Final Submission</div><ul>";
		foreach($modules as $module) {
			if($module->pblid==$pbl->id) {
				echo '<li>';
				$modules_name=get_record('assignment','id',$module->instanceid);
					echo "<a href='$CFG->wwwroot/mod/assignment/view.php?id=$module->moduleid'>$modules_name->name</a>";
				echo '</li>';
			}
		}
		echo '</ul>';
	}
	echo "</tr>";
	echo "</td>";
}
/*if(has_capability('mod/pbl:view_recent_update', $context)) {
	echo "<tr>";
	echo "<td>";
	echo '<b>Recent Updates</b>';
	echo "</tr>";
	echo "</td>";
}*/

echo "</table>";
echo "</td>";
echo "</tr>";

echo "</table>";
echo '</div>';
$group_arr=groups_get_all_groupings($course->id);
/*foreach ($group_arr as $g_arr)
	{
		echo $g_arr;
		echo '<br\>';
	}*/
echo $group_arr;
/// Finish the page
print_footer($course);

?>
