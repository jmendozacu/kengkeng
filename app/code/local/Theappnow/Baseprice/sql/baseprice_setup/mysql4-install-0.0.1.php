<?php

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS theappnow_baseprice; 

CREATE TABLE theappnow_baseprice (
  `baseprice_id` int(11) unsigned NOT NULL auto_increment,
  `name_category` varchar(255) NOT NULL default '',
  `price_value` float(25) NOT NULL,
  `category_id` int(10) NOT NULL,
  PRIMARY KEY (`baseprice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

