<?php
class Intell_Colorswatch_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/colorswatch?id=15 
    	 *  or
    	 * http://site.com/colorswatch/id/15 	
    	 */
    	/* 
		$colorswatch_id = $this->getRequest()->getParam('id');

  		if($colorswatch_id != null && $colorswatch_id != '')	{
			$colorswatch = Mage::getModel('colorswatch/colorswatch')->load($colorswatch_id)->getData();
		} else {
			$colorswatch = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($colorswatch == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$colorswatchTable = $resource->getTableName('colorswatch');
			
			$select = $read->select()
			   ->from($colorswatchTable,array('colorswatch_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$colorswatch = $read->fetchRow($select);
		}
		Mage::register('colorswatch', $colorswatch);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}