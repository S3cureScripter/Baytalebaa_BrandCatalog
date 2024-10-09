<?php
/**
 * @category   Baytalebaa
 * @package    Baytalebaa_Shops
 * @author     m.ashraf@baytalebaa.com
 */

namespace Baytalebaa\Shops\Model\Source;

use Baytalebaa\Shops\Model\ResourceModel\Catalogs\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;

class Catalogs extends AbstractSource implements OptionSourceInterface
{
    protected $catalogCollectionFactory;

    public function __construct(
        CollectionFactory $catalogCollectionFactory
    ) {
        $this->catalogCollectionFactory = $catalogCollectionFactory;
    }

    /**
     * Retrieve option array for shops
     *
     * @return array
     */
    public function getAllOptions()
    {
        $options = [];
        $collection = $this->catalogCollectionFactory->create();

        foreach ($collection as $shop) {
            $options[] = ['value' => $shop->getId(), 'label' => $shop->getTitle()]; // Use 'getTitle()' for shop title
        }

        return $options;
    }
}
