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



    public function getPostCollection()
    {
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $pagination = $this->_postFactory->create()->getCollection();
        $pagination->setPageSize($pageSize);
        $pagination->setCurPage($page);
        $pagination->addFieldToFilter('main_table.is_active', 1);
        $pagination->setOrder('post_id', 'DESC');
        return $pagination;
    }

    protected function _prepareLayout()
    {
        if ($this->getPostCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'mage.testimonials.pager'
            )->setTemplate('Mage_Testimonials::html/pager.phtml')
                    ->setAvailableLimit(array(5 => 5, 10 => 10, 15 => 15))
            ->setShowPerPage(true)
            ->setCollection(
                $this->getPostCollection()
            );

            $this->setChild('pager', $pager);
            $this->getPostCollection()->load();
        }
        return $this;
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }


}