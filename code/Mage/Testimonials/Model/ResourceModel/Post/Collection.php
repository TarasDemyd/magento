<?php
namespace Mage\Testimonials\Model\ResourceModel\Post;

use Magento\Framework\DB\Select;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
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

    /**
     * Initialize select
     *
     * @return $this
     *
     * Join two table customer_testimonials & customer_entity, & in table customer_testimonials add data two col firstname & lastname with table customer_entity in table customer_testimonials
     */
    protected function _initSelect()
    {
        $this->getSelect()
            ->join(
                ['ce1' => 'customer_entity'],
                'ce1.entity_id = main_table.user_id',
                [
                    'customer_first_name' => 'firstname',
                    'customer_last_name' => 'lastname',
                ]
            )->join(
                ['ce2' => 'customer_entity_varchar'],
                'ce2.entity_id=ce1.entity_id',
                ['customer_entity_first_name' => 'value'])
            ->join(
                ['ce3' => 'customer_entity_varchar'],
                'ce3.entity_id=ce1.entity_id',
                ['customer_entity_last_name' => 'value']
            );
        return $this;
    }





}

