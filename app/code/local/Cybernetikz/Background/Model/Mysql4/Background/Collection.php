<?php
class Cybernetikz_Background_Model_Mysql4_Background_Collection extends Varien_Data_Collection_Db
{
    protected $_backgroundTable;
 
    public function __construct()
    {
        $resources = Mage::getSingleton('core/resource');
        parent::__construct($resources->getConnection('background_read'));
        $this->_backgroundTable= $resources->getTableName('background/background');
 
        $this->_select->from(
        		array('background'=>$this->_backgroundTable),
 		       	array('*')
        		);
        $this->setItemObjectClass(Mage::getConfig()->getModelClassName('background/background'));
    }
}