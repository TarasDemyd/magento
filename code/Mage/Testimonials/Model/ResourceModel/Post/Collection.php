<?php
namespace Mage\Testimonials\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\DB\Select;


class Collection extends AbstractCollection
{
    protected $_idFieldName = 'post_id';
    protected $_eventPrefix = 'mage_testimonials_post_collection';
    protected $_eventObject = 'post_collection';



    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Mage\Testimonials\Model\Post::class, \Mage\Testimonials\Model\ResourceModel\Post::class);

    }






}

