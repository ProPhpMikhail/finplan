<?php
require_once(__DIR__ . '/../init.php');

$action = $argv[1];
if (isset($argv[2])) {
    $name = $argv[2];
}

switch ($action) {
    case 'roll':
        $class = require_once($name . '/migration.php');
        $class->up();
        break;
    case 'rollback':
        $class = require_once($name . '/migration.php');
        $class->down();
        break;
    case 'add':
        $ts = time();
        mkdir(__DIR__ . '/m' . $ts);
        file_put_contents(__DIR__ . '/m' . $ts . '/migration.php', file_get_contents(__DIR__ . '/migration_ex.php'));
        break;
    default:
        echo 'Unknown action';
}
