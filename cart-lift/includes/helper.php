<?php
use function cli\err;

if( !function_exists( 'cl_is_edd_active' ) ) {
    /**
     * check if edd is active
     *
     * @return bool
     */
    function cl_is_edd_active()
    {
        if( is_plugin_active( 'easy-digital-downloads/easy-digital-downloads.php' ) || is_plugin_active( 'easy-digital-downloads-pro/easy-digital-downloads.php' ) ) {
            return true;
        }
        return false;
    }
}


if( !function_exists( 'cl_is_wc_active' ) ) {
    /**
     * check if WC is active
     *
     * @return bool
     */
    function cl_is_wc_active()
    {
        if( class_exists( 'WooCommerce' ) ) {
            return true;
        }
        return false;
    }
}


if ( !function_exists( 'cl_is_lp_active' ) ) {
	/**
	 * check if LearnPress is active
	 *
	 * @return bool
	 */
	function cl_is_lp_active()
	{
		/*$active_plugins = get_option( 'active_plugins' );
		if ( in_array( 'learnpress/learnpress.php', $active_plugins ) ) {
			return true;
		}*/
		return false;
	}
}


if ( !function_exists( 'cl_get_email_template_by_id' ) ) {
	/**
	 * get template by id
	 *
	 * @param $template_id
	 * @return array
	 */
	function cl_get_email_template_by_id( $template_id )
	{
		global $wpdb;
        $cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
        $query                    = 'SELECT * FROM ' . $cl_email_templates_table . ' WHERE id = %d';
        $result                   = $wpdb->get_row( $wpdb->prepare( $query, $template_id ) ); // phpcs:ignore
        $campaign_meta            = unserialize( $result->email_meta );
		foreach ( $campaign_meta as $key => $meta_value ) {
			$result->{$key} = $campaign_meta[ "{$key}" ];
		}
		return $result;
	}
}


if( !function_exists( 'cl_send_email_templates' ) ) {
    /**
     * Send user abandon cart email
     *
     * @param $email_data
     * @param string $provider
     * @param array $payload
     * @param bool $test_email
     * @return bool
     */
    function  cl_send_email_templates( $email_data, $payload = array(), $test_email = false )
    {
        $body_email_preview = '';

        if( $test_email ) {
            $email_data = cl_get_dummy_email_data( $payload );
        }

        if( filter_var( $email_data->email, FILTER_VALIDATE_EMAIL ) ) {
            $email_meta = unserialize( $email_data->email_meta );

            // user information
            $other_fields = unserialize( $email_data->other_fields );

            $user_first_name = ucfirst( $other_fields[ 'first_name' ] );
            $user_last_name  = ucfirst( $other_fields[ 'last_name' ] );
            $user_full_name  = trim( $user_first_name . ' ' . $user_last_name );

            $email_meta_header_txt = '';
            if( isset( $email_meta[ 'email_header_text' ] ) ) {
                $email_meta_header_txt = $email_meta[ 'email_header_text' ];
            }

            $email_meta_header_color = '';
            if( isset( $email_meta[ 'email_header_color' ] ) ) {
                $email_meta_header_color = $email_meta[ 'email_header_color' ];
            }

            $email_meta_checkout_color = '';
            if( isset( $email_meta[ 'email_checkout_color' ] ) ) {
                $email_meta_checkout_color = $email_meta[ 'email_checkout_color' ];
            }
            $email_meta_checkout_text = '';
            if( isset( $email_meta[ 'email_checkout_text' ] ) ) {
                $email_meta_checkout_text = $email_meta[ 'email_checkout_text' ];
            }

            $body_email_preview      .= cart_lift_email_header( $email_meta_header_txt, $email_meta_header_color, false );
            $body_email_preview      .= convert_smilies( wp_unslash( $email_data->email_body ) );
            $body_email_preview_main = $body_email_preview;

            if( $user_first_name ) {
                $body_email_preview = str_replace( '{{customer.firstname}}', $user_first_name, $body_email_preview );
            } else {
                $body_email_preview = str_replace( '{{customer.firstname}}', '', $body_email_preview );
            }

            if( $user_last_name ) {
                $body_email_preview = str_replace( '{{customer.lastname}}', $user_last_name, $body_email_preview );
            } else {
                $body_email_preview = str_replace( '{{customer.lastname}}', '', $body_email_preview );
            }

            if( $user_full_name ) {
                $body_email_preview = str_replace( '{{customer.fullname}}', $user_full_name, $body_email_preview );
            } else {
                $body_email_preview = str_replace( '{{customer.fullname}}', '', $body_email_preview );
            }

            if( $user_first_name == null && $user_last_name == null && $user_full_name == null ) {

                $first_name_position = strpos( $body_email_preview_main, '{{customer.firstname}}' );
                $last_name_position  = strpos( $body_email_preview_main, '{{customer.lastname}}' );
                $full_name_position  = strpos( $body_email_preview_main, '{{customer.fullname}}' );

                $email  = $email_data->email;
                $result = strtok( $email, '@' );

                if( $first_name_position > $last_name_position && $first_name_position > $full_name_position ) {

                    $body_email_preview = str_replace( '{{customer.firstname}}', $result, $body_email_preview_main );
                    $body_email_preview = str_replace( '{{customer.lastname}}', '', $body_email_preview );
                    $body_email_preview = str_replace( '{{customer.fullname}}', '', $body_email_preview );

                } elseif( $last_name_position > $first_name_position && $last_name_position > $full_name_position ) {

                    $body_email_preview = str_replace( '{{customer.firstname}}', '', $body_email_preview_main );
                    $body_email_preview = str_replace( '{{customer.lastname}}', $result, $body_email_preview );
                    $body_email_preview = str_replace( '{{customer.fullname}}', '', $body_email_preview );

                } else {
                    $body_email_preview = str_replace( '{{customer.firstname}}', '', $body_email_preview_main );
                    $body_email_preview = str_replace( '{{customer.lastname}}', '', $body_email_preview );
                    $body_email_preview = str_replace( '{{customer.fullname}}', $result, $body_email_preview );
                }

            }


            // coupon information
            $coupon_code = '';

            if( $email_meta ) {
                if( $email_meta[ 'coupon' ] ) {
                    $generate_coupon   = true;
                    $if_category_exits = false;
                    $scope             = 'product';

                    // pro feature
                    if( apply_filters( 'is_cl_premium', false ) ) {
                        if( isset( $email_meta[ 'conditional_discount' ] ) && $email_meta[ 'conditional_discount' ] ) {
                            $generate_coupon = cl_check_if_product_exists( $email_data->cart_contents, $email_meta );
                            if( !$generate_coupon ) {
                                $generate_coupon   = cl_check_if_category_exists( $email_data->cart_contents, $email_meta );
                                $if_category_exits = true;
                                $scope             = 'category';
                            }
                        }
                    }

                    if( $generate_coupon && !$test_email ) {
                        $coupon_code = cl_generate_coupon_code( $email_data->id, $email_data->campaign_history_id, $email_meta, $email_data->provider, $scope );

                        $body_email_preview = str_replace( '{{cart.coupon_code}}', $coupon_code, $body_email_preview );
                    }

                }

            }

            if( $test_email ) {
                $body_email_preview = str_replace( '{{cart.coupon_code}}', 'CARTLIFT100', $body_email_preview );
            }

            $cart_meta     = unserialize( $email_data->cart_meta );

            // cart actions
            $token_data = array(
                'cl_session_id'     => $email_data->session_id,
                'coupon_code'       => $email_meta[ 'coupon' ] ? $coupon_code : null,
                'coupon_auto_apply' => isset( $email_meta[ 'coupon_auto_apply' ] ) ? 'yes' : 'no',
                'email'             => $email_data->email,
                'first_name'        => isset( $cart_meta[ 'first_name' ] ) ? $cart_meta[ 'first_name' ] : '',
                'last_name'         => isset( $cart_meta[ 'last_name' ] ) ? $cart_meta[ 'last_name' ] : '',
                'phone'             => isset( $cart_meta[ 'phone' ] ) ? $cart_meta[ 'phone' ] : '',
                'country'           => isset( $cart_meta[ 'country' ] ) ? $cart_meta[ 'country' ] : '',
                'address'           => isset( $cart_meta[ 'address' ] ) ? $cart_meta[ 'address' ] : '',
                'city'              => isset( $cart_meta[ 'city' ] ) ? $cart_meta[ 'city' ] : '',
                'postcode'          => isset( $cart_meta[ 'postcode' ] ) ? $cart_meta[ 'postcode' ] : '',
                'provider'          => $email_data->provider,
            );

            $checkout_url = cl_get_checkout_page_url( $email_data->provider );
            $token        = cl_get_data_token( $token_data );
            $checkout_url = add_query_arg( 'cl_token', $token, $checkout_url );

            /**
             * Filter Hook: cl_email_checkout_url
             *
             * Allows developers to modify the checkout URL used in email notifications before sending.
             *
             * This filter hook provides the ability to customize the checkout URL dynamically,
             * which can be useful for various purposes, such as appending query parameters,
             * altering the URL structure, or performing other URL-related customizations.
             *
             * @param string $checkout_url The checkout URL for the email notification.
             * @param string $token        The token associated with the transaction.
             * @param array  $email_data   An array of email data, including recipient and message details.
             *
             * @return string The modified checkout URL.
             * @since 3.1.9
             */
            $checkout_url = apply_filters( 'cl_email_checkout_url', $checkout_url, $token, $email_data );

            if( $email_meta_checkout_color && $email_meta_checkout_text ) {
                $checkout_btn = '<a target="_blank" style="word-wrap:break-word;line-height:100%;text-align:center;text-decoration:underline;background-color:' . $email_meta_checkout_color . ';font-size:16px;text-decoration:none; display: inline-block; padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px;color:#ffffff;letter-spacing:1px;font-weight:bold;" href="' . $checkout_url . '">' . $email_meta_checkout_text . '</a>';
            } elseif( $email_meta_checkout_color ) {

                $checkout_btn = '<a target="_blank" style="word-wrap:break-word;line-height:100%;text-align:center;text-decoration:underline;background-color: ' . $email_meta_checkout_color . ';font-size:16px;text-decoration:none; display: inline-block; padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px;color:#ffffff;letter-spacing:1px;font-weight:bold;" href="' . $checkout_url . '">' . __( 'Checkout', 'cart-lift' ) . '</a>';

            } elseif( $email_meta_checkout_text ) {

                $checkout_btn = '<a target="_blank" style="word-wrap:break-word;line-height:100%;text-align:center;text-decoration:underline;background-color: #6e42d3;font-size:16px;text-decoration:none; display: inline-block; padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px;color:#ffffff;letter-spacing:1px;font-weight:bold;" href="' . $checkout_url . '">' . $email_meta_checkout_text . '</a>';

            } else {
                $checkout_btn = '<a target="_blank" style="word-wrap:break-word;line-height:100%;text-align:center;text-decoration:underline;background-color: #6e42d3;font-size:16px;text-decoration:none; display: inline-block; padding-top:15px;padding-bottom:15px;padding-left:20px;padding-right:20px;color:#ffffff;letter-spacing:1px;font-weight:bold;" href="' . $checkout_url . '">' . __( 'Checkout', 'cart-lift' ) . '</a>';
            }

            $unsubscribe_key     = cl_encrypt_key( $email_data->id );
            $unsubscribe_element = '<a target="_blank" style="color: lightgray" href="' . get_option( 'siteurl' ) . '/?unsubscribe=Yes&unsubscribe_key=' . $unsubscribe_key . '">' . __( 'Unsubscribe', 'cart-lift' ) . '</a>';
            $body_email_preview  = str_replace( '{{cart.unsubscribe}}', $unsubscribe_element, $body_email_preview );
            $body_email_preview  = str_replace( 'http://{{cart.checkout_url}}', '{{cart.checkout_url}}', $body_email_preview );
            $body_email_preview  = str_replace( 'https://{{cart.checkout_url}}', '{{cart.checkout_url}}', $body_email_preview );
            $body_email_preview  = str_replace( '{{cart.checkout_url}}', $checkout_url, $body_email_preview );
            $body_email_preview  = str_replace( '{{cart.checkout_btn}}', $checkout_btn, $body_email_preview );
            $body_email_preview  = str_replace( '{{site.title}}', get_bloginfo(), $body_email_preview );
            $body_email_preview  = str_replace( '{{site.url}}', home_url(), $body_email_preview );


            if( false !== strpos( $body_email_preview, '{{cart.product.names}}' ) ) {
                if( !empty( $email_data->cart_contents ) ) {
                    $body_email_preview = str_replace( '{{cart.product.names}}', cl_get_comma_separated_product_names( $email_data->cart_contents, $email_data->provider, $test_email ), $body_email_preview );

                } else {
                    $body_email_preview = str_replace( '{{cart.product.names}}', '', $body_email_preview );
                }
            }

            // cart contents
            $product_table    = cl_get_email_product_table( $email_data->cart_contents, $email_data->cart_total, $email_data->provider, $test_email, false );
            $related_products = cl_get_email_related_products( $email_data->cart_contents, $email_data->provider, $test_email );

            $body_email_preview = str_replace( '{{cart.product.table}}', $product_table, $body_email_preview );
            $body_email_preview = str_replace( '{{cart.related_products}}', $related_products, $body_email_preview );


            if( !empty( $email_data->cart_contents ) ) {
                $email_subject_product = str_replace( '{{cart.product.names}}', cl_get_comma_separated_product_names( $email_data->cart_contents, $email_data->provider, true ), $email_data->email_subject );
                $email_subject         = $email_subject_product;

                if( $user_first_name != null ) {
                    $email_subject = str_replace( '{{customer.firstname}}', $user_first_name, $email_subject );

                } else {
                    $email_subject = str_replace( '{{customer.firstname}}', '', $email_subject );

                }

                if( $user_last_name != null ) {
                    $email_subject = str_replace( '{{customer.lastname}}', $user_last_name, $email_subject );

                } else {
                    $email_subject = str_replace( '{{customer.lastname}}', '', $email_subject );
                }

                if( $user_full_name != null ) {
                    $email_subject = str_replace( '{{customer.fullname}}', $user_full_name, $email_subject );
                } else {
                    $email_subject = str_replace( '{{customer.fullname}}', '', $email_subject );
                }

                if( $user_first_name == null && $user_last_name == null && $user_full_name == null ) {
                    $first_name_position = strpos( $email_subject_product, '{{customer.firstname}}' );
                    $last_name_position  = strpos( $email_subject_product, '{{customer.lastname}}' );
                    $full_name_position  = strpos( $email_subject_product, '{{customer.fullname}}' );

                    $email  = $email_data->email;
                    $result = strtok( $email, '@' );

                    if( $first_name_position > $last_name_position && $first_name_position > $full_name_position ) {

                        $email_subject = str_replace( '{{customer.firstname}}', $result, $email_subject_product );
                        $email_subject = str_replace( '{{customer.lastname}}', '', $email_subject );
                        $email_subject = str_replace( '{{customer.fullname}}', '', $email_subject );

                    } elseif( $last_name_position > $first_name_position && $last_name_position > $full_name_position ) {

                        $email_subject = str_replace( '{{customer.lastname}}', $result, $email_subject_product );
                        $email_subject = str_replace( '{{customer.fullname}}', '', $email_subject );
                        $email_subject = str_replace( '{{customer.firstname}}', '', $email_subject );

                    } else {

                        $email_subject = str_replace( '{{customer.fullname}}', $result, $email_subject_product );
                        $email_subject = str_replace( '{{customer.lastname}}', '', $email_subject );
                        $email_subject = str_replace( '{{customer.firstname}}', '', $email_subject );
                    }

                }


            } else {
                $email_subject         = str_replace( '{{cart.product.names}}', '', $email_data->email_subject );
                $email_subject_product = str_replace( '{{cart.product.names}}', '', $email_data->email_subject );


                if( $user_first_name ) {
                    $email_subject = str_replace( '{{customer.firstname}}', $user_first_name, $email_subject );
                } else {
                    $email_subject = str_replace( '{{customer.firstname}}', '', $email_subject );
                }

                if( $user_last_name ) {
                    $email_subject = str_replace( '{{customer.lastname}}', $user_last_name, $email_subject );
                } else {
                    $email_subject = str_replace( '{{customer.lastname}}', '', $email_subject );
                }

                if( $user_full_name ) {
                    $email_subject = str_replace( '{{customer.fullname}}', $user_full_name, $email_subject );
                } else {
                    $email_subject = str_replace( '{{customer.fullname}}', '', $email_subject );
                }

                if( !$user_first_name && !$user_last_name && !$user_full_name ) {
                    $fist_name_position = strpos( $email_subject_product, '{{customer.firstname}}' );
                    $last_name_position = strpos( $email_subject_product, '{{customer.lastname}}' );
                    $full_name_position = strpos( $email_subject_product, '{{customer.fullname}}' );


                    $email  = $email_data->email;
                    $result = strtok( $email, '@' );

                    if( $first_name_position > $last_name_position && $first_name_position > $full_name_position ) {

                        $email_subject = str_replace( '{{customer.firstname}}', $result, $email_subject_product );
                        $email_subject = str_replace( '{{customer.lastname}}', '', $email_subject );
                        $email_subject = str_replace( '{{customer.fullname}}', '', $email_subject );

                    } elseif( $last_name_position > $first_name_position && $last_name_position > $full_name_position ) {
                        if ( $email_data->provider === 'edd' ) {
                            $is_mail_sent = EDD()->emails->send( $email_data->email, $email_subject, $body_email_preview );
                        }

                        if ( $email_data->provider === 'lp' ) {
                            $lp_email      = new LP_Email();
                            $email_headers = array( 'Content-Type: text/html; charset=UTF-8' );

                            $is_mail_sent = wp_mail( $email_data->email, $email_subject, $body_email_preview, $email_headers, $lp_email->get_attachments() );
                        }
                        if ( $is_mail_sent ) {
                            return true;
                        }
                    }
                    else {

                        $email_subject = str_replace( '{{customer.fullname}}', $result, $email_subject_product );
                        $email_subject = str_replace( '{{customer.lastname}}', '', $email_subject );
                        $email_subject = str_replace( '{{customer.firstname}}', '', $email_subject );
                    }

                }
            }
            
            $body_email_preview       .= cart_lift_email_footer( false );
            $body_email_preview       = wpautop( $body_email_preview );
            $body_email_final_preview = stripslashes( $body_email_preview );

            if( $email_data->email ) {
                if( $email_data->provider === 'wc' ) {
                    if( !class_exists( 'WC_Email', false ) && defined( 'WC_ABSPATH' ) ) {
                        include_once WC_ABSPATH . 'includes/emails/class-wc-email.php';
                    }
                    $wc_email     = new WC_Email();
                    $is_mail_sent = $wc_email->send( $email_data->email, $email_subject, $body_email_preview, $wc_email->get_headers(), $wc_email->get_attachments() );
                }

                if( $email_data->provider === 'edd' ) {
                    $is_mail_sent = EDD()->emails->send( $email_data->email, $email_subject, $body_email_preview );
                }
                return $is_mail_sent ?? false;
            }
            return false;
        }
        return true;
    }
}


