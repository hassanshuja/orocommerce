<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Oro\Bundle\FrontendBundle\Request\FrontendHelper;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Ibnab\Bundle\QuantityImproverBundle\Form\Type\QuantityType;
use Oro\Bundle\ProductBundle\Form\Type\FrontendLineItemType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Ibnab\Bundle\QuantityImproverBundle\Provider\ConfigurationProvider;
use Ibnab\Bundle\QuantityImproverBundle\Validator\QuantityToOrderValidatorService;
use Symfony\Component\Form\FormError;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FrontendFrontendLineItemExtension extends AbstractTypeExtension {

    /** @var FrontendHelper */
    private $frontendHelper;

    /** @var ConfigurationProvider */
    private $configurationProvider;
    /**
     * @var QuantityToOrderValidatorService
     */
    protected $validatorService;
    /**
     * @var TranslatorInterface
     */
    protected $translator;    
    /**
     * @var RequestStack
     */
    protected $requestStack;
    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack
    ) {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }
     /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [FrontendLineItemType::class];
    }
    /**
     * @param ConfigurationProvider $configurationProvider
     */
    public function setConfigurationProvider(ConfigurationProvider $configurationProvider) {
        $this->configurationProvider = $configurationProvider;
    }

    /**
     * @param FrontendHelper $frontendHelper
     */
    public function setFrontendHelper(FrontendHelper $frontendHelper) {
        $this->frontendHelper = $frontendHelper;
    }
    /**
     * @param QuantityToOrderValidatorService $validatorService
     */
    public function setQuantityToOrderValidatorService(QuantityToOrderValidatorService $validatorService) {
        $this->validatorService = $validatorService;
    }
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $isEnable = $this->configurationProvider->isEnable();
        $isEnableSelect = $this->configurationProvider->isEnableSelect();
        if($isEnable){
          if ($this->frontendHelper->isFrontendRequest() && $isEnableSelect) {
            $product = $builder->getData()->getProduct();
            $qtyMulti = 1;
            $minimumQuantityToOrder = 0;
            $maximumQuantityToOrder= 1;
            if (!is_null($product)) {
                $minimumQuantityToOrder = $this->configurationProvider->getMinimumLimit($product);               
                $maximumQuantityToOrder = $this->configurationProvider->getMaximumLimit($product);
                $qtyMulti = $this->configurationProvider->getIncementQuantity($product);
            }

            $allowedQuantity = $this->getAllowedQuantity($minimumQuantityToOrder, $maximumQuantityToOrder, $qtyMulti);

            $builder->remove('quantity')->add(
                'quantity', QuantityType::class, [
                'required' => true,
                'label' => 'oro.product.lineitem.quantity.enter',
                'attr' => [
                    'placeholder' => 'oro.product.lineitem.quantity.placeholder',
                    'type' => 'number'
                ],
                'useInputTypeNumberValueFormat' => true,
                'choices' => $allowedQuantity,
                    ]
            );
               }
              $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $isEnableSelect = $this->configurationProvider->isEnableSelect();
                    $data = $event->getData();
                    $product = $event->getForm()->getData()->getProduct();
                    $currentQuantity = isset($data['quantity']) ? $data['quantity'] : 1;
                    
                    $allowedQuantity = [];
                    $qtyMulti = 1;
                    $minimumQuantityToOrder = 0;
                    $maximumQuantityToOrder = 1;
                    if (!is_null($product)) {
                        $minimumQuantityToOrder = $this->configurationProvider->getMinimumLimit($product);
                        $maximumQuantityToOrder = $this->configurationProvider->getMaximumLimit($product);
                        $qtyMulti = $this->configurationProvider->getIncementQuantity($product);
                    }
                if ($this->frontendHelper->isFrontendRequest() && $isEnableSelect) {
                    if ($minimumQuantityToOrder > 0 && ($maximumQuantityToOrder > $minimumQuantityToOrder || $maximumQuantityToOrder == $minimumQuantityToOrder)) {
                        $allowedQuantity = $this->getAllowedQuantity($minimumQuantityToOrder, $maximumQuantityToOrder, $qtyMulti);
                       
                        $event->getForm()->remove('quantity')->add(
                            'quantity', QuantityType::class, [
                            'required' => true,
                            'label' => 'oro.product.lineitem.quantity.enter',
                            'attr' => [
                                'placeholder' => 'oro.product.lineitem.quantity.placeholder',
                                'type' => 'number'
                            ],
                            'choices' => $allowedQuantity,
                            'useInputTypeNumberValueFormat' => true,
                            'data' => min(array_keys($allowedQuantity))
                        ]);
                    }
                }
                $request = $this->requestStack->getCurrentRequest(); 
                if($this->validatorService->isOutIncrementQuantity($qtyMulti,$minimumQuantityToOrder,$maximumQuantityToOrder,$currentQuantity)){
                    $request = $this->requestStack->getCurrentRequest(); 
                    $route = $request->attributes->get('_route');
                    $quantityForm = $event->getForm()->get('quantity');
                    if($route =='oro_api_shopping_list_frontend_put_line_item'){
                      //$event->getForm()->setData('quantity',0);
                      //$event->setData('quantity',0);
                      //$event->getForm()->addError(new FormError($this->translator->trans('ibnab.quantity_improver.fields.product.available_quantity_to_order.label')));                                                
                    }else{
                      //$event->getForm()->addError(new FormError($this->translator->trans('ibnab.quantity_improver.fields.product.available_quantity_to_order.label')));                        
                    }
                }

            }, 5
            )->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                
            });

          
        }
    }

    protected function getAllowedQuantity($minimumQuantityToOrder, $maximumQuantityToOrder, $qtyMulti = null) {
        $allowedQuantity = [];
        if ($minimumQuantityToOrder > 0 && $maximumQuantityToOrder > 0) {
            if (is_null($qtyMulti)) {
                $qtyMulti = $minimumQuantityToOrder;
            }
            for ($i = $minimumQuantityToOrder; $i < $maximumQuantityToOrder; $i = $i + $qtyMulti) {
                if ($this->is_decimal($i)) {
                    $digit = strlen(substr(strrchr($i, "."), 1));
                    $allowedQuantity[number_format($i, 2) . ""] = $i;
                } else {
                    $allowedQuantity[number_format($i) . ""] = $i;
                }
            }
            if ($this->is_decimal($maximumQuantityToOrder)) {
                $digit = strlen(substr(strrchr($i, "."), 1));
                $allowedQuantity[number_format($maximumQuantityToOrder, 2) . ""] = $maximumQuantityToOrder;
            } else {
                $allowedQuantity[number_format($maximumQuantityToOrder) . ""] = $maximumQuantityToOrder;
            }
        }
        //$allowedQuantity['1'] = 1; 
        return $allowedQuantity;
    }

    protected function is_decimal($val) {
        return is_numeric($val) && floor($val) != $val;
    }

    /**
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options) {
        if ($this->frontendHelper->isFrontendRequest()) {
            foreach ($view->children as $child) {
                if ($child->vars['name'] == 'quantity' /* || $child->vars['name'] == 'country_code' */) {
                    //var_dump($child->vars['attr']);die();
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType() {
        return FrontendLineItemType::class;
    }

}
