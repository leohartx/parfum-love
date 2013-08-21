<?php

class Intell_Colorswatch_Adminhtml_ColorswatchController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('colorswatch/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
	
	public function installAction(){
		//echo "Hello";die;
		
		$attributeInfo = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setCodeFilter('color')
                ->getFirstItem();
				
		// Add the attribute code here.
		$attribute = Mage::getModel('catalog/product')->getResource()->getAttribute("color");

		// Checking if the attribute is either select or multiselect type.
		if($attribute->usesSource()){
			// Getting all the sources (options) and print as label-value pair
			$options = $attribute->getSource()->getAllOptions(false);
		}
		
        foreach ($options as $item) {
			$model = Mage::getModel('colorswatch/colorswatch');
			$sel = $model->getCollection()->addFieldToFilter('option_id', $item['value'])->getData();
			//print_r($sel);die;
			if(empty($sel)){
				$data = array("title" => $item['label'], "content" => "Logo of " . $item['label'], "option_id" => $item['value'], "filename" => "");
				$model->setData($data);
				try {
					$model->save();
				} catch (Exception $e) {
					echo "<pre>";
					print_r($e);
					echo "</pre>";
				}
			}
			
			$this->_redirect('*/*/');
			
        }
		
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('colorswatch/colorswatch')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('colorswatch_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('colorswatch/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('colorswatch/adminhtml_colorswatch_edit'))
				->_addLeft($this->getLayout()->createBlock('colorswatch/adminhtml_colorswatch_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('colorswatch')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, str_replace(" ", "-", $_FILES['filename']['name']) );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = str_replace(" ", "-", $_FILES['filename']['name']);
			}
			else{
				//unset($data['filename']);
				$data['filename'] = "";
			}
	  			
	  			
			$model = Mage::getModel('colorswatch/colorswatch');
			
			//print_r($this->getRequest()->getParams());die;
			
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				
				if($data['filename']['delete'] == 1){
					$model->unsetData('filename');
				}
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('colorswatch')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);

				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('colorswatch')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('colorswatch/colorswatch');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $colorswatchIds = $this->getRequest()->getParam('colorswatch');
        if(!is_array($colorswatchIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($colorswatchIds as $colorswatchId) {
                    $colorswatch = Mage::getModel('colorswatch/colorswatch')->load($colorswatchId);
                    $colorswatch->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($colorswatchIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $colorswatchIds = $this->getRequest()->getParam('colorswatch');
        if(!is_array($colorswatchIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($colorswatchIds as $colorswatchId) {
                    $colorswatch = Mage::getSingleton('colorswatch/colorswatch')
                        ->load($colorswatchId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($colorswatchIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'colorswatch.csv';
        $content    = $this->getLayout()->createBlock('colorswatch/adminhtml_colorswatch_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'colorswatch.xml';
        $content    = $this->getLayout()->createBlock('colorswatch/adminhtml_colorswatch_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}