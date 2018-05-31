<?php

namespace Mage\Testimonials\Controller\Customer;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Event\Manager;
use Mage\Testimonials\Model\PostFactory;


class Delete extends Action
{
    /**
     * Core form key validator
     *
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;
    protected $customerSession;
    protected $postFactory;
    protected $resultJsonFactory;
    protected $eventManager;
    public function __construct(
        Context $context,
        Session $customerSession,
        PostFactory $postFactory,
        Validator $formKeyValidator,
        JsonFactory    $resultJsonFactory,
        Manager $eventManager

    ) {
        $this->formKeyValidator = $formKeyValidator;
        $this->customerSession = $customerSession;
        $this->postFactory = $postFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->eventManager = $eventManager;
        parent::__construct($context);
    }
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        if ($this->getRequest())


        {

            try {

                $postId = $this->getRequest()->getParam('id', false);
                $post = $this->postFactory->create()->load($postId);
                $post->delete();
                $post->cleanModelCache();
                $this->messageManager->addSuccessMessage(__('Your testimonial successfully deleted'));
                return $resultRedirect;



            }  catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            }
        }

    }
}