<?php

namespace Amasty\CoolModule\Controller\Message;

use Magento\Framework\App\Action\Action;

class Index extends Action
{
    public function execute()
    {
        echo 'Привет Amasty! Готов и буду изучать Magento 2. Пока, вроде, получается)))';
        exit();
    }
}
