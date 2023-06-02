<?php
unlink(dirname(__FILE__).'/.htaccess');
require_once(dirname(__FILE__).'/config/config.inc.php');
Tools::generateHtaccess();
Tools::clearCache($context->smarty);
if (method_exists('Tools', 'clearSf2Cache')) {
    Tools::clearSf2Cache();
}
echo "--Htaccesss update successfully!--";