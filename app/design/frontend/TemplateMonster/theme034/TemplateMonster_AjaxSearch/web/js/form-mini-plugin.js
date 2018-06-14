define([
    'jquery',
    'jquery/ui',
    'Magento_Search/form-mini',
    'TemplateMonster_AjaxSearch/js/tm-search-ajax'
], function($) {
    'use strict';

    $.widget('TemplateMonster.formMiniPlugin', $.tm.quickSearchAjax, {

        setActiveState: function (isActive) {
            this.searchLabel.toggleClass('active', isActive);
            $('.block-search .action.search').toggleClass('active', isActive);

            if (this.isExpandable) {
                this.element.attr('aria-expanded', isActive);
            }
        },

        _create: function() {
            this._super();
        }

    });

    return $.TemplateMonster.formMiniPlugin;
});