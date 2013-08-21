<?php
class IVVYRU_ConfDefault_Model_Product_Type_Configurable_Attribute extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('catalog/product_type_configurable_attribute');
    }

    public function addPrice($priceData)
    {
        $data = $this->getPrices();
        if (is_null($data)) {
            $data = array();
        }
        $data[] = $priceData;
        $this->setPrices($data);
        return $this;
    }

    public function getLabel()
    {
        if ($this->getData('use_default') && $this->getProductAttribute()) {
            return $this->getProductAttribute()->getStoreLabel();
        } else if (is_null($this->getData('label')) && $this->getProductAttribute()) {
            $this->setData('label', $this->getProductAttribute()->getStoreLabel());
        }

        return $this->getData('label');
    }
    
    protected function _afterSave()
    {
        parent::_afterSave();
        $this->_getResource()->saveLabel($this);
        $this->_getResource()->savePrices($this);
        $this->_getResource()->saveDefaults($this);
        return $this;
    }
}
