<?php

namespace Mage\Testimonials\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Mage\Testimonials\Model\PostFactory;
use Magento\Backend\App\Action\Context;

class InlineEdit extends Action
{
    /**
     * JSON Factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_jsonFactory;
    /**
     * Post Factory
     *
     * @var \Mage\Testimonials\Model\PostFactory
     */
    protected $_postFactory;
    /**
     * constructor
     *
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Mage\Testimonials\Model\PostFactory $postFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        JsonFactory $jsonFactory,
        PostFactory $postFactory,
        Context $context
    ){
        $this->_jsonFactory = $jsonFactory;
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        foreach (array_keys($postItems) as $postId) {
            /** @var \Mage\Testimonials\Model\PostFactory $post */
            $post = $this->_postFactory->create()->load($postId);
            try {
                $postData = $postItems[$postId];//todo: handle dates
                $post->addData($postData);
                $post->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithPostId($post, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithPostId(
                    $post,
                    __('Something went wrong while saving the Post.')
                );
                $error = true;
            }
        }
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
    /**
     * Add Post id to error message
     *
     * @param \Mage\Testimonials\Model\PostFactory $post
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithPostId(\Mage\Testimonials\Model\PostFactory $post, $errorText)
    {
        return '[ID: ' . $post->getId() . '] ' . $errorText;
    }
}