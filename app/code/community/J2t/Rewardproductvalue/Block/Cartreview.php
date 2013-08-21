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

class J2t_Rewardproductvalue_Block_Cartreview extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('j2trewardproductvalue/cart_review.phtml');
    }
    
    public function getRequiredPoints()
    {
        $required_points = 0;        
        $cartHelper = Mage::helper('checkout/cart');
        $items = $cartHelper->getCart()->getItems();
        
        foreach ($items as $item) {
            $required_points += Mage::getModel('catalog/product')->load($item->getProductId())->getData('j2t_rewardvalue') * $item->getQty();
        }        
        return $required_points;
    }
    
    public function getCartItems()
    {
        $cartHelper = Mage::helper('checkout/cart');
        $items = $cartHelper->getCart()->getItems();
        return $items;
    }
    
    public function getProductRequiredPoints($item)
    {
        return Mage::getModel('catalog/product')->load($item->getProductId())->getData('j2t_rewardvalue') * $item->getQty();
    }
    
    public function getProductName($item)
    {
        return $item->getProduct()->getName();
    }
    
    public function getProductThumbnail($item)
    {
        
        return $this->helper('catalog/image')->init($item->getProduct(), 'thumbnail');
    }
    
}