<?php
class Inchoo_Blog_Model_Resource_Post extends Mage_Eav_Model_Entity_Abstract
{
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('inchoo_blog_post');
        $this->setConnection(
            $resource->getConnection('blog_read'),
            $resource->getConnection('blog_write')
        );
    }
}