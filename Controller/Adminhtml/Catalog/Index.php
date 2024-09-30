<?php
namespace Baytalebaa\BrandCatalog\Controller\Adminhtml\Catalog;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Baytalebaa_BrandCatalog::catalog');
        $resultPage->getConfig()->getTitle()->prepend(__('Catalog Grid'));

        return $resultPage;
    }
}
