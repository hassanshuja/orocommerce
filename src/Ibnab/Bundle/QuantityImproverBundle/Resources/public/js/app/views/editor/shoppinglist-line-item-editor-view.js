import _ from 'underscore';
import $ from 'jquery';
import TextEditorView from 'oroform/js/app/views/editor/text-editor-view';
const NumberFormatter = require('orofilter/js/formatter/number-formatter');
import NumberFormat from 'orolocale/js/formatter/number';

const ShoppinglistLineItemEditorView = TextEditorView.extend({
    events: {
        'change select[name="quantity"]': 'onValueChange',
        'change select[name="unitCode"]': 'onUnitValueChange'
    },

    template: require('tpl-loader!ibnabquantityimprover/templates/editor/shoppinglist-line-item-editor.html'),

    constructor: function ShoppinglistLineItemEditorView(...args) {
        ShoppinglistLineItemEditorView.__super__.constructor.apply(this, args);
    },

    initialize(options) {
        this.formatter = new NumberFormatter(options);
        this.updateUnitList(this.model.get('unit'));
        ShoppinglistLineItemEditorView.__super__.initialize.call(this, options);
    },
    render() {
        if (this.options.quantity) {
            this.setFormState(this.options.quantity);
        }

        ShoppinglistLineItemEditorView.__super__.render.call(this);

        this.validator.settings.rules = {
            quantity: $.validator.filterUnsupportedValidators(this.getValidationRules())
        };
    },

    getTemplateData() {
        return {
            data: this.model.toJSON()
        };
    },

    focus(event) {      
        const focused = event.target.getAttribute('data-focused');
        const focusedQTY = event.target.getAttribute('data-focused');
        if (focused) {
            if (focused === '.select2-container') {
                this.$('select[name="unitCode"]').select2('open');
            }
            
        }
        
        if (focusedQTY) {
            if (focusedQTY === '.select2-container-qty') {               
                this.$('select[name="quantity"]').select2('open');
            }
            //return;
        }
        return;
        //this.$('select[name="quantity"]').setCursorToEnd().focus();
    },

    isChanged() {
        
        const res = _.some(Object.entries(this.getValue()), ([key, value]) => {
            return this.model.get(key) !== value;
        });

        this.model.set('_state', res);
        return res;
    },

    onFocusout(event) {       
        const select2 = this.$('select[name="unitCode"]').data('select2');
        const select2QTY = this.$('select[name="quantity"]').data('select2');
        if (
            !this.isChanged() &&
            !$.contains(this.el, event.relatedTarget) &&
            (!select2.opened() ||
            !select2QTY.opened())
        ) {
            //this.trigger('cancelAction');
        }

    },

    onValueChange() {
        this.updateSubmitButtonState();
        this.trigger('change');
    },

    onUnitValueChange(event) {
        this.onValueChange(event);
        this.updateUnitPrecision();
    },

    updateUnitPrecision() {
        const units = this.updateUnitList(this.$('select[name="unitCode"]').val());
        const precision = Object.values(units).find(unit => unit.selected).precision;
        this.$el.find('select[name="quantity"]')
            .data('precision', precision)
            .inputWidget('refresh');
    },

    getValue: function() {
        return {
            quantity: parseFloat(NumberFormat.unformatStrict(this.$('select[name="quantity"]').val())),
            unitCode: this.$('select[name="unitCode"]').val()
        };
    },

    updateUnitList(currentUnit) {
        return _.mapObject(this.model.get('units'), (unit, key) => {
            unit.selected = key === currentUnit;
            return unit;
        });
    },

    getServerUpdateData() {
        return {
            id: this.model.get('id'),
            ...this.getValue()
        };
    },

    getModelUpdateData() {
        const value = this.getValue();

        return {
            ...value,
            units: this.updateUnitList(value.unitCode)
        };
    }
});

export default ShoppinglistLineItemEditorView;
