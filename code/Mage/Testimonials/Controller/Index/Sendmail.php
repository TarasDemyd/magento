<?php

namespace Mage\Testimonials\Controller\Index;

use Magento\Framework\App\RequestInterface;

class Sendmail extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context
        , \Magento\Framework\App\Request\Http $request
        , \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
        , \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_request = $request;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $store = $this->_storeManager->getStore()->getId();
            $transport = $this->_transportBuilder->setTemplateIdentifier('sendmail_template')
                ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
                ->setTemplateVars(
                    [
                        'store' => $this->_storeManager->getStore(),
                    ]
                )
                ->setFrom('general')
                // you can config general email address in Store -> Configuration -> General -> Store Email Addresses
                ->addTo('customer@email.com', 'Customer Name')
                ->getTransport();
            $transport->sendMessage();
            $this->messageManager->addSuccessMessage( __('Your mail send') );
        }  catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

    }
}