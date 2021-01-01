# Configuration files

The module configuration files are located in /config folder.

Typical configuration files:

1) config.php


```php
use Xmf\Module\Admin;

$moduleDirName      = basename(dirname(__DIR__));

return (object)[
    'name'           => mb_strtoupper($moduleDirName) . ' ModuleConfigurator',
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
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/category',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/screenshots',
        //XOOPS_UPLOAD_PATH . '/flags'
    ],
    'copyBlankFiles' => [
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/category',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/screenshots',
        //XOOPS_UPLOAD_PATH . '/flags'
    ],

    'copyTestFolders' => [
        [
            XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/testdata/uploads',
            XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        ],
    ],

    'templateFolders' => [
        '/templates/',
        //            '/templates/blocks/',
        //            '/templates/admin/'
    ],
    //old files to be deleted during module update
    'oldFiles'        => [
        '/class/request.php',
        '/class/registry.php',
        '/class/utilities.php',
        '/class/util.php',
        //            '/include/constants.php',
        //            '/include/functions.php',
        '/ajaxrating.txt',
    ],
    //old folders to be deleted during module update
    'oldFolders'      => [
        '/images',
        '/css',
        '/js',
        '/tcpdf',
    ],
    // list all tables that should be renamed during module update
    'renameTables' => [
    //         'XX_archive'     => 'ZZZZ_archive',
    ],
    // module stats to be calculated
    'moduleStats'  => [
        //            'totalcategories' => $helper->getHandler('Category')->getCategoriesCount(-1),
        //            'totalitems'      => $helper->getHandler('Item')->getItemsCount(),
        //            'totalsubmitted'  => $helper->getHandler('Item')->getItemsCount(-1, [Constants::PUBLISHER_STATUS_SUBMITTED]),
    ],
    'modCopyright' => "<a href='https://xoops.org' title='XOOPS Project' target='_blank'>
                     <img src='" . Admin::iconUrl('xoopsmicrobutton.gif') . "' alt='XOOPS Project'></a>",
];
```


2) icons.php

```php
<?php

use Xmf\Module\Admin;

$pathIcon16    = Admin::iconUrl('', 16);
$moduleDirName = basename(dirname(__DIR__));

return (object)[
    'name'  => mb_strtoupper($moduleDirName) . ' IconConfigurator',
    'icons' => [
        'edit'    => "<img src='" . $pathIcon16 . "/edit.png'  alt=" . _EDIT . "' align='middle'>",
        'delete'  => "<img src='" . $pathIcon16 . "/delete.png' alt='" . _DELETE . "' align='middle'>",
        'clone'   => "<img src='" . $pathIcon16 . "/editcopy.png' alt='" . _CLONE . "' align='middle'>",
        'preview' => "<img src='" . $pathIcon16 . "/view.png' alt='" . _PREVIEW . "' align='middle'>",
        'print'   => "<img src='" . $pathIcon16 . "/printer.png' alt='" . _CLONE . "' align='middle'>",
        'pdf'     => "<img src='" . $pathIcon16 . "/pdf.png' alt='" . _CLONE . "' align='middle'>",
        'add'     => "<img src='" . $pathIcon16 . "/add.png' alt='" . _ADD . "' align='middle'>",
        '0'       => "<img src='" . $pathIcon16 . "/0.png' alt='" . 0 . "' align='middle'>",
        '1'       => "<img src='" . $pathIcon16 . "/1.png' alt='" . 1 . "' align='middle'>",
    ],
];
```

3) paths.php

```php
<?php

$moduleDirName      = basename(dirname(__DIR__));
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

return (object)[
    'name'          => mb_strtoupper($moduleDirName) . ' PathConfigurator',
    'paths'         => [
        'dirname'    => $moduleDirName,
        'admin'      => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName . '/admin',
        'modPath'    => XOOPS_ROOT_PATH . '/modules/' . $moduleDirName,
        'modUrl'     => XOOPS_URL . '/modules/' . $moduleDirName,
        'uploadPath' => XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        'uploadUrl'  => XOOPS_UPLOAD_URL . '/' . $moduleDirName,
    ],
    'uploadFolders' => [
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName,
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/category',
        XOOPS_UPLOAD_PATH . '/' . $moduleDirName . '/screenshots',
    ],
];
```
