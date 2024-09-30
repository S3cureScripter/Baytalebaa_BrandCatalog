<?php
namespace Baytalebaa\BrandCatalog\Model\ResourceModel;

class Catalog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	
	protected function _construct()
	{
		$this->_init('Baytalebaa_BrandCatalog_Catalog', 'catalog_id');
	}
	
}