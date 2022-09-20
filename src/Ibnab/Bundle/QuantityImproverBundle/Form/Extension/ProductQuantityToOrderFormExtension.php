<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Form\Extension;

use Oro\Bundle\CatalogBundle\Fallback\Provider\CategoryFallbackProvider;
use Oro\Bundle\EntityBundle\Entity\EntityFieldFallbackValue;
use Oro\Bundle\EntityBundle\Form\Type\EntityFieldFallbackValueType;
use Ibnab\Bundle\QuantityImproverBundle\Model\Inventory;
use Oro\Bundle\ProductBundle\Form\Type\ProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ProductQuantityToOrderFormExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $product = $builder->getData();
        // set category as default fallback
        if (!$product->getQuantityImproverIncrement()) {
            $entityFallback = new EntityFieldFallbackValue();
            $entityFallback->setFallback(CategoryFallbackProvider::FALLBACK_ID);
            $product->setQuantityImproverIncrement($entityFallback);
        }
        if (!$product->getEnableQuantityImproverIncrement()) {
            $entityFallback = new EntityFieldFallbackValue();
            $entityFallback->setFallback(CategoryFallbackProvider::FALLBACK_ID);
            $product->setEnableQuantityImproverIncrement($entityFallback);
        }

        $builder->add(
            Inventory::FIELD_ENABLE_INCREMENT_QUANTITY_TO_ORDER,
            EntityFieldFallbackValueType::class,
            [
                'label' => 'ibnab.quantity_improver.fields.product.enable_increment_quantity_to_order.label',
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