if( !function_exists( 'cl_get_dummy_email_data' ) ) {
    /**
     * Set dummy email data
     *
     * @param $payload
     * @return stdClass
     */
    function cl_get_dummy_email_data( $payload )
    {
        $current_user              = get_current_user();
        $email_data                = new stdClass();
        $email_data->id            = 0;
        $email_data->email         = $payload[ 'send_to' ] ? $payload[ 'send_to' ] : $current_user->user_email;
        $email_data->email_subject = $payload[ 'email_subject' ];
        $email_data->email_body    = $payload[ 'email_body' ];
        $email_data->cart_meta     = isset( $payload[ 'cart_meta' ] ) ? $payload[ 'cart_meta' ] : '' ;

        $type     = 'download';
        $provider = 'edd';
        if( cl_is_wc_active() ) {
            $type     = 'product';
            $provider = 'wc';
        }
        $args            = array(
            'posts_per_page' => 1,
            'orderby'        => 'rand',
            'fields'         => 'ids',
            'post_type'      => $type
        );
        $random_products = new WP_Query ( $args );
        if( $random_products->have_posts() ) {
            while( $random_products->have_posts() ) {
                $random_products->the_post();
                if( $provider === 'wc' ) {
                    $cart_product = new WC_Product( get_the_ID() );
                } elseif( $provider == 'edd' ) {
                    $cart_product = new EDD_Download( get_the_ID() );

                }
                $email_data->cart_total    = $cart_product->get_price();
                $email_data->cart_contents = serialize(
                    array(
                        array(
                            'id'       => get_the_ID(),
                            'quantity' => 1,
                        )
                    )
                );
            }
        }

        $email_data->provider     = $provider;
        $email_data->other_fields = serialize(
            array(
                'first_name' => 'John',
                'last_name'  => 'Doe',
            )
        );
        $email_data->email_meta   = serialize(
            array(
                'discount'             => 1,
                'coupon'               => 'CARTLIFT100',
                'email_header_text'    => $payload[ 'email_header_text' ],
                'email_header_color'   => $payload[ 'email_header_color' ],
                'email_checkout_color' => $payload[ 'email_checkout_color' ],
                'email_checkout_text'  => $payload[ 'email_checkout_text' ],
            )
        );
        $email_data->time         = current_time( CART_LIFT_DATETIME_FORMAT );
        $email_data->session_id   = md5( uniqid( wp_rand(), true ) );
        return $email_data;
    }
}


if( !function_exists( 'cl_check_if_product_exists' ) ) {
    /**
     * check if product id
     * exist in included products
     *
     * @param $cart_contents
     * @param $email_meta
     * @return bool
     */
    function cl_check_if_product_exists( $cart_contents, $email_meta )
    {
        $cart_contents = unserialize( $cart_contents );
        if( isset( $email_meta[ 'coupon_included_products' ] ) ) {
            $product_ids = is_array( $email_meta[ 'coupon_included_products' ] ) ?
                $email_meta[ 'coupon_included_products' ] : unserialize( $email_meta[ 'coupon_included_products' ] );
            foreach( $cart_contents as $cart_content ) {
                if( in_array( $cart_content[ 'id' ], $product_ids ) ) {
                    return true;
                }
            }
        }
        return false;
    }
}


if( !function_exists( 'cl_check_if_category_exists' ) ) {
    /**
     * check if product id
     * exist in included categories
     *
     * @param $cart_contents
     * @param $email_meta
     * @return bool
     */
    function cl_check_if_category_exists( $cart_contents, $email_meta )
    {
        $cart_contents = unserialize( $cart_contents );
        if( isset( $email_meta[ 'coupon_included_products' ] ) ) {
            $cat_ids = is_array( $email_meta[ 'coupon_included_categories' ] ) ?
                $email_meta[ 'coupon_included_categories' ] : unserialize( $email_meta[ 'coupon_included_categories' ] );
            foreach( $cart_contents as $cart_content ) {
                $term_list = wp_get_post_terms( $cart_content[ 'id' ], 'product_cat', array( 'fields' => 'ids' ) );
                if( is_array( $term_list ) && count( array_intersect( $cat_ids, $term_list ) ) > 0 ) {
                    return true;
                }
            }
        }
        return false;
    }
}


