<?php
$installer = $this;

$installer->startSetup();

/**
 * create pdfinvoiceplus table
 */
$installer->run("
ALTER TABLE {$this->getTable('store_location')}
 ADD COLUMN `img` varchar(255) default 'noImage.jpg',
 ADD COLUMN `time_open` varchar(255);
");

$installer->endSetup();