<?php
$installer = $this;

$installer->startSetup();

/**
 * create pdfinvoiceplus table
 */
$installer->run("
ALTER TABLE {$this->getTable('store_location')}
 ADD COLUMN `time_close` varchar(255);
");

$installer->endSetup();