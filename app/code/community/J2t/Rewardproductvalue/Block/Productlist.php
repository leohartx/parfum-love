<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@j2t-design.com so we can send you a copy immediately.
 *
 * @category   Magento extension
 * @package    J2T_Rewardproductvalue
 * @copyright  Copyright (c) 2011 J2T DESIGN. (http://www.j2t-design.net)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class J2t_Rewardproductvalue_Block_Productlist extends Mage_Catalog_Block_Product_List
{
    public function getPriceHtml($product, $displayMinimalPrice = false, $idSuffix = '')
    {
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
            return parent::getPriceHtml($product, $displayMinimalPrice, $idSuffix).$required_points;
        }
    }
}
