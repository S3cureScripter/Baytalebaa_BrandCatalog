<?php
namespace Baytalebaa\Shops\Setup;
use Magento\Framework\Setup\UpgradeSchemaInterface;
class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{

	public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();
        

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            /**
            * Creating table Baytalebaa_Shops_Catalog
            */
            if (!$installer->tableExists('Baytalebaa_Shops_Catalog')) 
            {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('Baytalebaa_Shops_Catalog')
                    )->addColumn(
                        'catalog_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Entity Id'
                    )->addColumn(
                        'Catalog_shop_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false, ],
                        'Shop ID'
                    )->addColumn(
                        'title',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true],
                        'Title'
                    )->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => false,'default' => 0],
                        'Status'
                    )->addColumn(
                        'content',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '2M',
                        ['nullable' => true,'default' => null],
                        'Content'
                    )->addColumn(
                        'image',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true,'default' => null],
                        'Image'
                    )->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false],
                        'Created At'
                    )->addForeignKey(
                        $installer->getFkName(
                            'Baytalebaa_Shops_Catalog', 
                            'shop_brand_id', 
                            'baytalebaa_shops',  
                            'shops_id'    // Referencing 'shops_id' in 'baytalebaa_shops'
                        ),
                        'shop_brand_id',                        // Column in Baytalebaa_Shops_Catalog
                        $installer->getTable('baytalebaa_shops'),      
                        'shops_id',                             // Correct column in baytalebaa_shops
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )->setComment(
                        'Baytalebaa Catalog Table'
                    );
                $installer->getConnection()->createTable($table);
            }	

            /**
            * Creating table Baytalebaa_Shops_SubCatalog
            */
            if (!$installer->tableExists('Baytalebaa_Shops_SubCatalog')) 
            {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('Baytalebaa_Shops_SubCatalog')
                    )->addColumn(
                        'subcatalog_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                        'Entity Id'
                    )->addColumn(
                        'catalog_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['unsigned' => true, 'nullable' => false, ],
                        'Shop ID'
                    )->addColumn(
                        'title',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true],
                        'Title'
                    )->addColumn(
                        'related_products',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true],
                        'Related Products'
                    )->addColumn(
                        'status',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        1,
                        ['nullable' => false,'default' => 0],
                        'Status'
                    )->addColumn(
                        'content',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '2M',
                        ['nullable' => true,'default' => null],
                        'Content'
                    )->addColumn(
                        'image',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable' => true,'default' => null],
                        'Image'
                    )->addColumn(
                        'created_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false],
                        'Created At'
                    )->addForeignKey(
                        $installer->getFkName(
                            'Baytalebaa_Shops_SubCatalog', 
                            'catalog_id', 
                            'Baytalebaa_Shops_Catalog',  
                            'catalog_id'    // Referencing 'catalog_id' in 'Baytalebaa_Shops_Catalog'
                        ),
                        'catalog_id',                        // Column in Baytalebaa_Shops_SubCatalog
                        $installer->getTable('Baytalebaa_Shops_Catalog'),     
                        'catalog_id',                             // Correct column in Baytalebaa_Shops_Catalog
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )->setComment(
                        'Baytalebaa Catalog Table'
                    );
                $installer->getConnection()->createTable($table);
            }	
        }
		$installer->endSetup();
	}
}