<?php
/**
 * @category   Baytalebaa
 * @package    Baytalebaa_Shops
 * @author     m.ashraf@baytalebaa.com
 */

namespace Baytalebaa\Shops\Model\Source;

use Baytalebaa\Shops\Model\ResourceModel\Shops\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

class Shops extends AbstractSource implements OptionSourceInterface
{
    protected $shopCollectionFactory;

    public function __construct(
        CollectionFactory $shopCollectionFactory
    ) {
        $this->shopCollectionFactory = $shopCollectionFactory;
    }

    /**
     * Retrieve option array for shops
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [];
        $collection = $this->shopCollectionFactory->create();

        foreach ($collection as $shop) {
            $options[] = ['value' => $shop->getId(), 'label' => $shop->getTitle()]; // Use 'getTitle()' for shop title
        }

        return $options;
    }
}
