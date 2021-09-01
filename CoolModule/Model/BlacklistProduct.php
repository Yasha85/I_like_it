<?php

namespace Amasty\CoolModule\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class BlacklistProduct
 * @method string getProduct_sku()
 * @method integer getProduct_qty()
 *
 */
class BlacklistProduct extends AbstractModel
{
    protected function _construct()
    {
       $this->_init(
           ResourceModel\BlacklistProduct::class
       );
    }
}
