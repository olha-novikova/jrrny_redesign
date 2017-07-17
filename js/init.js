(function($) {
    "use strict";
    
    /*****
     * Define some useful variables...
     *****/
    var $masonryContainer = $('.loop-masonry');    
    
    /*****
     * Define some helpful functions...
     *****/
    function ts_lol() {
        // LOL
    }
    
    function ts_open_hover_menu(target, callback) {
        var wrap = $(target).closest('.ts-hover-menu-wrap');
        wrap.not(".inuse").addClass("active inuse");
        if(wrap.find('.main-nav-search-sub-menu').length) {
            wrap.find('input[type="text"]').focus();
            wrap.closest('.main-nav').not('.ts-on-search').addClass('ts-on-search normal');
        }
        if(typeof(callback) == "function") {
            callback();
        }
    }

    function ts_close_hover_menus() {
        $(".ts-hover-menu-wrap").not(".active").removeClass("inuse");
        $(".ts-hover-menu-wrap.active").removeClass("active");
        $('#main-nav').not('.normal').removeClass('ts-on-search ts-on-shop');
        $('#main-nav:not(.normal):not(.ts-on-search):not(.ts-on-shop)').addClass('normal');
    }
    
    function ts_all_resize_actions() {
        ts_fix_section_margins();
        if(ts_browserWidth() >= 1040) {
            $('#main-nav').css('display','');
        }
    }

    function ts_fix_section_margins() {  
        var ww = $('.wrap-inner').width();
        var w = $('#main-container').width();
        var margin = (ww - w) / 2;
        if(!$('#main').hasClass('fullwidth') && ($('#main').hasClass('no-sidebar')||$('#main').hasClass('sidebar-comments-right'))) {
            $('#ts-post-the-content > .ts-color-section-fullwidth, #ts-post-the-content > .ts-color-section-wrap-wrap .ts-color-section-fullwidth').each(function() {
                    $(this).css('margin-left', '-'+margin+'px').css('margin-right', '-'+margin+'px');
            });
        }
        
        var fItem = $('#ts-post-the-content > :first');
        var lItem = $('#ts-post-the-content > :last');
        
        if(fItem.hasClass('ts-edge-to-edge')) {
            $('#main-container-wrap').addClass('no-top-padding');
            fItem.addClass('no-top-margin');
        }
        
        if(lItem.hasClass('ts-edge-to-edge')) {
            $('#main-container-wrap').addClass('no-bottom-padding');
            lItem.addClass('no-bottom-margin');
        }
    }

    function ts_browserHeight() {
        return $( window ).height();
    }

    function ts_browserWidth() {
        return $(window).width();
    }

    function ts_wrapWidth() {
        return $('#wrap').width();
    }

    function ts_topWrapHeight(plus) {
        var add = (plus) ? plus : 0;
        return Number($('#top-wrap').outerHeight(true)) + Number(add);
    }
       
    function ts_update_mini_cart() {
        if($('#ts-top-nav-shop-total').length) {
            var timestamp = $.now();
            $('#ts-top-nav-shop-total').load(ts_ajax_vars.ajaxurl+'?timestamp='+timestamp, {'action':'ts_reload_mini_cart'});
        }
    }
    
    function ts_update_postviews() {
        if($('#ts-postviews').length) {
            var timestamp = $.now();
            var pid = $('#ts-postviews').attr('data-pid');
            var nonce = $('#ts-postviews').attr('data-nonce');
            $.post(ts_ajax_vars.ajaxurl+'?timestamp='+timestamp, {'action':'ts_update_postviews','pid':pid,'nonce':nonce});
        }
    }

    function ts_email(a, b, c) {
        var text;  
        var tg='<';
        var at='@';
        var dest=a+at+b;
        text = (c=='' || !c) ? dest : c;
        document.write(tg+'a hr'+'ef="mai'+'lto:'+dest+'">'+text+tg+'/a>');
    }

    function ts_isRTL() {
        var isRTL = $('body').hasClass('rtl') ? true : false;
        return isRTL;
    }
    
    function ts_is_iPad() {
        var isiPad = navigator.userAgent.match(/iPad/i) != null;
        return isiPad;
    }

    function ts_flexslider() {
        $('.entry .flexslider').imagesLoaded(function() {
            $(this).flexslider({
                smoothHeight: true,
                prevText: '<i class="fa fa-chevron-left"></i>',
                nextText: '<i class="fa fa-chevron-right"></i>',
                pauseOnAction: true,
                pauseOnHover: false
            });
        });
    }
    
    function ts_flexslider_screen_height_fix() {
        if($('#main-slider-wrap').hasClass('slider-screen-height')) {
            var abh = ($('body').hasClass('admin-bar')) ? 32 : 0;
            var tbh = ($('body').hasClass('ts-full-top-bar')) ? $('#top-bar-wrap').outerHeight(true) : 0;
            var h = ts_browserHeight() - abh - tbh;
            h = (h > 800) ? 800 : h;
            $('#main-slider-wrap').find('.ts-slider-item').css('height',h+'px').data('height',h);
            
            var ic = $('#main-slider-wrap').find('.ts-slider-item').size();
            if(ic * 40 + 60 > h) {
                $('#main-slider-wrap').find('.ts-main-flex-nav').addClass('no-paging');
            }
        }
    }
        
    function ts_inview_animations() {
        if(ts_browserWidth() > 900 && !device.tablet() && !device.mobile()) {
            $('.ts-progress-bar').on("inview", function() {
                var progress = $(this).attr('data-percentage');
                $(this).animate({'width':'+'+progress+'%'}, 500).unbind('inview');
            });
            
            $("[class*='ts-fade-in']").each(function() {
                var delay = $(this).attr('data-delay');
                delay = (delay) ? delay : 0;
                var fade_class, ani, all_classes;
                all_classes = 'ts-fade-in ts-fade-in-from-left ts-fade-in-from-right ts-fade-in-from-top ts-fade-in-from-bottom';
                if($(this).hasClass('ts-fade-in-from-top')) {
                    fade_class = 'ts-fade-in-from-top';
                    ani = {opacity:1,top:0};
                } else if($(this).hasClass('ts-fade-in-from-right')) {
                    fade_class = 'ts-fade-in-from-right';
                    ani = {opacity:1,right:0};
                } else if($(this).hasClass('ts-fade-in-from-bottom')) {
                    fade_class = 'ts-fade-in-from-bottom';
                    ani = {opacity:1,bottom:0};
                } else if($(this).hasClass('ts-fade-in-from-left')) {
                    fade_class = 'ts-fade-in-from-left';
                    ani = {opacity:1,left:0};
                } else {
                    fade_class = 'ts-fade-in';
                    ani = {opacity:1};
                }
                if($(this).hasClass('ts-fade-in') || ts_browserWidth() < 720) {
                    $(this).on("inview",function(){
                        if(delay) {
                            $(this).delay(delay).animate({opacity:1}, 700, function() {
                                $(this).removeClass(all_classes).unbind('inview');
                            });
                        } else {
                            $(this).animate({opacity:1}, 700, function() {
                                $(this).removeClass(all_classes).unbind('inview');
                            });
                        }
                    });
                } else {
                    $(this).on("inview",function(){
                        if(delay) {
                            $(this).delay(delay).animate(ani, 500, function() {
                                $(this).removeClass(all_classes).unbind('inview');
                            });
                        } else {
                            $(this).animate(ani, 500, function() {
                                $(this).removeClass(all_classes).unbind('inview');
                            });
                        }
                    });
                }
            });
        } else {
            $('.ts-progress-bar').each(function() {
                var progress = $(this).attr('data-percentage');
                $(this).css({'width':progress+'%'});
            });
            $(".ts-fade-in,.ts-fade-in-from-left,.ts-fade-in-from-right,.ts-fade-in-from-top,.ts-fade-in-from-bottom").each(function() {
                $(this).css({'opacity':1,'top':'auto','right':'auto','bottom':'auto','left':'auto'}).unbind('inview');
            });
        }
    }

    function ts_magnificPopup() {
        $('a.mfp-iframe').magnificPopup({type:'iframe'});
        $('a.mfp-image').magnificPopup({
            type:'image',
            mainClass: 'mfp-with-zoom'
        });
        $('.ts-mfp-gallery').each(function() {
            $(this).magnificPopup({
                delegate: 'a.ts-image-link',
                type: 'image',
                gallery: {
                    enabled:true,
                    preload: [0,1]
                },
                mainClass: 'mfp-with-zoom'
            });
        });
    }
        
    function resizeMasonryEntries() {
        var prefix = ($masonryContainer.hasClass('portfolio-entries')) ? 'portfolio-' : '';
        if(ts_browserWidth() > 720) {
            $masonryContainer.removeClass('destroy-isotope').isotope({
                resizable: false,
                itemSelector: '.entry',
                layoutMode: 'masonry',
                transformsEnabled: (ts_isRTL()) ? false : true,
                masonry: {
                    gutterWidth: 0
                }
            }).isotope('reLayout', function() {
                $masonryContainer.parent().find('.threshold-pending').addClass('threshold');
            }).addClass('isotoped');
        } else {
            if($masonryContainer.hasClass('isotoped')) {
                $masonryContainer.isotope('destroy').removeClass('isotoped').parent().find('.threshold-pending').addClass('threshold');
            }
        }
    }
        
    function SFArrows() {
        var dir = ($('body').hasClass('rtl')) ? 'left' : 'right';
        $('.main-nav > ul.sf-menu > li > ul.sub-menu li a.sf-with-ul').each(function(){
            $(this).append('<span class="sf-sub-indicator"><i class="fa fa-angle-'+dir+'"></i></span>');
        });	
        $('.main-nav > div > ul.sf-menu > li > ul.sub-menu li a.sf-with-ul').each(function(){
            $(this).append('<span class="sf-sub-indicator"><i class="fa fa-angle-'+dir+'"></i></span>');
        });	
    }
    
    /*****
     * Call some helpful functions...
     *****/
    ts_update_mini_cart();
    ts_fix_section_margins();
    ts_inview_animations();
    ts_flexslider();
	SFArrows();
	ts_update_postviews();
    
    /*****
     * Miscellaneous fixes
     *****/
    if(ts_is_iPad()) {
        $('body').addClass('ts-ipad');
    }
    
    $('.ts-hover-menu-link').on('click', function(e) {
        var target = $(e.target);
        ts_open_hover_menu(e.target);
    });
    
    
    $('#top-bar > .middle-area').css('height', $('#top-bar').innerHeight() + 'px');
    
    $(document).on('click', function(e) {
        var target = $(e.target);
        if(!target.closest('.ts-hover-menu').length) {
            if(!target.is('input[name="Filedata"]')) {
                ts_close_hover_menus();
            }
        }
    });    
    
    $('.single-entry .post-password-form input[type="submit"]').addClass('button');
    
    if($('.ts-related-posts-on-single .entry').length < 1) {
        $('.ts-related-posts-on-single').remove();
    }
    
	/*****
	 * Responsive
	 *****/
	$(window).resize(function() {
        ts_all_resize_actions();
    });
	
    
    /*****
     * Sticky nav
     *****/
    if(jQuery('body').hasClass('ts-has-sticky-nav')) {
        $(window).scroll(function(){
            var nav_h = $('#nav').outerHeight();
            if ($(window).scrollTop() >= ts_topWrapHeight()) {
                //$('#main-nav').css('display', 'none').addClass('is-closed').removeClass('is-open');
                $('#ts-main-nav-inner-wrap').not('.stickied').addClass('stickied pos-fixed').fadeIn('fast');
                $('#nav').css('height', nav_h+'px').addClass('ts-contains-sticky-nav');
                var ww = $('html').width();
                var w = $('.wrap-inner').width();
                var margin = (ww - w) / 2;
                $('#ts-main-nav-inner-wrap.stickied').css('left', margin+'px').css('right', margin+'px');
            } else {
                $('#ts-main-nav-inner-wrap.stickied').removeClass('stickied pos-fixed');
                $('#nav').css('height', '').removeClass('ts-contains-sticky-nav');
            }
        });
    } 
    
    /*****
	 * Mobile Nav
	 *****/
	function ts_toggle_mobile_menu(action) {
        var _action = ($('#main-nav').hasClass('is-open') || action == 'open') ? 'open' : 'close';
        if($('#main-nav').hasClass('is-open')) {
            var back_to = $('#ts-main-nav-inner-wrap').attr('data-scrolltop');
            $('#main-nav').stop().slideUp('fast', function() {
                $(this).removeClass('is-open');
                if(back_to) {
                    $(window).scrollTop(back_to);
                    $('#ts-main-nav-inner-wrap').attr('data-scrolltop', '');
                }
            });
        } else {
            $('#main-nav').stop().slideDown('fast', function() {
                $(this).addClass('is-open');
            });
        }
	}
	$('#ts-top-mobile-menu').on('click', function() {
        var sticky_enabled = $('body').hasClass('ts-has-sticky-nav');
        var sticky_visible = $('#ts-main-nav-inner-wrap').hasClass('stickied');
        if(sticky_enabled && sticky_visible) {
            var _offset = $('#nav').offset();
            $('#ts-main-nav-inner-wrap').attr('data-scrolltop', $(window).scrollTop());
            $('html,body').scrollTop(_offset.top);
            ts_toggle_mobile_menu();
        } else {
            ts_toggle_mobile_menu();
        }
	});
    
    
    /*****
     * Fix image oembeds (eg. Flickr)
     * Why? In our functions file we wrap oembeds with a responsive div. 
     * Images don't need that, so we remove it here.
     *****/
    $('.ts-wp-oembed').not(':has(iframe)').not(':has(embed)').not(':has(object)').removeClass('fluid-width-video-wrapper');
    
    /*****
     * Flexslider
     *****/
    //ts_flexslider_screen_height_fix();
    $('.loop-slider-wrap .flexslider').imagesLoaded(function() {
        $(this).flexslider({
            smoothHeight: true,
            controlsContainer: '.ts-main-flex-nav',
            directionNav: false,
            prevText: '<i class="fa fa-chevron-left"></i>',
            nextText: '<i class="fa fa-chevron-right"></i>',
            pauseOnAction: true,
            pauseOnHover: false

        });
    });
    $('.loop-slider-wrap.flexslider').imagesLoaded(function() {
        $(this).flexslider({
            smoothHeight: true,
            controlsContainer: '.ts-main-flex-nav',
            directionNav: false,
            pauseOnAction: true,
            pauseOnHover: false
        });
    });

    $('#ts-news-ticker-wrap .flexslider').imagesLoaded(function() {
        $(this).flexslider({
            animation: "slide",
            animationLoop: true,
            itemWidth: 280,
            itemMargin: 20,
            prevText: '<i class="fa fa-chevron-left subtle-text-color"></i>',
            nextText: '<i class="fa fa-chevron-right subtle-text-color"></i>'
        });
    });

    /*****
     * Parallax
     *****/
    if(ts_browserWidth() > 900 && !device.tablet() && !device.mobile()) {
        //$('.ts-color-section-shortcode.parallax').parallax('50%',.6);
    }
    
    /*****
     * Infinite Scroll
     *****/
    function ts_infinite_scroll(element) {
        
        var infinite = $(element);
        
        infinite.find(".threshold").remove();
        infinite.find('.spinner').show();
        infinite.find(".alt-loader").hide();
        var qvars = $.parseJSON(infinite.find('.infinite-scroller-atts').text());
        var entries_container = infinite.parent().find('.entries');
        var timestamp = $.now();
        qvars.action = 'ts_load_infinite_blog';
        var attempt = (infinite.attr('data-attempt')) ? infinite.attr('data-attempt') : 0;
        attempt++;
        infinite.attr('data-attempt', attempt);
        var i_s_limit = (typeof qvars.infinite_scroll_limit !== 'undefined') ? qvars.infinite_scroll_limit : 100;
        infinite.attr('data-limit', i_s_limit);
        $.post(ts_ajax_vars.ajaxurl+'?timestamp='+timestamp, qvars, function(data) {
            var html_data = data;
            var entries = $(html_data).find('.loop').first().html();
            var new_qvars_str = $(html_data).find('.infinite-scroller-atts').first().text();
            var new_qvars = $.parseJSON(new_qvars_str);
            if(typeof new_qvars.is_final === 'undefined') console.log('Error: please set a static "Posts page" (Settings > Reading)');
            infinite.find('.infinite-scroller-atts').text(new_qvars_str);
            if(entries_container.hasClass('loop-masonry') && ts_browserWidth() > 720) {
                $masonryContainer.isotope('insert', $(entries));
                setTimeout(function() {
                    $masonryContainer.isotope('reLayout');
                    if(new_qvars.is_final == 1) {
                        $('.infinite-scroller').unbind("inview").remove();
                    } else {
                        infinite.prepend('<div class="threshold"></div>');
                    }
                }, 1000);
            } else {
                entries_container.append(entries);
                if(new_qvars.is_final == 1) {
                    $('.infinite-scroller').unbind("inview").remove();
                } else {
                    infinite.prepend('<div class="threshold"></div>');
                }
            }
            var limit_reached = (infinite.hasClass('has-infinite-scroll-limit') && attempt >= i_s_limit) ? true : false; 
            if(ts_browserWidth() <= 720 || infinite.hasClass('ts-use-infinite-scroll-button') || limit_reached) {
                infinite.find(".alt-loader").show();
                infinite.find('.spinner').hide();
                $('.infinite-scroller').unbind("inview");
            }
            console.log('attempt: '+attempt+'; limit: '+i_s_limit+';');
            ts_magnificPopup();
            ts_flexslider();
            ts_inview_animations();
        });
    }
    if($('.infinite-scroller').length) {
        
        var infinite = $('.infinite-scroller').first();
        var infinite_button = infinite.find('.alt-loader .button');
        infinite_button.on('click', function() { ts_infinite_scroll(infinite); });
        if(ts_browserWidth() > 720 && !infinite.hasClass('ts-use-infinite-scroll-button')) {
            infinite.find(".alt-loader").hide();
            infinite.on("inview", '.threshold', function() { ts_infinite_scroll(infinite); });
        } else {
            infinite.find('.spinner').hide();
        }
    }
    
    /*****
     * Featured photo captions on single pages
     *****/
    $('.single-entry .ts-featured-media-gallery .fp-caption-wrap').each(function() {
        var h = $(this).height();
        $(this).data('height', h).css("height",'0px').addClass('closed');
    }); 
    
    $('.single-entry .ts-featured-media-gallery').hover(
        function() {
            var h = $(this).find('.fp-caption-wrap').data('height');
            $( this ).find('.fp-caption-wrap').stop().animate({height:h+'px'});
        }, function() {
            $( this ).find('.fp-caption-wrap').stop().animate({height:'0px'});
        }
    );
    
    /*****
     * Smooth Page Scroll
     *****/
    if($('body').hasClass('smooth-page-scroll')) {
        addEvent("mousedown",smooth_page_scroll_mousedown);
        addEvent("mousewheel",smooth_page_scroll_wheel);
        smooth_page_scroll_init();
    } 
    
    /*****
     * WooCommerce
     *****/
    $('#woo-product-tabs li.reviews').on('click', function() {
        $('#review_form_wrapper').removeClass('hidden').find('p.stars span a').each(function() {
            $(this).html('<em>'+$(this).text()+'</em>');
        });
    });
    $('.products .ts-loop-product-top .add_to_cart_button.added').each(function() {
        $(this).closest('.product').addClass('in-cart');
    });
    /* woocommerce pre 2.3 */
    $('body.woocommerce-less-than-2dot3').on('adding_to_cart', function(a, b, c) {
        var pid = c.product_id.replace(/[^0-9.]/g, "")
        $(this).attr('data-currently-adding', pid);
        $('.products .post-'+pid).addClass('adding-to-cart');
        var the_button = $('.products .post-'+pid+' .add_to_cart_button');
        var add_to_cart_button_text = the_button.text();
        var add_to_cart_button_href = the_button.attr('href');
        the_button.attr('data-text', add_to_cart_button_text).attr('data-href', add_to_cart_button_href).attr('href', 'javascript:void(0)').css('opacity','.7').html('<i class="fa fa-cog fa-spin"></i>');
    });
    $('body.woocommerce-less-than-2dot3').on('added_to_cart', function(event, param1, param2) {
        var pid = $(this).attr('data-currently-adding');
        $(this).attr('data-currently-adding', '');
        $('.products .post-'+pid).removeClass('adding-to-cart').addClass('ts-in-cart');
        var button = $('.products .post-'+pid+' .add_to_cart_button');
        var add_to_cart_button_text = button.attr('data-text');
        var add_to_cart_button_href = button.attr('data-href');
        button.html('<i class="fa fa-check"></i>').attr('href', add_to_cart_button_href).css('opacity','');
        ts_update_mini_cart();
    });
    /* woocommerce 2.3+ */
    $('body.woocommerce-2dot3-plus .products .add_to_cart_button').on('click', function() {
        var pid = $(this).attr('data-product_id');
        $('body').attr('data-currently-adding', pid);
    });
    $('body.woocommerce-2dot3-plus').on('adding_to_cart', function() {
        var pid = $(this).attr('data-currently-adding');
        $('.products .post-'+pid).addClass('adding-to-cart');
        var the_button = $('.products .post-'+pid+' .add_to_cart_button');
        var add_to_cart_button_text = the_button.text();
        var add_to_cart_button_href = the_button.attr('href');
        the_button.attr('data-text', add_to_cart_button_text).attr('data-href', add_to_cart_button_href).attr('href', 'javascript:void(0)').css('opacity','.7').html('<i class="fa fa-cog fa-spin"></i>');
    });
    $('body.woocommerce-2dot3-plus').on('added_to_cart', function(event, param1, param2) {
        var pid = $(this).attr('data-currently-adding');
        $(this).attr('data-currently-adding', '');
        $('.products .post-'+pid).removeClass('adding-to-cart').addClass('ts-in-cart');
        var button = $('.products .post-'+pid+' .add_to_cart_button');
        var add_to_cart_button_text = button.attr('data-text');
        var add_to_cart_button_href = button.attr('data-href');
        button.html('<i class="fa fa-check"></i>').attr('href', add_to_cart_button_href).css('opacity','');
        ts_update_mini_cart();
    });
    $('#shiptobilling-checkbox').change(function() {
        if($(this).is(':checked')) {
            $('.shipping_address').addClass('hidden');
        } else {
            $('.shipping_address').removeClass('hidden');
        }
    });
    
    /*****
     * Share post
     *****/
    $('a.share-pop').click(function() {
        window.open($(this).attr('href'), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=400,width=600');
        return false;
    });
    
    /*****
     * Smooth Scroll
     *****/
    var ts_smoothScrollOffset = ($('body').hasClass('admin-bar')) ? 32 : 0;
    var ts_smoothScrollOffset_plus = ($('body').hasClass('ts-has-sticky-nav')) ? Number(ts_smoothScrollOffset) + 50 : ts_smoothScrollOffset;
    $('a.smoothscroll-up').smoothScroll();
    $('a.smoothscroll').smoothScroll({
        offset: -ts_smoothScrollOffset_plus
    });
    $('a#ts-back-to-top').smoothScroll({offset: -ts_smoothScrollOffset});
    $('a.reviews-smoothscroll').smoothScroll({
        offset: -ts_smoothScrollOffset_plus,
        beforeScroll: function () {
            $('#woo-product-tabs li.reviews').click();
        }
    });
    
    /*****
     * Magnific Popup
     *****/
    if($.magnificPopup) {
        ts_magnificPopup();
    }
    
    
    /*****
     * Masonry
     *****/
    $masonryContainer.imagesLoaded(function() {
        resizeMasonryEntries();
    });
    $(window).resize(function() {
        resizeMasonryEntries();
    });
    
    if(ts_isRTL()) {
        $.Isotope.prototype._positionAbs = function( x, y ) {
            return { right: x, top: y };
        };
    }
        
    /*****
     * Toggles
     *****/
    $('.toggles-wrapper .accordion-block').each(function() {
        $(this).find('.tab-head').each(function(i) {
            if($(this).hasClass('open')) {
                $(this).find('.flexible-map').addClass('mapped');
            }
            $(this).click(function(){
                var open_icon = $(this).closest('.tog-acc-wrapper').data('open-icon');
                var closed_icon = $(this).closest('.tog-acc-wrapper').data('closed-icon');
                if($(this).hasClass('active')) {
                    $(this).removeClass('active').find('i').removeClass(open_icon).addClass(closed_icon)
                    .parent().siblings().slideUp(250).end().removeClass('open');
                } else {
                    $(this).addClass('active').find('i').removeClass(closed_icon).addClass(open_icon)
                        .parent().siblings().slideDown(250).end().addClass('open');
                    $(this).parent().reloadMaps();
                }
            });
        });
    });
        
    /*****
     * Accordion
     *****/
    $('.accordion-wrapper .accordion-block').each(function() {
        $(this).find('.tab-head').each(function(i) {
            if($(this).hasClass('open')) {
                $(this).find('.flexible-map').addClass('mapped');
            }
            $(this).click(function(){
                var open_icon = $(this).closest('.tog-acc-wrapper').data('open-icon');
                var closed_icon = $(this).closest('.tog-acc-wrapper').data('closed-icon');
                if($(this).hasClass('active')) {
                    $(this).removeClass('active').find('i').removeClass(open_icon).addClass(closed_icon)
                    .parent().siblings().slideUp(250).end().removeClass('open');
                } else {
                    $(this).parent().siblings().find('.tab-head').removeClass('active')
                        .find('i').removeClass(open_icon).addClass(closed_icon);
                    $(this).parent().siblings().find('.tab-body').slideUp(250).end().removeClass('open');
                    $(this).addClass('active').find('i').removeClass(closed_icon).addClass(open_icon)
                        .parent().siblings().slideDown(250).end().addClass('open');
                    $(this).parent().reloadMaps();
                }
            });
        });
    });
    
    /*****
     * Tabs
     *****/
    $(function(){
        $(".tab-widget .tab-header").each(function(){
            $(this).find("li").each(function(e){
                $(this).click(function(){
                    $(this).addClass("active").siblings().removeClass("active")
                        .parents(".tab-widget").find(".tab-context")
                        .hide().end().find(".tab-context:eq("+e+")").fadeIn(250, function() {                        
                            $(this).reloadMaps();                        
                        });
                });
            });
        });
        $(".tab-widget").each(function(){
            var e=0;
            $(this).find(".tab-context.visible").each(function(t){e++});
            if(e<1){$(this).find(".tab-context").first().addClass("visible").find('.flexible-map').addClass('mapped')}
        });
    });
    
    
    /*****
     * Superfish
     *****/ 
    var superfish_options = {
        popUpSelector: 'ul.sub-menu,ul.children,.sf-mega',
         delay:  200,
         cssArrows:    false,
         speed: 'fast',
         animation:   {opacity:'show'},
         onBeforeShow: function() {
            if($(this).hasClass('invert')) return false;
            var secondary = ($(this).parent().parent().hasClass('sf-menu')) ? true : false;
            var offset = $(this).parent().offset();
            if (typeof offset === 'undefined') {
                // do nothing
            } else {
                var dir = (ts_isRTL()) ? 'left' : 'right';
                var _o = ts_wrapWidth() - offset.left + ((ts_browserWidth() - ts_wrapWidth())/2);
                var o = (secondary) ? _o : _o - $(this).parent().outerWidth();
                if(o < $(this).outerWidth()) {
                    if(secondary) {
                        $(this).css(dir, '-'+(Math.abs(ts_wrapWidth() - offset.left + ((ts_browserWidth() - ts_wrapWidth())/2) - $(this).parent().width() - 10))+'px');
                    } else {
                        $(this).addClass('invert');
                    }
                }
            }
         },
         onHide: function() {
            $(this).removeClass('invert').css('right', '');
         }
    };
    $(".main-nav .sf-menu").superfish(superfish_options);
	
	/*****
     * Main Menu Fix
     * (for when two top level items are active)
     *****/
    var active_menu_items = $('.main-nav > ul').find('> li.current_page_parent, > li.current_page_ancestor, > li.current-post-parent, > li.current-post-ancestor, > li.current-menu-item, > li.current-category-ancestor');
    var active_menu_items_count = active_menu_items.length;
    if(active_menu_items_count > 1) {
        $('.main-nav > ul > li').addClass('no-transitions');
        active_menu_items.addClass('ts-active-menu-item');
        if(active_menu_items_count > 1) {
            $('.ts-active-menu-item:not(:first)').removeClass('current_page_parent current_page_ancestor current-post-parent current-post-ancestor current-menu-item current-category-ancestor');
        }
    }
	
    /*****
     * Top Stuff Search
     *****/
    if($('.top-stuff-search-link').length) {
        $('.top-stuff-search-link').on('click', function() {
            var search = $(this).parent().find('.search-pocket-wrap');
            ts_open_hover_menu(search, function() {
                search.find('input[type="text"]').focus();
            });
        });
    }
	
    /*****
     * UberMenu
     *****/
    if($('body').hasClass('ts-override-ubermenu-styling')) {
        $('.ubermenu-nav > li > .ubermenu-submenu-align-full_width').each(function() {
            var count = $(this).children('.ubermenu-column-auto').length;
            count = (count > 6) ? 6 : count;
            $(this).children('.ubermenu-column-auto').addClass('ts-boxed-1-of-'+count);
        });
    }
    
    /*****
     * "Back to top" button
     *****/
    if(jQuery('#ts-back-to-top').length) {
        $(window).scroll(function(){
            if(ts_browserWidth() >= 720 || $('body').hasClass('ts-back-to-top-mobile')) {
                if ($(window).scrollTop() >= 500) {
                    $('#ts-back-to-top').addClass('hello');
                } else {
                    
                    $('#ts-back-to-top').removeClass('hello');
                }
            }
        });
    }
})(jQuery);