if( !function_exists( 'cl_generate_coupon_code' ) ) {


    /**
     * generate coupon code
     *
     * @param $cart_id
     * @param $email_meta
     * @param string $provider
     * @return mixed|void
     */
    function cl_generate_coupon_code( $cart_id, $campaign_history_id, $email_meta, $provider = 'wc', $scope = 'product' )
    {
        global $wpdb;
        $cl_cart_table             = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
        $coupon_code               = wp_generate_password( 10, false, false );

        $expiration_date = '';
        $amount          = $email_meta[ 'amount' ];


        $type        = $email_meta[ 'type' ];
        $frequency   = $email_meta[ 'coupon_frequency' ];
        $unit        = $email_meta[ 'coupon_frequency_unit' ];
        $product_ids = '';
        $cat_ids     = '';

        $is_pro_activate = apply_filters( 'is_cl_pro_active', false );

        // generate coupon code for WC
        if( $provider == 'wc' ) {
            $coupon    = array(
                'post_title'   => $coupon_code,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_author'  => 1,
                'post_type'    => 'shop_coupon'
            );
            $coupon_id = wp_insert_post( $coupon );

            if( isset( $email_meta[ 'conditional_discount' ] ) && $email_meta[ 'conditional_discount' ] != 0 ) {
                if( $scope === 'product' ) {
                    if( isset( $email_meta[ 'coupon_included_products' ] ) ) {
                        $product_ids = is_array( $email_meta[ 'coupon_included_products' ] ) ?
                            $email_meta[ 'coupon_included_products' ] : unserialize( $email_meta[ 'coupon_included_products' ] );
                        if( is_array( $product_ids ) ) {
                            $product_ids = implode( ",", $product_ids );
                        }
                        $amount = $email_meta[ 'coupon_included_products_amount' ];

                    }

                }

                if( $scope === 'category' ) {
                    if( isset( $email_meta[ 'coupon_included_categories' ] ) ) {
                        $cat_ids = is_array( $email_meta[ 'coupon_included_categories' ] ) ?
                            $email_meta[ 'coupon_included_categories' ] : unserialize( $email_meta[ 'coupon_included_categories' ] );
                        $amount  = $email_meta[ 'coupon_included_categories_amount' ];
                    }
                }
            }


            if( $is_pro_activate ) {
                $free_shipping  = isset( $email_meta[ 'free_shipping' ] ) ? ( $email_meta[ 'free_shipping' ] == 1 ? 'yes' : 'no' ) : 'no';
                $individual_use = isset( $email_meta[ 'individual_use' ] ) ? ( $email_meta[ 'individual_use' ] == 1 ? 'yes' : 'no' ) : 'no';
                update_post_meta( $coupon_id, 'individual_use', $individual_use );
                update_post_meta( $coupon_id, 'free_shipping', $free_shipping );
            } else {
                update_post_meta( $coupon_id, 'individual_use', 'no' );
                update_post_meta( $coupon_id, 'free_shipping', 'no' );
            }


            update_post_meta( $coupon_id, 'product_ids', $product_ids );
            update_post_meta( $coupon_id, 'product_categories', $cat_ids );
            update_post_meta( $coupon_id, 'exclude_product_ids', '' );
            update_post_meta( $coupon_id, 'discount_type', $type );
            update_post_meta( $coupon_id, 'coupon_amount', $amount );
            update_post_meta( $coupon_id, 'usage_limit', '1' );
            update_post_meta( $coupon_id, 'apply_before_tax', 'yes' );

            if( $email_meta[ 'coupon_frequency' ] ) {
                $expiration_date = strtotime( current_time( "Y:m:d h:m:s" ) . ' +' . $frequency . ' ' . $unit );
                update_post_meta( $coupon_id, 'date_expires', $expiration_date );
            }
        }

        // generate coupon code for EDD
        if( $provider == 'edd' ) {
            $product_ids = array();
            $frequency   = $email_meta[ 'coupon_frequency' ];
            $unit        = $email_meta[ 'coupon_frequency_unit' ];
            if( $frequency ) {
                $expiration_date = $exp_date = date( 'm/d/Y', strtotime( "+$frequency $unit" ) );;
            }
            $type = $email_meta[ 'type' ];
            if( $email_meta[ 'type' ] == 'flat_rate' ) {
                $type = 'flat';
            }
            if( isset( $email_meta[ 'conditional_discount' ] ) && $email_meta[ 'conditional_discount' ] ) {
                if( isset( $email_meta[ 'coupon_included_products' ] ) ) {
                    $product_ids = is_array( $email_meta[ 'coupon_included_products' ] ) ?
                        $email_meta[ 'coupon_included_products' ] : unserialize( $email_meta[ 'coupon_included_products' ] );
                    $amount      = $email_meta[ 'coupon_included_products_amount' ];
                }
            }

            $details = array(
                'name'          => $coupon_code,
                'code'          => $coupon_code,
                'type'          => $type,
                'status'        => 'active',
                'is_single_use' => 1,
                'amount'        => $amount,
                'expiration'    => $expiration_date,
                'products'      => $product_ids,
            );
            edd_store_discount( $details );
        }

        $wpdb->update(
            $cl_cart_table,
            array(
                'coupon_code' => $coupon_code,
            ),
            array(
                'id' => $cart_id
            )
        );

        $wpdb->update(
            $cl_campaign_history_table,
            array(
                'coupon_code' => $coupon_code,
            ),
            array(
                'id' => $campaign_history_id
            )
        );

        return $coupon_code;
    }
}


if ( !function_exists( 'cl_get_checkout_page_url' ) ) {
	/**
	 * get checkout page url
	 *
	 * @param $data
	 * @param string $provider
	 * @return string
	 */
	function cl_get_checkout_page_url( $provider = 'wc' )
	{
        $checkout_url = get_site_url();
		if ( $provider === 'wc' && cl_is_wc_active() ) {
			$checkout_url = wc_get_checkout_url();
		}
		if ( $provider === 'edd' && cl_is_edd_active() ) {
			$checkout_url = edd_get_checkout_uri();
		}
		if ( $provider === 'lp' && cl_is_lp_active() ) {
			$checkout_url = learn_press_get_checkout_url();
		}
        return esc_url( $checkout_url );
	}
}


if ( !function_exists( 'cl_get_data_token' ) ) {
    /**
     * Get token from  cart data
     * @param $data
     * @return string
     */
	function cl_get_data_token( $data )
	{
        return urlencode( base64_encode( http_build_query( $data ) ) );
	}
}


if ( !function_exists( 'cl_get_comma_separated_product_names' ) ) {
	/**
	 * get comma seperated name of cart products
	 *
	 * @param $contents
	 * @param string $provider
	 * @param bool $random
	 * @return string
	 */
	function cl_get_comma_separated_product_names( $contents, $provider = 'wc', $random = false )
	{
		$cart_comma_string = '';
		$cart_contents     = unserialize( $contents );
		$cart_length       = is_array( $cart_contents ) ? count( $cart_contents ) : 0;
		$index             = 0;
		if ( $random ) {
			$randIndex         = array_rand( $cart_contents );
			$cart_product_name = '';
			if ( $provider === 'wc' && cl_is_wc_active() ) {
				if ( get_post_status( $cart_contents[ $randIndex ][ 'id' ] ) ) {
					$cart_product      = new WC_Product( $cart_contents[ $randIndex ][ 'id' ] );
					$cart_product_name = $cart_product->get_name();
				}
			}
            elseif ( $provider === 'edd' && cl_is_edd_active() ) {
				if ( get_post_status( $cart_contents[ $randIndex ][ 'id' ] ) ) {
					$cart_product      = new EDD_Download( $cart_contents[ $randIndex ][ 'id' ] );
					$cart_product_name = $cart_product->get_name();
				}
			}
            elseif ( $provider === 'lp' && cl_is_lp_active() ) {
				if ( get_post_status( $cart_contents[ $randIndex ][ 'id' ] ) ) {
					$cart_product      = new LP_Course( $cart_contents[ $randIndex ][ 'id' ] );
					$cart_product_name = $cart_product->get_title();
				}
			}
			return $cart_product_name;
		}
		else {
			if ( is_array( $cart_contents ) ) {
				foreach ( $cart_contents as $key => $product ) {
					if ( $provider === 'edd' ) {
						if ( cl_is_edd_active() ) {
							if ( get_post_status( $product[ 'id' ] ) ) {
								$cart_product      = new EDD_Download( $product[ 'id' ] );
								$cart_product_name = $cart_product->get_name();
								$cart_comma_string .= $cart_product_name;
								if ( ( $cart_length - 1 ) != $index ) {
									$cart_comma_string .= ', ';
								}
							}
						}
					}
                    elseif ( $provider === 'wc' ) {
						if ( cl_is_wc_active() ) {
							if ( get_post_status( $product[ 'id' ] ) ) {
								$cart_product      = new WC_Product( $product[ 'id' ] );
								$cart_product_name = $cart_product->get_name();
								$cart_comma_string .= $cart_product_name;
								if ( ( $cart_length - 1 ) != $index ) {
									$cart_comma_string .= ', ';
								}
							}
						}
					}
                    elseif ( $provider === 'lp' ) {
						if ( cl_is_lp_active() ) {
							if ( get_post_status( $product[ 'id' ] ) ) {
								$cart_product      = new LP_Course( $product[ 'id' ] );
								$cart_product_name = $cart_product->get_title();
								$cart_comma_string .= $cart_product_name;
								if ( ( $cart_length - 1 ) != $index ) {
									$cart_comma_string .= ', ';
								}
							}
						}
					}
					$index++;
				}
			}
		}
		return $cart_comma_string;
	}
}


if( !function_exists( 'cl_get_email_product_table' ) ) {
    /**
     * get product table renderer for
     * email
     *
     * @param $cart_contents
     * @param string $provider
     * @param bool $dummy_content
     * @return string
     */
    function cl_get_email_related_products( $cart_contents, $provider = 'wc', $dummy_content = false )
    {
        $cart_contents = unserialize( $cart_contents );
        if( is_array( $cart_contents ) ) {
            $randIndex  = array_rand( $cart_contents );
            $categories = wp_get_post_categories( $cart_contents[ $randIndex ][ 'id' ] );
            $html       = '';
            $args       = array(
                'category__in'        => $categories,
                'post__not_in'        => array( $cart_contents[ $randIndex ][ 'id' ] ),
                'post_type'           => array( 'product', 'download' ),
                'posts_per_page'      => 2,
                'ignore_sticky_posts' => 1
            );
            $related    = get_posts( $args );
            if( $related ) {
                $html .= '<table align="center" cellpadding="0" cellspacing="0" border="0" role="presentation" style="width:100%;margin:0 auto;">';
                $html .= '<tr><td style="font-size:0;">&nbsp;</td><td align="center" style="width:640px;margin:0 auto;text-align: center;vertical-align: top;font-size: 0;background-color:#fffffe;">';
                foreach( $related as $post ) {
                    $html .= '<div align="center" style="width: 213px; display: inline-block; vertical-align: top;" class="val half">';
                    $html .= '<table align="left" cellpadding="0" cellspacing="0" border="0" role="presentation" style="width:200px;border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="full">';
                    $html .= '<tr>';
                    $html .= '<td align="center">';
                    $html .= '<a href="' . get_permalink() . '" target="_blank" style="text-decoration:none;">';
                    $html .= '<img style="border:0;margin:0;text-align:left;display:block;white-space:pre;" width="200" src="' . esc_url( get_the_post_thumbnail_url( $post->ID ) ) . '">';
                    $html .= '</a>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '<tr>';
                    $html .= '<td align="center" style="padding:20px 0;font-family:Helvetica, sans-serif;font-size:14px;"">';
                    $html .= '<p>' . get_the_title( $post->ID ) . '</p>';
                    $html .= '</td>';
                    $html .= '</tr>';
                    $html .= '</table></div>';
                }
                $html .= '</td></tr></table>';
            }
            return $html;
        }
    }
}


