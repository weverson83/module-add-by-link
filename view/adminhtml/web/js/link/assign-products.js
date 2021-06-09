/* global $, $H */

define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedProducts = config.selectedProducts,
            linkProducts = $H(selectedProducts),
            gridJsObject = window[config.gridJsObjectName];

        $('in_link_products').value = Object.toJSON(linkProducts);

        /**
         * Register Link Product
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerLinkProduct(grid, element, checked) {
            if (checked) {
                linkProducts.set(
                    element.value, 1
                );
            } else {
                linkProducts.unset(element.value);
            }
            $('in_link_products').value = Object.toJSON(linkProducts);
            grid.reloadParams = {
                'selected_products[]': linkProducts.keys()
            };
        }

        /**
         * Check product
         *
         * @param {String} event
         */
        function productCheckbox(event) {
            var element = Event.element(event);
            gridJsObject.setCheckboxChecked(element, element.checked);
        }

        /**
         * Initialize link product row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function linkProductRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0];

            if (checkbox) {
                Event.observe(checkbox, 'click', productCheckbox);
            }
        }

        gridJsObject.initRowCallback = linkProductRowInit;
        gridJsObject.checkboxCheckCallback = registerLinkProduct;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                linkProductRowInit(gridJsObject, row);
            });
        }
    };
});
