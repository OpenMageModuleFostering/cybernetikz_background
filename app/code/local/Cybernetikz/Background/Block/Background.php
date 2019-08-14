<?php
class Cybernetikz_Background_Block_Background extends Mage_Core_Block_Template{
	
	protected $_backgroundCollection = null;

    protected function _getCollection()
    {
		$collection=Mage::getResourceModel('background/background_collection');
				
		$ccontrollername = Mage::app()->getFrontController()->getRequest()->getControllerName();
		$cmodulename = Mage::app()->getFrontController()->getRequest()->getModuleName();
		
		if($ccontrollername=='category' || $ccontrollername=="product"){
			 $currentcategory = Mage::registry('current_category');
			 if( $ccontrollername=="product"){
				$catid = Mage::getModel('catalog/layer')->getCurrentCategory()->getId();
				if(empty($catid) || ($catid==2) ){
					$cat = Mage::registry('current_product')->getCategoryIds();
					if($cat[0]==2){
						$catid = $cat[1];
					}else{
						$catid = $cat[0];
					}
				}
				$collection->addFieldToFilter('page_id',"{$catid}");
				$collection->addFieldToFilter('bg_type',"category");
			}else{
				$collection->addFieldToFilter('page_id',"{$currentcategory->getEntityId()}");
				$collection->addFieldToFilter('bg_type',"category");
			}
					  
		}
		
		if($cmodulename=="cms"){
			$pageid = Mage::getSingleton('cms/page')->getId();
			$collection->addFieldToFilter('page_id',"{$pageid}");
			$collection->addFieldToFilter('bg_type',"{$cmodulename}");
		}
		
		$collection->getSelect()->order('id','ASC');
		$collection->getSelect()->limit(1);
		
		//echo $collection->getSelect()->__toString();
		//print_r($collection->getData());
		//exit;
		
		return $collection;
	
    }

    public function getBackgroundCollection()
    {	   
	    if (is_null($this->_backgroundCollection)) {
            $this->_backgroundCollection = $this->_getCollection();
        }
        return $this->_backgroundCollection;
    }

    public function getImageUrl($url)
    {
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).$url;
    }	
}