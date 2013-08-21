<?php
class Intell_Colorswatch_Block_Colorswatch extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getColorswatch()     
     { 
        if (!$this->hasData('colorswatch')) {
            $this->setData('colorswatch', Mage::registry('colorswatch'));
        }
        return $this->getData('colorswatch');
        
    }
	
	public function getSwatchImages(){
		$data = $this->getRequest()->getParams();
		// Collect options applicable to the configurable product
		$product = Mage::getModel('catalog/product')->load($data['id']);
		$productAttributeOptions = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
		
		//get associated products
		$associatedProductIds = $product->getTypeInstance(true)->getUsedProductIds($product);
		$associatedProducts = array();
		foreach($associatedProductIds as $associatedProductId){
			$clildProduct = Mage::getModel('catalog/product')->load($associatedProductId);
			$associatedProducts[$clildProduct->getColor()] = $clildProduct->getImageUrl();
		
		}
		
		$attributeOptions = array();
		$swatch = "<ul>";
		$imageHtml = '';
		$mediaUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
		//colorswatch model
		$colorSwatchModel = Mage::getModel('colorswatch/colorswatch');
		$collection = $colorSwatchModel->load();
		foreach ($productAttributeOptions as $productAttribute) {
			foreach ($productAttribute['values'] as $attribute) {
				$attributeOptions[$productAttribute['label']][$attribute['value_index']] = $attribute['store_label'];
				
				//swatch images
				$data = $collection->getCollection()->addFieldToFilter('option_id', $attribute['value_index'])->getData();
				$imageHtml = "<img alt='".$attribute['store_label']."' title='".$attribute['store_label']."' width='50' height='50' src='".$mediaUrl.$data[0]['filename']."'>";
				$sekId = '\"attribute'.$productAttribute['attribute_id'].'\"';
				$productImageUrl = '\"'.$associatedProducts[$attribute['value_index']].'\"';
				//preparing li for swatches on product detail page
				//$swatch .= "<li> <a href='javascript:void(0)' onclick='changesizedropdown(".$attribute['value_index'].",".$sekId.");'>".$imageHtml."</a></li>";
				$swatch .= "<li> <a href='javascript:void(0)' onclick='changesizedropdown(".$attribute['value_index'].",".$sekId.",".$productImageUrl.");'>".$imageHtml."</a></li>";
			}
		}
		$swatch .= "</ul>";
		
		//print_r($product->getFinalPrice());
		//print_r($productAttributeOptions);die;
		
		return $swatch;
	}
}