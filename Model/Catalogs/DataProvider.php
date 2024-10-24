<?php

namespace Baytalebaa\Shops\Model\Catalogs;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Baytalebaa\Shops\Model\ResourceModel\Catalogs\CollectionFactory;
use Magento\Framework\App\RequestInterface;

/**
 * Class DataProvider
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Constructor
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->request = $request;
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $this->loadedData = [];
        $shopId = $this->request->getParam('id');

        if ($shopId) {
            $shop = $this->collection->getItemById($shopId);

            if ($shop) {
                $this->loadedData[$shopId] = $shop->getData();

                // Convert the store_id (comma-separated values) into an array for multiselect
                if (isset($this->loadedData[$shopId]['store_id'])) {
                    $this->loadedData[$shopId]['storeviews'] = explode(',', $this->loadedData[$shopId]['store_id']);
                }
            }
        }

        return $this->loadedData;
    }
}
