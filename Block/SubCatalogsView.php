<?php
/**
 * @category   Baytalebaa
 * @package    Baytalebaa_Shops
 * @author     m.ashraf@baytalebaa.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Baytalebaa\Shops\Block;

use Magento\Framework\View\Element\Template\Context;
use Baytalebaa\Shops\Model\SubCatalogsFactory;
use Magento\Cms\Model\Template\FilterProvider;
/**
 * SubCatalogs View block
 */
class SubCatalogsView extends \Magento\Framework\View\Element\Template
{
    /**
     * @var subcatalog_id
     */
    protected $_subCatalogs;
    public function __construct(
        Context $context,
        SubCatalogsFactory $subCatalogs,
        FilterProvider $filterProvider
    ) {
        $this->_subCatalogs = $subCatalogs;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context);
    }

    public function _prepareLayout()
    {
        $this->pageConfig->getTitle()->set(__('Baytalebaa SubCatalogs Module View Page'));
        
        return parent::_prepareLayout();
    }

    public function getSingleData()
    {
        $id = $this->getRequest()->getParam('id');
        $subCatalogs = $this->_subCatalogs->create();
        $singleData = $subCatalogs->load($id);
        if($singleData->getShopsId() || $singleData['subcatalog_id'] && $singleData->getStatus() == 1){
            return $singleData;
        }else{
            return false;
        }
    }
}