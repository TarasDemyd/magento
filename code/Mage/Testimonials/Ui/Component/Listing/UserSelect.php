<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mage\Testimonials\Ui\Component\Listing;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Customer\Model\Customer;
use Magento\Framework\View\Design\Theme\Label\ListInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Framework\View\Model\PageLayout\Config\BuilderInterface;


class UserSelect implements OptionSourceInterface
{
    /**
     * @var \Magento\Framework\View\Design\Theme\Label\ListInterface
     */
    protected $themeList;
    protected $_customerCollectionFactory;
    protected $customer;
    protected $options;
    protected $pageLayoutBuilder;

    /**
     * Constructor
     *
     * @param ListInterface $themeList
     */
    public function __construct(ListInterface $themeList, Customer $customer,CollectionFactory $customerCollectionFactory,BuilderInterface $pageLayoutBuilder)
    {
        $this->themeList = $themeList;
        $this->customer = $customer;
        $this->_customerCollectionFactory  = $customerCollectionFactory;
        $this->pageLayoutBuilder = $pageLayoutBuilder;
    }

    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

        $collection = $this->_customerCollectionFactory->create()->getData();

        $options = [];
        foreach ($collection as $value) {
            $options[] = ['label' => $value['firstname'], 'value' => $value['entity_id']];
        }
        $this->options = $options;

        return $this->options;

    }
}
