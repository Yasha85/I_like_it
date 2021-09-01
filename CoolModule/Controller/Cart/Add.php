<?php

namespace Amasty\CoolModule\Controller\Cart;

use Amasty\CoolModule\Model\BlacklistProduct;
use Amasty\CoolModule\Model\BlacklistProductFactory;
use Amasty\CoolModule\Model\ResourceModel\BlacklistProduct\CollectionFactory;
use Amasty\CoolModule\Model\ResourceModel\BlacklistProduct as BlacklistProductResource;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Event\ManagerInterface as EventManager;

class Add extends Action
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var BlacklistProductFactory
     */

    protected $blacklistProductFactory;

    /**
     * @var BlacklistProductResource
     */
    protected $blacklistProductResource;


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
     * @var EventManager
     */
    private $eventManager;


    public function __construct(
        Context                    $context,
        ScopeConfigInterface       $scopeConfig,
        CheckoutSession            $session,
        CollectionFactory          $collectionFactory,
        BlacklistProductFactory    $blacklistProductFactory,
        ProductRepositoryInterface $productRepository,
        EventManager               $eventManager
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->session = $session;
        $this->collectionFactory = $collectionFactory;
        $this->blacklistProductFactory = $blacklistProductFactory;
        $this->productRepository = $productRepository;
        $this->eventManager = $eventManager;
        parent::__construct($context);
    }

    public function redirect()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('page');
        return $resultRedirect;
    }


    public function getProduct($sku)
    {
        $product = $this->productRepository->get($sku);
        return $product;
    }

    public function checkIsSimpleProduct($product): bool
    {
        return $product->getTypeId() === 'simple';
    }

    public function addProduct($quote, $product, $qty)
    {
        try {
            $quote->addProduct($product, $qty);
            $quote->save();

        } catch (LocalizedException $e) {
            $this->messageManager
                ->addNoticeMessage('Слишком многого хотите! Будьте скромнее. P.S унас нет столько товара');
        }
    }

    public function getQtyOutDb($sku)
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(
            'product_sku',
            ['eq' => $sku]
        );
        foreach ($collection as $item) {
            $this->limitQty = $item->getProduct_qty();
        }
    }

    public function getQuote()
    {
        $quote = $this->session->getQuote();
        if (!$quote->getId()) {
            $quote->save();
        }

        return $quote;
    }

    /**
     * @var integer
     */
    protected $limitQty;

    /**
     * @var integer
     */
    protected $cartQty;

    public function execute()
    {

        $sku = (string)$this->getRequest()->getParam('sku');
        $qty = $this->getRequest()->getParam('qty');

        $this->eventManager->dispatch(
            'amasty_coolModule_add_product',
            ['product_to_check' => $sku]
        );

        $this->getQtyOutDb($sku);
        $quote = $this->getQuote();

        if ($this->limitQty) {
            $items = $quote->getAllItems();
            if ($items) {
                foreach ($items as $item) {
                    if ($item->getSku() == $sku) {
                        $this->cartQty = $item->getQty();
                    }
                }
            }
            if (($this->cartQty + $qty) > $this->limitQty) {
                $qty = $this->limitQty - $this->cartQty;
                if ($qty == 0) {
                    $this->messageManager
                        ->addNoticeMessage('В корзине максимальное количество, больше не дадим!!!');
                    return $this->redirect();
                }
                $this->addProduct($quote, $this->getProduct($sku), $qty);
                $this->messageManager
                    ->addNoticeMessage('У нас есть лимиты, поэтому только ' .  $qty  . ' штук добавим');
                return $this->redirect();


            } else {
                $this->addProduct($quote, $this->getProduct($sku), $qty);
                return $this->redirect();

            }
        }

        try {
            $product = $this->getProduct($sku);

            if (!$this->checkIsSimpleProduct($product)) {
                $this->messageManager->addNoticeMessage('Ваш продукт не такой уж и simple');
                return $this->redirect();
            }

        } catch (NoSuchEntityException $e) {
            $this->messageManager->addNoticeMessage('Такого товара нет!!! Введите правильные данные');
            return $this->redirect();
        }

        $this->addProduct($quote, $product, $qty);

        return $this->redirect();
    }
}
