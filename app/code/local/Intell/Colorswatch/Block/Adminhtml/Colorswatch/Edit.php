<?php

class Intell_Colorswatch_Block_Adminhtml_Colorswatch_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'colorswatch';
        $this->_controller = 'adminhtml_colorswatch';
        
        $this->_updateButton('save', 'label', Mage::helper('colorswatch')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('colorswatch')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('colorswatch_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'colorswatch_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'colorswatch_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('colorswatch_data') && Mage::registry('colorswatch_data')->getId() ) {
            return Mage::helper('colorswatch')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('colorswatch_data')->getTitle()));
        } else {
            return Mage::helper('colorswatch')->__('Add Item');
        }
    }
}