if ( !function_exists( 'cl_get_email_product_table' ) ) {
	/**
	 * return table block of email template
	 *
	 * @param $cart_contents
	 * @param $cart_total
	 * @param string $provider
	 * @param bool $dummy_content
	 * @return string
	 */
	function cl_get_email_product_table( $cart_contents, $cart_total, $provider = 'wc', $dummy_content = false, $echo = true )
	{
		$text_align = is_rtl() ? 'right' : 'left';
		$total      = $cart_total;
		if ( $provider === 'wc' && cl_is_wc_active() ) {
			$total = wc_price( $cart_total );
		}
        elseif ( $provider === 'edd' && cl_is_edd_active() ) {
			$total = edd_currency_filter( $cart_total );
		}
        elseif ( $provider === 'lp' && cl_is_lp_active() ) {
			$total = learn_press_format_price( $cart_total );
		}
		ob_start(); ?>

        <div style="margin-bottom: 40px;">
            <table class="td" cellspacing="0" cellpadding="6"
                   style="color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;width: 100%;font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif"
                   border="1">
                <thead>
                <tr>
                    <th class="td" scope="col"
                        style="text-align:<?php
                        echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;"><?php
                        esc_html_e( 'Product', 'cart-lift' ); ?></th>
                    <th class="td" scope="col"
                        style="text-align:<?php
                        echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;"><?php
                        esc_html_e( 'Quantity', 'cart-lift' ); ?></th>
                    <th class="td" scope="col"
                        style="text-align:<?php
                        echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;"><?php
                        esc_html_e( 'Price', 'cart-lift' ); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $cart_items = unserialize( $cart_contents );
                if( !is_array( $cart_items ) || !count( $cart_items ) ) {
                    return;
                }

                if( $provider === 'wc' ) {
                    foreach( $cart_items as $item ) {
                        if( isset( $item[ 'variation_id' ] ) && $item[ 'variation_id' ] > 0 ) {
                            $product = wc_get_product( $item[ 'variation_id' ] );
                        } else {
                            $product = new WC_Product( $item[ 'id' ] );
                        }
                        if( $product && $product->is_in_stock() ) { ?>
                            <tr class='cart_item'>
                                <td class="td"
                                    style="text-align:<?php
                                    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
                                    <img style="width: 40px;height: 40px;border: none;font-size: 14px;font-weight: bold;text-decoration: none;text-transform: capitalize;vertical-align: middle;margin-right: 10px;max-width: 100%;width: 40px;height: 40px;"
                                         src="<?php
                                         echo esc_url( get_the_post_thumbnail_url( $item[ 'id' ] ) ); ?>"><?php
                                    echo $product->get_name(); ?>
                                </td>
                                <td class="td"
                                    style="text-align:<?php
                                    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php
                                    echo $item[ 'quantity' ]; ?></td>
                                <td class="td"
                                    style="text-align:<?php
                                    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php
                                    echo wc_price( $item[ 'quantity' ] * $product->get_price() ); ?></td>
                            </tr>
                            <?php
                        }
                    }
                }
                if( $provider === 'edd' ) {
	                foreach( $cart_items as $item ) {
		                $product = new EDD_Download( $item[ 'id' ] );

		                if ( edd_has_variable_prices( $item[ 'id' ] ) ) {
			                $variable_prices = $product->get_prices();
			                $price_id        = isset( $item[ 'options' ][ 'price_id' ] ) ? $item[ 'options' ][ 'price_id' ] : 1;
			                $name            = $product->get_name() . ' ' . $variable_prices[ $price_id ][ 'name' ];
			                $price           = $variable_prices[ $price_id ][ 'amount' ];
		                }
		                else {
			                $name  = $product->get_name();
			                $price = $product->get_price();
		                }

		                if( $product && $product->can_purchase() ) { ?>
                            <tr class='cart_item'>
                                <td class="td"
                                    style="text-align:<?php
				                    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
                                    <img style="width: 40px;height: 40px;border: none;font-size: 14px;font-weight: bold;text-decoration: none;text-transform: capitalize;vertical-align: middle;margin-right: 10px;max-width: 100%;width: 40px;height: 40px;"
                                         src="<?php
					                     echo esc_url( get_the_post_thumbnail_url( $item[ 'id' ] ) ); ?>"><?php
					                echo $name; ?>
                                </td>
                                <td class="td"
                                    style="text-align:<?php
				                    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php
					                echo $item[ 'quantity' ]; ?></td>
                                <td class="td"
                                    style="text-align:<?php
				                    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php
					                echo edd_currency_filter( $item[ 'quantity' ] * ( float ) $price ); ?></td>
                            </tr>
							<?php
						}
					}
				}
				if ( $provider === 'lp' && cl_is_lp_active() ) {
					foreach ( $cart_items as $item ) {
						$course = learn_press_get_course( $item[ 'id' ] );

						if ( $course->is_in_stock() ) { ?>
                            <tr class='cart_item'>
                                <td class="td"
                                    style="text-align:<?php
								    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
                                    <img style="width: 40px;height: 40px;border: none;font-size: 14px;font-weight: bold;text-decoration: none;text-transform: capitalize;vertical-align: middle;margin-right: 10px;max-width: 100%;width: 40px;height: 40px;"
                                         src="<?php
									     echo esc_url( get_the_post_thumbnail_url( $item[ 'id' ] ) ); ?>"><?php
									echo $course->get_title(); ?>
                                </td>
                                <td class="td"
                                    style="text-align:<?php
								    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php
									echo $item[ 'quantity' ]; ?>
                                </td>
                                <td class="td"
                                    style="text-align:<?php
								    echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php
									echo learn_press_format_price( $item[ 'quantity' ] * $course->get_price() ); ?>
                                </td>
                            </tr>
							<?php
						}
					}
				}

                ?>
                </tbody>
                <tfoot>
                <tr>
                    <th class="td" scope="row" colspan="2"
                        style="text-align:<?php
                        echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;">
                        <?php esc_html_e( 'Total:', 'cart-lift' );?>
                    </th>
                    <td class="td"
                        style="text-align:<?php
                        echo esc_attr( $text_align ); ?>; color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;padding: 12px;"><?php
                        echo $total; ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
        <?php
        $output = ob_get_contents();
        ob_end_clean();
        if( $echo ) {
            echo $output;
        } else {
            return $output;
        }
    }
}


if( !function_exists( 'cl_is_valid_token' ) ) {
    /**
     * check if token is valid
     *
     * @param $token
     * @return bool
     */
    function cl_is_valid_token( $token )
    {
        $token_data = cl_decode_token( $token );
        if( is_array( $token_data ) && array_key_exists( 'cl_session_id', $token_data ) ) {
            global $wpdb;
            $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
            $result        = $wpdb->get_row(
                $wpdb->prepare( 'SELECT * FROM `' . $cl_cart_table . '` WHERE session_id = %s', $token_data[ 'cl_session_id' ] ) // phpcs:ignore
            );
            if( isset( $result ) ) {
                return true;
            }
        }
        return false;
    }
}


if( !function_exists( 'cl_decode_token' ) ) {
    /**
     * decode token data
     *
     * @param $token
     * @return array
     */
    function cl_decode_token( $token )
    {
        $token = sanitize_text_field( $token );
        parse_str( base64_decode( urldecode( $token ) ), $token );
        return $token;
    }
}


if( !function_exists( 'cl_encrypt_key' ) ) {
    /**
     * Encrypt a key with AES
     *
     * @param $key
     * @return string
     */
    function cl_encrypt_key( $key )
    {
        $encrypted_key = Cart_Lift_Aes_Ctr::encrypt( $key, CART_LIFT_SECURITY_KEY, 256 );
        return $encrypted_key;
    }
}


if( !function_exists( 'cl_decrypt_key' ) ) {
    /**
     * Decrypt a key with AES
     *
     * @param $key
     * @return string
     */
    function cl_decrypt_key( $key )
    {
        $encrypted_key = Cart_Lift_Aes_Ctr::decrypt( $key, CART_LIFT_SECURITY_KEY, 256 );
        return $encrypted_key;
    }
}


if( !function_exists( 'cl_get_last_ndays' ) ) {
    function cl_get_last_ndays( $days, $format = 'd/m' )
    {
        $m         = date( "m" );
        $de        = date( "d" );
        $y         = date( "Y" );
        $dateArray = array();
        for( $i = 0; $i <= $days - 1; $i++ ) {
            $dateArray[] = date( $format, mktime( 0, 0, 0, $m, ( $de - $i ), $y ) );
        }
        return array_reverse( $dateArray );
    }
}


if( !function_exists( 'cl_date_range' ) ) {
    function cl_date_range( $first, $last, $step = '+1 day', $output_format = 'd/m/Y' )
    {
        $dates   = array();
        $selected_language = get_locale();
        if( in_array( $selected_language, ['ar', 'he_IL']) ){
            $current = strtotime( cl_arabic_hebrew_to_english_date($first) );
            $last    = strtotime( cl_arabic_hebrew_to_english_date($last) );
        } else{
            $current = strtotime( $first );
            $last    = strtotime( $last );
        }
        while( $current <= $last ) {

            $dates[] = date( $output_format, $current );
            $current = strtotime( $step, $current );
        }

        return $dates;
    }
}


