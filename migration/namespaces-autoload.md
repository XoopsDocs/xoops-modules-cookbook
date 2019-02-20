---
description: How to migrate older XOOPS modules to Namespaces and Autoloading?
---

# Namespaces/Autoload

1\) copy the folder /preloads to your module and change the name of the class in core.php

2\) split each class in your /class folder into Class and ClassHandler. For example, if you have a file 'items.php' with a class called Item and ItemHandler in a module called Bingo:

* add on top of the original class \(Bingo is the name of the module's directory, with first letter in CAPS\)

  ```php 
  <?php
  namespace XoopsModules\Bingo;
  ```

* rename the file to Item.php
* duplicate the file to ItemHandler.php
* in Item.php remove the ItemHandler code
* in ItemHandler.php remove the Item code

3\) add Helper.php class for consistency we call the class "Helper" and the variable ```$helper``` in all modules. Because it will be in namespace, we'll know exactly from which module it is coming from during debugging, so there is no need anymore to call it after the module, e.g. ```$publisher```, etc.

4\) replace all instantiation calls to the ItemHandler classes using XOOPS to call directly as 
```php
new `\Bingo\ItemHandler()" 
```
or using the Helper, which is the preferred way, e.g.:

```php 
$itemHandler = \XoopsModules\Bingo\Helper::getInstance()->getHandler('Item');`
```

Please note: I'm using the exact name of the class, incl. first letter in CAPS, because I don't want the Helper to deal with it, since some classes might have names like "ClassBestInTheWorld"

5\) when you using a namespaced Class, add on top the import code, e.g.:

```php 
use XoopsModules\Bingo\Item;
```

6\)In few cases you might include the call:

```php 
include dirname(__DIR__) . '/preloads/autoloader.php';
```
Because some files might not be aware of the namespaces.

7\) Classes that you use in all your modules, and you think that they would qualify for XMF, put them in /class/Common folder

8\) Few more items:

* make sure to rename the class file names so they match the class name, e.g. Item is in "Item.php"  \(the folders have to follow it as well\)
* adjust the constructors of the ItemHandler by using "Item::class", for example, from this:

```php
parent::__construct($db, 'bingo_items', 'item', 'itemid', 'title');
```
to this
```php
parent::__construct($db, 'bingo_items', Item::class, 'itemid', 'title');
```
* since we are now using namespaces, all XOOPS classes should be namespaced, i.e. you need to add "\" in front of them, so PHP knows what you mean
* make sure that all $helper instances exist
* remove direct links to module class files since we're using autoloading, e.g. these lines have to be deleted:

```php
include_once XOOPS_ROOT_PATH . '/modules/bingo/class/items.php';
```
since we're using now:

```php
$helper = \XoopsModules\Bingo\Helper::getInstance();
$itemHandler = $helper->getHandler('Item');
```
or directly:
```php
$itemHandler = \XoopsModules\Bingo\Helper::getInstance()->getHandler('Item');
```
The main objective was:

1\) standardization of the modules, i.e. standard classes are in /class/Common folder, so I can just copy them to each module I'm working on, and be done with it.

2\) common class and function names for the same classes and functions/methods, incl. PSR standards. That's why instance of Helper class should be $helper, and instance of a Handler class should be $classHandler and not the old $class\_handler or some other names

3\) We might also standardize on using the "reverse Hungarian notation" for variable, like: 
```php
$itemObj   
$itemsArray
```

to see right away what I'm dealing with, i.e. an object or an array, but if we completely switch to PHP7 and use strong typing, the need for it might go away.



