<?php

namespace Amasty\SecondModule\Controller\Index;

use Amasty\CoolModule\Controller\Index\Index as ControllerIndex;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Index extends ControllerIndex
{
    public function __construct
    (
        Context              $context,
        ScopeConfigInterface $scopeConfig,
        Session              $session
    )
    {
        $this->session = $session;
        parent::__construct($context, $scopeConfig);
    }

    public function execute()
    {
        if (!$this->session->isLoggedIn()) {
            die('Вы не авторизованы, залогиньтесь и получите доступ');
        } else {
            return ControllerIndex::execute();
        }
    }
}
