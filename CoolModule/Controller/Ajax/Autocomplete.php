<?php

namespace Amasty\CoolModule\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class Autocomplete extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonResultFactory;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;


    public function __construct(
        Context                  $context,
        JsonFactory              $jsonResultFactory,
        ProductCollectionFactory $productCollectionFactory
    )
    {
        $this->jsonResultFactory = $jsonResultFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $sku = $this->getRequest()->getParam('sku');
        $productCollection = $this->productCollectionFactory->create();
        $productCollection
            ->addAttributeToFilter('sku', ['like' => $sku . '%'])
            ->addAttributeToSelect('name')
            ->setPageSize(100); // в самый раз для запросов)

        $productList = [];

        foreach ($productCollection as $product) {
            $productList[] = $product->getSku() . " " . $product->getName();
        }

        $result = $this->jsonResultFactory->create();
        $result->setData($productList);
        return $result;
    }
}
