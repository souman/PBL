<?php  // $Id: lib.php,v 1.7.2.5 2009/04/22 21:30:57 skodak Exp $

/**
 * Library of functions and constants for module pbl
 * This file should have two well differenced parts:
 *   - All the core Moodle functions, neeeded to allow
 *     the module to work integrated in Moodle.
 *   - All the pbl specific functions, needed
 *     to implement all the module logic. Please, note
 *     that, if the module become complex and this lib
 *     grows a lot, it's HIGHLY recommended to move all
 *     these module specific functions to a new php file,
 *     called "locallib.php" (see forum, quiz...). This will
 *     help to save some memory when Moodle is performing
 *     actions across all modules.
 */

/// (replace pbl with the name of your module and delete this line)

$pbl_EXAMPLE_CONSTANT = 42;     /// for example


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @param object $pbl An object from the form in mod_form.php
 * @return int The id of the newly inserted pbl record
 */
function pbl_add_instance($pbl) {

    $pbl->timecreated = time();

    # You may have to add extra stuff in here #

    return insert_record('pbl', $pbl);
}


/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @param object $pbl An object from the form in mod_form.php
 * @return boolean Success/Fail
 */
function pbl_update_instance($pbl) {

    $pbl->timemodified = time();
    $pbl->id = $pbl->instance;

    # You may have to add extra stuff in here #

    return update_record('pbl', $pbl);
}


/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 */
function pbl_delete_instance($id) {

    if (! $pbl = get_record('pbl', 'id', $id)) {
        return false;
    }

    $result = true;

    # Delete any dependent records here #

    if (! delete_records('pbl', 'id', $pbl->id)) {
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
function pbl_user_outline($course, $user, $mod, $pbl) {
    return $return;
}


/**
 * Print a detailed representation of what a user has done with
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function pbl_user_complete($course, $user, $mod, $pbl) {
    return true;
}


/**
 * Given a course and a time, this module should find recent activity
 * that has occurred in pbl activities and print it out.
 * Return true if there was output, or false is there was none.
 *
 * @return boolean
 * @todo Finish documenting this function
 */
function pbl_print_recent_activity($course, $isteacher, $timestart) {
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
function pbl_cron () {
    return true;
}


/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of pbl. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $pblid ID of an instance of this module
 * @return mixed boolean/array of students
 */
function pbl_get_participants($pblid) {
    return false;
}


/**
 * This function returns if a scale is being used by one pbl
 * if it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $pblid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 */
function pbl_scale_used($pblid, $scaleid) {
    $return = false;

    //$rec = get_record("pbl","id","$pblid","scale","-$scaleid");
    //
    //if (!empty($rec) && !empty($scaleid)) {
    //    $return = true;
    //}

    return $return;
}


/**
 * Checks if scale is being used by any instance of pbl.
 * This function was added in 1.9
 *
 * This is used to find out if scale used anywhere
 * @param $scaleid int
 * @return boolean True if the scale is used by any pbl
 */
function pbl_scale_used_anywhere($scaleid) {
    if ($scaleid and record_exists('pbl', 'grade', -$scaleid)) {
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
function pbl_install() {
    return true;
}


/**
 * Execute post-uninstall custom actions for the module
 * This function was added in 1.9
 *
 * @return boolean true if success, false on error
 */
function pbl_uninstall() {
    return true;
}


//////////////////////////////////////////////////////////////////////////////////////
/// Any other pbl functions go here.  Each of them must have a name that
/// starts with pbl_
/// Remember (see note in first lines) that, if this section grows, it's HIGHLY
/// recommended to move all funcions below to a new "localib.php" file.
function pbl_makelocalhelplinkmain ($name, $value, $modulename) {

    global $CFG;

    $return = '<span class="helplink"><a target="popup" title="'.$value.'" href="'.$CFG->wwwroot.'/help.php?module='.$modulename.'&amp;file='.$name.'.html&amp;forcelang=" onclick="return openpopup(\'/help.php?module='.$modulename.'&amp;file='.$name.'.html&amp;forcelang=\', \'popup\', \'menubar=0,location=0,scrollbars,resizable,width=500,height=400\', 0);"><img alt="'.$value.'" src="'.$CFG->wwwroot.'/pix/help.gif" /></a></span>';

    return $return;
}

function module_id_name($name) {
    $return = get_record('modules','name',$name);
    return $return;
}

function update_pbl_forum($courseid,$pblid) {


    $return = get_record('modules','name','forum');
    $forumid=get_records('course_modules','module',$return->id);
    $maxidobj=get_record('pbl_forum','pblid',$pbl->id);
    if($maxidobj->forumid=='') {
        $maxid=0;
		foreach ($forumid as $x ) {

			if($maxid<$x->id){
				$maxid=$x->id;
				$instanceid=$x->instance;
			}
	    }
		$up->forumid=$maxid;
		$up->pblid=$pblid;
		$up->courseid=$courseid;
		$up->instanceid=$instanceid;
		return (insert_record('pbl_forum',$up));
    }
}


function update_pbl_discuss($courseid,$pblid,$type,$userid,$moduletype) {

    $return = get_record('modules','name',$type);
    $forumid=get_records('course_modules','module',$return->id);
    $maxid=0;
	foreach ($forumid as $x ) {
		if($maxid<$x->id){
		$maxid=$x->id;
		$instanceid=$x->instance;
		}
	}
		$up->type=$type;
		$up->pblid=$pblid;
		$up->courseid=$courseid;
		$up->userid=$userid;
		$up->instanceid=$instanceid;
		$up->groupid=0;
		$up->moduleid=$maxid;
		$up->moduletype=$moduletype;
		return (insert_record('pbl_discuss',$up));
}

 ?>