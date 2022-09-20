<?php

namespace Ibnab\Bundle\PlusMinusBundle\Provider;
use Oro\Bundle\ConfigBundle\Config\ConfigManager;

class ConfigurationProvider
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
     * @return string
     */
    public function getEnablePlusMinus()
    {       
        return $this->configManager->get(self::ENABLEPLUSMINUS_FIELD);    
    }

}
