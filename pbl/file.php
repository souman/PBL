<?php
/*
 * Created on 15-Jun-2011
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

 require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
//require_once(dirname(__FILE__).'/filelib.php');
global $CFG, $USER,$COURSE;
require_once("$CFG->libdir/filelib.php");
$id = required_param('id', PARAM_INT); //
$solutionid = required_param('solutionid', PARAM_INT); //

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

require_login($course);
$context = get_context_instance(CONTEXT_MODULE, $cm->id);
$solution=get_record('pbl_solution','id',$solutionid);
$lifetime = 0;
$filename=$solution->file_name;
$pathname=$solution->file_location;
if (file_exists($pathname)) {
		send_file($pathname, $filename, $lifetime, !empty($CFG->filteruploadedfiles));
    } else {
        header('HTTP/1.0 404 not found');
        error(get_string('filenotfound', 'error') . " ($pathname)", $CFG->wwwroot .'/course/view.php?id='. $course->id);
    }

?>
