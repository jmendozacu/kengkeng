<?php
/**
 * Created by PhpStorm.
 * User: tungnm
 * Date: 14/03/2017
 * Time: 15:15
 */
$installer = $this;

$quoteItem = $installer->getTable('sales/quote_item');
$installer->getConnection()
    ->addColumn($quoteItem, 'logo_item', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
	        'nullable' => false,
	        'grid' => false,
	        'comment' => 'Logo Item',
	        'length' => '2M'
            )
    );

$orderItem = $installer->getTable('sales/order_item');
$installer->getConnection()
    ->addColumn($orderItem, 'logo_item', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
	        'nullable' => false,
	        'grid' => false,
	        'comment' => 'Logo Item',
	        'length' => '2M'
            )
    );
$installer->getConnection()
    ->addColumn($orderItem, 'logo_status', array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER,
	        'nullable' => false,
	        'comment' => 'Logo Status',
	        'default' => 1
            )
    );

$invoiceItem = $installer->getTable('sales/invoice_item');
$installer->getConnection()
    ->addColumn($invoiceItem, 'logo_item', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
	        'nullable' => false,
	        'grid' => false,
	        'comment' => 'Logo Item',
	        'length' => '2M'
            )
    );

$creditmemoItem = $installer->getTable('sales/creditmemo_item');
$installer->getConnection()
    ->addColumn($creditmemoItem, 'logo_item', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
	        'nullable' => false,
	        'grid' => false,
	        'comment' => 'Logo Item',
	        'length' => '2M'
            )
    );
$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS cmsmart_comment_item; 

CREATE TABLE cmsmart_comment_item (

  `comment_id` int(11) unsigned NOT NULL auto_increment,

  `item_id` int(11) NOT NULL,

  `logo_item` varchar(255) NOT NULL default '',

  `comment` varchar(255) NOT NULL default '',

  `author` int(11) NOT NULL default 1,

  `created_at` timestamp NOT NULL default CURRENT_TIMESTAMP,

  PRIMARY KEY (`comment_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();