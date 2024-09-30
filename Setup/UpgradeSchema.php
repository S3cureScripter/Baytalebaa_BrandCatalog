<?php
namespace Baytalebaa\BrandCatalog\Setup;
use Magento\Framework\Setup\UpgradeSchemaInterface;
class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{

	public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            // upgrade Catalog table
            if (!$installer->tableExists('Baytalebaa_BrandCatalog_Catalog')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('Baytalebaa_BrandCatalog_Catalog')
                )
                    ->addColumn(
                        'catalog_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary'  => true,
                            'unsigned' => true,
                        ],
                        'Catalog ID'
                    )
                    ->addColumn(
                        'catalog_name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Catalog Name'
                    )
                    ->addColumn(
                        'catalog_image',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Catalog Featured Image'
                    )
                    ->addColumn(
                            'created_at',
                            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                            null,
                            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                            'Created At'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated At'
                    )->setComment('Catalog Table');
                $installer->getConnection()->createTable($table);
    
                $installer->getConnection()->addIndex(
                    $installer->getTable('Baytalebaa_BrandCatalog_Catalog'),
                    $setup->getIdxName(
                        $installer->getTable('Baytalebaa_BrandCatalog_Catalog'),
                        ['catalog_name','catalog_image'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['catalog_name','catalog_image'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }
            // upgrade Sub_Catalog table
            if (!$installer->tableExists('Baytalebaa_BrandCatalog_Sub_Catalog')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('Baytalebaa_BrandCatalog_Sub_Catalog')
                )
                    ->addColumn(
                        'catalog_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        [
                            'identity' => true,
                            'nullable' => false,
                            'primary'  => true,
                            'unsigned' => true,
                        ],
                        'Catalog ID'
                    )->addColumn(
                        'catalog_parent_id',
                        \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        ['nullable' => false, 'unsigned' => true],
                        'Catalog Parent ID'
                    )
                    ->addColumn(
                        'catalog_name',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        ['nullable => false'],
                        'Catalog Name'
                    )
                    ->addColumn(
                        'catalog_content',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        [],
                        'Catalog Content'
                    )
                    ->addColumn(
                        'catalog_image',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'Catalog Featured Image'
                    )
                    ->addColumn(
                            'created_at',
                            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                            null,
                            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                            'Created At'
                    )->addColumn(
                        'updated_at',
                        \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                        null,
                        ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                        'Updated At'
                    )->addForeignKey(
                        $installer->getFkName(
                            'Baytalebaa_BrandCatalog_Sub_Catalog', 
                            'catalog_parent_id', 
                            'Baytalebaa_BrandCatalog_Catalog', 
                            'catalog_id'
                        ),
                        'catalog_parent_id',
                        $installer->getTable('Baytalebaa_BrandCatalog_Catalog'),
                        'catalog_id',
                        \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                    )
                    ->setComment('Catalog Table');
                $installer->getConnection()->createTable($table);
    
                $installer->getConnection()->addIndex(
                    $installer->getTable('Baytalebaa_BrandCatalog_Sub_Catalog'),
                    $setup->getIdxName(
                        $installer->getTable('Baytalebaa_BrandCatalog_Sub_Catalog'),
                        ['catalog_name','catalog_content','catalog_image'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['catalog_name','catalog_content','catalog_image'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }

        }
        //Add Brand Column
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            // upgrade Catalog table
            if (!$installer->tableExists('Baytalebaa_BrandCatalog_Catalog')) {
                $table = $installer->getConnection()->newTable(
                    $installer->getTable('Baytalebaa_BrandCatalog_Catalog')
                )
                ->addColumn(
                    'catalog_brand_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'unsigned' => true],
                    'Catalog brand ID'
                )->addForeignKey(
                    $installer->getFkName(
                        'Baytalebaa_BrandCatalog_Catalog', 
                        'catalog_brand_id', 
                        'mgs_brand', 
                        'brand_id'
                    ),
                    'catalog_brand_id',
                    $installer->getTable('Baytalebaa_BrandCatalog_Catalog'),
                    'brand_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )->setComment('Add Brand Column');
                $installer->getConnection()->createTable($table);
    
                $installer->getConnection()->addIndex(
                    $installer->getTable('Baytalebaa_BrandCatalog_Catalog'),
                    $setup->getIdxName(
                        $installer->getTable('Baytalebaa_BrandCatalog_Catalog'),
                        ['brand_id','catalog_name','catalog_image'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['brand_id','catalog_name','catalog_image'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                );
            }

        }
        // create Brand_Catalog table
        // create Brand_Catalog_products table

		$installer->endSetup();
	}
}