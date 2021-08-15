<?php

namespace Amasty\CoolModule\Block;

use Magento\Framework\App\Action\Action;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template\Context;

class Index extends Template
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

    public function qtyDisplay()
    {
        return $this->scopeConfig->getValue('cool_config/general/qty_display');
    }

    public function inDefaultQty()
    {
        return $this->scopeConfig->getValue('cool_config/general/in_default_qty');
    }
    public function getToForm()
    {
        return $this->getUrl('*/cart/add');
    }
}
