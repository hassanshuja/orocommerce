define(function (require) {
    'use strict';

    var PlusMinus;
    var BaseComponent = require('oroui/js/app/components/base/component');
    var mediator = require('oroui/js/mediator');
    var routing = require('routing');
    var _ = require('underscore');

    PlusMinus = BaseComponent.extend({
        /**
         * @inheritDoc
         */
        constructor: function PlusMinus() {
            PlusMinus.__super__.constructor.apply(this, arguments);
        },
        /**
         * @constructor
         * @param {Object} options
         */
        initialize: function (options) {
            this.options = _.defaults(options || {}, this.options);
            this.detector();
        },
        detector: function () {
            $( ".btn-number").unbind( "click" );
            $( ".product-view-quantity__value .btn-number").unbind( "click" );
            $( ".product__qty-container.cart-table__form .btn-number").unbind( "click" );
            
            $('.btn-number').click(function (e) {
                var input = $(this).parent().parent().find(".product-item__qty-input");
                e.preventDefault();
                var type = $(this).attr('data-type');  
                var currentVal = parseInt(input.val());
                if (!isNaN(currentVal)) {
                    if (type == 'minus') {
                        if (currentVal > 1) {
                            input.val(currentVal - 1).trigger('change');
                        }
                        if (parseInt(input.val()) == 1) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {
                        input.val(currentVal + 1).trigger('change');
                        /*if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }*/

                    }
                } else {
                    input.val(0);
                }
            });
            
            
            
            $('.product__qty-container.cart-table__form .btn-number').click(function (e) {
               
                var input = $(this).parent().parent().find("[name='product_qty']");
                e.preventDefault();
                var type = $(this).attr('data-type');  
                var currentVal = parseInt(input.val());
                if (!isNaN(currentVal)) {
                    if (type == 'minus') {
                        if (currentVal > 1) {
                            input.val(currentVal - 1).trigger('change');
                        }

                    } else if (type == 'plus') {
                        input.val(currentVal + 1).trigger('change');

                    }
                } else {
                    input.val(0);
                }
            });  
            
            
            $('.product-view-quantity__value .btn-number').click(function (e) {
                
                var input = $(this).parent().parent().find("[name='oro_product_frontend_line_item[quantity]']");
                e.preventDefault();
                var type = $(this).attr('data-type');  
                var currentVal = parseInt(input.val());
                if (!isNaN(currentVal)) {
                    if (type == 'minus') {
                        if (currentVal > 1) {
                            input.val(currentVal - 1).trigger('change');
                        }
                        if (parseInt(input.val()) == 1) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        input.val(currentVal + 1).trigger('change');
                        /*if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }*/

                    }
                } else {
                    input.val(0);
                }
            });
            this.status("input[name='oro_product_frontend_line_item[quantity]']");
            this.statusCart("input[name='product_qty']");
        },
        status: function (element) {
            $(element).focusin(function () {
                $(this).data('oldValue', $(this).val());
            });
            $(element).change(function () {               
                var minValue = parseInt($(this).attr('min'));
                //maxValue = parseInt($(this).attr('max'));
                var valueCurrent = parseInt($(this).val());
                name = $(this).attr('name');
                if (valueCurrent >= 1) {
                    $(this).parent().find(".btn-number[data-type='minus']").removeAttr('disabled');
                } else {
                    $(this).val($(this).data('oldValue'));
                    $(this).parent().find(".btn-number[data-type='minus']").attr('disabled', 'disabled');
                    
                }

            });
            $(element).keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                        // Allow: Ctrl+A
                                (e.keyCode == 65 && e.ctrlKey === true) ||
                                // Allow: home, end, left, right
                                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                            // let it happen, don't do anything
                            return;
                        }
                        // Ensure that it is a number and stop the keypress
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                    });             
        },
        statusCart: function (element) {
            $(element).focusin(function () {
                $(this).data('oldValue', $(this).val());
            });
            $(element).change(function () {
                
                var minValue = 0;
                //maxValue = parseInt($(this).attr('max'));
                var valueCurrent = parseInt($(this).val());

                name = $(this).attr('name');
                if (valueCurrent >= 1) {
                    $(this).parent().parent().find(".btn-number[data-type='minus']").removeAttr('disabled');
                } else {
                    $(this).val($(this).data('oldValue'));
                    $(this).parent().parent().find(".btn-number[data-type='minus']").attr('disabled', 'disabled');
                    
                }
                /*
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    alert('Sorry, the maximum value was reached');
                    $(this).val($(this).data('oldValue'));
                }*/


            });
            $(element).keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                        // Allow: Ctrl+A
                                (e.keyCode == 65 && e.ctrlKey === true) ||
                                // Allow: home, end, left, right
                                        (e.keyCode >= 35 && e.keyCode <= 39)) {
                            // let it happen, don't do anything
                            return;
                        }
                        // Ensure that it is a number and stop the keypress
                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                            e.preventDefault();
                        }
                    });             
        }
    });

    return PlusMinus;
});
