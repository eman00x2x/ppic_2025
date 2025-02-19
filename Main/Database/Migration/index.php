<?php

require_once("Database.php");
require_once("DBMigration.php");

$host = "localhost";
$db = "philipr3_crm_api";
$user = "philipr3_crm2019";
$pass = "12345!@#$%PHILPropertiescrm";
$dbPrefix = "api";

$db = new DBMigration($host, $db, $user, $pass, $dbPrefix);
/* $db->migrateTableUser(); */
$db->migrateTableProperties();
/* $db->migrateTablePropertyImages();
$db->migrateTableArticles();
$db->migrateTableLeads(); */

?>