<?php
namespace Baytalebaa\BrandCatalog\Block\Adminhtml\Catalog;

class Index extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_catalog';
        $this->_blockGroup = 'Baytalebaa_BrandCatalog';
        $this->_headerText = __('Catalogs');
        $this->_addButtonLabel = __('Create New Catalog');
        parent::_construct();
    }
}
