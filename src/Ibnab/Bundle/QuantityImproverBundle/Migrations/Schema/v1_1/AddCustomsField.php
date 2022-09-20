<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Migrations\Schema\v1_1;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\ProductBundle\Entity\Product;
use Oro\Bundle\EntityConfigBundle\Migration\UpdateEntityConfigFieldValueQuery;
use Oro\Bundle\EntityConfigBundle\Migration\UpdateEntityConfigIndexFieldValueQuery;
use Ibnab\Bundle\QuantityImproverBundle\Migrations\Schema\v1_1\RemoveFieldQuery;
use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;

class AddCustomsField implements Migration{

    const PRODUCT_TABLE_NAME = 'oro_product';
    const FALLBACK_LOCALE_VALUE_TABLE_NAME = 'oro_fallback_localization_val';
    const QII = 'quantityImproverIncrement';

    /** @var ExtendExtension */
    protected $extendExtension;


    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries) {
        
        $table = $schema->getTable(self::PRODUCT_TABLE_NAME);
        //$this->removeQIIAttributes($queries, $table);
        /*$table->addColumn( self::QII, 'float',['length' => 20,'notnull' => false,                         
                         'oro_options' => [
                            'extend'    => [
                                'is_extend' => true,
                                'owner'     => ExtendScope::OWNER_SYSTEM
                            ],
                            'attribute' => [
                                'is_attribute' => true,
                                'enabled'      => true,
                                'visible'      => false,
                                'label'         => 'Quantity Increment',
                                'description'   => 'Quantity Increment'
                            ],

                            'entity' => [
                                'label'         => 'Quantity Increment',
                                'description'   => 'Quantity Increment'
                            ]
                            ]]);*/
        //$this->addCustomAttribute($schema, self::PRODUCT_TABLE_NAME);
        //$this->addQIIProductAttributes($queries);
    }

    /**
     * @param QueryBag $queries
     */
    public function removeQIIAttributes(QueryBag $queries, $table) {
        $QIIProductFields = [
            self::QII
        ];
        foreach ($QIIProductFields as $field) {
            $queries->addPreQuery(
                    new RemoveFieldQuery(
                    Product::class, $field
                    )
            );
            $table->dropColumn($field);
        }
    }

    public function getOrder() {
        return 90;
    }

}
