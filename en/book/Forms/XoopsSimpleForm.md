## XoopsSimpleForm

**Definition**
```php
XoopsSimpleForm( string $title, string $name, string $action, string $method = "post" ) 
```
**Usage**
Inherits from XoopsForm and will render the form with no tables and minimal formatting. 

**Full Example:**
```php
include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$form = new XoopsSimpleForm('Title of form', 'formname', 'targetpage.php', 'POST');
$form->addElement(new XoopsFormText('Field Label:', 'fieldname'));
$form->display();
```



![](../../assets/ClassUML/XoopsSimpleForm.png)

