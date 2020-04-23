## 8.0 XOOPS Coding Conventions


We don't want you to waste time when you program, so we follow the principles of [**Conventions Over Configuration**](https://en.wikipedia.org/wiki/Convention_over_configuration).

Following these rules will also make easier to maintain your code in the future by other developers. 

#### Database Conventions

* if you have a **User** entity class, then pluralized forms of entity class names are used as table names, i.e.  the table in the database should be named ```users``` by default.

* Entity property names are used for column names.

* Entity properties that are named ID or classname ID are recognized as primary key properties.

* A property is interpreted as a foreign key property if it's named ```<navigation property name><primary key property name>``` (for example, StudentID for the Student navigation property since the Student entity's primary key is ID). 
Foreign key properties can also be named the same - simply ```<primary key property name>``` (for example, EnrollmentID since the Enrollment entity's primary key is EnrollmentID).

#### File and Class Name Conventions

* For files that are uploaded by users, each module should use the ```/uploads``` folder



