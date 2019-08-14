<?php

class Cybernetikz_Background_Adminhtml_BackgroundController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
    {
        $this->_title($this->__('Manage Background'));
		$this->loadLayout()->renderLayout();
    }
    
	public function newAction()
    {
		$this->_title($this->__('Add New Background'));
        $this->loadLayout()->renderLayout();
    }
	
	public function postAction()
    {
		if ($data = $this->getRequest()->getPost()) {
			// Set Page Id
			if($data['bg_type'] == "default"){
				$data['page_id'] = 0;
			}elseif(!empty($data['page_id'])){
				$data['page_id'] = $data['page_id'];
			}else{
				$data['page_id'] = $data['category_id'];
			}			
			// Set Custom Selector
			if($data['selector'] == "custom" && !empty($data['custom_selector'])){
				$data['selector'] = $data['custom_selector'];
			}
			
			$backgroundCollections=Mage::getResourceModel('background/background_collection');
			$backgroundCollections->addFieldToFilter('page_id',$data['page_id']);
			$backgroundCollections->addFieldToFilter('bg_type',$data['bg_type']);
			$backgroundCollections->addFieldToFilter('selector',$data['selector']);
			$backgroundCollections->getSelect()->order('id','ASC');
			$backgroundCollections->getSelect()->limit(1);
			$background=$backgroundCollections->getData();
			$count = count($background);
			if($count<=0){			
				if(isset($_FILES['image_url']['name']) && $_FILES['image_url']['name'] != '') {
					try {	
						/* Starting upload */	
						$uploader = new Varien_File_Uploader('image_url');
						// Any extention would work
						$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
						$uploader->setAllowRenameFiles(true);
						$uploader->setFilesDispersion(false);
						// We set media as the upload dir
						$path = Mage::getBaseDir('media'). DS . "background" . DS ;
						
						$image_file_name = $_FILES['image_url']['name'];
						$retrnimage = $uploader->save($path, $image_file_name);
					} catch (Exception $e) {
						$this->_getSession()->addException($e, Mage::helper('background')->__('Error uploading file. Please try again later.'));
					}				
					$data['image_url'] = "background/".$retrnimage['file'];
				}
				
				$background = Mage::getModel('background/background')->setData($data);
				try {
					
					$background->save();
					
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('background')->__('Background has been successfully saved.'));
					
					// check if 'Save and Continue'
					if ($this->getRequest()->getParam('back')) {
						$this->_redirect('*/*/edit', array('id' => $background->getId()));
						return;
					}
				
					// go to grid
					$this->getResponse()->setRedirect($this->getUrl('*/*'));
					return;
					
				} catch (Exception $e){
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
					$this->_redirectReferer();
					return;
				}
        	}else{
				Mage::getSingleton('adminhtml/session')->addError("Same selector background not allowed. You already uplaoded a background for '".$background[0]['selector']."' selector.");
				$this->_redirectReferer();
				return;
			}
		}
        
		$this->getResponse()->setRedirect($this->getUrl('*/*'));
        return;
    }
	
	public function editAction()
    {
        $this->_title($this->__('Edit Background'));
		
		$id = $this->getRequest()->getParam('id',true);
		$background = Mage::getModel('background/background')->load($id, 'id')->getData();
		if(!empty($background)){
			$this->loadLayout()->renderLayout();
		}else{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('background')->__('Wrong URL. Please try again later.'));
			$this->getResponse()->setRedirect($this->getUrl('*/*'));
        	return;
		}
    }
	
	public function saveAction()
    {
        $data = $this->getRequest()->getPost();
		$id = $data['id'];
        if ($data = $this->getRequest()->getPost()) {
            // Set Page Id
			if($data['bg_type'] == "default"){
				$data['page_id'] = 0;
			}elseif(!empty($data['page_id'])){
				$data['page_id'] = $data['page_id'];
			}else{
				$data['page_id'] = $data['category_id'];
			}			
			// Set Custom Selector
			if($data['selector'] == "custom" && !empty($data['custom_selector'])){
				$data['selector'] = $data['custom_selector'];
			}
						
			//Slider Image file
        	if(isset($_FILES['image_url']['name']) && $_FILES['image_url']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('image_url');
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(true);
					$uploader->setFilesDispersion(false);
					// We set media as the upload dir
					$path = Mage::getBaseDir('media'). DS . "background" . DS ;
					
					$image_file_name = $_FILES['image_url']['name'];
					$retrnimage = $uploader->save($path, $image_file_name );
				} catch (Exception $e) {
    	            $this->_getSession()->addException($e, Mage::helper('background')->__('Error uploading file. Please try again later.'));
		        }
	  			$data['image_url'] = "background/".$retrnimage['file'];
			}
				
			$background = Mage::getModel('background/background')->load($id)->addData($data);
            try {
                $background->setId($id)->save();
 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('background')->__('Background has been successfully updated.'));
                
				// check if 'Save and Continue'
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $background->getId()));
                    return;
                }
				
				// go to grid
				$this->getResponse()->setRedirect($this->getUrl('*/*'));
                return;
				
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				
				// redirect to edit form
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*'));
    }
	
	public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id', false);
 
        try {
            Mage::getModel('background/background')->setId($id)->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('background')->__('Background has been successfully deleted.')); 
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			
			// check if error found and detele action from edit page
			if ($this->getRequest()->getParam('back')) {
				$this->_redirect('*/*/edit', array('id' => $id));
				return;
			}
        }
 
        $this->getResponse()->setRedirect($this->getUrl('*/*'));
    }
}
?>