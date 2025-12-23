<?php

require_once("../backend/Loader.php");

spl_autoload_register("\Loader::load");

$db_pool = new \db\DBPool();

$db_conn = $db_pool->get_connection();

echo "<pre>";
print_r($db_conn);
echo "</pre>";

$db_pool->release_connection($db_conn);

$db_conn = $db_pool->get_connection();

echo "<pre>";
print_r($db_conn);
echo "</pre>";

$db_pool->release_connection($db_conn);

echo "<pre>";
echo \Config\AppConfig::get_instance()->DB_USERNAME;
echo "</pre>";