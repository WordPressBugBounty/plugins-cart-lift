(function ( $ ) {

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *                                                                     
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    function isBlank( str ) {
        return ( !str || /^\s*$/.test( str ));
    }


    AnalyticsAction = {
        init: function () {
            AnalyticsAction.initDatePicker();
            $( document ).on( 'change', '#filter-option', AnalyticsAction.getAnalyticsData );
            $( document ).on( 'click', '#submit_range', AnalyticsAction.getAnalyticsData );
            $( document ).on( 'change', '#to', AnalyticsAction.getAnalyticsData );
        },

        getDate: function ( element ) {
            var date;
            try {
                date = $.datepicker.parseDate( dateFormat, element.value );
            } catch ( error ) {
                date = null;
            }
            return date;
        },


        initDatePicker: function () {
            var dateFormat = "M d, yy",
                from = $("#from")
                    .datepicker({
                        dateFormat: dateFormat,
                        numberOfMonths: 1,
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "2020:2040"
                    })
                    .datepicker("setDate", '-7d')
                    .on("change", function () {
                        let date_start = $('#from').val();
                        let date_end = $('#to').val();

                        let fromDate = new Date(date_start);
                        let toDate = new Date(date_end);

                        if (fromDate > toDate) {
                            alert("Start date cannot be later than end date.");
                            $('#from').datepicker('setDate', new Date()); // Reset start date to today
                            to.datepicker("option", "minDate", AnalyticsAction.getDate(this));
                            setTimeout(function() {
                                from.datepicker("hide").blur();
                                to.datepicker("hide").blur();
                            }, 10);
                            return;
                        }
                        to.datepicker("option", "minDate", AnalyticsAction.getDate(this));
                    }),

                to = $("#to")
                    .datepicker({
                        dateFormat: dateFormat,
                        numberOfMonths: 1,
                        changeMonth: true,
                        changeYear: true,
                        yearRange: "2020:2040"
                    })
                    .datepicker("setDate", new Date())
                    .on("change", function () {
                        let date_start = $('#from').val();
                        let date_end = $('#to').val();

                        let fromDate = new Date(date_start);
                        let toDate = new Date(date_end);

                        if (fromDate > toDate) {
                            alert("End date cannot be earlier than start date.");
                            $('#to').datepicker('setDate', new Date()); // Reset end date to today
                            from.datepicker("option", "maxDate", AnalyticsAction.getDate(this)).datepicker("hide");
                            setTimeout(function() {
                                from.datepicker("hide").blur();
                                to.datepicker("hide").blur();
                            }, 10);
                            return;
                        }
                        from.datepicker("option", "maxDate", AnalyticsAction.getDate(this));
                    });
        },

        set_value: function ( element, val ) {
            $( '#' + element ).html( val );
        },

        getAnalyticsData: function () {
            let $_this = $( this ),
                data = $_this.val(),
                loader = $( '#cl-loader' );
            data = 'custom';
            var date_start = '';
            var date_end = '';
            if ( data == '0' ) {
                data = 'weekly';
            } else if ( data == 'custom' || data == 'Submit' ) {
                data = 'custom';
                date_start = $( '#from' ).val();
                date_end = $( '#to' ).val();
            }
            let payload = {
                'range': data,
                'date_start': date_start,
                'date_end': date_end,
            };
            cl_chart.destroy();
            loader.fadeIn();
            wpAjaxHelperRequest( 'get-analytics-data', payload )
                .success( function ( response ) {
                    loader.fadeOut();
                    let data = response.data;
                    AnalyticsAction.set_value( 'recapturable-revenue', data.recapturable_revenue );
                    AnalyticsAction.set_value( 'recovered-revenue', data.recovered.revenue );
                    AnalyticsAction.set_value( 'abandoned-total', data.abandoned.total );
                    AnalyticsAction.set_value( 'abandoned-revenue', data.abandoned.revenue );
                    AnalyticsAction.set_value( 'abandoned-carts-rate', data.abandoned_carts_rate );
                    AnalyticsAction.set_value( 'actionable-carts', data.actionable_carts );
                    AnalyticsAction.set_value( 'total-email-sent', data.total_email_sent );


                    let chart_data = data.chart_data;
                    var label = chart_data.labels;
                    var datasets_abandoned = chart_data.abandoned;
                    var datasets_recovered = chart_data.recovered;

                    let direction = 'yes' === data.isRTL ? 'rtl' : 'ltr';

                    var config = {
                        type: 'line',
                        data: {
                            labels: label,
                            datasets: [ {
                                label: 'rtl' !== direction ? window.cart_lift_js_translatable.abandoned : window.cart_lift_js_translatable.recovered,
                                backgroundColor:'rtl' !== direction ?  '#ee8033' : '#6d41d3',
                                borderColor: 'rtl' !== direction ?  '#ee8033' : '#6d41d3',
                                data: 'rtl' !== direction ?  datasets_abandoned : datasets_recovered,
                                fill: false,
                            }, {
                                label: 'rtl' !== direction ? window.cart_lift_js_translatable.recovered : window.cart_lift_js_translatable.abandoned,
                                backgroundColor: 'rtl' !== direction ? '#6d41d3' : '#ee8033',
                                borderColor: 'rtl' !== direction ? '#6d41d3' : '#ee8033',
                                data: 'rtl' !== direction ? datasets_recovered : datasets_abandoned,
                                fill: false,
                            } ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            title: {
                                display: true,
                                text: window.cart_lift_js_translatable.cart_overview,
                                fontSize: 18,
                                fontColor: '#363b4e',
                                rtl: 'rtl' === direction ? true : false,
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                                bodySpacing: 12,
                                titleMarginBottom: 10,
                                xPadding: 7,
                                yPadding: 7,
                            },
                            hover: {
                                mode: 'nearest',
                                intersect: true
                            },
                            scales: {
                                xAxes: [ {
                                    display: true,
                                    scaleLabel: {
                                        display: true,
                                        rtl: 'rtl' === direction ? true : false,
                                    },
                                    ticks: {
                                        reverse: 'rtl' === direction ? true : false
                                    }
                                } ],
                                yAxes: [ {
                                    display: true,
                                    position: 'rtl' === direction ? 'right' : 'left',
                                    ticks: {
                                        stepSize: 1
                                    },
                                    scaleLabel: {
                                        display: true,
                                        rtl: 'rtl' === direction ? true : false,
                                    }
                                } ]
                            }
                        }
                    };
                    var ctx = document.getElementById( 'cl-chart' ).getContext( '2d' );
                    cl_chart = new Chart( ctx, config );
                } )
                .error( function ( response ) {
                    loader.fadeOut();

                } );

        },
    };

    CartActions = {
        init: function () {
            $( '.cl-cart-details' ).on( 'click', function ( e ) {
                e.preventDefault();
                $( this ).parents( '.single-cart-wrapper' ).find( '.cl-cart-modal' ).addClass( 'show' );
            } );
            $( '.cart-modal-close' ).on( 'click', function () {
                $( this ).parents( '.cl-cart-modal' ).removeClass( 'show' );
            } );

            $( document ).on( 'change', '#cl_status_filter', function ( e ) {
                $( "#cl_cart_filter" ).submit();
            } );

            $(document).on('change', '.cl-toggle-option.cl_action_schedular', function() {
                let checkbox = $(this);
                let cartId = checkbox.data('id');
                let newStatus = checkbox.is(':checked') ? 'active' : 'paused';

                var payload = {
                    'id': cartId,
                    'schedular_status': newStatus
                };

                wpAjaxHelperRequest( 'cl-update-schedular-status', payload )
                    .success( function ( response ) {
                        if (response.response === 'success') {
                            checkbox.attr('data-status', newStatus);
                        } else {
                            alert('Failed to update status');
                            checkbox.prop('checked', !checkbox.is(':checked')); // Revert checkbox state
                        }
                    } )
                    .error( function (  ) {
                        checkbox.prop('checked', !checkbox.is(':checked'));
                    } );
            });

        },
    };

    AlertModal = {
        init: function () {
            $( '.cl-cart-delete' ).on( 'click', function ( e ) {
                e.preventDefault();
                $( this ).parents( '.single-cart-wrapper' ).find( '.cl-alert-modal' ).addClass( 'show' );
            } );
            $( '.cl-alert-close, .cl-alert-cancel' ).on( 'click', function () {
                $( this ).parents( '.cl-alert-modal' ).removeClass( 'show' );
            } );

            //------campaign alert modal--------
            $( '.cl-campaign-delete' ).on( 'click', function ( e ) {
                e.preventDefault();
                $( this ).parents( '.single-campaign-wrapper' ).find( '.cl-alert-modal' ).addClass( 'show' );
            } );
            $( '.cl-alert-close, .cl-alert-cancel' ).on( 'click', function () {
                $( this ).parents( '.cl-alert-modal' ).removeClass( 'show' );
            } );

        },
    };

    EmailTemplateActions = {

        init: function () {
            $( document ).on( 'click', '.cl-send-test-email', EmailTemplateActions.send_test_preview_email );
            $( document ).on( 'click', '.cl-toggle-email-template-status-list', EmailTemplateActions.toggle_template_status_list_table );
            $( document ).on( 'click', '.cl-toggle-email-template-status', EmailTemplateActions.toggle_campaign_status );
            // $(document).on('click', '.cl-toggle-email-template-status', EmailTemplateActions.toggle_template_status_list_table);
            $( document ).on( 'click', '.cl-toggle-campaign-coupon', EmailTemplateActions.toggle_campaign_coupon );
            $( document ).on( 'submit', '#cl-email-template-edit-form', EmailTemplateActions.save_campaign );
            $( document ).on( 'change', '#cl-conditional-discount', EmailTemplateActions.toggle_conditional_discount );

            $( '#cl-campaign-email-header-color' ).wpColorPicker();
            $( '#cl-campaign-checkout-color' ).wpColorPicker();

            if ( $( "#cl-campaign-coupon" ).is( ":checked" ) ) {
                $( '.coupon-fields' ).show();
                if ( $( "#cl-conditional-discount" ).is( ":checked" ) ) {
                    $( '.cl-coupon-conditional-fields' ).show();
                    $( '#cl-coupon-amount' ).hide();
                } else {
                    $( '.cl-coupon-conditional-fields' ).hide();
                    $( '#cl-coupon-amount' ).show();
                }
            } else {
                $( '.coupon-fields' ).hide();
            }
            $( document ).on( 'click', '#cl-campaign-coupon', function () {
                $( '.coupon-fields' ).toggle( $( "#cl-campaign-coupon" ).is( ":checked" ) );
            } );

            $( document ).on( 'click', '#cl-conditional-discount', function () {
                $( '.cl-coupon-conditional-fields' ).toggle( $( "#cl-conditional-discount" ).is( ":checked" ) );
                $( '#cl-coupon-amount' ).toggle( !$( "#cl-conditional-discount" ).is( ":checked" ) );
            } );
        },

        toggle_template_status_list_table: function () {
            let $_this = $( this ),
                id = $_this.attr( 'id' ),
                template_id = $_this.attr( 'data-template-id' ),
                current_status = $_this.attr( 'data-status' );

            let payload = {
                'id': template_id,
                'status': current_status
            };

            wpAjaxHelperRequest( 'toggle-email-template-status', payload )
                .success( function ( response ) {
                    /*console.log( 'Woohoo!' );*/
                } )
                .error( function ( response ) {

                } );

        },

        toggle_campaign_status: function () {
            let $_this = $( this ),
                id = $_this.attr( 'id' ),
                value = $_this.val();
            if ( value == '1' ) {
                $_this.attr( 'data-status', 'off' );
                $_this.val( 0 );
            } else {
                $_this.attr( 'data-status', 'on' );
                $_this.val( 1 );
            }
        },

        toggle_campaign_coupon: function () {
            let $_this = $( this ),
                value = $_this.val();
            if ( value == '1' ) {
                $_this.val( 0 );
            } else {
                $_this.val( 1 );
            }
        },

        send_test_preview_email: function () {
            let email_subject = '',
                email_body = '',
                send_to = '',
                email_header_text = '',
                email_header_color = '#6e42d3',
                $_this = $( this );

            if ( $( "#wp-cl_email_body-wrap" ).hasClass( "tmce-active" ) ) {
                email_body = tinyMCE.get( 'cl_email_body' ).getContent();
            } else {
                email_body = $( '#cl_email_body' ).val();
            }
            email_subject = $( '#cl-email-subject' ).val();
            send_to = $( '#cl-test-email' ).val();
            email_header_text = $( '#cl-campaign-email-header' ).val();
            email_checkout_text = $( '#cl-campaign-checkout-text' ).val();
            email_header_color = $( '#cl-campaign-email-header-color' ).val();
            email_checkout_color = $( '#cl-campaign-checkout-color' ).val();
            if ( isBlank( email_subject ) ) {
                alert( 'Please add email subject' );
            } else if ( isBlank( email_body ) ) {
                alert( 'Please add email body' );
            } else if ( isBlank( send_to ) ) {
                alert( 'Please add recipient' );
            } else {

                let payload = {
                    'email_subject': email_subject,
                    'email_body': email_body,
                    'send_to': send_to,
                    'email_header_text': email_header_text,
                    'email_header_color': email_header_color,
                    'email_checkout_color': email_checkout_color,
                    'email_checkout_text': email_checkout_text,
                };
                let message_block = $( "#test_mail_response_msg" );
                message_block.html( '' );
                message_block.hide();
                wpAjaxHelperRequest( 'send-preview-email', payload )
                    .success( function ( response ) {
                        if ( response?.success ) {
                            message_block.removeClass( 'cl-error' );
                            message_block.addClass( 'cl-success' ).html( 'Email sent successfully.' ).delay( 1000 ).fadeOut();
                        } else {
                            message_block.addClass( 'cl-error' ).html( 'Email sending failed.' ).delay( 1000 ).fadeOut();
                        }
                        message_block.fadeIn();
                    } )
                    .error( function ( response ) {
                        message_block.addClass( 'cl-error' ).html( 'Email sending failed.' ).delay( 1000 ).fadeOut();
                        message_block.fadeIn();
                    } );
            }
        },

        save_campaign: function () {
            var frequency = $( "#cl-email-frequency" ).val(),
                unit = $( "#cl-email-unit" ).val();
            if ( unit === 'minute' ) {
                if ( frequency < 15 ) {
                    $( "#cl-email-frequency" ).parent( '.send-email-time' ).addClass( 'cl-field-error' );
                    alert( 'Minimum time limit 15 mins' );
                    return false;
                }
            } else {
                $( this ).submit();
            }
        },

        toggle_conditional_discount: function () {
            let $_this = $( this ),
                id = $_this.attr( 'id' ),
                value = $_this.val();
            if ( value == '1' ) {
                $_this.attr( 'data-status', 'off' );
                $_this.val( 0 );
            } else {
                $_this.attr( 'data-status', 'on' );
                $_this.val( 1 );
            }
        }
    };

    GeneralSettingsAction = {
        init: function () {
            $( document ).on( 'submit', '#general-settings-form', GeneralSettingsAction.save_general_settings );
        },
        save_general_settings: function ( e ) {
            e.preventDefault();
            var expiration_frequency = $( "#cl-expiration-time" ).val(),
                cut_off_frequency = $( "#cl-abandonment-time" ).val(),
                error = false;
            if ( cut_off_frequency < 15 ) {
                $( "#cl-abandonment-time" ).parent( '.cl-form-group' ).addClass( 'cl-field-error' );
                alert( 'Minimum time is limit 15 minutes' );
                return false;
            }
            if ( expiration_frequency < 7 ) {
                $( "#cl-expiration-time" ).parent( '.cl-form-group' ).addClass( 'cl-field-error' );
                alert( 'Minimum time is limit 7 days' );
                return false;
            }


            $( '#cl-loader' ).fadeIn();
            $( '#general_settings_notice' ).hide();

            var data = $( this ).serialize();
            let payload = {
                'data': data,
            };
            wpAjaxHelperRequest( 'general-save-form', payload )
                .success( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                    $( '#general_settings_notice' ).addClass( 'cl-success' ).fadeIn();
                    $( '#general_settings_notice' ).html( response.message );
                    setTimeout( function () {
                        $( '#general_settings_notice' ).removeClass( 'cl-success' ).fadeOut();
                    }, 3000 );
                } )
                .error( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                    $( '#general_settings_notice' ).addClass( 'cl-error' ).show();
                    $( '#general_settings_notice' ).text( 'Something wrong' );
                    setTimeout( function () {
                        $( '#general_settings_notice' ).removeClass( 'cl-error' ).fadeOut();
                    }, 3000 );
                } );
        }
    };

    OtherSMTPActions = {
        init: function () {
            $( document ).on( 'submit', '#smtp-save-form', OtherSMTPActions.set_smtp_data );
            $( document ).on( 'click', '.cl-toggle-option', OtherSMTPActions.toggle_settings_option );
            $( document ).on( 'click', '#cl_send_test', OtherSMTPActions.test_smtp_data );
        },

        toggle_settings_option: function () {
            let $_this = $( this ),
                id = $_this.attr( 'id' ),
                value = $_this.val();
            if ( value == '1' ) {
                $_this.attr( 'data-status', 'no' );
                $_this.val( 0 );
            } else {
                $_this.attr( 'data-status', 'yes' );
                $_this.val( 1 );
            }

            var name = $( this ).attr( 'name' );
            if ( name == 'enable_webhook' ) {
                if ( value == 0 ) {
                    $( '#cart_webhook' ).fadeIn();
                } else {
                    $( '#cart_webhook' ).fadeOut();
                }
            }

            if ( name == 'enable_cart_expiration' ) {
                if ( value == 0 ) {
                    $( '#enable_cart_expiration' ).fadeIn();
                } else {
                    $( '#enable_cart_expiration' ).fadeOut();
                }
            }


            if ( name == 'enable_weekly_report' ) {
                if ( value == 0 ) {
                    $( '#enable_weekly_report' ).fadeIn();
                } else {
                    $( '#enable_weekly_report' ).fadeOut();
                }
            }

            if ( name == 'enable_recaptcha_v3' ) {
                if ( value == 0 ) {
                    $( '#cl_recaptcha_v3' ).fadeIn();
                    $('#cl_recaptcha_v3_btn').css({
                        'pointer-events': '',
                        'opacity': ''
                    }).attr('disabled', false);
                } else {
                    $( '#cl_recaptcha_v3' ).fadeOut();
                }
            }

            if ( name == 'enable_cl_exclude_products' ) {
                if ( value == 0 ) {
                    $( '#enable_excluded_products_section' ).fadeIn();
                } else {
                    $( '#enable_excluded_products_section' ).fadeOut();
                }
            }

            if ( name == 'enable_cl_exclude_categories' ) {
                if ( value == 0 ) {
                    $( '#enable_excluded_categories_section' ).fadeIn();
                } else {
                    $( '#enable_excluded_categories_section' ).fadeOut();
                }
            }

            if ( name == 'enable_cl_exclude_countries' ) {
                if ( value == 0 ) {
                    $( '#enable_excluded_countries_section' ).fadeIn();
                } else {
                    $( '#enable_excluded_countries_section' ).fadeOut();
                }
            }

            var gdpr = $( this ).attr( 'name' );
            if ( gdpr == 'enable_gdpr' ) {
                if ( value == 0 ) {
                    $( '#cl-gdpr-message' ).fadeIn();
                } else {
                    $( '#cl-gdpr-message' ).fadeOut();
                }
            }
        },

        set_smtp_data: function ( e ) {
            e.preventDefault();
            $( '#cl-loader' ).fadeIn();
            $( '#smtp_notice' ).hide();
            var data = $( this ).serialize();
            var data = btoa( data );
            let payload = {
                'data': data,
            };
            wpAjaxHelperRequest( 'set-other-smtp-data', payload )
                .success( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                    $( '#smtp_notice' ).addClass( 'cl-success' ).show();
                    $( '#smtp_notice' ).text( response.message );
                    setTimeout( function () {
                        $( '#smtp_notice' ).removeClass( 'cl-success' ).fadeOut();
                    }, 3000 );
                } )
                .error( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                    $( '#smtp_notice' ).addClass( 'cl-error' ).show();
                    $( '#smtp_notice' ).text( 'Something wrong' );
                    setTimeout( function () {
                        $( '#smtp_notice' ).removeClass( 'cl-error' ).fadeOut();
                    }, 3000 );
                } );
        },

        test_smtp_data: function ( e ) {
            e.preventDefault();
            $( '#cl-loader' ).fadeIn();
            $( '#smtp_test_notice' ).hide();
            var data = $( "[name='cl_test_email']" ).val();

            let payload = {
                'data': data,
            };
            wpAjaxHelperRequest( 'test-smtp-data', payload )
                .success( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                    if ( response.status == 'success' ) {
                        $( '#smtp_test_notice' ).addClass( 'cl-success' ).show();

                    } else {
                        $( '#smtp_test_notice' ).addClass( 'cl-error' ).show();
                    }
                    $( '#smtp_test_notice' ).text( response.message );
                } )
                .error( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                    $( '#smtp_test_notice' ).addClass( 'cl-error' ).show();
                    $( '#smtp_test_notice' ).text( 'Error Occured' );
                } );
        },
    };

    CampaignCopyAction = {
        init: function () {
            $( document ).on( 'click', '.duplicate-control', CampaignCopyAction.campaign_copy_setup );
        },
        campaign_copy_setup: function ( e ) {
            e.preventDefault();
            $( '#cl-loader' ).fadeIn();

            var data = $( this ).attr( 'data-id' );

            let payload = {
                'data': data,
            };
            wpAjaxHelperRequest( 'campaign-copy-setup', payload )
                .success( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                    location.reload();
                } )
                .error( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                } );
        },
    };

    WebhookTestAction = {
        init: function () {
            $( document ).on( 'click', '#trigger_webhook', WebhookTestAction.cl_webhook_test );
        },

        cl_webhook_test: function ( e ) {
            e.preventDefault();
            $( '#cl-loader' ).fadeIn();
            $( '#webhook-notice' ).hide();
            var url = $( '#webhook_url' ).val();
            if ( $.trim( url ) !== "" ) {
                var param = {
                    "name": 'Demo',
                    "email": 'demo@gmail.com',
                    "status": 'abandoned',
                    "cart_total": '$216.00',
                    "provider": 'edd',
                    "product_table": '<div style="margin-bottom: 40px;"> <table class="td" cellspacing="0" cellpadding="6" style="color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;width: 100%;font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif" border="1"> <thead> <tr> <th class="td" scope="col" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;">Product</th> <th class="td" scope="col" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;">Quantity</th> <th class="td" scope="col" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;">Price</th> </tr> </thead> <tbody> <tr class="cart_item"> <td class="td" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;"><img style="width: 40px;height: 40px;border: none;font-size: 14px;font-weight: bold;text-decoration: none;text-transform: capitalize;vertical-align: middle;margin-right: 10px;max-width: 100%;width: 40px;height: 40px;" src="">DEMO 1</td> <td class="td" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;">1</td> <td class="td" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;">&#36;50</td> </tr> <tr class="cart_item"> <td class="td" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;"><img style="width: 40px;height: 40px;border: none;font-size: 14px;font-weight: bold;text-decoration: none;text-transform: capitalize;vertical-align: middle;margin-right: 10px;max-width: 100%;width: 40px;height: 40px;" src="">DEMO 2</td> <td class="td" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;">1</td> <td class="td" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;">&#36;166</td> </tr> </tbody> <tfoot> <tr> <th class="td" scope="row" colspan="2" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;">Total:</th> <td class="td" style="text-align:left; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;">&#36;216.00</td> </tr> </tfoot> </table> </div>',
                };
                $.ajax( {
                    url: url,
                    type: 'POST',
                    data: param,
                    success: function ( res ) {
                        $( '#cl-loader' ).fadeOut();
                        $( '#webhook-notice' ).show();
                        $( '#webhook-notice' ).text( 'Success' );
                    },
                    error: function () {
                        $( '#cl-loader' ).fadeOut();
                        $( '#webhook-notice' ).show();
                        $( '#webhook-notice' ).text( 'Error' );
                    }
                } );
            } else {
                $( '#cl-loader' ).fadeOut();
                $( '#webhook-notice' ).show();
                $( '#webhook-notice' ).text( 'No Data Given' );
                /*console.log( 'no data' );*/
            }
        },
    };


    SMTPSwitcherAction = {
        init: function () {
            $( document ).on( 'click', '.cl-toggle-option-smtp', SMTPSwitcherAction.enable_cl_smtp );
        },

        enable_cl_smtp: function ( e ) {
            $( '#cl-loader' ).fadeIn();
            let $_this = $( this ),
                id = $_this.attr( 'id' ),
                value = $_this.val();
            if ( value == '1' ) {
                $_this.attr( 'data-status', 'no' );
                $_this.val( 0 );
            } else {
                $_this.attr( 'data-status', 'yes' );
                $_this.val( 1 );
            }

            var data = $( this ).val();

            let payload = {
                'data': data,
            };
            wpAjaxHelperRequest( 'enable-cl-smtp', payload )
                .success( function ( response ) {
                    if ( response.data == '1' ) {
                        $( '#smtp-switch' ).fadeIn();
                        $( '#smtp-save-form' ).fadeIn();
                    } else {
                        $( '#smtp-switch' ).fadeOut();
                        $( '#smtp-save-form' ).fadeOut();
                    }
                    /*console.log( response.data );*/
                    $( '#cl-loader' ).fadeOut();
                } )
                .error( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                } );
        },
    };

    ManualRecoveryAction = {
        init: function () {
            $( document ).on( 'click', '#cl_manually_recovered', ManualRecoveryAction.run_manual_recovery );
        },
        run_manual_recovery: function ( e ) {
            let session_id = $( this ).attr( 'data-session-id' );
            let user_email = $( this ).attr( 'data-user-email' );
            let cart_id = $( this ).attr( 'data-cart-id' );
            let payload = {
                session_id: session_id,
                user_email: user_email,
                cart_id: cart_id,
            };
            $( '.cl-recovery-loader' ).fadeIn();
            wpAjaxHelperRequest( 'run-manual-recovery', payload )
            .success( function ( response ) {
                $( '.cl-recovery-loader' ).fadeOut();
                alert(response.message);
            } )
            .error( function ( response ) {
                $( '.cl-recovery-loader' ).fadeOut();
            } );
        }
    };

    RecaptchaAction = {
        init: function () {
            $( document ).on( 'click', '#cl_recaptcha_v3_btn', RecaptchaAction.cl_recaptcha_v3 );
        },
        cl_recaptcha_v3: function ( e ) {
            let recaptcha_enable_status = $("#enable_recaptcha_v3").val();
            let recaptcha_site_key = $("#recaptcha-v3-site-key").val();
            let recaptcha_secret_key = $("#recaptcha-v3-secret-key").val();
            let recaptcha_score = $("#recaptcha-v3-score").val();
            let is_site_key = true;
            let is_secret_key = true;
            let is_score = true;
            if(!recaptcha_site_key ){
                $("#cl-recaptcha-error-message-site-key").show();
                is_site_key = false;
            }else{
                $("#cl-recaptcha-error-message-site-key").hide();
                is_site_key = true;
            }

            if(!recaptcha_secret_key ){
                $("#cl-recaptcha-error-message-secret-key").show();
                is_secret_key = false;
            }else{
                $("#cl-recaptcha-error-message-secret-key").hide();
                is_secret_key = true;
            }

            if(!recaptcha_score ){
                $("#cl-recaptcha-error-message-score").show();
                is_score = false;
            }else{
                $("#cl-recaptcha-error-message-score").hide();
                is_score = true;
            }

            if(recaptcha_score < 0 || recaptcha_score > 1){
                $("#cl-recaptcha-error-message-score").text('The score must be between 0 to 1.');
                $("#cl-recaptcha-error-message-score").show();
                is_score = false;
            }

            if(!is_score || !is_secret_key || !is_site_key){
                return;
            }

            let payload = {
                recaptcha_enable_status: recaptcha_enable_status,
                recaptcha_site_key: recaptcha_site_key,
                recaptcha_secret_key: recaptcha_secret_key,
                recaptcha_score: recaptcha_score,
            };
            console.log('payload', payload);
            $('#cl-loader' ).fadeIn();

            wpAjaxHelperRequest( 'cl-recaptcha-v3', payload )
                .success( function ( response ) {
                    $('#cl-loader' ).fadeOut();
                    $("#recaptcha_v3_settings_notice").addClass( 'cl-success' ).fadeIn();
                    $("#recaptcha_v3_settings_notice").html( response.message )
                    setTimeout(function(){
                        $("#recaptcha_v3_settings_notice").removeClass( 'cl-success' ).fadeOut();
                    }, 3000);
                } )
                .error( function ( response ) {
                    $( '#cl-loader' ).fadeOut();
                } );
        }
    };

    $( document ).ready( function () {
        AnalyticsAction.init();
        CartActions.init();
        AlertModal.init();
        EmailTemplateActions.init();
        GeneralSettingsAction.init();
        CampaignCopyAction.init();
        WebhookTestAction.init();
        OtherSMTPActions.init();
        SMTPSwitcherAction.init();
        ManualRecoveryAction.init();
        RecaptchaAction.init();

        //-------setting panel tab--------
        if($( "#cl-settings-tabs" ).length > 0){

            $("#cl-settings-tabs").tabs({

                activate: function(event, ui) {
                let tabID = ui.newPanel.attr('id');
                let documentationLink = $('.tab-header .documentation a');
                
                if (tabID === 'twilio-sms') {
                    documentationLink.attr('href', 'https://rextheme.com/docs/twilio-sms-service-to-recover-abandoned-carts/');
                } else {
                    documentationLink.attr('href', 'https://rextheme.com/docs/configure-and-use-cart-lift/');
                }
                }
            });
            
        }


        if($( "#smtp-tabs" ).length > 0){
            $( "#smtp-tabs" ).tabs();
        }

        //--------niceSelect--------
        $( '.cl-select' ).niceSelect();
    } );

    $( document ).on( 'change', '#cl-campaign-coupon-unit, #cl-campaign-coupon-frequency', function ( e ) {
        var coupon_expiration_frequency = $( '#cl-campaign-coupon-frequency' ).val();
        var coupon_expiration_frequency_day = $( '#cl-campaign-coupon-unit' ).val();

        coupon_expiration_frequency = parseInt( coupon_expiration_frequency );

        if ( coupon_expiration_frequency < 2 && coupon_expiration_frequency_day === 'hour' ) {

            $( '#coupon_expiration_frequency' ).fadeIn();
            $( '#coupon_expiration_frequency' ).css( 'display', 'flex' );
            setTimeout( function () {
                $( 'input[name=cl_campaign_coupon_frequency]' ).val(2);
                $( '#coupon_expiration_frequency' ).fadeOut();
            }, 3000 );
        }
    } );

    $( document ).on( 'change', '#cl-campaign-enable-twilio', function ( e ) {
        var is_twilio_enabled = $( '#cl-campaign-enable-twilio' ).val();

        if ( is_twilio_enabled == 1 ) {
            $( '#cl-twilio-campaign-body' ).fadeIn();
        } else {

            $( '#cl-twilio-campaign-body' ).fadeOut();
        }
    } );


    $('#cl_excluded_products').select2({
        placeholder: 'Select products',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: select2_object?.ajax_url,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term,
                    action: 'cl_get_products',
                    security: window?.select2_object?.security
                };
            },
            processResults: function (data) {
                var terms = [];
                if (data) {
                    $.each(data, function (id, text) {
                        terms.push({ id: id, text: text });
                    });
                }
                return {
                    results: terms,
                };
            },
            cache: true
        }
    });

    $('#cl_excluded_categories').select2({
        placeholder: 'Select categories',
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: select2_object?.ajax_url,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term,
                    action: 'cl_get_categories',
                    security: window?.select2_object?.security
                };
            },
            processResults: function (data) {
                var terms = [];
                if (data) {
                    $.each(data, function (id, text) {
                        terms.push({ id: id, text: text });
                    });
                }
                return {
                    results: terms,
                };
            },
            cache: true
        }
    });

    $('#cl_excluded_countries').select2({
        placeholder: 'Select countries',
        allowClear: true
    });

})( jQuery );