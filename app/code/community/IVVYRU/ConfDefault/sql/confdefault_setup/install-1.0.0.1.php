<?php
$installer = $this;

$table = $installer->getConnection()
         ->newTable($installer->getTable('confdefault/confdefault'))
         ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
           'identity' => true
         , 'unsigned' => true
         , 'nullable' => false
         , 'primary'  => true 
         ), 'Value ID')
         ->addColumn('product_super_attribute_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array (
           'unsigned' => true
         , 'nullable' => false
         , 'default'  => 0
         ), 'Product Super Attribute ID')
         ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
           'unsigned' => true
         , 'default'  => 0 
         ), 'Store ID')
         ->addColumn('value_index', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
           'unsigned' => true
         , 'nullable' => false
         ), 'Value Index')
         ->addIndex($installer->getIdxName('confdefault/confdefault',array('store_id'))
                 ,  array('store_id'))
         ->addIndex($installer->getIdxName('confdefault/confdefault',array('product_super_attribute_id'))
                 ,  array('product_super_attribute_id'))
         ->addIndex($installer->getIdxName('confdefault/confdefault',array('value_index'))
                 ,  array('value_index'))
         ->addForeignKey($installer->getFkName('confdefault/confdefault','product_super_attribute_id','confdefault/confdefault','product_super_attribute_id')
                 ,  'product_super_attribute_id' , $installer->getTable('catalog/product_super_attribute'), 'product_super_attribute_id'
                 ,  Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
         ->addForeignKey($installer->getFkName('confdefault/confdefault','store_id','confdefault/confdefault','store_id')
                 ,  'store_id', $installer->getTable('core/store'), 'store_id'
                 ,  Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
         ->setComment('Configurable Products Defaults');
$installer->getConnection()->createTable($table);
