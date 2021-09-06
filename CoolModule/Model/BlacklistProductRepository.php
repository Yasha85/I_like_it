<?php

namespace Amasty\CoolModule\Model;

use Amasty\CoolModule\Model\BlacklistProduct;
use Amasty\CoolModule\Model\BlacklistProductFactory;
use Amasty\CoolModule\Model\ResourceModel\BlacklistProduct as BlacklistProductResource;

class BlacklistProductRepository
{

    /**
     * @var BlacklistProductFactory
     */
    protected $blacklistProductFactory;

    /**
     * @var BlacklistProductResource
     */
    protected $blacklistProductResource;


    public function __construct(

        BlacklistProductFactory  $blacklistProductFactory,
        BlacklistProductResource $blacklistProductResource
    )
    {
        $this->blacklistProductFactory = $blacklistProductFactory;
        $this->blacklistProductResource = $blacklistProductResource;

    }

    public function getById(int $id)
    {
        $blacklistProduct = $this->blacklistProductFactory->create();
        $this->blacklistProductResource->load(
            $blacklistProduct,
            $id
        );
        return $blacklistProduct;
    }

//        public function deleteById(int $id)
//        {
//            $blacklistProduct = $this->getById($id);
//            $this->blacklistProductResource->delete($blacklistProduct);
//        }


}

