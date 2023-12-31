function td_taxes(){
    var convert_type_invoice = (jQuery($.opt.invoice_include_tax).val());
    var varr = (jQuery($.opt.price_include_tax));

    if(convert_type_invoice == "simple"){
        varr.hide();
            jQuery('#th_tax').hide();
            jQuery('#th_discount').hide();
            jQuery('#td_discount').hide();
            jQuery('#td_taxes').hide();
            jQuery('th_preview_tax').hide();
            jQuery('th_preview_discount').hide();
            jQuery($.opt.taxes_product).empty();
            jQuery($.opt.Discount).empty();
    }else{
        varr.show();
        jQuery('#th_tax').show();
        jQuery('#th_discount').show();
        jQuery('#td_discount').show();
        jQuery('#td_taxes').show(); 
        jQuery('th_preview_tax').show();
        jQuery('th_preview_discount').show();
    }
}
(function (jQuery) {
    $.opt = {};  // jQuery Object

    jQuery.fn.invoice = function (options) {
        var ops = jQuery.extend({}, jQuery.fn.invoice.defaults, options);
        $.opt = ops;

        var inv = new Invoice();
        inv.init();

        jQuery('body').on('click', function (e) {
            var cur = e.target.id || e.target.className;

            if (cur == $.opt.addRow.substring(1))
                inv.newRow();

            if (cur == $.opt.delete.substring(1))
                inv.deleteRow(e.target);

            inv.init();
        });

        jQuery('body').on('keyup', function (e) {
            inv.init();
        });

        return this;
    };
}(jQuery));

function Invoice() {
    self = this;
}

