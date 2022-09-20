<?php

namespace Ibnab\Bundle\PlusMinusBundle\Layout\Extension;

use Oro\Component\Layout\ContextConfiguratorInterface;
use Oro\Component\Layout\ContextInterface;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;

class IbnabTypeLayoutToContext implements ContextConfiguratorInterface
{
    const ENABLEPLUSMINUS_FIELD = 'ibnab_plus_minus.enable';
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
            ->setRequired(['enable_plus_minus'])
            ->setAllowedTypes('enable_plus_minus', ['numeric']);
        $context->set('enable_plus_minus', $this->configManager->get(self::ENABLEPLUSMINUS_FIELD));
    }

}
