<?php

namespace Ibnab\Bundle\QuantityImproverBundle\EventListener;

use Oro\Bundle\CatalogBundle\Entity\Category;
use Oro\Bundle\UIBundle\Event\BeforeListRenderEvent;
use Oro\Bundle\UIBundle\Fallback\AbstractFallbackFieldsFormView;

class CategoryQuantityToOrderFormViewListener extends AbstractFallbackFieldsFormView
{
    /**
     * Add order by increment of x field to backend category section by twig template
     * @param BeforeListRenderEvent $event
     */
    public function onCategoryEdit(BeforeListRenderEvent $event)
    {
        $category = $this->getEntityFromRequest(Category::class);
        if ($category === null) {
            return;
        }

        $this->addBlockToEntityEdit(
            $event,
            'IbnabQuantityImproverBundle:Category:editQuantityToOrder.html.twig',
            'oro.catalog.sections.default_options'
        );
    }
}
