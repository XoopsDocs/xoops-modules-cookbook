# XOOPS Module Coding Style Guide (XOOPS 2.5.x)
## Definitions
The key words "MUST", "MUST NOT", "REQUIRED", "SHALL", "SHALL NOT", "SHOULD", "SHOULD NOT", "RECOMMENDED", "MAY", and "OPTIONAL" in this document are to be interpreted as described in [RFC 2119](http://tools.ietf.org/html/rfc2119).

## References
[PHP-FIG](http://www.php-fig.org/psr/) documents set a baseline for styles and preferences. Unless superceded by content in this document, the Accepted PSRx Standards Recommendations SHOULD be followed.

## Document Hierarchy
This document references other standards, guidelines and style guides.  The following document hierarchy outlines the order of precedence.  Higher priority documents take precedence over lesser priority documents.  If a document is silent on a particular subject then the next document in the hierarchy will be the authority on the subject.  The document hierarchy for the XOOPS Coding Style Guide shall be:
1. This document
2. PSR Style Guides (PSR-4, PSR-2, etc from the PHP-FIG working group)
3. 1TBS (One True Brace Style) style guide - based on the K&R style guide
4. Google HTML/CSS Style Guide
5. Zend Framework Coding Standard

## Goals
This coding style guide have been established to assist in the development and maintenance of XOOPS module code.  The end goal is to provide code of high quality, with fewer bugs, and is easily maintained. Standards help provide important guidelines which are useful during a development project, particularly when multiple developers are working on the same project. As with any standard or guide the goals above require an examination of the circumstances and balancing of various trade-offs.

These guidelines are not intended to stifle creativity or restrict a developer's ability to develop the features desired. The purpose is to require, or in most cases, recommend specific coding standards for developers to minimize coding differences where there are no functional reasons to follow alternate styles. These standards allow developers to work on scripts and maintain naming nomenclatures, minimize minor style differences, etc. to enhance both code reliability and maintainability.

An additional benefit of following the coding style guide is to provide an easier migration to XOOPS 2.6 and current/upcoming versions of the PHP programming language. This guide attempts to take into account potential changes in access methods, namespaces and several other considerations for future use.

### Applicability
This guide is intended to provide a quick _jump start_ to allow module developer's a coding standard to be used in the development of XOOPS modules.  Modules developed by/for the XOOPS Module Development Team MUST meet these guidelines. It is RECOMMENDED for all other modules so the code is maintainable and teams of developers can quickly contribute. Obviously in any Open Source ecosystem there are a variety of development models however independent (3rd party) developers are highly encouraged to use the guidelines in developing for the XOOPS system.

## Overview
### Assumptions:
- Reader can read/write PHP code
- Reader understands the basic structure of a XOOPS module
- Reader has a fair understanding of the XOOPS core

### Document IS:
- a quick introduction to give experienced PHP developer a 'running' start at coding a XOOPS module using a standardized method.
### Document IS NOT:
- a PHP primer
- a comprehensive module development guide
- a tutorial on the XOOPS core
- a tutorial to create a complete module

## Naming Styles
### Class and Method Names
Names for all classes, traits or interfaces defined in a module: 
1. Class names MUST be separated using camelCaps.
2. MUST start with the module name and declared using StudlyCaps. For example the Mylinks category class MUST be named `MylinksCategory` or something similar (i.e. `MylinksCat`).
3. The first letter for method (function) names SHOULD be in lowercase.

### Interface Names
Interface classes must follow the same conventions as other classes (see above), but must end with "_Interface", such as: `XformsElement_Interface`

### Constant / Define Names
Constants and define names not encapsulated in a class SHOULD be located in the ./language/<desired_language>/ folder with the following prefixes:

| Prefix | Language File Name | Description |
| :---: | ---: | --- |
| \_AM\_ | admin.php | Administration definitions |
| \_MD\_ | main.php | Main Operating definitions |
| \_MI\_ | modinfo.php | Module Information - used during initialization |
| \_MB\_ | blocks.php | Module Block definitions |

### Function Names
Module functions not encapsulated within a class SHOULD begin with the module's name followed by an underscore "_" and then followed by the function's purpose. For example the Newbb module's post count getter function SHOULD be named `newbb_getPostCount()`.

### Variable Names
1. Module variables intended to be shared outside the module SHOULD start with the originating module's name following camelCaps style. For example: $publisherItemArray()
2. Module and Third-party scripts, applications, libraries, etc. MUST not start with 'xoops_' but start with an identifier corresponding to the variable's origin.
3. It is RECOMMENDED that variable names are as explicit to it's intended content as possible. For example a variable containing an array of link objects would be named something like `$linkObjArray` which is preferrable to `$links` or even `$linkArray`.

### Abstractions Used in API (Class Interfaces)
When creating an API for use by application developers, if application developers must identify abstractions using a compound name, separate the names using underscores, not camelCaps. When the developer uses a string, normalize it to lowercase. Where reasonable, add constants to support this.

## File Formatting
### File Names
1. For all files, the file name MUST consist of alphanumeric characters, underscores, and the hyphen character ("-") only.
2. Any file that contains any PHP code MUST end with the ".php" extention.
3. HTML template files SHOULD end with the ".tpl" extension however ".html" is permissible.
4. File names MUST be in lowercase.

### Directory Names
1. For all directories, the directory name MUST consist of alphanumeric characters, underscores, and the hyphen character ("-") only.
2. Directory names MUST be in lowercase.

### PHP File Formatting
Module PHP files MUST follow the [PSR-1: Basic Coding Standard](http://www.php-fig.org/psr/psr-1/) and the [PSR-2: Coding Style Guide]{http://www.php-fig.org/psr/psr-2/)

## General Coding Practices
### Symbolic link usage
Symbolic links ('./' or '../') SHOULD be avoided where possible. For example
`include_once __DIR__ . '/header.php';`
is preferred to
`include_once './header.php';`

### Usage of PHP "eval()"
The use of PHP "eval" language construct (see http://www.php.net/manual/en/function.eval.php) is highly discouraged.  Using "eval" in modules developed by, or for, the XOOPS Module Development Team(e.g. those supported by the XOOPS Module Team - XMT) MUST NOT us the eval() construct.
> **Note:** This construct is prone to misuse and potentially opens the entire XOOPS installation to malicious activity.

### Unary Operators
Place unary operators (!, --, ...) adjacent to the affected variable; Do not place spaces between the unary operator and the variable.  For example:
```php
++$a; // not ++ $a or $a ++
$c = !$b; // not $c = ! $b or $c =! $b;
$d = &$e // not $d = & $e or $d =& $e
```
### Ternary Operators
It is RECOMMENDED that no more than two (2) levels of ternary operations be performed in any single statement and the second level SHOULD BE encapsulated in parenthesis. Each condition in the statement MUST have an explicit assignment.  For example:
```php
$a = (true === $b) ? 1 : 0;
// or
$a = (true === $b) ? ((1 === $c ) ? false : true) : false;
```
are both acceptable.  However:
```php
$a = (true === $b) ? ((1 === $c) ? false : ((0 === $d) ? false : true)) : false;
```
would be better broken into separate statements.

To fulfill the explicit assignment requirement a statement SHOULD use:
```php
$bad  = true;
$isOk = false;
$isOk = (false === $bad) ? $isOk : true;
```
and not:
```php
$bad  = true;
$isOk = false;
$isOk = (false === $bad) ?: true;
```

### Coding Style Observance (Code Modification)
It is REQUIRED for a developer to either honor the existing module coding styles (variable names, string embedding, etc.) or change all existing occurrances of a specific coding style to maintain consistency. For example if a module uses the embedded styling for string concatenation then any module modifications SHOULD also use embedded styling or change to using the single-quote method for string concatenation module-wide.
For example, if the module currently uses the single-quote method like:
`$myPath = XOOPS_ROOT_PATH . '/modules/ . $moduleDirName . '/myfile.php';`
then any changes to the existing module SHOULD use the same styling. The developer SHOULD NOT change this single instance of a string embed to something like:
`$myPath = XOOPS_ROOT_PATH . "/modules/{$moduleDirname}/yourfile.php;"`
but instead change it using the same styling:
`$myPath = XOOPS_ROOT_PATH . '/modules/ . $moduleDirName . '/yourfile.php';`

### Checking Variable Values
#### Type checking
Any variable value checking SHOULD use strict type checking (\'\=\=\=\' | \'\!\=\=\') where type juggling is not required. Enforcing strict type checking further reduces the potential for errors and is just good programming practice.

#### Comparing Values ([Yoda Conditions](https://en.wikipedia.org/wiki/Yoda_conditions))
When checking the value of a variable it is RECOMMENDED to put the value on the left of the equation and the variable name on the right. This method is called the _Yoda Conditions_ programming style. For example:
```php
if (0 == $var) {
    //do something
}
```
Is preferred over:
```php
if ($var == 0) {
    //do something
}
```
Using this method prevent s accidentally assigning a value to the $var if either a typo or wrong assignment operator is used.  This is because the following with throw a PHP error:
```php
if (0 = $var) {
    //do something
}
```
But the statement below will not, even though the intent is to check for zero, not assign it to zero:
```php
if ($var = 0) {
    //do something
}
```

### Required Var names
| Var Name | Description |
| :---: | --- |
| modversion | This is the array used by XOOPS to determine many of the module's initialization values |

### Recommended Var names
| Var Name | Description |
| :---: | --- |
| modDirName | This is the sub-directory where the module is located |
| adminObj | The Admin object created by \Xmf\Module\Admin |

## Usage of Single/Double Quotes
### Output HTML Strings
### Concatenation
It is RECOMMENDED developers use a consistent method throughout the module to concatenate strings using PHP. Example 1: 
```php
$myString = 'This is my ' . $string . ' for display';
```
**or** Example 2: 
```php
$myString = "This is my {$string} for display";
```
but both methods SHOULD NOT be used in the same module. Consistency is far more important and preferrable to toggling between two varying styles which are more error prone. If the first method above (Example 1:) is used then it is RECOMMENDED that the developer _always_ use single-quotes (\'). In Example 2: above double-quotes are REQUIRED for the string to render correctly however it RECOMMENDED that the developer _always_ use double-quotes (\") even if the string does not contain a variable.
> ![](../assets/info/info.png)
**Note:** If embedding the variable in the string like Example 2: then it is RECOMMENDED the variable be encased in curly-braces `{$variable}`.

## Comments
### PhpDocumentor Comments
Using PhpDocumentor (PhpDoc) style comments (\/\*\* <comment> \*\/) SHOULD NOT be used when the comments are only intended to be viewed inside the commented file to document code flow, for code inspection, etc. PhpDoc style comments SHOULD be used to document the file header (DocBlock), functions and methods. PhpDoc style comments MUST be used to document any API accessed outside of an individual file.
#### License Headers (DocBlock)
The XOOPS core code is licensed under [GNU GPLv2](http://www.gnu.org/licenses/gpl-2.0.html). Many XOOPS modules are also licensed using this same license. A module MUST state the licensing model used independent of which license is used. A header similar to the following format SHOULD be at the beginning of the file and a `LICENSE.txt` file SHOULD be in the release package in the ./docs directory. A new file header SHOULD use the following format:
```php
/*
 You may not change or alter any portion of this comment or credits of
 supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit
 authors.

 This program is distributed in the hope that it will be useful, but
 WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/
*/
/**
 * <Place a description of this file's function>
 *
 * @package   module\<module_directory_name>\<category>
 * @author    <your_name>
 * @copyright Copyright (c) <year> {@link <support_website_url> <site_name>}
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since::   <version_of_module_where_this_file_first_appears>
 */
```
 > **Note:** If you edit an existing file please add a copyright notice with your name if you consider your changes substantial enough to claim copyright. As a rule of thumb, this is the case if you contributed more than 10% of the total number of lines of code (excluding comments) to the file. Any file submitted MUST NOT remove or alter an existing copyright notice.

#### Type Hinting
For type-hinting in PHPDocs and casting, use bool (instead of boolean), int (instead of integer), float (instead of double or real);

### Class, Method, Function Comments
All class constructs (class, interface, traits, etc), class methods, and stand alone functions SHOULD be marked with PHPDoc markup. For example:
```php
. . .
/**
 * Description of what this method does
 * 
 * @since 1.4
 *
 * @param XoopsModule $module the instance of a XOOPS module to manipulate
 * @param string $email where to send the results
 *
 * @return bool true on success, false on failure
 */
public function sendEmail(XoopsModule $module, $email = null) {
  // ...
}
. . .
```

### Indentation
Comments should be indented to a level consistent with the code it is commenting. Commenting indentation for code (ie. code temporarily removed) should start at column 0 (zero) so it is easily identified as code/comments to be removed before release.

### PHP Comments - Single line
* Comments inside a PHP file SHOULD utilize the standard single line (`// <comment>`) format unless the comment is intended to be used for code inspection.
* Comments intended for code inspection SHOULD use (`/* <comment> */`) multi-line style comments. For example: `/* @var XoopsObject $object */`

* Comments MUST not utilize C-style (`# <comment>`) style comments.

### PHP Comments - Multi-line
Multiline comments SHOULD use the standard multi-line (`/* <comment> */`) method. Multi-line PhpDoc comments SHOULD be used in accordance with the _PhpDocumentor Comments_ paragraph above.
 
### HTML / SMARTY Comments
It is RECOMMENDED that only comments to be viewed by end users (administrators, site users, etc.) use HTML comments (```<!-- <comment> -->```). It is preferred that any _internal_ comments intended for modules/theme developers utilize smarty comments ```<{* <comment> *}>``` since these comments are not rendered in the generated HTML.

### Comment Example
```php
/**
 * This is a file DocBlock
 *
 * @copyright Copyright (c) 2017 XOOPS project
 * @package Xmf\Module\Helper
 *
 */
 use Xmf\Request;
 
 . . .
 
 // Get input from the user <- USE THIS SINGLE LINE COMMENT STYLE
 $id = Request::getInt('id', 0, 'POST);
 
 # Check to see if this is a valid id <- DON'T USE THIS STYLE COMMENT
 if ($id > 0) {
    /* <- USE THIS MULTI-LINE COMMENT STYLE
     * The id is okay,
     * so now we'll make sure it's for a real item
     */
     $goodObject = $myObjectHandler->get($id);
     /** <- DON'T USE THIS MULTI-LINE STYLE COMMENT
      * Now we've checked to see if the id is good,
      * we need to figure out if the object is real
      */
    . . .
 }
```
