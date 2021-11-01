# 8.0 XOOPS Coding Conventions


We don't want you to waste time when you program, so we follow the principles of [**Conventions Over Configuration**](https://en.wikipedia.org/wiki/Convention_over_configuration).

Following these rules will also make it easier to maintain your code in the future by other developers. 

## Principles and rules
Here are the most important rules:

* [KISS](https://odan.github.io/learn-php/#kiss) - Keep it simple, stupid
* [YAGNI](https://odan.github.io/learn-php/#yagni) - You Arent Gonna Need It
* [DRY](https://odan.github.io/learn-php/#dry) - Don’t repeat yourself
* [SOLID](https://odan.github.io/learn-php/#solid) - The First 5 Principles of Object Oriented Design
* [The Boy Scout Rule](https://deviq.com/boy-scout-rule/) - Leave your code better than you found it

## Naming

* Use english names only (class names, method names, comments, variables names, database table and field names, etc…).
* Use class suffixes / prefixes according to [PSR Naming Conventions](https://www.php-fig.org/bylaws/psr-naming-conventions/).
* Follow industry best practices for your directory and file structure:
  * [thephpleague/skeleton](https://github.com/thephpleague/skeleton) - A skeleton repository for packages
  * [pds/skeleton](https://github.com/php-pds/skeleton) - Names for your root-level directories

## Database Conventions

* Table names should be singular, e.g. when we have a **User** entity class, the table in the database should be named ```user``` by default. See the reasoning behind it [here](https://tqdev.com/2021-should-table-names-be-singular-or-plural)

* Entity property names are used for column names.

* Entity properties that are named ID or classname ID are recognized as primary key properties.

* A property is interpreted as a foreign key property if it's named ```<navigation property name><primary key property name>``` (for example, StudentID for the Student navigation property since the Student entity's primary key is ID). 
Foreign key properties can also be named the same - simply ```<primary key property name>``` (for example, EnrollmentID since the Enrollment entity's primary key is EnrollmentID).

## Common rules
* All methods must have [type declaration](https://www.php.net/manual/en/migration70.new-features.php) and return type declaration.
* Methods without return statement must declared with ```void``` as their return type.
* Class properties must have [typed properties](https://wiki.php.net/rfc/typed_properties_v2) (PHP 7.4+).
* Don’t mix data types for parameters and return types, except for ```nullable```.
* Don’t ```extend``` classes or create ```abstract``` classes for the sake of “code reuse”, except for traits with test code only.
* Create ```final``` classes by default, except you have to mock it (e.g. repositories).
* Create ```private``` methods by default. Don’t create ```protected``` methods.
* All method parameters must be used.

## Dependency injection
* Use composition over inheritance.
* Declare all class dependencies only within the constructor.
* Don’t inject the container (PSR-11). The service locator is an anti-pattern.
* A constructor can accept only dependencies as object.
* Scalar data types (string, int, float, array) are not allowed for the constructor. Pass them as parameter object.

## Tools
* Use a static code analyzer to detect bugs and errors. For example:
  * [phpstan](https://github.com/phpstan/phpstan)
  * [Php Inspections EA Extended](https://plugins.jetbrains.com/plugin/7622-php-inspections-ea-extended-)
  * [SonarLint Plugin](https://odan.github.io/2019/12/01/the-phpstorm-sonarlint-plugin.html)

## Read more
* [Object Design Style Guide](https://www.manning.com/books/object-design-style-guide?a_aid=object-design&a_bid=4e089b42)
* [Hexagonal Architecture](https://odan.github.io/learn-php/#hexagonal-architecture)
* http://bestpractices.thecodingmachine.com/
* https://odan.github.io/learn-php/

### File Uploads

* For files that are uploaded by users, each module should use the ```/uploads``` folder

 
In this section we used materials from various authors, incl. [Daniel Opitz](https://odan.github.io/2019/12/06/php-best-practice-2019.html)

