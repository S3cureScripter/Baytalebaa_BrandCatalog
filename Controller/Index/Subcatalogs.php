<?php

namespace Baytalebaa\Shops\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\NotFoundException;
use Baytalebaa\Shops\Block\Subcatalogs as Shops;

class Subcatalogs extends \Magento\Framework\App\Action\Action
{
	protected $_shops;

	public function __construct(
        Context $context,
        Shops $shops
    ) {
        $this->_shops = $shops;
        parent::__construct($context);
    }

	public function execute()
    {
    	if(!$this->_shops->getSingleData()){
    		throw new NotFoundException(__('Parameter is incorrect.'));
    	}
    	
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}