if ( !function_exists( 'cl_get_analytics_data' ) ) {
	/**
	 * @param $range
	 * @param null $start_date
	 * @param null $end_date
	 * @return array
	 */
	function cl_get_analytics_data( $range, $start_date = null, $end_date = null )
	{
		global $wpdb;
		$cl_cart_table             = $wpdb->prefix . CART_LIFT_CART_TABLE;
		$cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;

		$abandoned_array = array();
		$recovered_array = array();
		$level_array     = array();
        $isRTL = 'no';
        
		if ( $range === 'weekly' ) {
			$abandoned_sql   = 'Select SUM(cart_total) as total, count(status) as count, date(time) as time from ' . $cl_cart_table . ' where status = "abandoned" And time >= ( CURDATE() - INTERVAL 7 DAY ) GROUP BY DATE(time)';
			$recovered_sql   = 'Select SUM(cart_total) as total, count(status) as count, date(time) as time from ' . $cl_cart_table . ' where status = "recovered" And time >= ( CURDATE() - INTERVAL 7 DAY ) GROUP BY DATE(time)';
			$statistical_sql = 'Select email, cart_total, status from ' . $cl_cart_table . ' where time >= ( CURDATE() - INTERVAL 7 DAY )';
			$email_sql       = 'Select COUNT(*) from ' . $cl_campaign_history_table . ' WHERE email_sent = 1 AND CAST(schedule_time AS DATE) >= ( CURDATE() - INTERVAL 7 DAY )';
			$date_data       = cl_get_last_ndays( 7, 'Ymd' );
			$level_array     = cl_get_last_ndays( 7, 'M d, Y' );
		}
        elseif ( $range === 'monthly' ) {
			$abandoned_sql   = 'Select SUM(cart_total) as total, count(status) as count, date(time) as time from ' . $cl_cart_table . ' where status = "abandoned" And time >= ( CURDATE() - INTERVAL 30 DAY ) GROUP BY DATE(time)';
			$recovered_sql   = 'Select SUM(cart_total) as total, count(status) as count, date(time) as time from ' . $cl_cart_table . ' where status = "recovered" And time >= ( CURDATE() - INTERVAL 30 DAY ) GROUP BY DATE(time)';
			$statistical_sql = 'Select email, cart_total, status from ' . $cl_cart_table . ' where time >= ( CURDATE() - INTERVAL 30 DAY )';
			$email_sql       = 'Select COUNT(*) from ' . $cl_campaign_history_table . ' WHERE email_sent = 1 AND CAST(schedule_time AS DATE) >= ( CURDATE() - INTERVAL 30 DAY )';
			$date_data       = cl_get_last_ndays( 30, 'Ymd' );
			$level_array     = cl_get_last_ndays( 30, 'M d, Y' );
		}
        elseif ( $range === 'custom' ) {
            $selected_language = get_locale();
            if( in_array( $selected_language, array( 'ar', 'he_IL' ) ) ) {
                $startDate = cl_arabic_hebrew_to_english_date($start_date);
                $endDate   = cl_arabic_hebrew_to_english_date($end_date);
                $isRTL = 'yes';
            } else{
                $startDate       = date( 'Y-m-d', strtotime( $start_date ) );
			    $endDate         = date( 'Y-m-d', strtotime( $end_date ) );
            }
			$abandoned_sql   = 'Select SUM(cart_total) as total, count(status) as count, date(time) as time from ' . $cl_cart_table . ' where status = "abandoned" And CAST(time AS DATE) BETWEEN "' . $startDate . '" AND "' . $endDate . '" GROUP BY DATE(time)';
			$recovered_sql   = 'Select SUM(cart_total) as total, count(status) as count, date(time) as time from ' . $cl_cart_table . ' where status = "recovered" And CAST(time AS DATE) BETWEEN "' . $startDate . '" AND "' . $endDate . '" GROUP BY DATE(time)';
			$statistical_sql = 'Select email, cart_total, status from ' . $cl_cart_table . ' where CAST(time AS DATE) BETWEEN "' . $startDate . '" AND "' . $endDate . '"';
			$email_sql       = 'Select COUNT(*) from ' . $cl_campaign_history_table . ' WHERE email_sent = 1 AND CAST(schedule_time AS DATE) BETWEEN "' . $startDate . '" AND "' . $endDate . '"';
			$date_data       = cl_date_range( $start_date, $end_date, '+1 day', 'Ymd' );
			$level_array     = cl_date_range( $start_date, $end_date, '+1 day', 'M d, Y' );
		}

		$ad_results = $wpdb->get_results( $abandoned_sql );
		$rd_results = $wpdb->get_results( $recovered_sql );


		// get abandoned data for chart
		$temp_data = array();
		foreach ( $ad_results as $result ) {
			$temp_data[ date( 'Ymd', strtotime( $result->time ) ) ] = $result->count;
		}
		foreach ( $date_data as $value ) {
			if ( array_key_exists( (int) $value, $temp_data ) ) {
				$abandoned_array[] = $temp_data[ $value ];
			}
			else {
				$abandoned_array[] = '';
			}
		}

		// get recovered data for chart
		$temp_data = array();
		foreach ( $rd_results as $result ) {
			$temp_data[ date( 'Ymd', strtotime( $result->time ) ) ] = $result->count;
		}
		foreach ( $date_data as $value ) {
			if ( array_key_exists( (int) $value, $temp_data ) ) {
				$recovered_array[] = $temp_data[ $value ];
			}
			else {
				$recovered_array[] = '';
			}
		}

		// get statistical data
        $total_carts             = 0;
        $total_abandoned_cart    = 0;
        $total_abandoned_revenue = 0;
        $total_recovered_cart    = 0;
        $total_recovered_revenue = 0;
        $total_actionable_carts  = 0;
        $abandoned_carts_rate    = 0;
        $recapturable_revenue    = 0;

		$results = $wpdb->get_results( $statistical_sql );
		foreach ( $results as $result ) {
			$total_carts += 1;

			// find abandoned and recovered data
			if ( $result->status === 'abandoned' ) {
				$total_abandoned_cart    += 1;
				$total_abandoned_revenue += $result->cart_total;
				if ( $result->email ) {
					$total_actionable_carts += 1;
				}
			}
            elseif ( $result->status === 'recovered' ) {
				$total_recovered_cart    += 1;
				$total_recovered_revenue += $result->cart_total;

			}
			if ( $result->email ) {
				if ( $result->status === 'abandoned' || $result->status === 'processing' ) {
					$recapturable_revenue += $result->cart_total;
				}
			}

		}

		if ( $total_carts > 0 && $total_abandoned_cart > 0 ) {
			$abandoned_carts_rate = number_format( ( $total_abandoned_cart * 100 ) / $total_carts, 2 );
		}
		$total_email_sent = $wpdb->get_var( $email_sql );

		if ( cl_is_wc_active() ) {
			return array(
				'chart_data'           => array(
					'labels'    => $level_array,
					'abandoned' => $abandoned_array,
					'recovered' => $recovered_array,
				),
				'abandoned'            => array(
					'total'   => $total_abandoned_cart,
					'revenue' => wc_price( $total_abandoned_revenue ),
				),
				'recovered'            => array(
					'total'   => $total_recovered_cart,
					'revenue' => wc_price( $total_recovered_revenue ),
				),
				'actionable_carts'     => $total_actionable_carts,
				'abandoned_carts_rate' => $abandoned_carts_rate . '%',
				'total_email_sent'     => $total_email_sent,
				'recapturable_revenue' => wc_price( $recapturable_revenue ),
                'isRTL'                => $isRTL
			);
		}

		if ( cl_is_edd_active() ) {
			return array(
				'chart_data'           => array(
					'labels'    => $level_array,
					'abandoned' => $abandoned_array,
					'recovered' => $recovered_array,
				),
				'abandoned'            => array(
					'total'   => $total_abandoned_cart,
					'revenue' => edd_currency_filter( $total_abandoned_revenue ),
				),
				'recovered'            => array(
					'total'   => $total_recovered_cart,
					'revenue' => edd_currency_filter( $total_recovered_revenue ),
				),
				'actionable_carts'     => $total_actionable_carts,
				'abandoned_carts_rate' => $abandoned_carts_rate . '%',
				'total_email_sent'     => $total_email_sent,
				'recapturable_revenue' => edd_currency_filter( $recapturable_revenue ),
                'isRTL'                => $isRTL
			);
		}

		if ( cl_is_lp_active() ) {
			return array(
				'chart_data'           => array(
					'labels'    => $level_array,
					'abandoned' => $abandoned_array,
					'recovered' => $recovered_array,
				),
				'abandoned'            => array(
					'total'   => $total_abandoned_cart,
					'revenue' => learn_press_format_price( $total_abandoned_revenue ),
				),
				'recovered'            => array(
					'total'   => $total_recovered_cart,
					'revenue' => learn_press_format_price( $total_recovered_revenue ),
				),
				'actionable_carts'     => $total_actionable_carts,
				'abandoned_carts_rate' => $abandoned_carts_rate . '%',
				'total_email_sent'     => $total_email_sent,
				'recapturable_revenue' => learn_press_format_price( $recapturable_revenue ),
                'isRTL'                => $isRTL
			);
		}
        return array();
	}
}


if( !function_exists( 'cl_get_campaign_data' ) ) {
    function cl_get_campaign_data()
    {
        global $wpdb;
        $cl_cart_table             = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $cl_email_templates_table  = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
        $sql                       = "SELECT e.id, e.template_name, e.email_subject, e.frequency, e.unit, e.active FROM $cl_email_templates_table as e ORDER BY e.unit ASC, e.frequency ASC";
		$results                   = $wpdb->get_results( $sql );

		$data = array();
		foreach ( $results as $result ) {
			$total_email_sql  = "Select COUNT(*) from $cl_campaign_history_table WHERE  email_sent = 1 AND campaign_id='%d'";
			$total_email_sent = $wpdb->get_var( $wpdb->prepare( $total_email_sql, $result->id ) );
			$total_sms_sql  = "Select COUNT(*) from $cl_campaign_history_table WHERE  sms_sent = 1 AND campaign_id='%d'";
			$total_sms_sent = $wpdb->get_var( $wpdb->prepare( $total_sms_sql, $result->id ) );

			if ( is_null( $total_email_sent ) ) {
				$total_email_sent = 0;
			}
			if ( is_null( $total_sms_sent ) ) {
                $total_sms_sent = 0;
			}

			$sql            = "Select COUNT(*) as count, SUM(cart_total) as sum from $cl_cart_table WHERE last_sent_email='%d' AND status='recovered'";
			$recovered_data = $wpdb->get_row( $wpdb->prepare( $sql, $result->id ) );

			$percentage = number_format( $total_email_sent, 2 );
			if ( $total_email_sent > 0 ) {
				$percentage = number_format( ( $recovered_data->count * 100 ) / $total_email_sent, 2 );

			}
			$total_recovered = 0;
			if ( cl_is_wc_active() ) {
				$total_recovered = wc_price( $recovered_data->sum );
			}
            elseif ( cl_is_edd_active() ) {
				$total_recovered = edd_currency_filter( $recovered_data->sum );
			}
            elseif ( cl_is_lp_active() ) {
				$total_recovered = learn_press_format_price( $recovered_data->sum );
			}
			$data[] = array(
				'id'              => $result->id,
				'campaign_name'   => $result->template_name,
				'email_subject'   => $result->email_subject,
				'frequency'       => $result->frequency,
				'unit'            => $result->frequency > 1 ? $result->unit . 's' : $result->unit,
				'active'          => $result->active,
				'total_emails'    => $total_email_sent,
				'total_sms'       => $total_sms_sent,
				'recovered'       => $recovered_data->count,
				'recovered_total' => $total_recovered,
				'percentage'      => $percentage . '%',
			);
		}
		return $data;
	}
}


if( !function_exists( 'cl_get_total_email_template' ) ) {
    /**
     * Get number of campaigns
     *
     * @return string
     */
    function cl_get_total_email_template()
    {
        global $wpdb;
        $cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
        $total_email_template     = $wpdb->get_var( "SELECT COUNT(*) FROM $cl_email_templates_table" );
        return $total_email_template;
    }
}


