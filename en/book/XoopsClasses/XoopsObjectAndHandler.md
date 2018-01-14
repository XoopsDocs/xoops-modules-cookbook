## XoopsObject and XoopsObjectHandler

XOOPS has two abstract classes to aid in the class development **XoopsObject **and **XoopsObjectHandler**/**XoopsPersistableObjectHandler**

The idea behind them is that a class can extend **XoopsObject** to describe an object, whereas extending **XoopsObjectHandler **will give more like an interface for handling the objects, i.e. get, insert, delete and create objects. 

E.g. for a ```ThisObject``` class, you can make a ```ThisObjectHandler``` to get, insert, delete and create ```ThisObject``` objects.

The advantages of extending these two classes are for **XoopsObject**:
*   Automatic access (inheritance) to methods, easing the assignment/retrieval of variables
*   Automatic access to methods for cleaning/sanitizing variables

and for **XoopsObjectHandler**:
* A place to put all those functions working with more than one object (e.g. a "getAllObjects()" function).

These functions will become easier to track down in the file system (since they are connected to a class, it is just a matter of finding the class and not going through the function files in the module/core/PHP native in search for it.

An additional idea is that the XoopsObjectHandler-extending class should be a **Data Access Object**, i.e. the class, which handles database calls - and leaving the XoopsObject-extending class to have object-describing methods, such as methods which handle and manipulate variables, calling methods on the handler for retrieving, updating and inserting data in the database.

NOTE: A change in the class factory method is expected in Xoops 2.2, so the advice from the core devs is that you should not go to a great length to change existing modules to follow this architecture - however, I hope that this guide makes it easier to understand the core classes, which use this architecture.
