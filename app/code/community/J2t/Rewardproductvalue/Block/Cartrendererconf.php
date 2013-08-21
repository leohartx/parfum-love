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

class J2t_Rewardproductvalue_Block_Cartrendererconf extends Mage_Checkout_Block_Cart_Item_Renderer_Configurable
{
    public function getProductName()
    {
        $qty = $this->getItem()->getQty();
        $points = Mage::getModel('catalog/product')->load($this->getItem()->getProductId())->getData('j2t_rewardvalue');
        
        $required_points = '';
        
        if ($points > 0){
            $required_points = ' ('.$this->__('%s points required', ($points * $qty)).')';
        }
        
        return parent::getProductName().$required_points;
    }
}