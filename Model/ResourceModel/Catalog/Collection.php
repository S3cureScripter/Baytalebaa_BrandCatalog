<?php
namespace Baytalebaa\BrandCatalog\Model\ResourceModel\Catalog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'catalog_id';


	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Baytalebaa\BrandCatalog\Model\Catalog', 'Baytalebaa\BrandCatalog\Model\ResourceModel\Catalog');
	}

}

