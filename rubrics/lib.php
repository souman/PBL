<?php  // $Id: lib.php,v 1.7.2.5 2009/04/22 21:30:57 skodak Exp $

/**
 * Library of functions and constants for module rubrics
 * This file should have two well differenced parts:
 *   - All the core Moodle functions, neeeded to allow
 *     the module to work integrated in Moodle.
 *   - All the rubrics specific functions, needed
 *     to implement all the module logic. Please, note
 *     that, if the module become complex and this lib
 *     grows a lot, it's HIGHLY recommended to move all
 *     these module specific functions to a new php file,
 *     called "locallib.php" (see forum, quiz...). This will
 *     help to save some memory when Moodle is performing
 *     actions across all modules.
 */

/// (replace rubrics with the name of your module and delete this line)

$rubrics_EXAMPLE_CONSTANT = 42;     /// for example


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $rubrics An object from the form in mod_form.php
 * @return int The id of the newly inserted rubrics record
 */
function rubrics_add_instance($rubrics) {

    $rubrics->timecreated = time();

    # You may have to add extra stuff in here #

    return insert_record('rubrics', $rubrics);
}


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $rubrics An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function rubrics_update_instance($rubrics) {

    $rubrics->timemodified = time();
    $rubrics->id = $rubrics->instance;

    # You may have to add extra stuff in here #

    return update_record('rubrics', $rubrics);
}


/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function rubrics_delete_instance($id) {

   if (! $rubrics = get_record('rubrics', 'id', $id)) {
        return false;
    }

    $result = true;

    # Delete any dependent records here #

    if (! delete_records('rubrics', 'id', $rubrics->id)) {
        $result = false;
    }
    //echo $COURSE->id;
    $name_to_id=get_record('modules','name','rubrics');
    $moduleid=get_record('course_modules','module',$name_to_id->id,'instance',$rubrics->id);
    if (! delete_records('rubrics_form', 'moduleid', $moduleid->id)) {
        $result = false;
    }
    if (! delete_records('rubrics_user_record', 'rubrics_id', $moduleid->id)) {
        $result = false;
    }
    if (! delete_records('rubric_record', 'moduleid', $moduleid->id)) {
        $result = false;
    }

    return $result;
}


/**
 * Return a small object with summary information about what a
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 */
function rubrics_user_outline($course, $user, $mod, $rubrics) {
    return $return;
}


/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function rubrics_user_complete($course, $user, $mod, $rubrics) {
    return true;
}


/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in rubrics activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function rubrics_print_recent_activity($course, $isteacher, $timestart) {
    return false;  //  True if anything was printed, otherwise false
}


/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such
 * as sending out mail, toggling flags etc ...
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function rubrics_cron () {
    return true;
}


/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of rubrics. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $rubricsid ID of an instance of this module
 * @return mixed boolean/array of students
 */
function rubrics_get_participants($rubricsid) {
    return false;
}


/**
 * This function returns if a scale is being used by one rubrics
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $rubricsid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 */
function rubrics_scale_used($rubricsid, $scaleid) {
    $return = false;

    //$rec = get_record("rubrics","id","$rubricsid","scale","-$scaleid");
    //
    //if (!empty($rec) && !empty($scaleid)) {
    //    $return = true;
    //}

    return $return;
}


/**
 * Checks if scale is being used by any instance of rubrics.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any rubrics
 */
function rubrics_scale_used_anywhere($scaleid) {
    if ($scaleid and record_exists('rubrics', 'grade', -$scaleid)) {
        return true;
    } else {
        return false;
    }
}


/**
 * Execute post-install custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function rubrics_install() {
    return true;
}


/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function rubrics_uninstall() {
    return true;
}


//////////////////////////////////////////////////////////////////////////////////////
/// Any other rubrics functions go here.  Each of them must have a name that
/// starts with rubrics_
/// Remember (see note in first lines) that, if this section grows, it's HIGHLY
/// recommended to move all funcions below to a new "localib.php" file.
function get_default($table,$column,$value) {
	$return=get_record($table,$column,$value);
	if($return!=NULL){
		return $return->value;
	}
	else {
		return NULL;
	}
}

/**
 * Checks if two user belongs to the same group or not.
 * @param int $user1id Id of the first user
 * @param int $user2id Id of the second user
 * @param int $course Id of the course
 */
function user_belong_to_same_group($user1id,$user2id,$courseid) {
	echo $user1id;
	echo '<br/>';
	echo $user2id;
	$groupids1=get_records('groups_members','userid',$user1id);
	$groupids2=get_records('groups_members','userid',$user2id);
	$check=0;
	foreach($groupids1 as $groupid1) {
		foreach($groupids2 as $groupid2) {
			if ($groupid1->groupid==$groupid2->groupid) {
				$check=1;
			}
		}
	}
	if($check==1) {
		return true;
	}
	else{
		return false;
	}
}

?>
