<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Seleda\Cdek;

class AutoLoader
{
    public $class_index = array();
    public $module_path = _PS_MODULE_DIR_.'cdek20'.DIRECTORY_SEPARATOR;

    public static function init()
    {
        spl_autoload_register([new self, 'autoload']);
    }

    public function __construct()
    {
        $class_index_path = $this->module_path.'class_index.php';
        if (file_exists($class_index_path)) {
            $this->class_index = require $class_index_path;
        } else {
            $this->setClassIndex('classes');
            $this->setClassIndex('controllers');
        }
    }

    public function setClassIndex($dir)
    {
        $dir = str_replace($this->module_path, '', $dir);
        foreach (scandir($this->module_path.$dir) as $file) {
            if (strpos($file, '.') === 0) {
                continue;
            }

            $file_path = $dir.DIRECTORY_SEPARATOR.$file;

            if (is_dir($this->module_path.$file_path)) {
                $this->setClassIndex($this->module_path.$file_path);
                continue;
            }
            if (substr($file, -4) != '.php') {
                continue;
            }

            preg_match('/namespace\s+([\w\\\]+);/u', file_get_contents($this->module_path.$file_path), $match);
            $namespace = isset($match[1]) && $match[1] ? $match[1].'\\' : '';
            $this->class_index[$namespace.substr($file, 0, -4)] = $file_path;
        }
    }

    public function generateClassIndex()
    {
        $contentNamespacedStub = '<?php '."\n".'namespace Seleda;'."\n\n".'return array('."\n";
        foreach ($this->class_index as $class_name => $path) {
            $contentNamespacedStub .= '\''.$class_name.'\''.' => \''.$path.'\','."\n";
        }
        $contentNamespacedStub .= ');';
        $this->dumpFile($this->module_path.'class_index.php', $contentNamespacedStub);
    }

    public function dumpFile($filename, $content)
    {
        $dir = dirname($filename);

        // Will create a temp file with 0600 access rights
        // when the filesystem supports chmod.
        $tmpFile = tempnam($dir, basename($filename));
        if (false === @file_put_contents($tmpFile, $content)) {
            return false;
        }
        // Ignore for filesystems that do not support umask
        @chmod($tmpFile, file_exists($filename) ? fileperms($filename) : 0666 & ~umask());
        rename($tmpFile, $filename);

        return true;
    }

    public function autoload($class_name)
    {
        if (isset($this->class_index[$class_name])) {
            require_once $this->module_path.$this->class_index[$class_name];
        }
    }

    public function __destruct()
    {
        $class_index_path = $this->module_path.'class_index.php';
        if (file_exists($class_index_path)) {
            return;
        }
        $this->generateClassIndex();
    }
}

AutoLoader::init();
