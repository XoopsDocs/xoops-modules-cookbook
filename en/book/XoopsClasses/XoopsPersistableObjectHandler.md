## XoopsPersistableObjectHandler

#### Definition
**XoopsPersistableObjectHandler(Database $db )**

The ```XoopsPersistableObjectHandler``` is an enhanced version of ```XoopsObjectHandler``` that makes it possible to build a fully functional object handler class with a simple one-line constructor. 

As previously mentioned, it is based on the [Data Mapper Patter](https://martinfowler.com/eaaCatalog/dataMapper.html) and partially the [Repository Pattern](https://martinfowler.com/eaaCatalog/repository.html)

![](../../assets/ClassUML/XoopsPersistableObjectHandler.png)

Most Important Methods
* **create()**: Create a new object
* **get()**: read data from the database and instantiate an object
* **insert()**: The current object is inserted into the database or its data is updated. The system automatically goes through the process of data validation and escaping to validate query syntax correctness and security of the operation of the database; we strongly recommend that module developers adopt this method or inherit this method of writing data to the database.
* **delete()**: delete the current object from the database
* **deleteAll()**: Delete all objects from the database that meet the conditions described in the $criteria
* **updateAll()**: update the database to meet the $criteria as described in the conditions of all the objects field data
* **getObjects()**: read from the database that meet the $criteria as described in the conditions of all the data and instantiated as an object
* **getAll():** read from the database to meet the $criteria as described in the conditions of all the data and returns an array of instantiated as an object or as an array of objects
* **getList()**: read from the database to meet the $criteria as described in the conditions of all the data and returns an array of objects
* **getIds()**: read from the database to meet the $criteria as described in the conditions of all the data and returns an array of corresponding primary key value
* **getCount()** the number of objects in the database that meet conditions  described in the $criteria 
* **getCounts()** Array read from the database to meet conditions  described in the $criteria   
* **getByLink()**: Union query, read from the database to meet the $criteria as described in the conditions of all the data and returns an array of instantiated as an object or
* **getCountByLink()**: Union query, read from the database to meet the $criteria as described in the conditions of the amount of data
* **getCountsByLink()**: Union query, read from the database to meet the $criteria as described in the conditions of the amount of data array
* **updateByLink()**: linking table mode, update the database to meet the $criteria as described in the conditions of all the objects field data
* **deleteByLink()**: linking table mode, the data from the database to delete $criteria described meet the conditions of all the objects
* **cleanOrphan()**: linking table mode, remove orphaned data in the data table
* **synchronization()**: Data Synchronization

**Advantages of Data Mapper architecture:**
* Each object has a single responsibility, thus preserving the integrity of SOLID design principles, and keeping each object simple and to the point.
* The business logic and persistence are loosely coupled - if you want to persist in an XML file or some other format, you can just write a new mapper and don't have to touch the domain object. (see [here](http://russellscottwalker.blogspot.com/2013/10/active-record-vs-data-mapper.html))

#### Usage

