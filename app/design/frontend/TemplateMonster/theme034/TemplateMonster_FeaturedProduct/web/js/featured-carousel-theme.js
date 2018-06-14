/**
 * Copyright © 2015. All rights reserved.
 */

define([
    'jquery',
    'featuredCarousel'
], function($){
    "use strict";

    $.widget('TemplateMonster.featuredCarouselTheme', $.TemplateMonster.featuredCarousel, {


        options: {
            items: 3,
            itemsDesktop: [1199,3],
            itemsDesktopSmall: [979,3],
            itemsTablet: [768,2],
            itemsMobile: [479,1],
            autoPlay: false,
            navigation:true,
            pagination: false,
            paginationWrapper: '',
            addClassActive: true,
            navigationText: []
        },

        _create: function() {
            var owl = this.element;
            var afterMove = {
                afterMove: function () {
                    $('.owl-item', owl).removeClass('product-border-left-none');
                    $('.owl-item.active', owl).first().addClass('product-border-left-none');
                }
            };
            owl.owlCarousel($.extend(this.options, afterMove)).trigger('afterMove');
            this._wrap(owl);
        },

        _wrap: function (owl) {
            var controls = $('.owl-controls', owl);
            if(controls.length && controls.closest('.featured-prods-modern').length){
                controls.append("<div class='owl-controls-inner'></div>");
                $('.owl-controls-inner', controls).append($('.owl-pagination', controls));
                $('.owl-controls-inner', controls).append($('.owl-buttons', controls));
            }
        }
        
    });

    return $.TemplateMonster.featuredCarouselTheme;

});