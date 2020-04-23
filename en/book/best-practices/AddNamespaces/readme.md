## This is a short description how to add usage of namespaces to your module

The module name of the included files is set by default as 'myexample'.

In this description we say, that the name of the module, which you want to adapt, is 'mymodule'.
So, if you copy the code from this tutorial then replace 'mymodule' by your module name. 

Pay attention to upper case: if in the tutorial there is 'Mymodule' then your module name must also starts with upper case.

In this example we say mymodule contains categories and items

### 1) copy the folder /preloads to your module and change the name of the class in core.php
  replace

    class MyexampleCorePreload extends XoopsPreloadItem
  by

    class MymoduleCorePreload extends XoopsPreloadItem

  the first character must be capital (MymoduleCorePreload, not mymoduleCorePreload)
### 2) split each class in your folder /class folder into Class and ClassHandler
e.g. you have till now "items.php"
* split into Items.php and ItemsHandler.php (first character of module name must be capital, and "Handler" must also start with capital)

  add to each

       namespace XoopsModules\Mymodule;

   (first character of module name must be capital)

* in Items.php
  * remove the ItemsHandler code (all from "class MymodulesItemsHandler extends XoopsPersistableObjectHandler")
  * change

        class MymoduleItems extends XoopsObject  

    into  

        class Items extends \XoopsObject

  * in ItemsHandler.php
    * remove the Class code (all from "class MymodulesItems extends XoopsObject")
    * change
        * replace

              class MymoduleItemsHandler extends XoopsPersistableObjectHandler

          by

              class ItemsHandler extends \XoopsPersistableObjectHandler

        * change in: `public function __construct(\XoopsDatabase $db)`

          replace

               parent::__construct($db, 'mymodule_items', 'mymoduleitemss', 'item_id', 'item_title');

          by

               parent::__construct($db, 'mymodule_items', Items:class, 'item_id', 'item_title');"

    * the same for categories.php

### 3) add or replace Helper.php class
for consistency we call the class "Helper" and the variable "$helper" in all
modules. Because it will be in namespace, we'll know exactly from which
module it is coming from during debugging, so there is no need anymore to
call it "$publisher", etc.

replace in Helper.php

    namespace XoopsModules\Myexample;

by

    namespace XoopsModules\Mymodule;

### 4) replace all instantiation calls to the ClassHandler classes using XOOPS
to call directly, e.g calling handler for items in mymodule
* as

      new \Mymodule\ItemsHandler()

* or using Helper

      $itemsHandler = \XoopsModules\Mymodule\Helper::getInstance()->getHandler('Items');

Please note: use the exact name of the class, incl. first letter in
CAPS, because we don't want the Helper to deal with it, since some classes
might have names like "ClassBestInTheWorld"

### 5) when you using a namespaced Class, add on top the import code, e.g.:

      use XoopsModules\Mymodule\Items;
      use XoopsModules\Mymodule\Categories;

### 6) replace all Xoops classes by calling the namespaces

* general Xoops objects

      XoopsUser          by \XoopsUser
      XoopsLists         by \XoopsLists
      XoopsDatabase      by \XoopsDatabase
      XoopsMediaUploader by \XoopsMediaUploader
      XoopsPageNav       by \XoopsPageNav
      XoopsTpl           by \XoopsTpl
      XoopsModule        by \XoopsModule
      XoopsPreloadItem   by \XoopsPreloadItem
      CriteriaCompo      by \CriteriaCompo
      Criteria           by \Criteria

* Xoops form objects

      XoopsThemeForm  by \XoopsThemeForm
      XoopsFormSelect by \XoopsFormSelect
      XoopsFormText   by \XoopsFormText
      and so on for each XoopsForm...

### 7) Usage of Xmf\Request

Add to each file where you want to replace XoopsRequest by Xmf\Request the call

    use Xmf\Request;

now you can repace e.g.

    $op = XoopsRequest::getString('op', 'list');

by

    $op = Request::getString('op', 'list');


### One more thing
in few cases you might include in header.php the call:

    include dirname(__DIR__) . '/preloads/autoloader.php';

Because some files might not be aware of the namespaces.
