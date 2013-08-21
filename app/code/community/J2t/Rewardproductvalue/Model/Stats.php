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

class J2t_Rewardproductvalue_Model_Stats extends Rewardpoints_Model_Stats
{
    const TYPE_POINTS_REQUIRED  = '-10';
    
    public function checkProcessedUsageOrder($customer_id, $quote_id, $order_id){
        $collection = $this->getCollection();
        $collection->getSelect()->where('customer_id = ?', $customer_id);
        $collection->getSelect()->where('quote_id = ?', $quote_id);
        $collection->getSelect()->where('order_id = ?', $order_id);
        $collection->getSelect()->where('points_spent > 0');
        
        $row = $collection->getFirstItem();
        if (!$row) return $this;
        return $row;
    }
}

