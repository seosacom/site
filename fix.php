<?php
require_once('config/config.inc.php');
Configuration::updateGlobalValue('PS_REWRITING_SETTINGS', 1);
Configuration::updateValue('PS_REWRITING_SETTINGS', 1);
Tools::generateHtaccess();
echo "--Fix after install successfully!--";