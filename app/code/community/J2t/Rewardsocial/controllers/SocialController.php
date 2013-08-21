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
 * @package    J2t_Rewardsocial
 * @copyright  Copyright (c) 2012 J2T DESIGN. (http://www.j2t-design.net)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class J2t_Rewardsocial_SocialController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {
        $network = $this->getRequest()->getParam('network');
        $customerId = Mage::helper('customer')->getCustomer()->getId();
        $points_fb = Mage::getStoreConfig('rewardpoints/social/facebook_like_points', Mage::app()->getStore()->getId());
        $points_gp = Mage::getStoreConfig('rewardpoints/social/googleplus_points', Mage::app()->getStore()->getId());
        $points_pin = Mage::getStoreConfig('rewardpoints/social/pinterest_points', Mage::app()->getStore()->getId());
        $points_tt = Mage::getStoreConfig('rewardpoints/social/twitter_points', Mage::app()->getStore()->getId());
        
        
        //count lines per day
        //max_share_per_customer
        
        $product_id = $this->getRequest()->getParam('product_id');
        $process_points = true;
        /*if ($product_id){
            //verify product
            $product = Mage::getModel('catalog/product')->load($product_id);
            if (!$product->getId()){
                $process_points = false;
            }
        }*/
        
        
        if ($product_id){
            //verify product
            $product = Mage::getModel('catalog/product')->load($product_id);
            if (!$product->getId()){
                $process_points = false;
            } else {
                $points_fb = $product->getRewardsocialFacebook();
                $points_gp = $product->getRewardsocialGoogle();
                //$points_pin = $product->getRewardsocialPinterest();
                $points_tt = $product->getRewardsocialTwitter();
            }
        }
        
        
        $max_shares = (int)Mage::getStoreConfig('rewardpoints/social/max_share_per_customer', Mage::app()->getStore()->getId());
        if ($max_shares > 0 && $process_points){
            //check
            $current_usage = Mage::helper('j2trewardsocial')->checkDailySocial($customerId);
            if ($current_usage >= $max_shares){
                $process_points = false;
            }
        }
        
        
        if ($network == "fb" && $customerId && $points_fb > 0 && $process_points){
            Mage::getModel("rewardpoints/observer")->recordPoints($points_fb, $customerId, Rewardpoints_Model_Stats::TYPE_POINTS_FB, false, $product_id, true);
        } else if($network == "gp" && $customerId && $points_gp > 0 && $process_points) {
            Mage::getModel("rewardpoints/observer")->recordPoints($points_gp, $customerId, Rewardpoints_Model_Stats::TYPE_POINTS_GP, false, $product_id, true);
        } else if($network == "pin" && $customerId && $points_pin > 0 && $process_points) {
            Mage::getModel("rewardpoints/observer")->recordPoints($points_pin, $customerId, Rewardpoints_Model_Stats::TYPE_POINTS_PIN, false, $product_id, true);
        } else if($network == "tt" && $customerId && $points_tt > 0 && $process_points) {
            Mage::getModel("rewardpoints/observer")->recordPoints($points_tt, $customerId, Rewardpoints_Model_Stats::TYPE_POINTS_TT, false, $product_id, true);
        }
        die;
        $this->loadLayout();
        $this->renderLayout();
    }
    
    public function loginAction()
    {
        $referer = $this->_getRefererUrl();
        if ($referer) {
            Mage::getSingleton('customer/session')->setBeforeAuthUrl($referer);
        }
        $this->getResponse()->setRedirect(Mage::getUrl("customer/account/login"));
    }
    
}