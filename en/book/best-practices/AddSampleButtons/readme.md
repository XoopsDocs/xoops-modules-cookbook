In this example we say:
- the name of the module is mymodule


# 1) Adapt xoops_version.php
add definition of vars (if not existing)

      $moduleDirName      = basename(__DIR__);
      $moduleDirNameUpper = mb_strtoupper($moduleDirName);

and add following preference

      /**
       * Make Sample button visible?
       */
      $modversion['config'][] = [
          'name'        => 'displaySampleButton',
          'title'       => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON',
          'description' => 'CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC',
          'formtype'    => 'yesno',
          'valuetype'   => 'int',
          'default'     => 1,
      ];

# 2) Language files      
add the defines to your language file common.php

      //Sample Data
      define('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA', 'Import Sample Data (will delete ALL current data)');
      define('CO_' . $moduleDirNameUpper . '_' . 'SAMPLEDATA_SUCCESS', 'Sample Date uploaded successfully');
      define('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA', 'Export Tables to YAML');
      define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON', 'Show Sample Button?');
      define('CO_' . $moduleDirNameUpper . '_' . 'SHOW_SAMPLE_BUTTON_DESC', 'If yes, the "Add Sample Data" button will be visible to the Admin. It is Yes as a default for first installation.');
      define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA', 'Export DB Schema to YAML');
      define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_SUCCESS', 'Export DB Schema to YAML was a success');
      define('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA_ERROR', 'ERROR: Export of DB Schema to YAML failed');

add to your language file admin.php and modinfo.php following line

    require_once __DIR__ . '/common.php';

# 3) copying following folders and files into your module

  * test/*
  * class/Common/*
  * class/Utility.php
  * include/config.php


replace all namespace calls

wgexample

by

mymodule


# 3) Adapt admin/index.php

add following code to your admin/index.php

      //------------- Test Data ----------------------------
      if ($helper->getConfig('displaySampleButton')) {
          xoops_loadLanguage('admin/modulesadmin', 'system');
          require dirname(__DIR__) . '/testdata/index.php';

          $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'ADD_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=load', 'add');
          $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'SAVE_SAMPLEDATA'), '__DIR__ . /../../testdata/index.php?op=save', 'add');
          //    $adminObject->addItemButton(constant('CO_' . $moduleDirNameUpper . '_' . 'EXPORT_SCHEMA'), '__DIR__ . /../../testdata/index.php?op=exportschema', 'add');
          $adminObject->displayButton('left', '');
      }
      //------------- End Test Data ----------------------------
