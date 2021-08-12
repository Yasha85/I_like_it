<?php

namespace Amasty\CoolModule\Controller\Index;

use Amazon\Payment\Observer\SandboxSimulation;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Index extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Context              $context,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;

        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->scopeConfig->isSetFlag('cool_config/general/enabled')) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        } else {
            die('<h1>Ну уж простите, сегодня не работаем! <br>Обратитесь к администратору.</h1><p>Администрация</p>');
        }
    }
}
