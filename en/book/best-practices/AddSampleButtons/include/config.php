<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    XOOPS Project https://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team
 */
function getConfig()
{
    $moduleDirName      = basename(dirname(__DIR__));
    $moduleDirNameUpper = mb_strtoupper($moduleDirName);
    
    if (!defined($moduleDirNameUpper . '_AUTHOR_LOGOIMG')) {
        $pathIcon32 = \Xmf\Module\Admin::iconUrl('', 32);
        define($moduleDirNameUpper . '_AUTHOR_LOGOIMG', $pathIcon32 . '/xoopsmicrobutton.gif');
    }

    return (object)[
        'name'           => mb_strtoupper($moduleDirName) . ' Module Configurator',
        'paths'          => [
            'dirname'    => $moduleDirName,
            'admin'      => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/admin',
            'modPath'    => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName,
            'modUrl'     => XOOPS_URL . '/modules/' . $moduleDirName,
            'uploadPath' => XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
            'uploadUrl'  => XOOPS_UPLOAD_URL . '/' . $moduleDirName,
        ],
        'uploadFolders'  => [
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images',
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images/items',
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images/timelines',
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/temp',
        ],
        'copyBlankFiles' => [
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images',
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images/items',
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/images/timelines',
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/temp',
        ],

        'copyTestFolders' => [
            [
                XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/testdata/uploads',
                XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
            ],
        ],

        'templateFolders' => [
            '/templates/',
            '/templates/blocks/',
            '/templates/admin/'
        ],
        'oldFiles'        => [
            '/include/install.php',
            '/include/update.php',
        ],
        'oldFolders'      => [
        ],

        'renameTables' => [//         'XX_archive'     => 'ZZZZ_archive',
        ],
        'modCopyright' => "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . constant($moduleDirNameUpper . '_AUTHOR_LOGOIMG') . '\' alt=\'XOOPS Project\'></a>',
    ];
}
