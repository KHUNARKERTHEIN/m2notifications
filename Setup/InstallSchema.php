<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Notifications setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
		$installer = $setup;
		$installer->startSetup();

		/**
		 * Creating table ksolves_custom_notifications
		 */
		$table = $installer->getConnection()->newTable(
			$installer->getTable('ksolves_custom_notifications')
		)->addColumn(
			'custom_notification_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			11,
			['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
			'Custom Notification Id'
		)->addColumn(
			'customer_group_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			15,
			['nullable' => false, 'unsigned' => true ],
			'Customer Group id'
		)->addColumn(
			'customer_group',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			15,
			['nullable' => false, 'unsigned' => true ],
			'Customer Group'
		)->addColumn(
			'custom_image',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			255,
			['nullable' => true,'default' => null],
			'Custom Image'
		)->addColumn(
			'custom_url',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			'64k',
			['nullable' => false],
			'Custom Url'
		)->addColumn(
			'custom_message',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			'64k',
			['nullable' => false,'default' => null],
			'Custom Message'
		)->addColumn(
			'status',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			5,
			['nullable' => true,'default' => 0],
			'Status'
		)->addColumn(
		    'created_at',
		    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
		    null,
		    ['nullable' => false],
		    'Created At'
		)->addColumn(
		    'updated_at',
		    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
		    null,
		    ['nullable' => false],
		    'Updated At'

		)->addForeignKey(
            $installer->getFkName(
                $installer->getTable('ksolves_custom_notifications'),
                'customer_group_id',
                $installer->getTable('customer_group'),
                'customer_group_id'
            ),
            'customer_group_id',
            $installer->getTable('customer_group'),
            'customer_group_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            
		)->setComment(
            'Ksolves Custom Notifications Table'
        );
		$installer->getConnection()->createTable($table);
		$installer->getConnection()->addIndex(
				$installer->getTable('ksolves_custom_notifications'),
				$setup->getIdxName(
					$installer->getTable('ksolves_custom_notifications'),
					['customer_group','custom_message'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			),
				['customer_group','custom_message'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);

		/**
		 * ksolves_customer_admin_notifications table
		 */ 
		$table = $installer->getConnection()->newTable(
			$installer->getTable('ksolves_customer_admin_notifications')
		)->addColumn(
			'customer_notification_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			null,
			['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
			' Customer Notification Id'
		)->addColumn(
			'group_notifications_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			11,
			['nullable' => false, 'unsigned' => true],
			'Group Notifications Id'
		)->addColumn(
			'customer_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			11,
			['nullable' => true,'unsigned' => true],
			'Customer Id'
		)->addColumn(
			'customer_group_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			11,
			['nullable' => true,'default' => null],
			'Customer Group id'
		)->addColumn(
			'status',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			5,
			['nullable' => true,'default' => 0],
			'Status'
		)->addColumn(
			'is_read',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			5,
			['nullable' => true,'default' => 0],
			'Read Status'
		)->addColumn(
		    'created_at',
		    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
		    null,
		    ['nullable' => false],
		    'Created At'

        )->addForeignKey(
            $installer->getFkName(
                'ksolves_customer_admin_notifications',
                'group_notifications_id',
                'ksolves_custom_notifications',
                'custom_notification_id'
            ),
            'group_notifications_id',
            $installer->getTable('ksolves_custom_notifications'), 
            'custom_notification_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE

        )->addForeignKey(
            $installer->getFkName(
                'ksolves_customer_admin_notifications',
                'customer_id',
                'customer_entity',
                'entity_id'
            ),
            'customer_id',
            $installer->getTable('customer_entity'), 
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
               
		)->setComment( 
			'Ksolves Customer Admin Notifications Table'
		);
		$installer->getConnection()->createTable($table);
		$installer->getConnection()->addIndex(
				$installer->getTable('ksolves_customer_admin_notifications'),
				$setup->getIdxName(
					$installer->getTable('ksolves_customer_admin_notifications'),
					['customer_id',]),
				['customer_id']
			);
		

		/**
		 * Creating table ksolves_wishlist_notifications
		 */
		$table = $installer->getConnection()->newTable(
			$installer->getTable('ksolves_wishlist_notifications')
		)->addColumn(
			'wishlist_notification_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			11,
			['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
			'Notification Id'
		)->addColumn(
			'wishlist_item_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			10,
			['nullable' => true , 'unsigned' => true],
			'Wishlist Item Id'
		)->addColumn(
			'customer_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			10,
			['nullable' => true , 'unsigned' => true],
			'Customer Id'
		)->addColumn(
			'customer_name',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			35,
			['nullable' => true , 'unsigned' => true],
			'Customer Name'
		)->addColumn(
			'product_id',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			10,
			['nullable' => true, 'unsigned' => true],
			'Product Id'
		)->addColumn(
			'product_name',
			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
			30,
			['nullable' => true, 'unsigned' => true],
			'Product Name'
		)->addColumn(
			'is_read',
			\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
			5,
			['nullable' => true,'default' => 0],
			'Read Status'
		)->addColumn(
		    'created_at',
		    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
		    null,
		    ['nullable' => false],
		    'Created At'
		
		)->addForeignKey(
            $installer->getFkName(
                $installer->getTable('ksolves_wishlist_notifications'),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id'
            ),
            'product_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                $installer->getTable('ksolves_wishlist_notifications'),
                'customer_id',
                $installer->getTable('customer_entity'),
                'entity_id'
            ),
            'customer_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                $installer->getTable('ksolves_wishlist_notifications'),
                'wishlist_item_id',
                $installer->getTable('wishlist_item'),
                'wishlist_item_id'
            ),
            'wishlist_item_id',
            $installer->getTable('wishlist_item'),
            'wishlist_item_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            
		)->setComment( 
			'Ksolves Wishlist Notifications Table'
		);
		$installer->getConnection()->createTable($table);
		$installer->getConnection()->addIndex(
				$installer->getTable('ksolves_wishlist_notifications'),
				$setup->getIdxName(
					$installer->getTable('ksolves_wishlist_notifications'),
						['customer_name','product_name'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
					['customer_name','product_name'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				);

		$installer->endSetup();
	}
}
