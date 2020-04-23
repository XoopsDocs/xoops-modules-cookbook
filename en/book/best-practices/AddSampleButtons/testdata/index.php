<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package
 * @since           2.5.9
 * @author          Michael Beck (aka Mamba)
 */

use XoopsModules\Myexample;
use XoopsModules\Myexample\Common;

require dirname(dirname(dirname(__DIR__))) . '/mainfile.php';
include dirname(__DIR__) . '/preloads/autoloader.php';
$op = \Xmf\Request::getCmd('op', '');

switch ($op) {
    case 'load':
        loadSampleData();
        break;
    case 'save':
        saveSampleData();
        break;
}

// XMF TableLoad for SAMPLE data

function loadSampleData()
{
    $moduleDirName      = basename(dirname(__DIR__));
    $moduleDirNameUpper = mb_strtoupper($moduleDirName);
    $helper             = Myexample\Helper::getInstance();
    $utility            = new Myexample\Utility();
    $configurator       = new Common\Configurator();
    // Load language files
    $helper->loadLanguage('admin');
    $helper->loadLanguage('modinfo');
    $helper->loadLanguage('common');

    //    $items = \Xmf\Yaml::readWrapped('quotes_data.yml');
    //    \Xmf\Database\TableLoad::truncateTable($moduleDirName . '_quotes');
    //    \Xmf\Database\TableLoad::loadTableFromArray($moduleDirName . '_quotes', $items);

    $tables = \Xmf\Module\Helper::getHelper($moduleDirName)->getModule()->getInfo('tables');

    foreach ($tables as $table) {
        $tabledata = \Xmf\Yaml::readWrapped($table . '.yml');
        \Xmf\Database\TableLoad::truncateTable($table);
        \Xmf\Database\TableLoad::loadTableFromArray($table, $tabledata);
    }

    // load permissions
    $table     = 'group_permission';
    $tabledata = \Xmf\Yaml::readWrapped($table . '.yml');
    $mid       = \Xmf\Module\Helper::getHelper($moduleDirName)->getModule()->getVar('mid');
    loadTableFromArrayWithReplace($table, $tabledata, 'gperm_modid', $mid);

    //  ---  COPY test folder files ---------------
    if (is_array($configurator->copyTestFolders) && count($configurator->copyTestFolders) > 0) {
        //        $file =  dirname(__DIR__) . '/testdata/images/';
        foreach (array_keys($configurator->copyTestFolders) as $i) {
            $src  = $configurator->copyTestFolders[$i][0];
            $dest = $configurator->copyTestFolders[$i][1];
            $utility::rcopy($src, $dest);
        }
    }

    redirect_header('../admin/index.php', 1, constant('CO_' . $moduleDirNameUpper . '_' . 'SAMPLEDATA_SUCCESS'));
}

function saveSampleData()
{
    $moduleDirName      = basename(dirname(__DIR__));
    $moduleDirNameUpper = mb_strtoupper($moduleDirName);

    $tables = \Xmf\Module\Helper::getHelper($moduleDirName)->getModule()->getInfo('tables');

    foreach ($tables as $table) {
        \Xmf\Database\TableLoad::saveTableToYamlFile($table, $table . '_' . date('Y-m-d H-i-s') . '.yml');
    }

    $criteria = new \CriteriaCompo();
    $criteria->add(new \Criteria('gperm_modid', \Xmf\Module\Helper::getHelper($moduleDirName)->getModule()->getVar('mid')));
    $skipColumns[] = 'gperm_id';
    \Xmf\Database\TableLoad::saveTableToYamlFile('group_permission', 'group_permission_' . date('Y-m-d H-i-s') . '.yml', $criteria, $skipColumns);
    unset($criteria);

    redirect_header('../admin/index.php', 1, constant('CO_' . $moduleDirNameUpper . '_' . 'SAMPLEDATA_SUCCESS'));
}

function exportSchema()
{
    try {
        $moduleDirName      = basename(dirname(__DIR__));
        $moduleDirNameUpper = mb_strtoupper($moduleDirName);

        $migrate = new \Xmf\Database\Migrate($moduleDirName);
        $migrate->saveCurrentSchema();

        redirect_header('../admin/index.php', 1, constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_SUCCESS'));
    }
    catch (\Exception $e) {
        exit(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_ERROR'));
    }
}

/**
 * loadTableFromArrayWithReplace
 *
 * @param string $table  value with should be used insead of original value of $search
 *
 * @param array  $data   array of rows to insert
 *                       Each element of the outer array represents a single table row.
 *                       Each row is an associative array in 'column' => 'value' format.
 * @param string $search name of column for which the value should be replaced
 * @param        $replace
 * @return int number of rows inserted
 */
function loadTableFromArrayWithReplace($table, $data, $search, $replace)
{
    /** @var \XoopsDatabase */
    $db = \XoopsDatabaseFactory::getDatabaseConnection();

    $prefixedTable = $db->prefix($table);
    $count         = 0;

    $sql = 'DELETE FROM ' . $prefixedTable . ' WHERE `' . $search . '`=' . $db->quote($replace);

    $result = $db->queryF($sql);

    foreach ($data as $row) {
        $insertInto  = 'INSERT INTO ' . $prefixedTable . ' (';
        $valueClause = ' VALUES (';
        $first       = true;
        foreach ($row as $column => $value) {
            if ($first) {
                $first = false;
            } else {
                $insertInto  .= ', ';
                $valueClause .= ', ';
            }

            $insertInto .= $column;
            if ($search === $column) {
                $valueClause .= $db->quote($replace);
            } else {
                $valueClause .= $db->quote($value);
            }
        }

        $sql = $insertInto . ') ' . $valueClause . ')';

        $result = $db->queryF($sql);
        if (false !== $result) {
            ++$count;
        }
    }

    return $count;
}
