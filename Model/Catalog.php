<?php
namespace Baytalebaa\BrandCatalog\Model;
class Catalog extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'Baytalebaa_BrandCatalog_Catalog';

	protected $_cacheTag = 'Baytalebaa_BrandCatalog_Catalog';

	protected $_eventPrefix = 'Baytalebaa_BrandCatalog_Catalog';

	protected function _construct()
	{
		$this->_init('Baytalebaa\BrandCatalog\Model\ResourceModel\Catalog');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}