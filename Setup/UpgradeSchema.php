<?php
/**
 * @author Ksolves Team
 * @copyright Copyright (c) 2020 Ksolves (https://www.ksolves.com)
 * @package Ksolves_Notifications
 */

namespace Ksolves\Notifications\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Notifications schema update
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$setup->startSetup();
		if (version_compare($context->getVersion(), '2.0.0', '<=')) {
		    $installer = $setup;
		    $installer->startSetup();
		    /* Create Ksolves_orders_notifications table  */
		 $table = $installer->getConnection()
	        ->newTable($installer->getTable('ksolves_orders_notifications')
	    	)->addColumn(
	        	'order_notification_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				15,
				['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
				'Order Notification Id'
			)->addColumn(
				'customer_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				15,
				['nullable' => false , 'unsigned' => true],
				'Customer Id'
			)->addColumn(
				'customer_name',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				35,
				['nullable' => false],
				'Customer Name'
			)->addColumn(
				'customer_email',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				35,
				['nullable' => false],
				'Customer Email'
			)->addColumn(
				'order_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				15,
				['nullable' => false , 'unsigned' => true],
				'Order Id'		
			)->addColumn(
	        	'increment_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				15,
				['nullable' => false],
				'Order Increment Id'
			)->addColumn(
	        	'invoice_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				10,
				['nullable' => true],
				'Order Invoice Id'
			)->addColumn(
	        	'shipment_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				10,
				['nullable' => true],
				'Order Shipment Id'
			)->addColumn(
	        	'creditmemo_id',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				10,
				['nullable' => true],
				'Order Credit Memo Id'
			)->addColumn(
				'order_status',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				15,
				['nullable' => false],
				'Order Status'
			)->addColumn(
				'is_read',
				\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				1,
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
	                $installer->getTable('ksolves_orders_notifications'),
	                'order_id',
	                $installer->getTable('sales_order'),
	                'entity_id'
	            ),
	            'order_id',
	            $installer->getTable('sales_order'),
	            'entity_id',
	            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE

	        )->addForeignKey(
	            $installer->getFkName(
	                $installer->getTable('ksolves_orders_notifications'),
	                'customer_id',
	                $installer->getTable('customer_entity'),
	                'entity_id'
	            ),
	            'customer_id',
	            $installer->getTable('customer_entity'),
	            'entity_id',
	            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE 
	            
			)->setComment(
	            'Ksolves Orders Notifications Table'
			);
			$installer->getConnection()->createTable($table);
			$installer->getConnection()->addIndex(
			$installer->getTable('ksolves_orders_notifications'),
			$setup->getIdxName(
				$installer->getTable('ksolves_orders_notifications'),
				['increment_id','invoice_id','order_status','customer_name','customer_email'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			),
			['increment_id','invoice_id','order_status','customer_name','customer_email'],
			\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
		);
				$installer->endSetup();
		}
	}
}
