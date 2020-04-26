# Enable a Module to Use XOOPS Classes

### About this Primer

This primer is intended to provide a quick 'jump start' to allow module developers a rudimentary guide to add class support to a XOOPS module. A developer should be able to enable a simple XOOPS class for a module after completing this primer. In general, good design practices for variable sanitation and error checking are not included in this primer but should be implemented in any production module.

**Assumptions:**

- Reader can read/write PHP code
- Reader understands the basic structure of a XOOPS module
- Reader has a fair understanding of the XOOPS core

**Primer IS:**

- quick primer to get experienced PHP developer a 'running' start at adding class support to a XOOPS module.

**Primer IS NOT:**

- a PHP primer
- a comprehensive module development guide
- a tutorial on the XOOPS core
- a tutorial to create a complete module
- an example of how to combine multiple MySQL table fields into a single XOOPS object

**Background**

This primer assumes the module has a MySQL database table defined as follows:
```mysql
CREATE TABLE `mytable` (

`myId` tinyint(4) NOT NULL auto_increment,
`myTitle` varchar(100) NOT NULL default '',
`myDate` date,
`myUrl` text NOT NULL default '',
PRIMARY KEY (`myId`),
KEY (`myTitle`),
KEY (`myDate`)
) ENGINE=MyISAM;
```


This is typically, for most modules, found in the _'./mymodule/sql/mysql.sql'_ file.

Historically, most modules have done something similar to the following to retrieve an entry from the database:
```php
$id = Xmf\Request::getInt('id', 0, 'GET');
$id = Xmf\Request::getInt('id', $id, 'POST');
 
$query = "SELECT * FROM" . $xoopsDB->prefix('mytable')
         . " WHERE `myId`='" . $id . "' LIMIT 0,1"; 
$result = $xoopsDB->query($query); 
$myrow = $xoopsDB->fetchRow($result); 
```
Then to display the individual fields uses something like the following:
```php
echo 'My ID: ' . $myrow['myId'] . '<br>';
echo 'My Title: ' . $myts->htmlSpecialChars($myrow['myTitle']) . '<br>';
echo 'My Date: ' . $myrow['myDate'] . '<br>';
echo 'My URL: ' . $myts->htmlSpecialChars($myrow['myUrl']) . '<br>'; 
```
**Note:** _Hopefully the text strings above are defined in a language file, but that's a topic for another tutorial._

Or the module may use the XOOPS templating system so it will then just pass the information for template processing - something like:

```$xoopsTpl->assign('myrow', $myrow);```

**Why Add Class Support**

A more secure, and object oriented method is to use the built in XOOPS _Object_ and _Objecthandler_ classes. These classes handle the database CRUD operations for you so you do not have to become a database guru. Historically most module developers have assumed, rightly so, that XOOPS uses MySQL and therefore codes the database calls directly into the module. This was done for a multitude of reasons. For example, the developer:

- didn't understand, or want to learn, the XOOPS methods to access the database
- wanted to 'optimize' the database access
- couldn't get the XOOPS core to perform the access required

However, doing this prevents the module from being able to be used on other databases without modification since the structure of the MySQL calls are embedded in the module code. This 'direct' database access also leads to less secure (sanitized) injection code being executed since the data has to be 'manually' cleaned by the module before it is used in the database call. Many developers are very good at doing this, as it has always been 'required', but other developers - particularly beginning PHP developers are not as thorough. XOOPS is an open source project that encourages 3rd party developers to be involved. The XOOPS core developers however do not provide any quality assurance on modules. This can lead to some very insecure code in modules.

**Adding Class Support**

Create _'Myclass.php'_

To add a class to a module requires a class to be created to access the MySQL data. This is done by creating a file with the same name as the class - in this case use 'Myclass'. The class file needs to be located in the module's class folder. If this is the first class then create the 'class' folder in the module's root folder. So in this case the folder structure would look like this diagram.

