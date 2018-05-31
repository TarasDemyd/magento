<?php

namespace Mage\Testimonials\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Event\Manager;
use Mage\Testimonials\Model\PostFactory;


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


        if ($this->getRequest())


        {

            try {
                $post = $this->postFactory->create();
                $post->setUserId($this->customerSession->getCustomer()->getId());
                $post->setPostContent($this->getRequest()->getParam('postcontent'));
                $post->setCreatedAt(date('Y-m-d h:i:s', time()));
                $post->save();
                $post->cleanModelCache();



                $this->eventManager->dispatch('mage_testimonials_email', [
                    'customer' => (array)$this->customerSession->getCustomer()->getData(),
                    'testimonial' => (array)$post->getData()
                ]);

                $this->messageManager->addSuccessMessage( __('Your testimonial added and waiting for moderation') );


            }  catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
            }
        }

    }
}