<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 20.12.17
 * Time: 18:47
 */
namespace Mage\Testimonials\Ui\Component\Listing;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Customer\Model\Customer;
class FullName extends Column
{
    /**
     * @var Escaper
     */
    protected $escaper;
    /**
     * @var Customer
     */
    protected $customer;
    /**
     * CustomerLastName constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param Customer $customer
     * @param Escaper $escaper
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        Customer $customer,
        Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
        $this->escaper = $escaper;
        $this->customer = $customer;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $customer = $this->customer->load($item['user_id']);
                $item[$this->getData('name')] = $this->escaper->escapeHtml($customer->getName());
            }
        }
        return $dataSource;
    }
}