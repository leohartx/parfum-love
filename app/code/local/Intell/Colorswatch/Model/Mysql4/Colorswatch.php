<?php

class Intell_Colorswatch_Model_Mysql4_Colorswatch extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the colorswatch_id refers to the key field in your database table.
        $this->_init('colorswatch/colorswatch', 'colorswatch_id');
    }
}