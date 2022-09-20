<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Form\Extension;

use Oro\Bundle\CatalogBundle\Form\Extension\AbstractFallbackCategoryTypeExtension;
use Oro\Bundle\EntityBundle\Form\Type\EntityFieldFallbackValueType;
use Ibnab\Bundle\QuantityImproverBundle\Model\Inventory;
use Symfony\Component\Form\FormBuilderInterface;
use Oro\Bundle\CatalogBundle\Form\Type\CategoryType;

class CategoryQuantityToOrderFormExtension extends AbstractFallbackCategoryTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [CategoryType::class];
    }
    /**
     * {@inheritdoc}
     */
    public function getFallbackProperties()
    {
        return [
            Inventory::FIELD_INCREMENT_QUANTITY_TO_ORDER,
            Inventory::FIELD_ENABLE_INCREMENT_QUANTITY_TO_ORDER
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add(
            Inventory::FIELD_ENABLE_INCREMENT_QUANTITY_TO_ORDER,
            EntityFieldFallbackValueType::class,
            [
                'label' => 'ibnab.quantity_improver.fields.product.increment_quantity_to_order.label',
                'required' => false,
            ]
        );
        $builder->add(
            Inventory::FIELD_INCREMENT_QUANTITY_TO_ORDER,
            EntityFieldFallbackValueType::class,
            [
                'label' => 'ibnab.quantity_improver.fields.product.increment_quantity_to_order.label',
                'required' => false,
            ]
        );

    }
}
