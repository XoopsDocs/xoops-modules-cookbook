---
description: How to migrate older XOOPS modules to Namespaces and Autoloading?
---

# Namespaces/Autoload

1\) copy the folder /preloads to your module and change the name of the class in core.php

2\) split each class in your /class folder into Class and ClassHandler

* add on top of the original class \(Xxxxx is the name of the module's directory, with first letter in CAPS\)

  namespace XoopsModules\Xxxxx

* rename the file to Class.php
* copy the files to ClassHandler.php
* in Class.php remove the ClassHandler code
* in ClassHandler.php remove the Class code

3\) add Helper.php class for consistency we call the class "Helper" and the variable "$helper" in all modules. Because it will be in namespace, we'll know exactly from which module it is coming from during debugging, so there is no need anymore to call it "$publisher", etc.

4\) replace all instantiation calls to the ClassHandler classes using XOOPS to call directly as "new `\Xxxxx\ClassHandler()" or using Helper, e.g.:`

`$tagHandler = \XoopsModules\Tag\Helper::getInstance()->getHandler('Tag');`

Please note: I'm using the exact name of the class, incl. first letter in CAPS, because I don't want the Helper to deal with it, since some classes might have names like "ClassBestInTheWorld"

5\) when you using a namespaced Class, add on top the import code, e.g.:

`use XoopsModules\Xxxxx\Class;`

6\) One more thing: in few cases you might include the call:

`include dirname(__DIR__) . '/preloads/autoloader.php';`  



Because some files might not be aware of the namespaces.

7\) Classes that you use in all your modules, and you think that they would qualify for XMF, put them in /class/Common folder

8\) Few more items:

* rename the class file names so they match the class name, e.g. Team is in "Team.php"  \(the folders have to follow it as well\)
* adjust the constructors of the ClassHandler by using "Class::class"
* since we are now using namespaces, all XOOPS classes should be namespaced, i.e. you need to add "\" in front of them, so PHP knows what you mean
* make sure that all $helper instances exist
* remove direct links to class files since we're using autoloading

The main objective was:

1\) standardization of the modules, i.e. standard classes are in /class/Common folder, so I can just copy them to each module I'm working on, and be done with it.

2\) common class and function names for the same classes and functions/methods, incl. PSR standards. That's why instance of Helper class should be $helper, and instance of a Handler class should be $classHandler and not the old $class\_handler or some other names

3\) I'm also slowly seeing the advantage of using the "reverse Hungarian notation" for variable, like: $itemObj   
$itemsArray

to see right away what I'm dealing with, but if we completely switch to PHP7 and use strong typing, the need for it might go away.



