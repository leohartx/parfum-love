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
?>
<?php
$installer = $this;
$attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
                        ->setCodeFilter('j2t_rewardvalue')
                        ->getFirstItem();

if(!$attributeInfo->getAttributeId()){
    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
    $installer->startSetup();
    /**
     * Adding Different Attributes
     */

    // adding attribute group
    $setup->addAttributeGroup('catalog_product', 'Default', 'J2T Reward Value', 1000);

    // the attribute added will be displayed under the group/tab Special Attributes in product edit page
    $setup->addAttribute('catalog_product', 'j2t_rewardvalue', array(
        'group'         => 'J2T Reward Value',
        'input'         => 'text',
        'type'          => 'text',
        'label'         => 'J2T Reward Value',
        'frontend_class'=> 'validate-digits',
        'backend'       => '',
        'visible'       => 1,
        'required'      => 0,
        'user_defined' => 0,
        'searchable' => 0,
        'filterable' => 0,
        'comparable'    => 0,
        'visible_on_front' => 1,
        'visible_in_advanced_search'  => 0,
        'is_html_allowed_on_front' => 0,
        'used_in_product_listing' => true,
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));
    
    $installer->run("ALTER TABLE {$this->getTable('rewardpoints_account')} ADD `quote_id` INT( 11 ) NULL;");

    $installer->endSetup();
}
