<?php
/**
 * @category   Baytalebaa
 * @package    Baytalebaa_Shops
 * @author     m.ashraf@baytalebaa.com
 * @copyright  This file was generated by using Module Creator(http://code.vky.co.in/magento-2-module-creator/) provided by VKY <viky.031290@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Baytalebaa\Shops\Controller\Adminhtml\Shops;

class NewAction extends \Baytalebaa\Shops\Controller\Adminhtml\Shops
{

    public function execute()
    {
        $this->_forward('edit');
    }
}