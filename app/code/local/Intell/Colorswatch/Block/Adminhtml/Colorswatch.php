<?php
class Intell_Colorswatch_Block_Adminhtml_Colorswatch extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_colorswatch';
    $this->_blockGroup = 'colorswatch';
    $this->_headerText = Mage::helper('colorswatch')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('colorswatch')->__('Add Item');
    parent::__construct();
	
	$this->_removeButton('add');
	$this->_addButton('load', array(
		'label'     => Mage::helper('colorswatch')->__('Load Attribute Values'),
		'onclick'   => "setLocation('".$this->getUrl('*/*/install')."')",
	   
	));
	
  }
}