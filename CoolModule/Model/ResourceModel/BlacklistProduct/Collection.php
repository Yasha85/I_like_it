<?php
namespace Amasty\CoolModule\Model\ResourceModel\BlacklistProduct;

use Amasty\CoolModule\Model\BlacklistProduct;
use Amasty\CoolModule\Model\ResourceModel\BlacklistProduct as BlacklistProductResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            BlacklistProduct::class,
            BlacklistProductResource::class
        );
    }
}
