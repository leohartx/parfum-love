<?php

class Intell_Colorswatch_Model_Mysql4_Colorswatch_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('colorswatch/colorswatch');
    }
}