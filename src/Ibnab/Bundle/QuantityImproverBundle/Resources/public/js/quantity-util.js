define(function(require) {
    'use strict';

    const _ = require('underscore');

    const QuantityUtil = {

        updateSelect: function(model,minimumQuantityToOrder,maximumQuantityToOrder, $el,qtyMulti) {
            const options = [];
            const oldValue = $el.val();
            if (minimumQuantityToOrder > 0 && (maximumQuantityToOrder > minimumQuantityToOrder || maximumQuantityToOrder == minimumQuantityToOrder)) {
                var i;
                $el.empty();
                for (i = minimumQuantityToOrder; i < maximumQuantityToOrder; i = i + qtyMulti) {
                    options.push(this.generateSelectOption(i, i));
                    $el.append(new Option(i, i, false, false)).trigger('change');
                }
                
                options.push(this.generateSelectOption(maximumQuantityToOrder, maximumQuantityToOrder));
                $el.append(new Option(maximumQuantityToOrder, maximumQuantityToOrder, false, false)).trigger('change');
                $el.prop('disabled', false);
                $el.prop('readonly', options.length <= 1); 
                $el.inputWidget('refresh');
            }

        },

        generateSelectOption: function(value, label) {
            return '<option value="' + _.escape(value) + '">' + _.escape(label) + '</option>';
        }
    };

    return QuantityUtil;
});
