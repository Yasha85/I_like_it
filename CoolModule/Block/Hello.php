<?php

namespace Amasty\CoolModule\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template\Context;

class Hello extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Context              $context,
        ScopeConfigInterface $scopeConfig,
        array                $data = []
    ){
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function sayHelloFromConfig()
    {
        return $this->scopeConfig->getValue('cool_config/general/greeting_text');
    }
}
