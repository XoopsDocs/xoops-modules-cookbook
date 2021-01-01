# XoopsGroupFormCheckBox

XoopsGroupFormCheckBox renders checkbox options for a group permission form


![](../../assets/uml/XoopsGroupFormCheckBox.png)

#### Definition
```php
class XoopsGroupFormCheckBox extends XoopsFormElement
{
    /**
     * Pre-selected value(s)
     *
     * @var array ;
     */
    public $_value = array();
    /**
     * Group ID
     *
     * @var int
     */
    public $_groupId;
    /**
     * Option tree
     *
     * @var array
     */
    public $_optionTree = array();

    /**
     * Constructor
     * @param      $caption
     * @param      $name
     * @param      $groupId
     * @param null $values
     */
    public function __construct($caption, $name, $groupId, $values = null)
    {
        $this->setCaption($caption);
        $this->setName($name);
        if (isset($values)) {
            $this->setValue($values);
        }
        $this->_groupId = $groupId;
    }
```


