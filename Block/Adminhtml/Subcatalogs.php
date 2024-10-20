<?php
/**
 * @category   Baytalebaa
 * @package    Baytalebaa_Shops
 * @author     m.ashraf@baytalebaa.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Baytalebaa\Shops\Block\Adminhtml;

class Subcatalogs extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'subcatalogs';
        $this->_headerText = __('Subcatalogs');
        $this->_addButtonLabel = __('Add New Subcatalogs');
        parent::_construct();
    }
}
