<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
create table store_location(store_location_id int not null auto_increment, name varchar(100), primary key(store_location_id),
store_name varchar(255),
store_id int(11),
latitude float,
longitude float,
address varchar(255),
postcode varchar(255),
city varchar(255)

);
 
		
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 