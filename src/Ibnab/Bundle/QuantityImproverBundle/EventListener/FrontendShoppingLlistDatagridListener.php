<?php

namespace Ibnab\Bundle\QuantityImproverBundle\EventListener;

use Oro\Bundle\DataGridBundle\Event\PreBuild;
use Oro\Bundle\DataGridBundle\Datagrid\Common\DatagridConfiguration;
use Ibnab\Bundle\QuantityImproverBundle\Provider\ConfigurationProvider;

class FrontendShoppingLlistDatagridListener {

    /**
     * @var ConfigManager
     */
    protected $configProvider;
    
    const QUANTITY_COLUMN = 'quantity';
    const MINQTYTTOORDER = 'minimumQuantityToOrder';
    const MAXQTYTTOORDER = 'maximumQuantityToOrder';
    const QTYINCR = 'quantityImproverIncrement';
    const QTYISELECT = 'quantitySelect';
    /**
     * FrontendShoppingLlistDatagridListener constructor.
     */
    public function __construct(ConfigurationProvider $configProvider) {
        $this->configProvider = $configProvider;
    }

    /**
     * change configuration of datagird frontend-customer-user-shopping-list-edit-grid to implement
     * explicit choices of buying by quantity of increment x of select box
     * @param PreBuild $event
     */
    public function onBuildBefore(PreBuild $event) {
        if ($this->configProvider->isEnable() && $this->configProvider->isEnableSelect()){
        $config = $event->getConfig();
        $quantityColumn = [
            'frontend_type' => 'shoppinglist-line-item',
            'frontend_template' => 'tpl-loader!ibnabquantityimprover/templates/datagrid/cell/shoppinglist-line-item.html',
            'inline_editing' =>
            [
                'enable' => true,
                'editor' =>
                ['view' => 'ibnabquantityimprover/js/app/views/editor/shoppinglist-line-item-editor-view'],
                'validation_groups' => ['update']
            ]
        ];
         $quantityInline = [
            'enable'=> true,
            'mobile_enabled'=> true,
            'entity_name' => "Oro\\Bundle\\ShoppingListBundle\\Entity\\LineItem",
            'plugin' => 'ibnabquantityimprover/js/datagrid/plugin/shopping-list-inline-editing-plugin',
            'cell_editor'=> ['component'=> 'oroshoppinglist/js/app/components/shopping-list-cell-popup-editor-component'],
            'save_api_accessor'=>
                ['http_method'=> 'PUT',
                'route'=> 'oro_shopping_list_frontend_line_item_batch_update',
                'query_parameter_names'=> ['_wid']
                ]

        ];       


        $quantityProperties = [
            self::MINQTYTTOORDER=> [],
            self::MAXQTYTTOORDER=> [],
            self::QTYINCR=> [],
            self::QTYISELECT=> ['renderable' => false],

        ];
        $this->addConfigElement($config, '[columns]', $quantityColumn, self::QUANTITY_COLUMN);
        $this->addArrayConfigElement($config, '[inline_editing]', $quantityInline);
        $this->addArrayConfigElement($config, '[properties]', $quantityProperties);
        }
       
    }

    /**
     * @param DatagridConfiguration $config
     * @param string $path
     * @param mixed $element
     * @param mixed $key
     */
    protected function addConfigElement(DatagridConfiguration $config, $path, $element, $key = null) {
        $select = $config->offsetGetByPath($path);
        if ($key) {
            $select[$key] = $element;
        } else {
            $select[] = $element;
        }
        $config->offsetSetByPath($path, $select);
    }
    /**
     * @param DatagridConfiguration $config
     * @param string $path
     * @param mixed $array
     */
    protected function addArrayConfigElement(DatagridConfiguration $config, $path, $array) {
        $config->offsetAddToArrayByPath($path,$array);
    }

}
