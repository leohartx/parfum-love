<?php

class IVVYRU_ConfDefault_Block_Catalog_Product_Edit_Tab_Super_Config extends Mage_Adminhtml_Block_Widget
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setProductId($this->getRequest()->getParam('id'));
        $this->setTemplate('ivvyru/confdefault/catalog/product/edit/super/config.phtml');
        $this->setId('config_super_product');
        $this->setCanEditPrice(true);
        $this->setCanReadPrice(true);
    }

    public function getTabClass()
    {
        return 'ajax';
    }

    public function isReadonly()
    {
         return $this->_getProduct()->getCompositeReadonly();
    }

    protected function _prepareLayout()
    {
        $this->setChild('grid',
            $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_super_config_grid',
                'admin.product.edit.tab.super.config.grid')
        );

        $this->setChild('create_empty',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('catalog')->__('Create Empty'),
                    'class' => 'add',
                    'onclick' => 'superProduct.createEmptyProduct()'
                ))
        );

        if ($this->_getProduct()->getId()) {
            $this->setChild('simple',
                $this->getLayout()->createBlock('adminhtml/catalog_product_edit_tab_super_config_simple',
                    'catalog.product.edit.tab.super.config.simple')
            );

            $this->setChild('create_from_configurable',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label' => Mage::helper('catalog')->__('Copy From Configurable'),
                        'class' => 'add',
                        'onclick' => 'superProduct.createNewProduct()'
                    ))
            );
        }

        return parent::_prepareLayout();
    }

    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }

    public function getAttributesJson()
    {
        $attributes = $this->_getProduct()->getTypeInstance(true)
            ->getConfigurableAttributesAsArray($this->_getProduct());
        if(!$attributes) {
            return '[]';
        } else {
            // Hide price if needed
            foreach ($attributes as &$attribute) {
                if (isset($attribute['values']) && is_array($attribute['values'])) {
                    foreach ($attribute['values'] as &$attributeValue) {
                        if (!$this->getCanReadPrice()) {
                            $attributeValue['pricing_value'] = '';
                            $attributeValue['is_percent'] = 0;
                        }
                        $attributeValue['can_edit_price'] = $this->getCanEditPrice();
                        $attributeValue['can_read_price'] = $this->getCanReadPrice();
                    }
                }
            }
        }
        return Mage::helper('core')->jsonEncode($attributes);
    }

    public function getLinksJson()
    {
        $products = $this->_getProduct()->getTypeInstance(true)
            ->getUsedProducts(null, $this->_getProduct());
        if(!$products) {
            return '{}';
        }
        $data = array();
        foreach ($products as $product) {
            $data[$product->getId()] = $this->getConfigurableSettings($product);
        }
        return Mage::helper('core')->jsonEncode($data);
    }

    public function getConfigurableSettings($product) {
        $data = array();
        $attributes = $this->_getProduct()->getTypeInstance(true)
            ->getUsedProductAttributes($this->_getProduct());
        foreach ($attributes as $attribute) {
            $data[] = array(
                'attribute_id' => $attribute->getId(),
                'label'        => $product->getAttributeText($attribute->getAttributeCode()),
                'value_index'  => $product->getData($attribute->getAttributeCode())
            );
        }

        return $data;
    }

    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    public function getGridJsObject()
    {
        return $this->getChild('grid')->getJsObjectName();
    }

    public function getNewEmptyProductUrl()
    {
        return $this->getUrl(
            '*/*/new',
            array(
                'set'      => $this->_getProduct()->getAttributeSetId(),
                'type'     => Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                'required' => $this->_getRequiredAttributesIds(),
                'popup'    => 1
            )
        );
    }

    public function getNewProductUrl()
    {
        return $this->getUrl(
            '*/*/new',
            array(
                'set'      => $this->_getProduct()->getAttributeSetId(),
                'type'     => Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
                'required' => $this->_getRequiredAttributesIds(),
                'popup'    => 1,
                'product'  => $this->_getProduct()->getId()
            )
        );
    }

    public function getQuickCreationUrl()
    {
        return $this->getUrl(
            '*/*/quickCreate',
            array(
                'product'  => $this->_getProduct()->getId()
            )
        );
    }

    protected function _getRequiredAttributesIds()
    {
        $attributesIds = array();
        $configurableAttributes = $this->_getProduct()
            ->getTypeInstance(true)->getConfigurableAttributes($this->_getProduct());
        foreach ($configurableAttributes as $attribute) {
            $attributesIds[] = $attribute->getProductAttribute()->getId();
        }

        return implode(',', $attributesIds);
    }

    public function escapeJs($string)
    {
        return addcslashes($string, "'\r\n\\");
    }

    public function getTabLabel()
    {
        return Mage::helper('catalog')->__('Associated Products');
    }

    public function getTabTitle()
    {
        return Mage::helper('catalog')->__('Associated Products');
    }

    public function canShowTab()
    {
        return true;
    }

    public function isHidden()
    {
        return false;
    }

    public function getShowUseDefaultPrice()
    {
        return !Mage::helper('catalog')->isPriceGlobal()
            && $this->_getProduct()->getStoreId();
    }
}
