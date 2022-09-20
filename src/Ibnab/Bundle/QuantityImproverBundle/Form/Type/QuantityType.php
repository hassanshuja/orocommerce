<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Form\Type;

use Oro\Bundle\CurrencyBundle\Rounding\RoundingServiceInterface;
use Oro\Bundle\ProductBundle\Form\Type\QuantityType as ParentQuantityType;
use Oro\Bundle\FormBundle\Form\Type\Select2ChoiceType;
use Oro\Bundle\LocaleBundle\Formatter\NumberFormatter;

class QuantityType extends ParentQuantityType
{
    const NAME = 'oro_quantity_select';

    /**
     * @var NumberFormatter
     */
    private $numberFormatter;

    /** @var string */
    protected $productClass;

    /**
     * @param NumberFormatter $numberFormatter
     * @param string $productClass
     */
    public function __construct(NumberFormatter $numberFormatter, $productClass)
    {
        $this->numberFormatter = $numberFormatter;
        $this->productClass = $productClass;
        parent::__construct($this->numberFormatter, $productClass);
    }

    /** {@inheritDoc} */
    public function getParent()
    {
        return Select2ChoiceType::class;
    }
    /** {@inheritDoc} */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::NAME;
    }
}
