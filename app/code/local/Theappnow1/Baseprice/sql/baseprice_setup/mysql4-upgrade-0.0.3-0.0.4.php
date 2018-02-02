<?php



/** @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS cmsmart_logo_item; 

CREATE TABLE cmsmart_logo_item (

  `logo_id` int(11) unsigned NOT NULL auto_increment,

  `item_id` int(11) NOT NULL,

  `logo_item` varchar(255) NOT NULL default '',

  `status` int(11) NOT NULL default 1,

  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,

  PRIMARY KEY (`logo_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();



