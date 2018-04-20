<?php

namespace Mage\Testimonials\Block;

class Testimonials extends \Magento\Framework\View\Element\Template
{
    protected $_postFactory;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mage\Testimonials\Model\PostFactory $postFactory
    )
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    public function getPostCollection(){
        /** @var \Mage\Testimonials\Model\Post $post */
        $post = $this->_postFactory->create();
        return $post->getCollection();
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}