Invoice.prototype = {
    constructor: Invoice,

    init: function () {
        this.calcTax();
        this.calcTotal();
        this.calcTotalQty();
        this.calcSubtotal();
        this.calcGrandTotal();
    },

    /**
     * Calculate total price of an item.
     *
     * @returns {number}
     */


calcTotal: function () {
    
    // var sub_tax= (jQuery('#sub_tax').val());
    // console.log(tax_input)
    varr = (jQuery($.opt.price_include_tax).val());
    if(varr == 'without'){
        jQuery($.opt.parentClass).each(function (i) {
            var row = jQuery(this);
            var total = row.find($.opt.price).val() * row.find($.opt.qty).val()  - row.find($.opt.Discount).val()*row.find($.opt.price).val()* row.find($.opt.qty).val();
            var tax = (row.find($.opt.price).val() * row.find($.opt.qty).val()  - row.find($.opt.Discount).val()*row.find($.opt.price).val()* row.find($.opt.qty).val())*row.find($.opt.taxes_product).val();
            total = self.roundNumber(total, 2);
            row.find($.opt.total).html(total);
            row.find($.opt.total).val(total);
        });    
    }else{
        jQuery($.opt.parentClass).each(function (i) {
            var row = jQuery(this);
            var subto = (row.find($.opt.price).val() * row.find($.opt.qty).val()  - row.find($.opt.Discount).val()*row.find($.opt.price).val()* row.find($.opt.qty).val())*row.find($.opt.taxes_product).val();
            var total = row.find($.opt.price).val() * row.find($.opt.qty).val() - row.find($.opt.Discount).val()*row.find($.opt.price).val()* row.find($.opt.qty).val() + subto;
            total = self.roundNumber(total, 2);
            row.find($.opt.total).html(total);
            row.find($.opt.total).val(total);
        });
    }


    return 1;
},
    /***
     * Calculate total quantity of an order.
     *
     * @returns {number}
     */
    calcTotalQty: function () {
         var totalQty = 0;
         jQuery($.opt.qty).each(function (i) {
             var qty = jQuery(this).val();
             if (!isNaN(qty)) totalQty += Number(qty);
         });

         totalQty = self.roundNumber(totalQty, 2);

         jQuery($.opt.totalQty).html(totalQty);

         return 1;
     },

    /***
     * Calculate subtotal of an order.
     *
     * @returns {number}
     */
    calcSubtotal: function () {
         var subtotal = 0;
         jQuery($.opt.total).each(function (i) {
             var total = jQuery(this).html();
             if (!isNaN(total)) subtotal += Number(total);
         });

         subtotal = self.roundNumber(subtotal, 2);

         jQuery($.opt.subtotal).html(subtotal);
         jQuery('.subtotal').val(subtotal);

         return 1;
     },




     /***
     * Calculate tax.
     *
     * @returns {number}
     */
    
    calcTax: function () {
         var varr = (jQuery($.opt.price_include_tax).val());
         var convert_type_invoice = (jQuery($.opt.invoice_include_tax).val());
         td_taxes();  
            if(varr == 'without'){
               jQuery($.opt.taxes_total).show();
               jQuery($.opt.tax_tax).show();
               var tot_tax =[(jQuery($.opt.price).val()*jQuery($.opt.qty).val())  - (jQuery($.opt.Discount).val()*jQuery($.opt.price).val()*jQuery($.opt.qty).val())]*jQuery($.opt.taxes_product).val();
               tot_tax = self.roundNumber(tot_tax, 2);
               jQuery($.opt.taxes_total).html(tot_tax);
               jQuery('.total_tax').val(tot_tax);
               return 1;
            }
            else if(varr == 'with'){
               jQuery($.opt.taxes_total).hide();
               jQuery($.opt.tax_tax).hide();
               return 1;
            }else{
                var tdd = jQuery('.total_tax').val();
        
                if (tdd == 0.00) {
        
        
                    var totaltax = 0;
                    jQuery($.opt.taxes_product).empty();
                    jQuery($.opt.Discount).empty();
                    jQuery($.opt.tax).each(function (i) {
                        var tax = jQuery(this).val();
                        if (!isNaN(tax)) totaltax += Number(tax);
                    });
        
                    totaltax = self.roundNumber(totaltax, 2);
                    jQuery('.total_tax').val(totaltax);
                     
                    return 1;
        
                }else{
                    var totaltax = 0;
                    jQuery($.opt.taxes_product).empty();
                    jQuery($.opt.Discount).empty();

                    jQuery($.opt.tax).each(function (i) {
                        var tax = jQuery(this).val();
                        if (!isNaN(tax)) totaltax += Number(tax);
                    });
        
                    totaltax = self.roundNumber(totaltax, 2);
                    jQuery('.total_tax').val(totaltax);
                     
                    return 1;
                }
            }
        // }
    },


    /**
     * Calculate grand total of an order.
     *
     * @returns {number}
     */
    calcGrandTotal: function () {
        var varr = (jQuery($.opt.price_include_tax).val());
        var total_discount = Number(jQuery($.opt.discount).val());
        var tax = jQuery($.opt.total_tax).val();
        var total_taxes_invoice = Number(jQuery($.opt.tax_invoice).val());
        td_taxes();  
        if(varr == "without"){
            var grandTotal = Number(jQuery($.opt.subtotal).html()) + ((Number(tax))) - (Number(jQuery($.opt.subtotal).html()) * (Number(total_discount)/100))+ (Number(jQuery($.opt.subtotal).html())*Number(total_taxes_invoice));
            grandTotal = self.roundNumber(grandTotal, 2);
        
            jQuery($.opt.grandTotal).html(grandTotal);
            jQuery('.grandtotal').val(grandTotal);
            return 1;
        }else{
            var grandTotal = Number(jQuery($.opt.subtotal).html()) - (Number(jQuery($.opt.subtotal).html()) * (Number(total_discount)/100)) + (Number(jQuery($.opt.subtotal).html())*Number(total_taxes_invoice));
            grandTotal = self.roundNumber(grandTotal, 2);
        
            jQuery($.opt.grandTotal).html(grandTotal);
            jQuery('.grandtotal').val(grandTotal);
            return 1;
        }


    },

    /**
     * Add a row.
     *
     * @returns {number}
     */
    newRow: function () {
        jQuery(".item-row:last").after('<tr class="item-row"><td class="item-name"><input type="text" name="items[]" class="form-control item" placeholder="Item" type="text"></td><td><input class="form-control price invo" name="price[]" placeholder="Price" type="text"> </td><td><input class="form-control qty" name="quantity[]" placeholder="Quantity" type="text"></td><td><div class="delete-btn"><span class="currency_wrapper"></span><span class="total">0.00</span><a class="delete" href="javascript:;" title="Remove row">&times;</a><input type="hidden" class="total" name="total_price[]" value=""></div></td></tr>');
        if (jQuery($.opt.delete).length > 0) {
            jQuery($.opt.delete).show();
        }

        return 1;
    },

    /**
     * Delete a row.
     *
     * @param elem   current element
     * @returns {number}
     */
    deleteRow: function (elem) {
        jQuery(elem).parents($.opt.parentClass).remove();

        if (jQuery($.opt.delete).length < 2) {
            jQuery($.opt.delete).hide();
        }

        return 1;
    },

    /**
     * Round a number.
     * Using: http://www.mediacollege.com/internet/javascript/number/round.html
     *
     * @param number
     * @param decimals
     * @returns {*}
     */
    roundNumber: function (number, decimals) {
        var newString;// The new rounded number
        decimals = Number(decimals);

        if (decimals < 1) {
            newString = (Math.round(number)).toString();
        } else {
            var numString = number.toString();

            if (numString.lastIndexOf(".") == -1) {// If there is no decimal point
                numString += ".";// give it one at the end
            }

            var cutoff = numString.lastIndexOf(".") + decimals;// The point at which to truncate the number
            var d1 = Number(numString.substring(cutoff, cutoff + 1));// The value of the last decimal place that we'll end up with
            var d2 = Number(numString.substring(cutoff + 1, cutoff + 2));// The next decimal, after the last one we want

            if (d2 >= 5) {// Do we need to round up at all? If not, the string will just be truncated
                if (d1 == 9 && cutoff > 0) {// If the last digit is 9, find a new cutoff point
                    while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
                        if (d1 != ".") {
                            cutoff -= 1;
                            d1 = Number(numString.substring(cutoff, cutoff + 1));
                        } else {
                            cutoff -= 1;
                        }
                    }
                }

                d1 += 1;
            }

            if (d1 == 10) {
                numString = numString.substring(0, numString.lastIndexOf("."));
                var roundedNum = Number(numString) + 1;
                newString = roundedNum.toString() + '.';
            } else {
                newString = numString.substring(0, cutoff) + d1.toString();
            }
        }

        if (newString.lastIndexOf(".") == -1) {// Do this again, to the new string
            newString += ".";
        }

        var decs = (newString.substring(newString.lastIndexOf(".") + 1)).length;

        for (var i = 0; i < decimals - decs; i++)
            newString += "0";
        //var newNumber = Number(newString);// make it a number if you like

        return newString; // Output the result to the form field (change for your purposes)
    }
};

/**
 *  Publicly accessible defaults.
 */
jQuery.fn.invoice.defaults = {
    addRow: "#addRow",
    delete: ".delete",
    parentClass: ".item-row",

    price: ".price",
    qty: ".qty",
    taxes_product: ".taxes_product",
    Discount: ".Discount",
    total: ".total",
    totalQty: "#totalQty",

    subtotal: "#subtotal",
    discount: "#discount",
    tax: ".tax",
    total_tax: ".total_tax",
    invoice_include_tax: "#invoice_include_tax",
    th_preview_discount: ".th_preview_discount",
    th_preview_tax: ".th_preview_tax",
    tax_invoice: '#tax_id_',
    tax_tax: "#tax_tax",
    taxes_total: "#total_tax",
    price_include_tax: "#price_include_tax",
    shipping: "#shipping",
    grandTotal: "#grandTotal"
};
