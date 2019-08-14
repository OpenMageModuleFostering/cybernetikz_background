<?php

class Cybernetikz_Background_Adminhtml_MyformController extends Mage_Adminhtml_Controller_Action
{
public function indexAction()
    {
        $this->loadLayout()->renderLayout();
    }
    
	public function pagemanageAction()
    {
        $this->loadLayout()->renderLayout();
    }
	
   public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id', false);
 
        try {
            Mage::getModel('background/background')->setId($id)->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('background')->__('Background Image successfully deleted.')); 
        } catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
 
        $this->_redirectReferer();
    }
	
	
	public function postAction()
    {
		if ($data = $this->getRequest()->getPost()) {
			if(!empty($data['page_id'])){
				$data['page_id']= $data['page_id'];
			}else{
				$data['page_id']= $data['category_id'];
			}
			
			$collection=Mage::getResourceModel('background/background_collection');
			$collection->addFieldToFilter('page_id',$data['page_id']);
			$collection->addFieldToFilter('bg_type',$data['bg_type']);
			$collection->getSelect()->order('id','ASC');
			$collection->getSelect()->limit(1);
			$backgroundCollection=$collection->getData();
			$count=count($backgroundCollection);
			if($count<=0){				
				if(isset($_FILES['image_url']['name']) && $_FILES['image_url']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('image_url');
					// Any extention would work
					$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					// We set media as the upload dir
					$path = Mage::getBaseDir('media'). DS . "background" . DS ;				
					$filename = $_FILES['image_url']['name'];
					$image_file_name = time().'-'.$filename;
					$retrnimage=$uploader->save($path, $image_file_name);
				} catch (Exception $e) {
					$this->_getSession()->addException($e, Mage::helper('background')->__('Error uploading file. Please try again later.'));
				}				
				$data['image_url'] = "background/".$retrnimage['file'];
				}
				$background = Mage::getModel('background/background')->setData($data);
				try {
					$background->save();
					Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('background')->__('Background Image was successfully saved.'));
					$this->getResponse()->setRedirect($this->getUrl('*/*/pagemanage'));
					return;
				} catch (Exception $e){
					Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				}
			}else{
				Mage::getSingleton('adminhtml/session')->addError("Already uplaod background for this page.");
				$this->_redirectReferer();
				return;
			}
        	
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/pagemanage'));
        return;
    }
	
	public function editpageAction()
    {
        $this->loadLayout()->renderLayout();
    }
	
	    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
		$id = $data['id'];
        if ($data = $this->getRequest()->getPost()) {
            //Slider Image file
        	if(isset($_FILES['image_url']['name']) && $_FILES['image_url']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('image_url');
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					$uploader->setFilesDispersion(false);
					// We set media as the upload dir
					$path = Mage::getBaseDir('media'). DS . "background" . DS ;
				
					$filename = $_FILES['image_url']['name'];		
					$image_file_name = time().'-'.$filename;
					$retrnimage=$uploader->save($path, $image_file_name );
				} catch (Exception $e) {
    	            $this->_getSession()->addException($e, Mage::helper('background')->__('Error uploading file. Please try again later.'));
		        }
	  			$data['image_url'] = "background/".$retrnimage['file'];
			}
				
			$background = Mage::getModel('background/background')->load($id)->addData($data);
            try {
                $background->setId($id)->save();
 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('background')->__('Background Image successfully updated.'));
                $this->getResponse()->setRedirect($this->getUrl('*/*/pagemanage'));
                return;
            } catch (Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/pagemanage'));
    }
}
?>