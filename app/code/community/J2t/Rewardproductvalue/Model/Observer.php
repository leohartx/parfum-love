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

class J2t_Rewardproductvalue_Model_Observer extends Mage_Core_Model_Abstract {
    
    protected function _redirectToCart($text, $redirect = false, $throwException = false){
        if (!$redirect){
            header('Content-type: application/x-json');
            $error = array('success' => false, 'error' => true, 'error_messages' => $text);
            echo Mage::helper('core')->jsonEncode($error);
        } else {
            
            if ($throwException){
                 //$url = $url_referrer;
                 Mage::throwException($text);
            } else {
                Mage::getSingleton('checkout/session')
                    ->addError($text);
            }
            $url = Mage::getModel('core/url')
                       ->getUrl("checkout/cart/index");
            

            Mage::app()
                ->getResponse()
                ->setRedirect($url);

            Mage::app()
                ->getResponse()
                ->sendResponse();
        }
        
    }
    
    protected function checkQuote($quote, $redirect = false, $throwException = false){
        //get points requires for this order
        $required_points = 0;
        foreach ($quote->getAllItems() as $item) {
            $qty = $item->getQty();
            $points = Mage::getModel('catalog/product')->load($item->getProductId())->getData('j2t_rewardvalue');
            if ($points > 0){
                $required_points += $points * $qty;
            }
        }
        
        if ($required_points){
            //get points used as discount
            $points_used = Mage::helper('rewardpoints/event')->getCreditPoints();
            
            //get customer points
            $customer_points = 0;
            if ($customer_id = $quote->getCustomer()->getId()){
                //$store_id = Mage::app()->getStore()->getId();
                if (Mage::getStoreConfig('rewardpoints/default/flatstats', $store_id)){
                    $reward_flat_model = Mage::getModel('rewardpoints/flatstats');
                    $customer_points = $reward_flat_model->collectPointsCurrent($customer_id, $quote->getStoreId());
                } else {
                    $reward_model = Mage::getModel('rewardpoints/stats');
                    $customer_points = $reward_model->getPointsCurrent($customer_id, $quote->getStoreId());
                }

                if (($customer_points - $points_used - $required_points) >= 0){
                    //OK, remove points
                    $reward_model = Mage::getModel('j2trewardproductvalue/stats');
                    
                    //TODO: Create checkProcessedUsageOrder function
                    $test_points = $reward_model->checkProcessedUsageOrder($customer_id, $quote->getId(), J2t_Rewardproductvalue_Model_Stats::TYPE_POINTS_REQUIRED);
                    if (!$test_points->getId()){
                        $post = array('quote_id' => $quote->getId(), 'order_id' => J2t_Rewardproductvalue_Model_Stats::TYPE_POINTS_REQUIRED, 'customer_id' => $customer_id, 'store_id' => $quote->getStoreId(), 'points_spent' => $required_points, 'convertion_rate' => Mage::getStoreConfig('rewardpoints/default/points_money', Mage::app()->getStore()->getId()));
                        $reward_model->setData($post);
                        $reward_model->save();
                    }
                    
                    
                } else {
                    //KO, not enough points
                    $this->_redirectToCart(Mage::helper('j2trewardproductvalue')->__('Required points for this order not met. %s points is required, but you have %s point(s) left.', $required_points, ($customer_points - $points_used) ), $redirect, $throwException);
                    exit;
                }

            } else {
                //order can't be process, because new customer
                $this->_redirectToCart(Mage::helper('j2trewardproductvalue')->__('No points available for this user.'), $redirect, $throwException);
                exit;
            }            
        }
    }
    
    public function multipleOrderCheck($observer){
        $event = $observer->getEvent();
        $quote = $event->getQuote();
        $this->checkQuote($quote, true);
        
    }
    
    
    protected function getCurrentPath(){
        /*$urlRequest = Mage::app()->getFrontController()->getRequest();
        $urlPart = $urlRequest->getServer('ORIG_PATH_INFO');
        if(is_null($urlPart))
        {
            $urlPart = $urlRequest->getServer('PATH_INFO');
        }
        $urlPart = substr($urlPart, 1 );
        $currentUrl = $this->getUrl($urlPart);
        
        return $currentUrl;*/
        return Mage::helper('core/url')->getCurrentUrl();
    }
    
    public function quoteToOrder($observer){
        $event = $observer->getEvent();
        $quote = $event->getOrder()->getQuote();
        //$order = $invoice->getOrder();
        //$this->getCurrentPath()
        if (preg_match("/paypal\/express/i", $this->getCurrentPath())) {
            $this->checkQuote($quote, true, true);
        } else {
            $this->checkQuote($quote, false);
        }
        
    }
}
