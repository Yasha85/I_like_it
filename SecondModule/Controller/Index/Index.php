<?php

namespace Amasty\SecondModule\Controller\Index;

use Amasty\CoolModule\Controller\Index\Index as ControllerIndex;
use Magento\Framework\App\ObjectManager;

class Index extends ControllerIndex
{
    public function execute()
    {
        $objectManager = ObjectManager::getInstance();
        $customerSession = $objectManager->get('Magento\Customer\Model\Session');

        if (!$customerSession->isLoggedIn()) {
            die('Вы не авторизованы, залогиньтесь и получите доступ');
        }
        else {
            return ControllerIndex::execute();
        }
    }
}
