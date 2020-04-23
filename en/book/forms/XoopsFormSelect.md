## XoopsFormSelect

**Definition**
```php
XoopsFormSelect($caption, $name, $value=null, $size=1, $multiple=false)
```

**Usage**
XoopsFormSelect is a normal drop-down menu.

**Most Important Functions**
*   addOption($value, $name="", $disabled = false) - add an option to the select, if no name is given the value carries over. From XOOPS 2.2, $disabled can be set to true in order to make the option non-selectable
*   addOptionArray($options, $disabled=array()) - add options from a value-name pair array. The array's key is the value. From XOOPS 2.2, $disabled can be an array of values that should be disabled

**Child Classes**
*   XoopsFormSelectCountry($caption, $name, $value=null, $size=1) - Countries
*   XoopsFormSelectGroup($caption, $name, $include_anon=false, $value=null, $size=1, $multiple=false) - Usergroups
*   XoopsFormSelectLang($caption, $name, $value=null, $size=1) - Language selection
*   XoopsFormSelectMatchOption($caption, $name, $value=null, $size=1) - Match ("Starts with", "Ends With", "Equals" or "Contains") selection
*   XoopsFormSelectTheme($caption, $name, $value=null, $size=1) - Theme selection
*   XoopsFormSelectTimezone($caption, $name, $value=null, $size=1) - Timezone selection
*   XoopsFormSelectUser($caption, $name, $include_anon=false, $value=null, $size=1, $multiple=false) - User selection, $include_anon will include the anonymous user if true

Common for all these classes is that they do not need the addOption - options are automatically populated


![](../../assets/uml/XoopsFormSelect.png)

