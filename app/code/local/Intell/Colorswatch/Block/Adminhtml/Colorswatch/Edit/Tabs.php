<?php

class Intell_Colorswatch_Block_Adminhtml_Colorswatch_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('colorswatch_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('colorswatch')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('colorswatch')->__('Item Information'),
          'title'     => Mage::helper('colorswatch')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('colorswatch/adminhtml_colorswatch_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}