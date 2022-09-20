<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Layout\Extension;

use Oro\Component\Layout\ContextConfiguratorInterface;
use Oro\Component\Layout\ContextInterface;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;

class IbnabTypeLayoutToContext implements ContextConfiguratorInterface
{
    const ENABLEQUANTITYIMPROVER_FIELD = 'ibnab_quantity_improver.enable';
    /**
     * @var ConfigManager
     */
    protected $configManager;

    /**
     * @param ConfigManager $configManager
     */
    public function __construct(ConfigManager $configManager)
    {
        $this->configManager = $configManager;
    }
    /**
     * {@inheritdoc}
     */
    public function configureContext(ContextInterface $context)
    {
        $context->getResolver()
            ->setRequired(['enable_quantity_improver'])
            ->setAllowedTypes('enable_quantity_improver', ['numeric']);
        $context->set('enable_quantity_improver', $this->configManager->get(self::ENABLEQUANTITYIMPROVER_FIELD));
    }

}
