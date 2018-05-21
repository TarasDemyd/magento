<?php

namespace Mage\Testimonials\Controller\Index;


use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Manager;
use Mage\Testimonials\Model\PostFactory;
use Magento\Framework\Message\ManagerInterface;


class Store extends Action
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
        /**
         * @var \Magento\Framework\Controller\Result\Redirect $resultRedirect
         */


        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        $result = $this->resultJsonFactory->create();
        if (!$this->customerSession->getCustomer()->getId() or !$this->formKeyValidator->validate($this->getRequest()
            )) {
            return $resultRedirect;

        }

        if ($this->getRequest())


        {

            try {
                $post = $this->postFactory->create();
                $post->setUserId($this->customerSession->getCustomer()->getId());
                $post->setPostContent($this->getRequest()->getParam('post-content'));
                $post->setCreatedAt(date('Y-m-d h:i:s', time()));
                $post->save();
                $this->messageManager->addSuccessMessage( __('Your testimonial added and waiting for moderation') );

                return $resultRedirect;


            }  catch (\Exception $e) {
                $response = [
                    'errors' => true,
                    'message' => __('Server Error.')
                ];
            }
            return $result->setData($response);
        }
    }
}