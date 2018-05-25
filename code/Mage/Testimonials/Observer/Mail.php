<?php
namespace Mage\Testimonials\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Validator\EmailAddress;
use Magento\Framework\DataObject;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Area;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Mage\Testimonials\Model\PostFactory;

class Mail implements ObserverInterface
{
    const XML_PATH_EMAIL_RECIPIENT = 'testi/general/recipient_email';
    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;
    /**
     * @var EmailAddress
     */
    protected $validatorEmail;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var PostFactory
     */

    protected $_testimonials;
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    /**
     * TestimonialsObserver constructor.
     * @param TransportBuilder $transportBuilder
     * @param StoreManagerInterface $storeManager
     * @param EmailAddress $validatorEmail
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager,
        EmailAddress $validatorEmail,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->validatorEmail = $validatorEmail;
        $this->_transportBuilder = $transportBuilder;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }


    public function execute(Observer $observer)
    {

        $customer = $observer->getData('customer');
        $testimonial = $observer->getData('testimonial');
        $post_content = $testimonial['post_content'];
        $email = $customer['email'];
        $date = $testimonial['created_at'];
        $storeScope = ScopeInterface::SCOPE_STORE;

        if ($this->validatorEmail->isValid($email)) {
            $customerObject = new DataObject();
            $templateParams = [
                'date' => $date,
                'email' => $email,
                'post_content' => $post_content
            ];
            $customerObject->setData($templateParams);
            $this->_transportBuilder->setTemplateIdentifier(
                'sendmail_template'
            )->setTemplateOptions(
                [
                    'area' => Area::AREA_FRONTEND,
                    'store' => $this->storeManager->getStore()->getId(),
                ]
            )->setTemplateVars(
                ['data' => $customerObject]
            )->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope));
            $transport = $this->_transportBuilder->getTransport();
            try {
                $transport->sendMessage();
            } catch (\Exception $e) {
                ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->debug($e->getMessage());
            }
        }
    }
}