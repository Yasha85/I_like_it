<?php

namespace Amasty\CoolModule\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

class Add extends Action
{

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var CheckoutSession
     */
    private $session;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductCollectionFactory
     */

    private $productCollectionFactory;

    public function __construct(
        Context                    $context,
        ScopeConfigInterface       $scopeConfig,
        CheckoutSession            $session,
        ProductRepositoryInterface $productRepository,
        ProductCollectionFactory   $productCollectionFactory
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context);
    }

    public function redirect()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('page');
        return $resultRedirect;
    }

    public function execute()
    {
        $sku = $this->getRequest()->getParam('sku');
        $qty = $this->getRequest()->getParam('qty');

        $quote = $this->session->getQuote();
        if (!$quote->getId()) {
            $quote->save();
        }
        try {
            $product = $this->productRepository->get($sku); //Можно и через фабрику, но тут не обязательно.

        } catch (NoSuchEntityException $e) {
            $this->messageManager->addNoticeMessage('Такого товара нет!!! Введите правильные данные');
            return $this->redirect();
        }

        if ($product->getTypeId() !== 'simple') {
            $this->messageManager->addNoticeMessage('Ваш продукт не такой уж и simple');
            return $this->redirect();

        } else {

            try {
                $quote->addProduct($product, $qty);
                $quote->save();

            } catch (LocalizedException $e) {
                $this->messageManager->addNoticeMessage('Слишком многого хотите! Будьте скромнее. P.S унас нет столько товара');
            }
            return $this->redirect();
        }
    }
}
