<?php

class Intell_Colorswatch_Block_Adminhtml_Colorswatch_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('colorswatch_form', array('legend'=>Mage::helper('colorswatch')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('colorswatch')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'image', array(
          'label'     => Mage::helper('colorswatch')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      /*$fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('colorswatch')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('colorswatch')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('colorswatch')->__('Disabled'),
              ),
          ),
      ));
	  */
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('colorswatch')->__('Content'),
          'title'     => Mage::helper('colorswatch')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getColorswatchData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getColorswatchData());
          Mage::getSingleton('adminhtml/session')->setColorswatchData(null);
      } elseif ( Mage::registry('colorswatch_data') ) {
          $form->setValues(Mage::registry('colorswatch_data')->getData());
      }
      return parent::_prepareForm();
  }
}