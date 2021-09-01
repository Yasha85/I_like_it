<?php

namespace Amasty\CoolModule\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\DB\AbstractDb;

class BlacklistProduct extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
            \Amasty\CoolModule\Setup\InstallSchema::TABLE_NAME,
            'blacklist_product_id'
        );
    }

}
