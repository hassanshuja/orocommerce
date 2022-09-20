<?php

namespace Ibnab\Bundle\PlusMinusBundle\Layout\DataProvider;

use Oro\Bundle\ConfigBundle\Config\ConfigManager;

class LayoutType
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
     * @return LoginPage
     */
    public function getCurrentLayout()
    {
        return $this->configManager->get(self::ENABLEPLUSMINUS_FIELD);
    }
}