if( !function_exists( 'cl_get_cart_data' ) ) {
    /**
     * @param $offset
     * @param $per_page
     * @param string $email
     * @return array
     */
    function cl_get_cart_data( $offset, $per_page, $search_query )
    {
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $cart_data     = array();
        $data          = array();
        $total_rows    = $wpdb->get_var( "SELECT COUNT(*) FROM {$cl_cart_table} " );
        $total_pages   = ceil( $total_rows / $per_page );
        $limit         = '';

        $where     = array();
        $where_str = '';

        $show_pagination = true;
        if( $search_query[ 's' ] || $search_query[ 'status' ] ) {

            $where[] = isset( $search_query[ 's' ] ) ? ( $search_query[ 's' ] ? "email LIKE '%" . $search_query[ 's' ] . "%' " : '' ) : '';
            $where[] = isset( $search_query[ 'status' ] ) ? ( $search_query[ 'status' ] ? "status = '" . $search_query[ 'status' ] . "' " : '' ) : '';
            $where   = array_filter( $where );
            if( !empty( $where ) ) {
                $where_str = " WHERE ";
                $where_str .= implode( ' AND ', $where );
            }
            $show_pagination = false;
        } else {
            $limit = " LIMIT $offset, $per_page";
        }

        $results = $wpdb->get_results( "SELECT * FROM {$cl_cart_table} $where_str ORDER BY time DESC $limit" );

        foreach( $results as $result ) {
            $products      = array();
            $provider      = $result->provider;
            $cart_contents = $result->cart_contents ? unserialize( $result->cart_contents ) : array();
            if( $cart_contents ) {
                foreach( $cart_contents as $item ) {
                    $id = !empty( $item[ 'variation_id' ] ) ? $item[ 'variation_id' ] : $item[ 'id' ];
                    if( 'wc' === $provider && cl_is_wc_active() ) {
                        $product = wc_get_product( $id );
                        if ( $product && $product->is_in_stock() ) {
                            $product_price = $product->get_price();
                            $product_price = is_numeric( $product_price ) ? (float)$product_price : 0;
                            $quantity      = !empty( $item[ 'quantity' ] ) && is_numeric( $item[ 'quantity' ] ) ? (int)$item[ 'quantity' ] : 0;
                            $products[]    = [
                                'id'             => $id,
                                'name'           => $product->get_name(),
                                'featured_image' => get_the_post_thumbnail_url( $id, 'thumbnail' ) ? get_the_post_thumbnail_url( $id, 'thumbnail' ) : CART_LIFT_URL . 'admin/images/logo-placeholder.png',
                                'price'          => wc_price( $quantity * $product_price ),
                                'quantity'       => $quantity
                            ];
                        }
                    }

                    if( 'edd' === $provider && cl_is_edd_active() ) {
                        $product = new EDD_Download( $item[ 'id' ] );

                        if ( edd_has_variable_prices( $item['id'] ) ) {
                            $variable_prices = $product->get_prices();
                            $price_id = isset( $item[ 'options' ][ 'price_id' ] ) ? $item[ 'options' ][ 'price_id' ] : 1;
                            $price = $variable_prices[ $price_id ][ 'amount' ];
                            $name = $product->get_name() . ' ' . $variable_prices[ $price_id ][ 'name' ];
                        }
                        else {
                            $price = $product->get_price();
                            $name = $product->get_name();
                        }

                        if( $product && $product->can_purchase() ) {
                            $products[] = array(
                                'id'             => $id,
                                'name'           => $name,
                                'featured_image' => get_the_post_thumbnail_url( $item[ 'id' ], 'thumbnail' ) ? get_the_post_thumbnail_url( $item[ 'id' ], 'thumbnail' ) : CART_LIFT_URL . 'admin/images/logo-placeholder.png',
                                'price'          => edd_currency_filter( $item[ 'quantity' ] * ( float ) $price ),
                                'quantity'       => $item[ 'quantity' ],
                            );
                        }
                    }
                }
            }


            $user_fullname = __( 'NOT FOUND', 'cart-lift' );
            $phone         = __( 'NOT FOUND', 'cart-lift' );
	        $ip            = __( 'UNKNOWN', 'cart-lift' );
            $first_name    = '';
            $last_name     = '';
	        if ( $result->cart_meta ) {
		        $cart_meta     = $result->cart_meta ? unserialize( $result->cart_meta ) : array(
			        'first_name' => '',
			        'last_name'  => '',
			        'ip'         => '',
		        );
		        $first_name    = isset( $cart_meta[ 'first_name' ] ) ? $cart_meta[ 'first_name' ] : $first_name;
		        $last_name     = isset( $cart_meta[ 'last_name' ] ) ? $cart_meta[ 'last_name' ] : $last_name;
		        $user_fullname = $first_name . ' ' . $last_name;
		        $user_fullname = $user_fullname !== '' ? $user_fullname : __( 'NOT FOUND', 'cart-lift' );
		        $phone         =  isset( $cart_meta[ 'phone' ] ) && $cart_meta[ 'phone' ] !== '' ? $cart_meta[ 'phone' ] : __( 'NOT FOUND', 'cart-lift' );
		        $ip            = isset( $cart_meta[ 'ip' ] ) ? $cart_meta[ 'ip' ] : $ip;
	        }

            $cart_total = 0;
            if( cl_is_wc_active() ) {
                $cart_total = wc_price( $result->cart_total );
            } else {
                $cart_total = edd_currency_filter( $result->cart_total );
            }
            $format = get_option( 'time_format' ) . ', ' . get_option( 'date_format' );
            $time   = date( $format, strtotime( $result->time ) );

            $data[] = array(
                'id'           => $result->id,
                'session_id'   => $result->session_id,
                'products'     => $products,
                'email'        => $result->email,
                'cart_meta'    => !empty( $result->cart_meta  ) ? unserialize( $result->cart_meta ) : array(),
                'user_name'    => $user_fullname,
                'cart_total'   => $cart_total,
                'status'       => $result->status ?? 'processing',
                'schedular_status' => $result->schedular_status ?? 'active',
                'time'         => $time,
                'unsubscribed' => $result->unsubscribed ?? 0,
                'phone'        => $phone,
                'ip'           => $ip,
            );
        }
        return array(
            'cart_data'   => $data,
            'total_pages' => $total_pages,
            'pagination'  => $show_pagination,
        );
    }
}


if( !function_exists( 'cl_get_scheduled_email' ) ) {
    /**
     * @param $session_id
     * @return array
     */
    function cl_get_scheduled_logs( $session_id )
    {
        global $wpdb;
        $cl_email_templates_table  = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
        $result                    = $wpdb->get_results( $wpdb->prepare( "Select * from $cl_campaign_history_table as h INNER JOIN $cl_email_templates_table as c on h.campaign_id = c.id WHERE h.session_id='%s' AND h.email_sent <> -1 ORDER BY h.schedule_time ASC", $session_id ) );
        $schedule_email            = [];
        foreach( $result as $item ) {
            $schedule_email[] = array(
                'id'            => $item->id,
                'template_name' => $item->template_name,
                'campaign_id'   => $item->campaign_id,
                'session_id'    => $item->session_id,
                'twilio'        => $item->twilio_sms,
                'email_sent'    => $item->email_sent,
                'sms_sent'      => $item->sms_sent,
                'schedule_time' => $item->schedule_time,
            );
        }
        
        return $schedule_email;
    }
}


if( !function_exists( 'cl_time_elapsed_string' ) ) {
    /**
     * @param $datetime
     * @param bool $full
     * @return string
     * @throws Exception
     * @link https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
     */
    function cl_time_elapsed_string( $datetime, $log_sent = 1, $full = false )
    {
        $now  = new DateTime( current_time( CART_LIFT_DATETIME_FORMAT ) );
        $ago  = new DateTime( $datetime );
        $diff = $now->diff( $ago );

        $text = 'ago';
        if( $ago > $now ) {
            $text = 'left';
        }

        $diff->w = floor( $diff->d / 7 );
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach( $string as $k => &$v ) {
            if( $diff->$k ) {
                $v = $diff->$k . ' ' . $v . ( $diff->$k > 1 ? 's' : '' );
            } else {
                unset( $string[ $k ] );
            }
        }

        if( !$full ) $string = array_slice( $string, 0, 1 );
        $status = $string ? implode( ', ', $string ) . ' ' . $text : 'just now';

        return $text === 'ago' && (int)$log_sent !== 1 ? 'SMS Sending Failed' : $status;
    }
}


if( !function_exists( 'cl_email_template' ) ) {
    /**
     * @param string $type
     * @param array notice_info
     * @return string
     */
    function cl_email_template( $type, $notice_info, $notice_type = 'abandoned' )
    {
        $html = '';
        if( $type == 'admin' ) {
            if( $notice_type === 'abandoned' ) {
                $html = '
                  <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); border-radius: 3px;">
                      <tbody>
                          <tr>
                              <td align="center" valign="top">
                              <!-- Header -->
                                  <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color: #96588a; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;">
                                      <tbody>
                                          <tr>
                                            <td id="header_wrapper" style="padding: 36px 48px; display: block; background-color: #6e41d3;">
                                                <h1 style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1; color: #ffffff;">Cart Lift Abandoned Notice</h1>
                                            </td>
                                          </tr>
                                      </tbody>
                                  </table>
                              <!-- End Header -->
                              </td>
                          </tr>

                          <tr>
                              <td align="center" valign="top">
                              <!-- Body -->
                                  <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                      <tbody>
                                          <tr>
                                              <td valign="top" id="body_content" style="background-color: #ffffff;">
                                              <!-- Content -->
                                                  <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tbody>
                                                      <tr>
                                                        <td valign="top" style="padding: 48px 48px 32px;">' . $notice_info . '

                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                              <!-- End Content -->
                                              </td>
                                          </tr>
                                          <!-- Footer-->
                                          <tr>
                                              <td colspan="2" valign="middle" id="credit" style="border-radius: 6px; border: 0; color: #8a8a8a; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 12px; line-height: 150%; text-align: center; padding: 24px 0;">
                                                  <p style="margin: 0 0 16px;">Cart Lift Abandoned Cart</p>
                                              </td>
                                          </tr>
                                          <!-- Footer-->
                                      </tbody>
                                 </table>
                              <!-- End Body -->
                              </td>
                          </tr>
                      </tbody>
                  </table>
                  ';
            } else {
                $html = '
                  <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); border-radius: 3px;">
                      <tbody>
                          <tr>
                              <td align="center" valign="top">
                              <!-- Header -->
                                  <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color: #96588a; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;">
                                      <tbody>
                                          <tr>
                                            <td id="header_wrapper" style="padding: 36px 48px; display: block; background-color: #6e41d3;">
                                                <h1 style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1; color: #ffffff;">Cart Lift Recovered Notice</h1>
                                            </td>
                                          </tr>
                                      </tbody>
                                  </table>
                              <!-- End Header -->
                              </td>
                          </tr>

                          <tr>
                              <td align="center" valign="top">
                              <!-- Body -->
                                  <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                      <tbody>
                                          <tr>
                                              <td valign="top" id="body_content" style="background-color: #ffffff;">
                                              <!-- Content -->
                                                  <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                    <tbody>
                                                      <tr>
                                                        <td valign="top" style="padding: 48px 48px 32px;">' . $notice_info . '

                                                        </td>
                                                      </tr>
                                                    </tbody>
                                                  </table>
                                              <!-- End Content -->
                                              </td>
                                          </tr>
                                          <!-- Footer-->
                                          <tr>
                                              <td colspan="2" valign="middle" id="credit" style="border-radius: 6px; border: 0; color: #8a8a8a; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 12px; line-height: 150%; text-align: center; padding: 24px 0;">
                                                  <p style="margin: 0 0 16px;">Cart Lift</p>
                                              </td>
                                          </tr>
                                          <!-- Footer-->
                                      </tbody>
                                 </table>
                              <!-- End Body -->
                              </td>
                          </tr>
                      </tbody>
                  </table>
                  ';
            }
            return $html;
        } else {
            // $cart_page = $notice_info['page'];
            // $cart_amount = $notice_info['amount'];

            $html = '
              <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: #ffffff; border: 1px solid #dedede; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); border-radius: 3px;">
                  <tbody>
                      <tr>
                          <td align="center" valign="top">
                          <!-- Header -->
                              <table border="0" cellpadding="0" cellspacing="0" width="100%" id="template_header" style="background-color: #96588a; color: #ffffff; border-bottom: 0; font-weight: bold; line-height: 100%; vertical-align: middle; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-radius: 3px 3px 0 0;">
                                  <tbody>
                                      <tr>
                                        <td id="header_wrapper" style="padding: 36px 48px; display: block; background-color: #6e41d3;">
                                            <h1 style="font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1; color: #ffffff;">Please Reconsider Your Cart</h1>
                                        </td>
                                      </tr>
                                  </tbody>
                              </table>
                          <!-- End Header -->
                          </td>
                      </tr>

                      <tr>
                          <td align="center" valign="top">
                          <!-- Body -->
                              <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_body">
                                  <tbody>
                                      <tr>
                                          <td valign="top" id="body_content" style="background-color: #ffffff;">
                                          <!-- Content -->
                                              <table border="0" cellpadding="20" cellspacing="0" width="100%">
                                                <tbody>
                                                  <tr>
                                                    <td valign="top" style="padding: 48px 48px 32px;">
                                                      <div id="body_content_inner" style="color: #636363; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 14px; line-height: 150%; text-align: left;">
                                                          <p style="margin: 0 0 16px;">You have an active cart. You may reconsider purchasing your products. From the link below</p>
                                                          ' . $notice_info . '
                                                      </div>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                          <!-- End Content -->
                                          </td>
                                      </tr>
                                      <!-- Footer-->
                                      <tr>
                                          <td colspan="2" valign="middle" id="credit" style="border-radius: 6px; border: 0; color: #8a8a8a; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 12px; line-height: 150%; text-align: center; padding: 24px 0;">
                                              <p style="margin: 0 0 16px;">Cart Lift Abandoned Cart</p>
                                          </td>
                                      </tr>
                                      <!-- Footer-->
                                  </tbody>
                             </table>
                          <!-- End Body -->
                          </td>
                      </tr>
                  </tbody>
              </table>
              ';
            return $html;
        }
    }
}


