<?php  //$Id: upgrade.php,v 1.2 2007/08/08 22:36:54 stronk7 Exp $

// This file keeps track of upgrades to
// the newmodule module
//
// Sometimes, changes between versions involve
// alterations to database structures and other
// major things that may break installations.
//
// The upgrade function in this file will attempt
// to perform all the necessary actions to upgrade
// your older installtion to the current version.
//
// If there's something it cannot do itself, it
// will tell you what you need to do.
//
// The commands in here will all be database-neutral,
// using the functions defined in lib/ddllib.php

function xmldb_rubrics_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

     if ($result && $oldversion < 2007040225) {
    /// Define field moduleid to be added to rubric_record
        $table = new XMLDBTable('rubric_record');
        $field = new XMLDBField('moduleid');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, null, null, 'texts');

    /// Launch add field moduleid
        $result = $result && add_field($table, $field);

     }
    return $result;
}

?>
