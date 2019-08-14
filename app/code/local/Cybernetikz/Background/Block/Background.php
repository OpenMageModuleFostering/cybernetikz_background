<?php
class Cybernetikz_Background_Block_Background extends Mage_Core_Block_Template{
	
	protected $_backgroundCollection = NULL;
	
	protected $_backgroundColCount = NULL;
	
    protected function _getCollection()
    {
		$ccontrollername = Mage::app()->getFrontController()->getRequest()->getControllerName();
		$cmodulename = Mage::app()->getFrontController()->getRequest()->getModuleName();		
		
		// Check Category / Product Page
		if($ccontrollername=='category' || $ccontrollername=="product"){
			 $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
			 $collection = Mage::getResourceModel('background/background_collection');
			 
			 // Product Page
			 if($ccontrollername=="product"){
				$catId = Mage::getModel('catalog/layer')->getCurrentCategory()->getId();
				if(empty($catId) || ($catId==$rootCategoryId) ){
					$currentProduct = Mage::registry('current_product');
					if(!method_exists($currentProduct, 'getCategoryIds')){
						$params = Mage::app()->getFrontController()->getRequest()->getParams();
						if($params['cat_id']){
							$catId = $params['cat_id'];
							$categoryIds = NULL;
						}else{
							$categoryIds = Mage::getModel('catalog/product')->load($params['id'])->getCategoryIds();
						}
					}else{
						$categoryIds = $currentProduct->getCategoryIds();
					}
					
					if(count($categoryIds)>0){
						foreach($categoryIds as $categroyId){
							if($categroyId != $rootCategoryId){
								$catId = $categroyId;
								break;
							}
						}
					}
				}
				
				$collection->addFieldToFilter('page_id',"{$catId}");
				$collection->addFieldToFilter('bg_type',"category");
				
			}else{
				// Category Page
				$currentCategory = Mage::registry('current_category');
				$collection->addFieldToFilter('page_id',"{$currentCategory->getEntityId()}");
				$collection->addFieldToFilter('bg_type',"category");
				
			}
			
			$collection->getSelect()->order('id','ASC');
			
			// Count Select Bangrounds
			$this->_backgroundColCount = $collection->count();
			
		}
		
		// Check CMS Page
		if($cmodulename=="cms"){
			$pageId = Mage::getSingleton('cms/page')->getId();
			$collection = Mage::getResourceModel('background/background_collection');
			$collection->addFieldToFilter('page_id',"{$pageId}");
			$collection->addFieldToFilter('bg_type',"{$cmodulename}");
			$collection->getSelect()->order('id','ASC');
			
			// Count Select Bangrounds
			$this->_backgroundColCount = $collection->count();
		}
				
		// If background not found, check default background
		if(empty($this->_backgroundColCount) || $this->_backgroundColCount==0){
			$collection = Mage::getResourceModel('background/background_collection');
			$collection->addFieldToFilter('page_id',"0");
			$collection->addFieldToFilter('bg_type',"default");
			$collection->getSelect()->order('id','ASC');
		}
				
		return $collection;
	
    }

    public function getBackgroundCollection()
    {	   
	    if (is_null($this->_backgroundCollection)) {
            $this->_backgroundCollection = $this->_getCollection();
        }
        return $this->_backgroundCollection;
    }

    public function getImageFullUrl($url)
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$url;
    }	
}