if( !function_exists( 'cl_get_general_settings_data' ) ) {
    /**
     * get the general settings
     * data
     *
     * @param $key
     * @return string
     */
    function cl_get_general_settings_data( $key )
    {
        $default_general_settings = apply_filters(
            'cl_default_general_settings', array(
                'cart_tracking'          => 1,
                'remove_carts_for_guest' => 0,
                'disable_purchased_products_campaign' => 0,
                'notify_abandon_cart'    => 0,
                'notify_recovered_cart'  => 0,
                'manually_recovered_cart' => 0,
                'enable_smtp'            => 0,
                'enable_webhook'         => 0,
                'enable_weekly_report'   => 0,
                'cart_webhook'           => '',
                'weekly_report_start_day' => '',
				'weekly_report_email'    => '',
                'weekly_report_email_from' => '',
                'weekly_report_email_body' => __('Please find the attached weekly report', 'cart-lift'),
                'enable_gdpr'            => 1,
                'gdpr_text'              => __( 'Your email address will help us support your shopping experience throughout the site. Please check our Privacy Policy to see how we use your personal data.', 'cart-lift' ),
                'disable_branding'       => 0,
                'cart_expiration_time'   => 30,
                'cart_abandonment_time'  => 15,
                'recovered'              => 0,
                'abandoned'              => 0,
                'completed'              => 0,
                'enable_cart_expiration'   => 0,
                'enable_cl_exclude_products' =>0,
                'enable_cl_exclude_categories' =>0,
                'enable_cl_exclude_countries' =>0,
            )
        );
        $general_settings = get_option( 'cl_general_settings', $default_general_settings );
        return isset( $general_settings[ $key ] ) ? $general_settings[ $key ] : '';
    }
}


if( !function_exists( 'cl_trigger_webhook' ) ) {
    /**
     * Send webhook.
     * @param $data
     */
    function cl_trigger_webhook( $data )
    {
        $url    = cl_get_general_settings_data( 'cart_webhook' );
        $params = http_build_query( $data );
        wp_remote_post(
            $url,
            array(
                'body'        => $params,
                'timeout'     => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(),
                'cookies'     => array(),
            )
        );
    }
}


if( !function_exists( 'cl_restricted_users' ) ) {
    /**
     * Send webhook.
     *
     * @param $user
     * @return bool
     */
    function cl_restricted_users( $user )
    {
        $general = get_option( 'cl_general_settings' );
        if( array_key_exists( $user, $general ) ) {
            if( $general[ $user ] == 1 ) {
                return true;
            }
        }
        return false;
    }
}


if( !function_exists( 'cl_get_scheduled_log_data' ) ) {

    /**
     * Gets cart data for sending emails/sms
     * @param $scheduled_logs
     * @return stdClass
     */
    function cl_get_scheduled_log_data( $scheduled_logs )
    {
        $user                                = get_user_by( 'email', $scheduled_logs->email );
        $scheduled_data                      = new stdClass();
        $scheduled_data->campaign_history_id = isset( $scheduled_logs->id ) ? $scheduled_logs->id : '';
        $scheduled_data->id                  = isset( $scheduled_logs->cart_id ) ? $scheduled_logs->cart_id : '';
        $scheduled_data->email               = isset( $scheduled_logs->email ) ? $scheduled_logs->email : '';
        $scheduled_data->session_id          = isset( $scheduled_logs->session_id ) ? $scheduled_logs->session_id : '';
        $scheduled_data->cart_contents       = isset( $scheduled_logs->cart_contents ) ? $scheduled_logs->cart_contents : '';
        $scheduled_data->cart_total          = isset( $scheduled_logs->cart_total ) ? $scheduled_logs->cart_total : '';
        $scheduled_data->provider            = isset( $scheduled_logs->provider ) ? $scheduled_logs->provider : '';
        $scheduled_data->time                = isset( $scheduled_logs->time ) ? $scheduled_logs->time : '';
        $scheduled_data->status              = isset( $scheduled_logs->status ) ? $scheduled_logs->status : '';
        $scheduled_data->twilio_sms          = isset( $scheduled_logs->twilio_sms ) ? $scheduled_logs->twilio_sms : '';
        $scheduled_data->twilio_sms_body     = isset( $scheduled_logs->twilio_sms_body ) ? $scheduled_logs->twilio_sms_body : '';

        $first_name = '';
        $last_name  = '';
        if ( $user ) {
            if ( $user->first_name && $user->last_name ) {
                $first_name = $user->first_name;
                $last_name  = $user->last_name;
            }
            else {
                $cart_meta  = $scheduled_logs->cart_meta ? unserialize( $scheduled_logs->cart_meta ) : array(
                    'first_name' => '',
                    'last_name'  => '',
                );
                $first_name = $cart_meta[ 'first_name' ];
                $last_name  = $cart_meta[ 'last_name' ];
            }
        }
        else {
            $cart_meta  = $scheduled_logs->cart_meta ? unserialize( $scheduled_logs->cart_meta ) : array(
                'first_name' => '',
                'last_name'  => '',
            );
            $first_name = $cart_meta[ 'first_name' ];
            $last_name  = $cart_meta[ 'last_name' ];
        }

        $scheduled_data->other_fields = serialize(
            array(
                'first_name' => $first_name,
                'last_name'  => $last_name,
            )
        );

        $scheduled_data->email_subject   = isset( $scheduled_logs->email_subject ) ? stripslashes( $scheduled_logs->email_subject ) : '';
        $scheduled_data->email_body      = isset( $scheduled_logs->email_body ) ? $scheduled_logs->email_body : '';
        $scheduled_data->email_meta      = isset( $scheduled_logs->email_meta ) ? $scheduled_logs->email_meta : '';
        $scheduled_data->cart_meta       = isset( $scheduled_logs->cart_meta ) ? $scheduled_logs->cart_meta : '';
        $scheduled_data->twilio_sms      = isset( $scheduled_logs->twilio_sms ) ? $scheduled_logs->twilio_sms : '';
        $scheduled_data->twilio_sms_body = isset( $scheduled_logs->twilio_sms_body ) ? $scheduled_logs->twilio_sms_body : '';
        $scheduled_data->provider        = isset( $scheduled_logs->provider ) ? $scheduled_logs->provider : '';

        return $scheduled_data;
    }
}


if( !function_exists( 'cl_is_twilio_enabled' ) ) {

    /**
     * Check if Twilio is enabled in settings options.
     * @return int|mixed
     */
    function cl_is_twilio_enabled()
    {
        $twilio_settings  = get_option( 'cl_twilio_settings' );
        return isset( $twilio_settings[ 'enabler' ] ) ? $twilio_settings[ 'enabler' ] : 0;
    }
}


if( !function_exists( 'cl_get_twilio_settings_data' ) ) {

    /**
     * Gets Twilio data saved in settings menu.
     * @return array
     */
    function cl_get_twilio_settings_data()
    {
        $twilio_settings = get_option( 'cl_twilio_settings' );
        return array(
                'twilio_account_sid' => isset( $twilio_settings[ 'twilio_account_sid' ] ) ? $twilio_settings[ 'twilio_account_sid' ] : '',
                'twilio_auth_token' => isset( $twilio_settings[ 'twilio_auth_token' ] ) ? $twilio_settings[ 'twilio_auth_token' ] : '',
                'twilio_mobile_number' => isset( $twilio_settings[ 'twilio_mobile_number' ] ) ? $twilio_settings[ 'twilio_mobile_number' ] : ''
        );
    }
}


if( !function_exists( 'cl_get_orders_by_email' ) ) {

    /**
     * Gets all orders by user's email
     *
     * @param $email
     * @param $provider
     * @return array|false|stdClass|WC_Order[]|WP_Post[]
     */
    function cl_get_orders_by_email( $email, $provider, $cart_added_time = '' )
    {
        $cart_added_time = strtotime( $cart_added_time );

        if ( $provider === 'wc' ) {
            $wc_orders = array();
            $args = array(
                'parent' => null,
                'customer' => '',
                'email' => $email,
                'exclude' => array(),
                'orderby' => 'date',
                'order' => 'DESC',
                'return' => 'objects',
                'paginate' => false,
                'date_before' => '',
                'date_after' => '',
            );
            $orders = wc_get_orders( $args );
            foreach ( $orders as $order ) {
                $ordered_date = $order->get_date_created();
                $format = get_option( 'date_format' ) . ' ' . get_option( 'time_format' );
                $ordered_date = strtotime( $ordered_date->date( $format ) );

                if ( $ordered_date >= $cart_added_time || $ordered_date <= strtotime( time() ) ) {
                    $wc_orders[] = $order;
                }
            }
            return $wc_orders;
        }
        elseif ( $provider === 'edd' ) {
            $customer = new EDD_Customer( $email );
            $payments = $customer->get_payments();

            foreach ( $payments as $payment ) {
                $payment_date = EDD()->utils->date( $payment->date, wp_timezone_string(), true );
                $payment_date = isset( $payment_date->date ) ? strtotime( $payment_date->date ) : false;

                if ( $payment_date && $payment_date >= $cart_added_time || $payment_date <= strtotime( time() ) ) {
                    $downloads = $payment->downloads;
                    foreach ( $downloads as $download ) {
                        $purchased_ids[] = $download[ 'id' ];
                    }
                }
            }
            return array_unique( $purchased_ids );
        }
        return array();
    }
}


if( !function_exists( 'cl_check_if_product_is_ordered' ) ) {

    /**
     * Check if the products are already placed order.
     *
     * @param $orders
     * @param $abandoned_cart
     * @param $provider
     * @return bool
     */
    function cl_check_if_product_is_ordered( $orders, $abandoned_cart, $provider )
    {
        $abandoned_cart = unserialize( $abandoned_cart );
        $abandoned_ids = array();
        $order_ids = array();

        foreach ( $abandoned_cart as $data ) {
            $abandoned_ids[] = $data[ 'id' ];
        }

        if ( $provider === 'wc' ) {
            foreach ( $orders as $order ) {
                $order_items = $order->get_items();
                foreach ( $order_items as $item ) {
                    $order_ids[] = $item->get_product_id();
                }
                $common_items = is_array( $order_ids ) ? array_intersect( $abandoned_ids, $order_ids ) : 0;
                return count( $common_items ) > 0;
            }
        }
        elseif ( $provider === 'edd' ) {
            $common_items = is_array( $orders ) ? array_intersect( $abandoned_ids, $orders ) : 0;
            return count( $common_items ) > 0;
        }
        return false;
    }
}


