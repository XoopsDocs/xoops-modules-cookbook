## Key Classes/Objects API

#### Using Xoops API
In addition to the Xoops API documentation http://api.xoops.org/ here we also need guidelines for module writers as to what classes to use when, why, and how, etc. For example:
   * XoopsObject Data Access classes

If you create a module class (in the class folder of your module) called ```<Module>MyClass``` that extends ```XoopsObject```:
```php
    include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
    class ModuleMyClass extends XoopsObject
    {
    /**
    * Constructor
    **/
    function __construct() // Constructor
    {
    $this->initVar('my_variable', XOBJ_DTYPE_INT, NULL);
    }
    }
```
and a handler called <Module>MyClassHandler that extends XoopsPersistableObjectHandler:
```php
   class ModuleMyClassHandler extends XoopsPersistableObjectHandler
   {
   }
```
you can then obtain a refereance to an instance the handler class like so:
In /modules/module/index.php:
```php
   $myClassHandler = xoops_getModuleHandler('MyClass');
   ```

Built in data object handlers can be obtained via xoops_gethandler('<class>').
Search for the use of this idiom in other modules for an idea of how these classes let you build data access objects to interface with corresponding database tables.

![](../assets/uml/XoopsObject.png)

![](../assets/uml/XoopsPersistableObjectHandler.png)
