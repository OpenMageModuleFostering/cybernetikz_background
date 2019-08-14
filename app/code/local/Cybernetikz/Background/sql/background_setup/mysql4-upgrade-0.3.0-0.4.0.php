<?php
$installer = $this;
$installer->startSetup();

$installer->run("
	ALTER TABLE {$this->getTable('background/background')} ADD `selector` VARCHAR(255) NOT NULL AFTER `image_url`;
	ALTER TABLE {$this->getTable('background/background')} ADD `repeat` varchar(50) NULL AFTER `selector`;
	ALTER TABLE {$this->getTable('background/background')} ADD `position` varchar(50) NULL AFTER `repeat`;
	ALTER TABLE {$this->getTable('background/background')} ADD `attachment` varchar(50) NULL AFTER `position`;
	ALTER TABLE {$this->getTable('background/background')} ADD `color` varchar(50) NULL AFTER `attachment`;
	ALTER TABLE {$this->getTable('background/background')} ADD `size` varchar(50) NULL AFTER `color`;
	ALTER TABLE {$this->getTable('background/background')} ADD `origin` varchar(50) NULL AFTER `size`;
	ALTER TABLE {$this->getTable('background/background')} ADD `clip` varchar(50) NULL AFTER `origin`;
	ALTER TABLE {$this->getTable('background/background')} ADD `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation Time' AFTER `clip`;
");
   
$installer->endSetup();