if( !function_exists( 'cl_get_general_settings' ) ) {

    /**
     * Gets Cart-Lift General Settings
     *
     * @return false|mixed|void
     */
    function cl_get_general_settings()
    {
        $default_general_settings = apply_filters(
            'cl_default_general_settings', array(
            'cart_tracking'          => 1,
            'remove_carts_for_guest' => 1,
            'disable_purchased_products_campaign' => 0,
            'notify_abandon_cart'    => 0,
            'notify_recovered_cart'  => 0,
            'manually_recovered_cart' => 0,
            'enable_smtp'            => 0,
            'enable_webhook'         => 0,
            'enable_weekly_report'   => 0,
            'cart_webhook'           => '',
            'weekly_report_start_day' => '',
			'weekly_report_email'    => '',
            'weekly_report_email_from' => '',
            'weekly_report_email_body' => __('Please find the attached weekly report', 'cart-lift'),
            'enable_gdpr'            => 1,
            'gdpr_text'              => __( 'Your email address will help us support your shopping experience throughout the site. Please check our Privacy Policy to see how we use your personal data.', 'cart-lift' ),
            'disable_branding'       => 0,
            'cart_expiration_time'   => 30,
            'cart_abandonment_time'  => 15,
        ) );
        return get_option( 'cl_general_settings', $default_general_settings );
    }
}


if( !function_exists( 'cl_is_paddle_gateway_active' ) ) {
    /**
     * Checks if paddle gateway is active.
     * @return bool
     */
    function cl_is_paddle_gateway_active()
    {
        $paddle_status = false;
        if( function_exists( 'edd_is_gateway_active' ) && !$paddle_status ) {
            $paddle_status = edd_is_gateway_active( 'smartpay_paddle' );
        }
        if( class_exists( 'WC' ) && function_exists( 'get_available_payment_gateways' ) && !$paddle_status ) {
            $wc_gateways = WC()->payment_gateways->get_available_payment_gateways();
            $paddle_status = isset( $wc_gateways[ 'smartpay_paddle' ] );
        }
        return $paddle_status;
    }
}

function edd_is_ajax_disabledsdsad()
{
    return true;
}

//add_filter('edd_is_ajax_disabled', 'edd_is_ajax_disabledsdsad');

function cl_arabic_hebrew_to_english_date($dateString) {
    // Define arrays mapping short forms of Arabic and Hebrew month names to English
    $arabicMonths = array(
        'يناير' => 'Jan',
        'فبراير' => 'Feb',
        'مارس' => 'Mar',
        'أبريل' => 'Apr',
        'مايو' => 'May',
        'يونيو' => 'Jun',
        'يوليو' => 'Jul',
        'أغسطس' => 'Aug',
        'سبتمبر' => 'Sep',
        'أكتوبر' => 'Oct',
        'نوفمبر' => 'Nov',
        'ديسمبر' => 'Dec'
    );

    $hebrewMonths = array(
        'ינו' => 'Jan',
        'פבר' => 'Feb',
        'מרץ' => 'Mar',
        'אפר' => 'Apr',
        'מאי' => 'May',
        'יונ' => 'Jun',
        'יול' => 'Jul',
        'אוג' => 'Aug',
        'ספט' => 'Sep',
        'אוק' => 'Oct',
        'נוב' => 'Nov',
        'דצמ' => 'Dec'
    );

    $selected_language = get_locale();

    if( 'ar' === $selected_language ){
        // Replace short forms of Arabic month names with English
        foreach ($arabicMonths as $shortForm => $englishMonth) {
            if (strpos($dateString, $shortForm) !== false) {
                $dateString = str_replace($shortForm, $englishMonth, $dateString);
                break; // Exit loop once replaced
            }
        }
    } else {
         // Replace short forms of Hebrew month names with English
        foreach ($hebrewMonths as $shortForm => $englishMonth) {
            if (strpos($dateString, $shortForm) !== false) {
                $dateString = str_replace($shortForm, $englishMonth, $dateString);
                break; // Exit loop once replaced
            }
        }
    }

    // Convert the modified date string to Y-m-d format
    $englishDate = date('Y-m-d', strtotime($dateString));
    return $englishDate;
}


if(  !function_exists( 'cl_remove_abandoned_cart_record')){
    /**
     * Remove a record from the abandoned cart table.
     *
     * This function deletes a record from the abandoned cart table in the database
     * where the email matches the provided user email and the status is 'processing'.
     *
     * @since 3.1.15
     * @param string $user_email The email of the user whose record should be removed.
     */
    function cl_remove_abandoned_cart_record( $user_email, $session_id, $status = 'processing' ){
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $wpdb->delete( $cl_cart_table, array( 'email' => $user_email, 'status' => $status, 'session_id' => $session_id ) );
    }

    if( !function_exists('cl_get_wc_products') ){
        /**
         * Retrieves an array of active WooCommerce products.
         *
         * This function checks if the WooCommerce plugin is active,
         * and fetches their respective products using the `wc_get_products` function.
         * It includes both simple and variable products, with variable product variations.
         *
         * @since 3.1.15
         * @return array An associative array of product IDs as keys and product titles as values.
         */

        function cl_get_wc_products( $term ){
            $products = [];
	        if( function_exists( 'cl_is_wc_active' ) && cl_is_wc_active() ) {
		        $data_store = WC_Data_Store::load( 'product' );
		        $ids        = $data_store->search_products( $term, '', false, false, 10);
		        $product_objects = array_filter( array_map( 'wc_get_product', $ids ), 'wc_products_array_filter_readable' );
                if( !empty( $product_objects ) ){
	                foreach ( $product_objects as $product_object ) {
		                $formatted_name = $product_object->get_formatted_name();
		                $products[ $product_object->get_id() ] = rawurldecode( $formatted_name );
	                }
                }
	        }
            return $products;
        }
    }

    if( !function_exists('cl_get_edd_products') ){
        /**
         * Retrieves an array of active Easy Digital Downloads products.
         *
         * This function checks if Easy Digital Downloads (EDD) plugin is active,
         * and fetches their respective products using the `edd_get_downloads` function.
         *
         * @since 3.1.15
         * @return array An associative array of product IDs as keys and product titles as values.
         */

        function cl_get_edd_products( $term ){
            $products = [];
	        if( function_exists( 'cl_is_edd_active' ) && cl_is_edd_active() ) {
		        $args = array(
			        'fields'        => 'ids',
			        'post_type'     => 'download',
			        'no_found_rows' => true,
			        's'             => $term
		        );
		        $downloads = get_posts( $args );

                if( !empty( $downloads ) ){
	                foreach ( $downloads as $download_id ) {
		                $download = new EDD_Download( $download_id );
		                $products[$download_id] = $download->get_name();
	                }
                }
	        }
	        return $products;
        }
    }
}


if ( ! function_exists( 'get_products_category_id' ) ) {
    /**
     * Get the category IDs for a list of products.
     *
     * This function retrieves the category IDs for a given list of product IDs.
     * It checks if WooCommerce and Easy Digital Downloads (EDD) are active and fetches
     * the respective category IDs for the products.
     *
     * @since 3.1.15
     * @param array $ids An array of product IDs.
     * @return array An array of unique category IDs.
     */
    function get_products_category_id( $ids ) {
        $categories = [];

        if( cl_is_wc_active() ) {
            foreach ( $ids as $id ) {
                $product = wc_get_product( $id );
                if ( $product ) {
                    $product_categories = $product->get_category_ids();
                    $categories = array_merge( $categories, $product_categories );
                }
            }
        }

        if( cl_is_edd_active() ) {
            foreach ( $ids as $id ) {
                $product_categories = edd_get_download_category_ids( $id );
                $categories = array_merge( $categories, $product_categories );
            }
        }

        return array_unique( $categories );
    }
}

if ( ! function_exists( 'edd_get_download_category_ids' ) ) {
    /**
     * Retrieves the category IDs for a given EDD download.
     *
     * @param int $download_id The ID of the download.
     * @return array An array of category IDs.
     * @since 3.1.15
     */
    function edd_get_download_category_ids( $download_id ) {
        $terms = wp_get_post_terms( $download_id, 'download_category', array( 'fields' => 'ids' ) );
        return $terms ?: [];
    }
}


if( ! function_exists('cl_get_wc_categories') ){
    /**
     * Retrieves an array of WooCommerce product categories.
     *
     * This function checks if the WooCommerce plugin is active,
     * and fetches the categories that match the given term.
     * It returns an associative array of category IDs as keys and category names as values.
     *
     * @since 3.1.15
     * @param string $term The term to search for in category names.
     * @return array An associative array of category IDs and names.
     */
    function cl_get_taxonomies( $term ){
        $taxonomies_array = [];
        if(cl_is_wc_active()){
            $taxonomies_array[] = 'product_cat';
        }
        if(cl_is_edd_active()){
            $taxonomies_array[] = 'download_category';
        }
        $args = [
            'taxonomy'     => $taxonomies_array,
            'hide_empty'   => false,
            'name__like'   => $term,
            'fields'       => 'id=>name'
        ];
        return get_terms( $args );
    }
}

if ( ! function_exists( 'get_wc_and_edd_countries' ) ) {
    /**
     * Retrieves a unique list of countries from WooCommerce and Easy Digital Downloads.
     *
     * This function checks if WooCommerce and Easy Digital Downloads are active,
     * retrieves their respective country lists, and returns a unique set of countries.
     *
     * @return array An associative array of country codes and names.
     * @since 3.1.15
     */
    function get_wc_and_edd_countries() {
        $wc_countries = cl_get_wc_countries();
        $edd_countries = cl_get_edd_countries();
        $all_countries = array_merge( $wc_countries, $edd_countries );
        return !empty( $all_countries  ) ? array_unique( $all_countries ) : [];
    }
}

if( !function_exists('cl_get_wc_countries')){
    /**
     * Retrieves an array of WooCommerce countries.
     *
     * This function checks if the WooCommerce plugin is active,
     * and fetches the list of countries available in WooCommerce.
     * It returns an associative array of country codes as keys and country names as values.
     *
     * @since 3.1.15
     * @return array An associative array of country codes and names.
     */
    function cl_get_wc_countries(){
        $countries_array = [];
        if (cl_is_wc_active()) {
            $wc_countries = WC()->countries->get_countries();

            if (!empty($wc_countries)) {
                foreach ($wc_countries as $code => $name) {
                    $countries_array[$code] = $name;
                }
            }
        }

        return $countries_array;
    }
}


if( !function_exists('cl_get_edd_countries')){
    /**
     * Retrieves an array of Easy Digital Downloads (EDD) countries.
     *
     * This function checks if the EDD plugin is active,
     * and fetches the list of countries available in EDD.
     * It returns an associative array of country codes as keys and country names as values.
     *
     * @since 3.1.15
     * @return array An associative array of country codes and names.
     */
    function cl_get_edd_countries(){
        $countries_array = [];
        if (cl_is_edd_active()) {
            $edd_countries = edd_get_country_list();

            if (!empty($edd_countries)) {
                foreach ($edd_countries as $code => $name) {
                    if (!isset($countries_array[$code])) {
                        $countries_array[$code] = $name;
                    }
                }
            }
        }

        return $countries_array;
    }
}
