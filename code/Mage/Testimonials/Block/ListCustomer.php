<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mage\Testimonials\Block;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

/**
 * Customer Reviews list block
 *
 * @api
 * @since 100.0.2
 */
class ListCustomer extends \Magento\Customer\Block\Account\Dashboard
{

    protected $_postFactory;

    /**
     * @var \Magento\Customer\Helper\Session\CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $customerAccountManagement
     * @param \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $customerAccountManagement,
        \Magento\Customer\Helper\Session\CurrentCustomer $currentCustomer,
        \Mage\Testimonials\Model\PostFactory $postFactory,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->_postFactory = $postFactory;
        parent::__construct(
            $context,
            $customerSession,
            $subscriberFactory,
            $customerRepository,
            $customerAccountManagement,
            $data
        );

    }


    protected function _prepareLayout()
    {

        if ($this->getPostCollection()) {
            $pager = $this->getLayout()->createBlock(
                'Magento\Theme\Block\Html\Pager',
                'mage.testimonials.pager'
            )->setTemplate('Mage_Testimonials::html/customer/pager.phtml')
                ->setAvailableLimit(array(5 => 5, 10 => 10, 15 => 15))
                ->setShowPerPage(true)
                ->setCollection(
                    $this->getPostCollection()
                );

            $this->setChild('pager', $pager);
            $this->getPostCollection()->load();
        }
    }


    public function getPostCollection()
    {
        $customerId = $this->currentCustomer->getCustomerId();
        $page = ($this->getRequest()->getParam('p')) ? $this->getRequest()->getParam('p') : 1;
        $pageSize = ($this->getRequest()->getParam('limit')) ? $this->getRequest()->getParam('limit') : 5;
        $pagination = $this->_postFactory->create()->getCollection();
        $pagination->setPageSize($pageSize);
        $pagination->setCurPage($page);
        $pagination->addFieldToFilter('main_table.is_active', 1);
        $pagination->addFieldToFilter('main_table.user_id', $customerId);
        $pagination->setOrder('post_id', 'DESC');
        return $pagination;
    }


    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get review URL
     *
     * @param \Mage\Testimonials\Model\PostFactory $edit
     * @return string
     * @since 100.2.0
     */
    public function getEditUrl($edit)
    {
        return $this->getUrl('testimonials/customer/view', ['id' => $edit->getPostId()]);
    }

    public function getDeleteUrl($edit)
    {
        return $this->getUrl('testimonials/customer/delete', ['id' => $edit->getPostId()]);
    }



}
