<?php
require_once(dirname(__FILE__).'/config/config.inc.php');

$login = $argv[1];
$password = $argv[2];

Db::getInstance()->update(
    'employee',
    array(
        'email' => $login,
        'passwd' => Tools::encrypt($password)
    ),
    ' `id_employee` = 1'
);

echo "--Admin in shop update successfully!--";