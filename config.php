<?php

use lib\ConfigHelper;
use lib\LogHelper;

/*
 * THIS IS THE CONFIG FILE FOR
 *
 * A. INSTALLATION SETTINGS
 * B. DEVELOPMENT SETTINGS
 */

/****************************************************************
A. INSTALLATION SETTINGS
****************************************************************/

// BASE URL =================================
// Project URL Config. example: http://openmedis.davidhuser.ch/api
ConfigHelper::write('path', 'http://openmedis-prod.davidhuser.ch/api');

// DATABASE =================================
// MySQLi DB Config
ConfigHelper::write('db.host', 'localhost');

// database root name (in ehealthbox it is openmedis)
ConfigHelper::write('db.basename', '');
ConfigHelper::write('db.user', '');
ConfigHelper::write('db.password', '');

// LOGGING =================================
// enable logging on whole server. Log file under directory /log/log.log.
// it logs e.g. who logged in, who deleted assets, error messages, ...
ConfigHelper::write('log', TRUE);

// how long the logging should be kept on the server before the file gets deleted.
// e.g. 168 => 168/24 = 7 days.
ConfigHelper::write('log.hours', 168);

// DEBUG MODE =================================
// enable debug mode on whole server. See slim docs.
ConfigHelper::write('debug', TRUE);

// COMPRESSION =================================
// determine if serverside compression library zlib for gzip is enabled. Otherwise data usage can get large!
if (extension_loaded("zlib")) {
    ob_start("ob_gzhandler");
} else {
    $message = 'No zlib extension for HTTP compression installed on server.
    Please contact webhoster to install the zlib extension.
    PHPinfo can be found under www.example.com/api/info.php';
    echo $message;
    LogHelper::write($message);
}

/****************************************************************
B. DEVELOPMENT SETTINGS
 ****************************************************************/

// MOBILE SCOPE FOR MYSQL DB TABLES ==================
// relevant tables in mobile scope
$tables = array('assets', 'location', 'facilities', 'contacttype', 'contact', 'donors', 'agents', 'suppliers',
    'manufactures', 'consumables', 'consumables_linked', 'employees', 'stock', 'department',
    'essential_equipment', 'assetgenericname', 'assetutilization', 'assetstatus', 'assetcategory', 'intervention',
    'request', 'request_st', 'warrantycontract', 'intervention_material', 'intervention_work', 'visit_type',
    'failurcateg', 'failurecause');

ConfigHelper::write('db.mysql_tables', $tables);
