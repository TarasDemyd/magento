<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mage\Testimonials\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Mage\Testimonials\Model\Post;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;

class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Mage_Testimonials::save';
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Mage\Testimonials\Model\PostFactory
     */
    private $pageFactory;

    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    private $pageRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Mage\Testimonials\Model\PostFactory $pageFactory
     * @param \Magento\Cms\Api\PageRepositoryInterface $pageRepository
     *
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        \Mage\Testimonials\Model\PostFactory $pageFactory = null,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->pageFactory = $pageFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\Mage\Testimonials\Model\PostFactory::class);
        $this->pageRepository = $pageRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Cms\Api\PageRepositoryInterface::class);
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {


        try {
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());

            $post = $this->pageFactory->create();
            $post->setUserId($this->getRequest()->getParam('user_select'));
            $post->setPostContent($this->getRequest()->getParam('testimonial_content'));
            $post->setCreatedAt(date('Y-m-d h:i:s', time()));
            $post->save();
            $post->cleanModelCache();

            $this->messageManager->addSuccessMessage( __('Your testimonial saved') );
            return $resultRedirect;


        }  catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

    }
}
