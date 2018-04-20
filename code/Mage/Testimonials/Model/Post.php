<?php

namespace Mage\Testimonials\Model;

class Post extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'mage_testimonials_post';

    protected $_cacheTag = 'mage_testimonials_post';

    protected $_eventPrefix = 'mage_testimonials_post';

    protected function _construct()
    {
        $this->_init('Mage\Testimonials\Model\ResourceModel\Post');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}