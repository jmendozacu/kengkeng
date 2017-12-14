<?php
$installer = $this;
$installer->startSetup();
$installer->createEntityTables(
    $this->getTable('complexworld/eavblogpost')
);
$installer->addEntityType('complexworld_eavblogpost', array(
    //entity_mode is the URI you'd pass into a Mage::getModel() call
    'entity_model'    => 'complexworld/eavblogpost',

    //table refers to the resource URI complexworld/eavblogpost
    //...eavblog_posts

    'table'           =>'complexworld/eavblogpost',
));
$installer->endSetup();