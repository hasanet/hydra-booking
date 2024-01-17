(function($) {	
	$(document).ready(function(){

        /**
         * Conditional show/hide fields in admin settings
         * 
         * @since 1.0
         */
        // Date conditional
        if(typeof hydra_allowed_date !== 'undefined'){
            if( hydra_allowed_date == 'always' ){
                $('.allowed-date-range, .allowed-specific-date').hide();
                $('.cond-disabled-date').show();
            } else if( hydra_allowed_date == 'range' ){
                $('.allowed-date-range, .cond-disabled-date').show();
                $('.allowed-specific-date').hide();
            } else if( hydra_allowed_date == 'specific' ){
                $('.allowed-specific-date').show();
                $('.allowed-date-range, .cond-disabled-date').hide();                
            } else {
                $('.allowed-date-range, .allowed-specific-date, .cond-disabled-date').hide();
            } 
            $( 'input[name="hydra_allowed_date"]' ).on('change', function(){
                var hydra_allowed_date = $( this ).val();
                if( hydra_allowed_date == 'always' ){
                    $('.allowed-date-range, .allowed-specific-date').hide();
                    $('.cond-disabled-date').show();
                } else if( hydra_allowed_date == 'range' ){
                    $('.allowed-date-range, .cond-disabled-date').show();
                    $('.allowed-specific-date').hide();
                } else if( hydra_allowed_date == 'specific' ){
                    $('.allowed-specific-date').show();
                    $('.allowed-date-range, .cond-disabled-date').hide();                
                }           
            });

            // Time conditional
            if( hydra_allowed_time == 'always' ){
                $('.allowed-day-time-date, .allowed-specific-time-date, .allowed-time-date').hide(); 
            } else if( hydra_allowed_time == 'day' ){
                $('.allowed-day-time-date, .allowed-time-date').show();
                $('.allowed-specific-time-date').hide(); 
            } else if( hydra_allowed_time == 'specific' ){
                $('.allowed-specific-time-date, .allowed-time-date').show();
                $('.allowed-day-time-date').hide();                
            }   else {
                $('.allowed-day-time-date, .allowed-specific-time-date, .allowed-time-date').hide(); 
            }

            $( 'input[name="hydra_allowed_time"]' ).on('change', function(){
                var hydra_allowed_time = $( this ).val();
                if( hydra_allowed_time == 'always' ){
                    $('.allowed-day-time-date, .allowed-specific-time-date, .allowed-time-date').hide(); 
                } else if( hydra_allowed_time == 'day' ){
                    $('.allowed-day-time-date, .allowed-time-date').show();
                    $('.allowed-specific-time-date').hide(); 
                } else if( hydra_allowed_time == 'specific' ){
                    $('.allowed-specific-time-date, .allowed-time-date').show();
                    $('.allowed-day-time-date').hide();                
                }          
            });

            // WooCommerce conditional
            if( hydra_woo == '1' ){
                $('.cond-product-conf, .product-exist, .product-custom').show();                           
            } else {
                $('.cond-product-conf, .product-exist, .product-custom').hide();
            }

            $( 'input[name="hydra_woo"]' ).on('change', function(){
                if( this.checked ){
                    $('.cond-product-conf').show();
                    if( hydra_product == 'exist' ){
                        $('.product-exist').show();
                        $('.product-custom').hide();               
                    } else if( hydra_product == 'custom' ){
                        $('.product-custom').show();
                        $('.product-exist').hide();
                    } 
                } else {
                    $('.cond-product-conf, .product-exist, .product-custom').hide();
                }          
            });

            if( hydra_product == 'exist' && hydra_woo == '1' ){
                $('.product-exist').show();
                $('.product-custom').hide();               
            } else if( hydra_product == 'custom' && hydra_woo == '1' ){
                $('.product-custom').show();
                $('.product-exist').hide();
            } else {
                $('.product-exist, .product-custom').hide();
            }

            $( 'input[name="hydra_product"]' ).on('change', function(){
                var hydra_product = $( this ).val();
                if( hydra_product == 'exist' ){
                    $('.product-exist').show();
                    $('.product-custom').hide();               
                } else if( hydra_product == 'custom' ){
                    $('.product-custom').show();
                    $('.product-exist').hide();
                }          
            });

            /**
             * Initiate date in Contact form 7 panel
             * 
             * @since 1.0
             */
            // Allowed Dates - Range
            $(".allowed-start-date").flatpickr({
                "plugins": [new rangePlugin({ input: ".allowed-end-date"})],           
                allowInput: true,
                dateFormat: "Y-m-d",
            });

            // Allowed Dates - Specific
            $(".allowed-specific-date").flatpickr({           
                allowInput: true,
                mode: "multiple",
                dateFormat: "Y-m-d",
            });

            // Min/Max Dates - Range
            $(".min-date, .max-date").flatpickr({
                allowInput: true,
                dateFormat: "Y-m-d",
            });

            // Disabled Dates - Range
            $(".disabled-start-date").flatpickr({
                "plugins": [new rangePlugin({ input: ".disabled-end-date"})],           
                allowInput: true,
                dateFormat: "Y-m-d",
            });

            // Disabled - Specific
            $(".disabled-specific-date").flatpickr({           
                allowInput: true,
                mode: "multiple",
                dateFormat: "Y-m-d",
            });
            // Disabled - Specific
            $(".specific-date-time").flatpickr({           
                allowInput: true,
                mode: "multiple",
                dateFormat: "Y-m-d",
            });
        }
       
       

        


        /**
         * Initiate time in Contact form 7 panel
         * 
         * @since 1.0
         */
         $('#min-time, #max-time, #from-dis-time, #to-dis-time, #max-day-time, #min-day-time').timepicker();

    });
})(jQuery);

/**
 * Ajax install WooCommerce
 * 
 * @since 1.0
 */
(function($) {
	
	$(document).ready(function(){	

        $(document).on('click', '.tf-install', function(e) {
            e.preventDefault();

            var current = $(this);
            var plugin_slug = current.attr("data-plugin-slug");

            current.addClass('updating-message').text('Installing...');

            var data = {
                action: 'tf_ajax_install_plugin',
                _ajax_nonce: hydracf7_params.hydracf7_nonce,
                slug: plugin_slug,
            };

            jQuery.post( hydracf7_params.ajax_url, data, function(response) {
                //console.log(response);
                //console.log(response.data.activateUrl);
                current.removeClass('updating-message');
                current.addClass('updated-message').text('Installed!');
                current.attr("href", response.data.activateUrl);
            })
            .fail(function() {
                current.removeClass('updating-message').text('Failed!');
            })
            .always(function() {
                current.removeClass('install-now updated-message').addClass('activate-now button-primary').text('Activating...');
                current.unbind(e);
                current[0].click();
            });
        });

    });

})(jQuery);