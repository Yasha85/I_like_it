<?php

namespace Amasty\SecondModule\Plugin;

use Magento\Framework\UrlInterface;

class ChangePath
{
    /**
     * @var UrlInterface;
     */

    private $urlBuilder;

    public function __construct(
        UrlInterface $urlBuilder
    )
    {
        $this->urlBuilder = $urlBuilder;
    }

    public function afterGetToForm(
        $subject,
        $result
    )
    {
        return $this->urlBuilder->getUrl('checkout/cart/add');
    }
}