Now use an editor (PhpStorm, Eclipse, Notepad++, etc.) and create the class file called _'Myclass.php'_. Just 'piggyback' on the XOOPS functionality already built into the framework to create a simple class. Add the following to the class file:
```php
<?php

namespace XoopsModules\Mymodule;

/**
 * Xoops myModule - a test module
 *
 * You may not change or alter any portion of this comment or credits of supporting
 * developers from this source code or any supporting source code which is
 * considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright ::  &copy; The XOOPS Project http://sourceforge.net/projects/xoops/
 * @package   :: mymodule
 * @subpackage:: class
 * @since     :: File available since version 1.00
 * @author    :: zyspec 
 */

class Myclass extends \XoopsObject
{
    function __construct()
    {
        //definitions of the table field names from the database 
        $this->initVar('myId', XOBJ_DTYPE_INT, null, false);
        $this->initVar('myTitle', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('myDate', XOBJ_DTYPE_DATE);
        $this->initVar('myUrl', XOBJ_DTYPE_TXTAREA);
    }

    function __toString()
    {
        return $this->getVar('myTitle');
    }
}

```

Now save these contents to _'./mymodule/class/Myclass.php'_.

As next, we'll create a Handler class for our Myclass:

Create _'MyclassHandler.php'_

To add a class to a module requires a class to be created to access the MySQL data. This is done by creating a file with the same name as the class - in this case use 'Myclass'. The class file needs to be located in the module's class folder. If this is the first class then create the 'class' folder in the module's root folder. So in this case the folder structure would look like this diagram.

Now use an editor (PhpStorm, Eclipse, Notepad++, etc.) and create the class file called _'Myclass.php'_. Just 'piggyback' on the XOOPS functionality already built into the framework to create a simple class. Add the following to the class file:
```php
<?php

namespace XoopsModules\Mymodule;

/**
 * Xoops myModule - a test module
 *
 * You may not change or alter any portion of this comment or credits of supporting
 * developers from this source code or any supporting source code which is
 * considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright ::  &copy; The XOOPS Project http://sourceforge.net/projects/xoops/
 * @package   :: mymodule
 * @subpackage:: class
 * @since     :: File available since version 1.00
 * @author    :: zyspec 
 */

class MyclassHandler extends \XoopsPersistableObjectHandler
{
    function __construct($db)
    {
        parent::__construct($db, 'mytable', Myclass::class, 'myId');
    }
}
```

Now save these contents to _'./mymodule/class/MyclassHandler.php'_.

### Accessing Object Data

To create the same query as above using the newly created class (Myclass) to access the database, you can do something like this:
```php
<?php

use XoopsModules\Mymodule\Helper;

$id = Xmf\Request::getInt('id', 0, 'GET');
$id = Xmf\Request::getInt('id', $id, 'POST');

$myclassHandler = Helper::getInstance()->getHandler('Myclass');
$myrowObj = $myclassHandler->get($id);
$fields = array('myId', 'myTitle', 'myDate', 'myUrl');
$myrow = $myrowObj->getValues($fields);
```

But the preferred and recommended way to retrieve the same information is to combine the new class along with using the XOOPS [_CriteriaCompo_ and _Criteria_](../core/Criteria.md):

```php
<?php

use XoopsModules\Mymodule\Helper;

$id = Xmf\Request::getInt('id', 0, 'GET');
$id = Xmf\Request::getInt('id', $id, 'POST');

$myclassHandler = Helper::getInstance()->getHandler('Myclass');
$criteria      = new \CriteriaCompo();
$criteria->add(new \Criteria('myId', $id, '=');
$criteria->setLimit(1);
$fields = ['myId', 'myTitle', 'myDate', 'myUrl'];
$myrow  = $myclassHandler->getAll($criteria, $fields, false);
```

The example above, while on the surface doesn't appear really does anything different than the 'original' code at the top of this tutorial, does begin to build on the XOOPS class functionality to 'start' providing more object oriented programming.

This example utilizes the Xmf\Request class to sanitize some of the class variable to make it easier to provide more robust, secure applications. This isn't obvious from this small example however it does become clear when developing more complex data manipulations - specifically when writing data back to the database.

As more experience is gained using the methods provided by the XOOPS classes module developers can begin to code modules that manipulate the objects returned by the classes and thus further improve the code reliability over array manipulation.
