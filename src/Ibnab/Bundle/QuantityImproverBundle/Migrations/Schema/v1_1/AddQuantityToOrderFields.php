<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\CatalogBundle\Fallback\Provider\CategoryFallbackProvider;
use Oro\Bundle\EntityBundle\EntityConfig\DatagridScope;
use Oro\Bundle\EntityBundle\Fallback\Provider\SystemConfigFallbackProvider;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Ibnab\Bundle\QuantityImproverBundle\Model\Inventory;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class AddQuantityToOrderFields implements Migration, ExtendExtensionAwareInterface
{
    /** @var ExtendExtension */
    protected $extendExtension;

    /**
     * @param ExtendExtension $extendExtension
     */
    public function setExtendExtension(ExtendExtension $extendExtension)
    {
        $this->extendExtension = $extendExtension;
    }

    /**
     * @param Schema $schema
     * @param QueryBag $queries
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $this->addIncrementQuantityToOrderFieldsToProduct($schema);
        $this->addIncrementQuantityFieldToCategory($schema); 
    }

    /**
     * @param Schema $schema
     */
    protected function addIncrementQuantityToOrderFieldsToProduct(Schema $schema)
    {
        $this->addFallbackRelation(
            $schema,
            'oro_product',
            Inventory::FIELD_INCREMENT_QUANTITY_TO_ORDER,
            'ibnab.quantity_improver.fields.product.increment_quantity_to_order.label',
            [
                CategoryFallbackProvider::FALLBACK_ID => ['fieldName' => Inventory::FIELD_INCREMENT_QUANTITY_TO_ORDER],
                SystemConfigFallbackProvider::FALLBACK_ID => [
                    'configName' => 'ibnab_quantity_improver.increment_quantity_to_order',
                ],
            ]
        );

    }

    /**
     * @param Schema $schema
     */
    protected function addIncrementQuantityFieldToCategory(Schema $schema)
    {
        $this->addFallbackRelation(
            $schema,
            'oro_catalog_category',
            Inventory::FIELD_INCREMENT_QUANTITY_TO_ORDER,
            'ibnab.quantity_improver.fields.product.increment_quantity_to_order.label',
            [
                SystemConfigFallbackProvider::FALLBACK_ID => [
                    'configName' => 'ibnab_quantity_improver.increment_quantity_to_order',
                ],
            ]
        );
    }

    /**
     * @param Schema $schema
     * @param string $tableName
     * @param string $fieldName
     * @param string $label
     * @param array $fallbackList
     */
    protected function addFallbackRelation(Schema $schema, $tableName, $fieldName, $label, $fallbackList)
    {
        $table = $schema->getTable($tableName);
        $fallbackTable = $schema->getTable('oro_entity_fallback_value');
        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            $fieldName,
            $fallbackTable,
            'id',
            [
                'entity' => [
                    'label' => $label,
                ],
                'extend' => [
                    'owner' => ExtendScope::OWNER_CUSTOM,
                    'cascade' => ['all'],
                ],
                'form' => [
                    'is_enabled' => false,
                ],
                'view' => [
                    'is_displayable' => false,
                ],
                'datagrid' => [
                    'is_visible' => DatagridScope::IS_VISIBLE_FALSE,
                ],
                'fallback' => [
                    'fallbackList' => $fallbackList,
                ],
            ]
        );
    }
}
