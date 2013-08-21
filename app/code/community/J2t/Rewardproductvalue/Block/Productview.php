<?php

class J2t_Rewardproductvalue_Block_Productview extends Mage_Catalog_Block_Product_View
{
    public function getTierPriceHtml($product = null)
    {
        $product = $this->getProduct();
        
        $points = $product->getData('j2t_rewardvalue');
        $required_points = '';
        if ($points > 0){
            $required_points = $this->getLayout()->createBlock('j2trewardproductvalue/rewardvalue')
                    ->setTemplate('j2trewardproductvalue/rewardvalue.phtml')
                    ->setProduct($product)
                    ->toHtml();
        }
        
        if ($product->getPrice() == 0 && $points > 0){
            return $required_points;
        } else {
            return parent::getTierPriceHtml($product).$required_points;
        }
    }
}