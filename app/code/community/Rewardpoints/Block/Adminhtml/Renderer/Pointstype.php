<?php
/**
 * J2T RewardsPoint2
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
 * @package    RewardsPoint2
 * @copyright  Copyright (c) 2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Rewardpoints_Block_Adminhtml_Renderer_Pointstype extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $status_field = Mage::getStoreConfig('rewardpoints/default/status_used', Mage::app()->getStore()->getId());
        
        $order_id = $row->getData($this->getColumn()->getIndex());
        $points_type = array(Rewardpoints_Model_Stats::TYPE_POINTS_REVIEW => Mage::helper('rewardpoints')->__('Review points'), 
            Rewardpoints_Model_Stats::TYPE_POINTS_NEWSLETTER => Mage::helper('rewardpoints')->__('Newsletter points'), //OK
            Rewardpoints_Model_Stats::TYPE_POINTS_GP => Mage::helper('rewardpoints')->__('Google Plus points'),
            Rewardpoints_Model_Stats::TYPE_POINTS_FB => Mage::helper('rewardpoints')->__('Facebook Like points'),
            Rewardpoints_Model_Stats::TYPE_POINTS_PIN => Mage::helper('rewardpoints')->__('Pinterest points'),
            Rewardpoints_Model_Stats::TYPE_POINTS_TT => Mage::helper('rewardpoints')->__('Twitter points'),
            Rewardpoints_Model_Stats::TYPE_POINTS_ADMIN => Mage::helper('rewardpoints')->__('Store input %s', ($row->getRewardpointsDescription()) ? ' - '.$row->getRewardpointsDescription() : ''),
            Rewardpoints_Model_Stats::TYPE_POINTS_REGISTRATION => Mage::helper('rewardpoints')->__('Registration points'),
            Rewardpoints_Model_Stats::TYPE_POINTS_REQUIRED => Mage::helper('rewardpoints')->__('Points used on products')
                );
        if (Mage::getConfig()->getModuleConfig('J2t_Rewardshare')->is('active', 'true')){
            $points_type[J2t_Rewardshare_Model_Stats::TYPE_POINTS_SHARE] = Mage::helper('j2trewardshare')->__('Gift (shared points)');
            //J2t_Rewardshare_Model_Stats::TYPE_POINTS_SHARE => Mage::helper('j2trewardshare')->__('Gift (shared points)')
        }
        //$model->getRewardpointsDescription()
        if ( ($order_id > 0) || ($order_id != "" && !is_numeric($order_id)) ){
            $order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
            return Mage::helper('rewardpoints')->__('Points related to order #%s (%s)', $order_id, Mage::helper('rewardpoints')->__($order->getData($status_field)));
        } elseif (isset($points_type[$order_id])) {
            return $points_type[$order_id];
        } else {
            return null;
        }
    }
}

