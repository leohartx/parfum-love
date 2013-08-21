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
?>
<?php
$installer = $this;
$installer->startSetup();
$installer->run("ALTER TABLE {$this->getTable('rewardpoints_account')} ADD COLUMN `rewardpoints_status` VARCHAR( 255 ) NULL AFTER `quote_id`;");
$installer->run("ALTER TABLE {$this->getTable('rewardpoints_account')} ADD COLUMN `rewardpoints_state` VARCHAR( 255 ) NULL AFTER `rewardpoints_status`;");
$installer->run("ALTER TABLE {$this->getTable('rewardpoints_account')} ADD COLUMN `date_order` DATETIME NULL AFTER `rewardpoints_state`;");


$installer->run("ALTER TABLE {$this->getTable('rewardpoints_account')} ADD INDEX `rewardpoints_status` (`rewardpoints_status`);");
$installer->run("ALTER TABLE {$this->getTable('rewardpoints_account')} ADD INDEX `rewardpoints_state` (`rewardpoints_state`);");

if (version_compare(Mage::getVersion(), '1.4.0', '>=')){
    $installer->run("UPDATE {$this->getTable('rewardpoints_account')} SET   `rewardpoints_status` = (SELECT `status` FROM {$this->getTable('sales/order')} WHERE {$this->getTable('sales/order')}.increment_id = {$this->getTable('rewardpoints_account')}.order_id), 
                                                                            `rewardpoints_state` = (SELECT `state` FROM {$this->getTable('sales/order')} WHERE {$this->getTable('sales/order')}.increment_id = {$this->getTable('rewardpoints_account')}.order_id),
                                                                            `date_order` = (SELECT {$this->getTable('sales/order')}.`date_order` FROM {$this->getTable('sales/order')} WHERE {$this->getTable('sales/order')}.increment_id = {$this->getTable('rewardpoints_account')}.order_id);");
} else {
    $table_sales_order = $this->getTable('sales/order').'_varchar';
    
    $states = array(
        Mage_Sales_Model_Order::STATE_NEW, 
        Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, 
        Mage_Sales_Model_Order::STATE_PROCESSING, 
        Mage_Sales_Model_Order::STATE_COMPLETE, 
        Mage_Sales_Model_Order::STATE_CLOSED, 
        Mage_Sales_Model_Order::STATE_CANCELED, 
        Mage_Sales_Model_Order::STATE_HOLDED
        );
    $installer->run("UPDATE {$this->getTable('rewardpoints_account')} SET   `rewardpoints_state` = (   SELECT order_state.value 
                                                                                                        FROM $table_sales_order as order_state 
                                                                                                        WHERE order_state.entity_id IN ( 
                                                                                                            SELECT orders.entity_id FROM {$this->getTable('sales/order')} as orders 
                                                                                                            WHERE orders.increment_id = {$this->getTable('rewardpoints_account')}.order_id
                                                                                                        )
                                                                                                        WHERE order_state.value in ('".implode("','", $states)."') ORDER BY value_id DESC
                                                                                                        LIMIT 1
                                                                                                     );");
}

$installer->endSetup();