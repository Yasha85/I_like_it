<?php

namespace Amasty\SecondModule\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;

class CheckPromoSku implements ObserverInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CheckoutSession
     */
    private $session;

    public function __construct(

        CheckoutSession            $session,
        ProductRepositoryInterface $productRepository,
        ScopeConfigInterface       $scopeConfig

    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->productRepository = $productRepository;
        $this->session = $session;
    }

    public function execute(Observer $observer)
    {
        $inputSku = $observer->getData('product_to_check');
        $promoSku = $this->scopeConfig->getValue('second_config/general/promo_sku');
        $forSku = $this->scopeConfig->getValue('second_config/general/for_sku');
        $forSku = explode(", ", $forSku);

        if (in_array($inputSku, $forSku)) {
            $quote = $this->session->getQuote();
            if (!$quote->getId()) {
                $quote->save();
            }
            $product = $this->productRepository->get($promoSku);
            $quote->addProduct($product, 1);
            $quote->save();

        }
    }
}
