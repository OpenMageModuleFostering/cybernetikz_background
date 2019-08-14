<?php
$installer = $this;
$installer->startSetup();
 
$installer->run("
CREATE TABLE {$this->getTable('background/background')} (
`id` INT(10) NOT NULL AUTO_INCREMENT,
`bg_type` VARCHAR(100) NOT NULL,
`page_id` INT NOT NULL,
`image_url` VARCHAR(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
   
$installer->endSetup();