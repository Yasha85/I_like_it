<?php

namespace Amasty\CoolModule\Cron;

use Amasty\CoolModule\Model\BlacklistProduct;
use Amasty\CoolModule\Model\BlacklistProductFactory;
use Amasty\CoolModule\Model\BlacklistProductRepository;
use Amasty\CoolModule\Model\ResourceModel\BlacklistProduct as BlacklistProductResource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use psr\Log\LoggerInterface;
use Magento\Store\Model\ScopeInterface;

class SendBlacklistEmail
{
    /**
     * @var BlacklistProductRepository
     */
    protected $blacklistProductRepository;
    /**
     * @var BlacklistProduct
     */
    protected $blacklistProduct;

    /**
     * @var BlacklistProductFactory
     */

    protected $blacklistProductFactory;

    /**
     * @var BlacklistProductResource
     */
    protected $blacklistProductResource;


    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;


    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var FactoryInterface
     */
    protected $templateFactory;

    public function __construct(
        BlacklistProductResource   $blacklistProductResource,
        BlacklistProductRepository $blacklistProductRepository,
        BlacklistProduct           $blacklistProduct,
        ScopeConfigInterface       $scopeConfig,
        BlacklistProductFactory    $blacklistProductFactory,
        LoggerInterface            $logger,
        StoreManagerInterface      $storeManager,
        TransportBuilder           $transportBuilder,
        FactoryInterface           $templateFactory
    )
    {
        $this->blacklistProductResource = $blacklistProductResource;
        $this->transportBuilder = $transportBuilder;
        $this->blacklistProductRepository = $blacklistProductRepository;
        $this->blacklistProduct = $blacklistProduct;
        $this->scopeConfig = $scopeConfig;
        $this->blacklistProductFactory = $blacklistProductFactory;
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->templateFactory = $templateFactory;

    }

    public function execute()
    {
        $blacklistProduct = $this->blacklistProductRepository->getById(1);
        $templateId = $this->scopeConfig->getValue('cool_config/send_email_config/email_template');
        $senderName = "Admin";
        $senderEmail = "amastyAdmin@amasty.com";
        $toEmail = $this->scopeConfig->getValue('cool_config/send_email_config/email');


        $templateVars = [
            'blacklist' => $blacklistProduct,
            'blacklist_product_id' => $blacklistProduct->getId(),
            'product_sku' => $blacklistProduct->getProduct_sku(),
            'product_qty' => $blacklistProduct->getProduct_qty()
        ];
        $storeId = $this->storeManager->getStore()->getId();

        $from = [
            'email' => $senderEmail,
            'name' => $senderName

        ];

        $templateOptions =
            [
                'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                'store' => $storeId
            ];

        /**
         * @var \Magento\Email\Model\Transport $transport
         */
        $transport = $this->transportBuilder->setTemplateIdentifier($templateId, ScopeInterface::SCOPE_STORE)
            ->setTemplateOptions($templateOptions)
            ->setTemplateVars($templateVars)
            ->setFromByScope($from)
            ->addTo($toEmail)
            ->getTransport();

//        $transport->sendMessage();

//        /**
//         * @var \Magento\Framework\Mail\EmailMessage $message
//         */

//        $message = $transport->getMessage();
//        $messageText = $message->getBodyText();

        $template = $this->templateFactory->get($templateId);
        $template->setVars($templateVars)
            ->setOptions($templateOptions);
        $emailBody = $template->processTemplate();

        $emailBlacklistBody = $this->blacklistProductFactory->create();
        $emailBlacklistBody->setEmail_body($emailBody);
        $this->blacklistProductResource->save($emailBlacklistBody);

        $this->logger->debug('Amasty CoolModule Job');
    }
}
