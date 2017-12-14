<?php
/**
 * Upgrade script
 * User: Hoang Van Cong
 * Date: 2/28/2017
 * Time: 3:10 PM
 */


$installer = $this;



$installer->startSetup();



$installer->run("
DROP TABLE IF EXISTS theappnow_tierprice;
CREATE TABLE theappnow_tierprice (
  `id` int(11) unsigned NOT NULL auto_increment,
  `level` int(10) not null,
  `qty` int(10) NOT NULL,
  `price_deviation` float(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");



$installer->endSetup();



