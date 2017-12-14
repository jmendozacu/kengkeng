<?php
$installer = $this;
$installer->startSetup();
//$sql=<<<SQLTEXT
//create table promotional_services(id int not null auto_increment, description varchar(100), price float default 0, ordering int(11),  primary key(id));
//
//INSERT INTO `promotional_services` (`id`, `description`, `price`, `ordering`) VALUES
//(1, '1x Embroidery logo + AU 5$ each unit', 5, 1),
//(2, '2x Embroidery logo + AU $9 each unit', 9, 2),
//(3, '1x Printing Logo + AU $4 each unit', 4, 3),
//(4, '2x Printing logo + AU $7 each unit', 7, 3);
//SQLTEXT;
//
//
//$installer->getConnection()->addColumn($this->getTable('sales_flat_quote_item'), 'service_type', 'varchar(255) NOT NULL');
//$installer->getConnection()->addColumn($this->getTable('sales_flat_order_item'), 'service_price', 'float');
//$installer->getConnection()->addColumn($this->getTable('sales_flat_order_item'), 'repeat_work', 'int');
$installer->getConnection()
    ->addColumn($installer->getTable('sales/quote_address'),
        'new_logo_artwork_setup',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable'  => true,
            'comment' => 'New logo artwork setup',
        )
    );

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'),
        'new_logo_artwork_setup',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable'  => true,
            'comment' => 'New logo artwork setup',
        )
    );

$installer->getConnection()
    ->addColumn($installer->getTable('sales/invoice'),
        'new_logo_artwork_setup',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable'  => true,
            'comment' => 'New logo artwork setup',
        )
    );

$installer->getConnection()
    ->addColumn($installer->getTable('sales/creditmemo'),
        'new_logo_artwork_setup',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable'  => true,
            'comment' => 'New logo artwork setup',
        )
    );


$installer->getConnection()
    ->addColumn($installer->getTable('sales/quote_address'),
        'base_new_logo_artwork_setup',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable'  => true,
            'comment' => 'New logo artwork setup',
        )
    );

$installer->getConnection()
    ->addColumn($installer->getTable('sales/order'),
        'base_new_logo_artwork_setup',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable'  => true,
            'comment' => 'New logo artwork setup',
        )
    );

$installer->getConnection()
    ->addColumn($installer->getTable('sales/invoice'),
        'base_new_logo_artwork_setup',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable'  => true,
            'comment' => 'New logo artwork setup',
        )
    );

$installer->getConnection()
    ->addColumn($installer->getTable('sales/creditmemo'),
        'base_new_logo_artwork_setup',
        array(
            'type' => Varien_Db_Ddl_Table::TYPE_DECIMAL,
            'length' => '12,4',
            'nullable'  => true,
            'comment' => 'New logo artwork setup',
        )
    );
//
//
//$installer->run($sql);
$installer->endSetup();
//