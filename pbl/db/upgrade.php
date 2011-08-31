<?php  //$Id: upgrade.php,v 1.2 2007/08/08 22:36:54 stronk7 Exp $

// This file keeps track of upgrades to
// the pbl module
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

function xmldb_pbl_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

/// And upgrade begins here. For each one, you'll need one
/// block of code similar to the next one. Please, delete
/// this comment lines once this file start handling proper
/// upgrade code.

/// if ($result && $oldversion < YYYYMMDD00) { //New version in version.php
///     $result = result of "/lib/ddllib.php" function calls
/// }

/// Lines below (this included)  MUST BE DELETED once you get the first version
/// of your module ready to be installed. They are here only
/// for demonstrative purposes and to show how the pbl
/// iself has been upgraded.

/// For each upgrade block, the file pbl/version.php
/// needs to be updated . Such change allows Moodle to know
/// that this file has to be processed.

/// To know more about how to write correct DB upgrade scripts it's
/// highly recommended to read information available at:
///   http://docs.moodle.org/en/Development:XMLDB_Documentation
/// and to play with the XMLDB Editor (in the admin menu) and its
/// PHP generation posibilities.

/// First example, some fields were added to the module on 20070400
    if ($result && $oldversion < 2007101530) {

/// Define table pbl_groups_members to be created
        $table = new XMLDBTable('pbl_groups_members');

    /// Adding fields to table pbl_groups_members
        $table->addFieldInfo('id', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null, null);
        $table->addFieldInfo('userid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, null, null);
        $table->addFieldInfo('groupid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, null, null);

    /// Adding keys to table pbl_groups_members
        $table->addKeyInfo('primary', XMLDB_KEY_PRIMARY, array('id'));

    /// Launch create table for pbl_groups_members
        $result = $result && create_table($table);
    }

    return $result;
}

?>
