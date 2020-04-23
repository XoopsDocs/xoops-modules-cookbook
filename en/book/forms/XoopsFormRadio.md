## XoopsFormRadio

**Definition**
```php
XoopsFormRadio($caption, $name, $value = null, $id = "")
```

**Usage**
This class shows radio boxes with text. Each radio box must be added with addOption($value, $name="") or addOptionArray($options) function from this class. 

addOption can add only one option and addOptionArray can add array items, thats why parameter $options can be only a associative array of value->name pairs. Child classes

![](../../assets/uml/XoopsFormRadio.png)

