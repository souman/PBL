<?php
/*
 * Created on 17-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT);// course_module ID, or
$groupid = optional_param('groupid',0,PARAM_INT);
$userid = optional_param('userid',0,PARAM_INT);
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
$navlinks[] = array('name' => 'Report', 'link' => '', 'type' => 'activityinstance');

$navigation = build_navigation($navlinks);

print_header_simple(format_string($pbl->name), '', $navigation, '', '', true,
              update_module_button($cm->id, $course->id, $strpbl), navmenu($course, $cm));

 if(has_capability('mod/pbl:view_report', $context)) {
 	$members=get_records('groups_members','groupid',$groupid);
 	$rlfs=get_records('pbl_rlf','pblid',$id);
 	$solutions=get_records('pbl_solution','pblid',$id);
 	$discuss=get_records('pbl_discuss','pblid',$pbl->id);
 	$forum_discuss=get_records('forum_discussions','course',$course->id);
 	//$chats=get_records();
 	//$wiki=get_records();
 	$rlf_num=0;
 	$member_num=0;
 	$solution_num=0;
 	$chat_num=0;
 	$wiki_num=0;
 	$forum_num=0;
 	$forum_post_num=0;
 	$forum_post_rating=0;
 	$forum_discuss_num=0;
 	$chat_msg_no=0;
 	foreach($members as $member) {
 		$member_num=$member_num+1;
 		foreach($rlfs as $rlf) {
 			if($rlf->userid==$member->userid) {
				$rlf_num=$rlf_num+1;
 			}
 		}
 		foreach($solutions as $solution) {
 			if($solution->userid==$member->userid) {
				$solutions_num=$solutions_num+1;
 			}
 		}
 		foreach($discuss as $discus) {
			//echo $discus->userid;
			//echo '<br/>';
 			if($discus->userid==$member->userid) {
 				//echo "in discuss";
	 			if($discus->type=='chat') {
	 				$chat_num=$chat_num+1;
	 			}
	 			if($discus->type=='forum') {
	 				$forum_num=$forum_num+1;
	 			}
	 			if($discus->type=='wiki') {
	 				$wiki_num=$wiki_num+1;
	 			}
 			}
 		}

 	}
 	$pblforums=get_records('pbl_forum','pblid',$pbl->id);
 	foreach($pblforums as $pblforum) {
		$forum_num=$forum_num+1;
 	}
 	foreach($forum_discuss as $forum_discus) {
			foreach($discuss as $discus) {
				if ($forum_discus->forum==$discus->instanceid && $discus->type=='forum') {
					$forum_post_num=$forum_post_num+count_records('forum_posts','discussion',$forum_discus->id);
					$forum_discuss_num=$forum_discuss_num+1;
				}
			}
			foreach($pblforums as $pblforum) {
				if ($forum_discus->forum==$pblforum->instanceid) {
					$forum_post_num=$forum_post_num+count_records('forum_posts','discussion',$forum_discus->id);
					$forum_discuss_num=$forum_discuss_num+1;
				}
			}
 	}
 	foreach($discuss as $discus) {
 		if($discus->type='chat') {
	 		$chat_msgs=get_records('chat_messages','chatid',$discus->instanceid);
	 		foreach($chat_msgs as $chat_msg) {
	 			if($chat_msg->groupid==$groupid && $chat_msg->message!='enter' && $chat_msg->message!='exit' ) {
	 				$chat_msg_no=$chat_msg_no+1;
	 			}
	 		}
 		}
 	}
echo '<br/>';
echo '<center>';
echo '<b>Report for '.$pbl->name;
echo '<br/>';
echo 'Number of members in the group: ';
echo $member_num;
echo '</b><br/>';
echo '<br/>';
echo '</center>';
echo
	'<table border="1" summary="Report for $pbl->name" align="center" cellpadding="10">
    <thead>
    	<tr>
        	<th scope="col">Parameter</th>
            <th scope="col">&nbsp;Total&nbsp;&nbsp;</th>
            <th scope="col">&nbsp;Avg&nbsp;&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        	<td>Number of RLF documented</td>
            <td>'.$rlf_num.'</td>
            <td>'.floor(($rlf_num/$member_num) * 100 + .5) * .01.'</td>
        </tr>
        <tr>
        	<td>Number of Solution proposed</td>
            <td>'.$solutions_num.'</td>
            <td>'. floor(($solutions_num/$member_num) * 100 + .5) * .01.'</td>
        </tr>
        <tr>
        	<td>Number of Chat sessions</td>
            <td>'.$chat_num.'</td>
            <td>'.floor(($chat_num/$member_num) * 100 + .5) * .01.'</td>
        </tr>
        <tr>
        	<td>Number of Chat messages</td>
            <td>'.$chat_msg_no.'</td>
            <td>'.floor(($chat_msg_no/$member_num) * 100 + .5) * .01.'</td>
        </tr>
         <tr>
        	<td>Number of Wiki</td>
            <td>'.$wiki_num.'</td>
            <td>'.floor(($wiki_num/$member_num) * 100 + .5) * .01.'</td>
        </tr>
         <tr>
        	<td>Number of Forums</td>
            <td>'.$forum_num.'</td>
            <td>'.floor(($forum_num/$member_num) * 100 + .5) * .01.'</td>
        </tr>
        <tr>
        	<td>Number of discussion in the Forums</td>
            <td>'.$forum_discuss_num.'</td>
            <td>'.floor(($forum_discuss_num/$member_num) * 100 + .5) * .01.'</td>
        </tr>
        <tr>
        	<td>Number of posts in the Forums</td>
            <td>'.$forum_post_num.'</td>
            <td>'.floor(($forum_post_num/$member_num) * 100 + .5) * .01.'</td>
        </tr>
    </tbody>
</table>';
 }
$members=get_records('groups_members','groupid',$groupid);

//$students = get_role_users($student_role->id, $coursecontext);
 echo '<br/>';
 echo '<hr/>';
 echo '<center>';
echo 'Select the user to view individual report';
 $options['show_report.php?id='.$id.'&groupid='.$groupid.'&userid='.$userid] = "Select User to See Individual Record";
     echo '<center><br /><table width=270><tr><td><div><form action="?id='.$id.'" name="addtask" id="taskform" method="post"><select name="addtaskvalue" onchange="document.addtask.action = document.addtask.addtaskvalue.options[document.addtask.addtaskvalue.selectedIndex].value;document.addtask.submit(); return true;" >';

		foreach ($members as $member) {
			$user=get_record('user','id',$member->userid);
			$username=fullname($user);
        	$options['individual_report.php?id='.$id.'&userid='.$member->userid.'&groupid='.$member->groupid] =$username;
		}

        foreach ($options as $optionkey => $optionvalue) {
            echo '<option value ="'.$optionkey.'">'.$optionvalue.'</option>';
        }

        echo '</select>'.pbl_makelocalhelplinkmain ("createsubmission", "Help with Create Submission (new window)", "pbl").'</form></div></td></tr></table></center>';
echo '</center>';
 print_footer($course);
?>
