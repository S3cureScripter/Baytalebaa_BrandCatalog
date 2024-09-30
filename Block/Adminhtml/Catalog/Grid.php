<?php

namespace Baytalebaa\BrandCatalog\Block\Adminhtml\Catalog;

use Magento\Backend\Block\Widget\Grid\Extended;
use Baytalebaa\BrandCatalog\Model\ResourceModel\Catalog\CollectionFactory as CatalogCollectionFactory;

class Grid extends Extended
{
    protected $catalogCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        CatalogCollectionFactory $catalogCollectionFactory,
        array $data = []
    ) {
        $this->catalogCollectionFactory = $catalogCollectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    protected function _prepareCollection()
    {
        // Create the collection using the injected factory
        $collection = $this->catalogCollectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'catalog_id',
            [
                'header' => __('ID'),
                'index' => 'catalog_id',
                'type' => 'number'
            ]
        );
        
        $this->addColumn(
            'catalog_name',
            [
                'header' => __('Catalog Name'),
                'index' => 'catalog_name'
            ]
        );
        $this->addColumn(
            'catalog_image',
            [
                'header' => __('Catalog Image'),
                'index' => 'catalog_image',
                'renderer' => \MGS\Brand\Block\Adminhtml\Brand\Grid\Renderer\Image::class,
                'width' => '100px',
                'escape' => false
            ]
        );

        // Add timestamps
        $this->addColumn(
            'created_at',
            [
                'header' => __('Created At'),
                'index' => 'created_at',
                'type' => 'datetime'
            ]
        );

        $this->addColumn(
            'updated_at',
            [
                'header' => __('Updated At'),
                'index' => 'updated_at',
                'type' => 'datetime'
            ]
        );

        return parent::_prepareColumns();
    }
}
