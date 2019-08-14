<?php
class Cybernetikz_Background_Model_Mysql4_Background extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init('background/background', 'id');
    }
}