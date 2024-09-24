<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://rextheme.com/
 * @since      1.0.0
 *
 * @package    Wp_Abandoned_Cart
 * @subpackage Wp_Abandoned_Cart/admin/partials
 */

$page_url = admin_url( 'admin.php?page=cart_lift&action=email_templates');
$cart_search = isset($_GET['cart_search']) ? esc_attr($_GET['cart_search']) : '';
$general_settings = function_exists('cl_get_general_settings') ? cl_get_general_settings() : [];
$manually_recovered_cart = isset($general_settings['manually_recovered_cart']) ? $general_settings['manually_recovered_cart'] : '0';   
?>
<div class="cl-cart">

        <div class="cart-header">
            <h4 class="title"><?php echo __( 'Real Time Updates', 'cart-lift' ); ?></h4>
            
            <div class="right-element">
                <form method="get" id="cl_cart_filter">
                    <div class="cart-filter-wrapper">
                            <input type="hidden" name="page" value="cart_lift"/>
                            <input type="hidden" name="action" value="carts"/>
                            <span class="label"><?php __('Filter:', 'cart-lift') ?></span>
                            <select id="cl_status_filter" name="status" class="cl-select">
                                <?php
                                $current_status = isset($_GET['status']) ? $_GET['status'] : '';
                                $status = array(
                                    ''              => __('All', 'cart-lift'),
                                    'processing'    => __('Processing', 'cart-lift'),
                                    'abandoned'     => __('Abandoned', 'cart-lift'),
                                    'completed'     => __('Completed', 'cart-lift'),
                                    'recovered'     => __('Recovered', 'cart-lift'),
                                );

                                foreach ( $status as $key => $value ) {
                                    printf( "<option %s value='%s'>%s</option>\n",
                                        selected( $key, $current_status, false ),
                                        esc_attr( $key ),
                                        esc_attr( $value )
                                    );
                                }
                                ?>
                            </select>

                            <input type="search" name="cart_search" placeholder="<?php echo __('Search by customer email...', 'cart-lift'); ?>" value="<?php echo $cart_search; ?>"/>
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19 18" width="19" height="18">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-1247 -136L73 -136L73 841L-1247 841Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        .search-icon { fill: none;stroke: #6e42d3;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.7 }
                                    </style>
                                    <g id="Carts" clip-path="url(#cp1)">
                                        <g id="Calender">
                                            <path id="Combined Shape" class="search-icon" d="M9.22 14.44C12.66 14.44 15.44 11.66 15.44 8.22C15.44 4.79 12.66 2 9.22 2C5.79 2 3 4.79 3 8.22C3 11.66 5.79 14.44 9.22 14.44ZM17 16L13.62 12.62" />
                                        </g>
                                    </g>
                                </svg>
                            </button>
                    </div>
                </form>

                <?php
                    if(apply_filters('is_cl_premium', false)) {
                        ?>
                        <a class="cl-btn report-download" href="<?php echo CART_LIFT_PRO_URL . 'report.xlsx'; ?>" download><?php echo __( 'Download Report', 'cart-lift' ); ?></a>
                        <?php
                    }else {
                        $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
                        ?>
                            <a class="cl-btn report-download disabled" href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e( 'Click to Upgrade Pro', 'cart-lift' ); ?>">
                                <?php echo __( 'Download Report', 'cart-lift' ); ?>
                                <span class="pro-tag"><?php echo __( 'pro', 'cart-lift' ); ?></span>
                            </a>
                        <?php
                    }
                ?>
            </div>
        </div>

        <div class="cart-list-wrapper">
            <ul class="cart-list-header">
                <li class="sl">#</li>
                <li class="email"><?php echo __( 'Email', 'cart-lift' ); ?></li>
                <li class="product"><?php echo __( 'Products on Cart', 'cart-lift' ); ?></li>
                <li class="amount"><?php echo __( 'Amount', 'cart-lift' ); ?></li>
                <li class="status"><?php echo __( 'Status', 'cart-lift' ); ?></li>
                <li class="status"><?php echo __( 'Paused/Active', 'cart-lift' ); ?></li>
                <li class="date"><?php echo __( 'Date', 'cart-lift' ); ?> </li>
                <li class="action"><?php echo __( 'Action', 'cart-lift' ); ?> </li>
            </ul>
            <?php
                if( !empty($cart_data['cart_data']) ){
                    $i = 0;
                    foreach ($cart_data['cart_data'] as $data) {
                    $i++;
                    $quantity = 0;

                    foreach ($data['products'] as $product) {
                        $quantity += $product['quantity'];
                    }

                    $status_cls = $data['status'];

                    ?>
                    <div class="single-cart-wrapper">
                        <ul class="single-cart-list">
                            <li class="sl" data-title="<?php _e( 'SL No :', 'cart-lift' ); ?>"><?php echo sprintf("%02d", $i); ?></li>
                            <li class="email" data-title="<?php _e( 'Email :', 'cart-lift' ); ?>"><?php echo $data['email']; ?></li>
                            <li class="product" data-title="<?php _e( 'Product :', 'cart-lift' ); ?>"><span><?php echo $quantity; ?></span></li>
                            <li class="amount" data-title="<?php _e( 'Amount :', 'cart-lift' ); ?>"><?php echo $data['cart_total']; ?></li>
                            <li class="status" data-title="<?php _e( 'Status :', 'cart-lift' ); ?>"><span class="<?php echo $status_cls; ?>"><?php echo $data['status']; ?></span></li>
                            <li class="status" data-title="<?php _e( 'Paused/Active:', 'cart-lift' ); ?>">
                                <span class="cl-switcher">
                                    <input
                                            class="cl-toggle-option cl_action_schedular"
                                            type="checkbox"
                                            id="<?php echo !empty( $data['id'] ) ? 'cl_schedular_'.$data['id'] : ''; ?>"
                                            data-id="<?php echo !empty( $data['id'] ) ? $data['id'] : ''; ?>"
                                            data-status="<?php echo !empty( $data['schedular_status'] ) ? $data['schedular_status'] : ''; ?>"
                                            <?php echo !empty( $data['schedular_status'] ) && $data['schedular_status'] == 'active' ? 'checked' : ''; ?>
                                    >
                                    <label for="<?php echo !empty( $data['id'] ) ? 'cl_schedular_'.$data['id'] : ''; ?>"></label>
                                </span>
                            </li>
                            <li class="date" data-title="<?php _e( 'Date :', 'cart-lift' ); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 20" width="12" height="16">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-264 -265L1056 -265L1056 712L-264 712Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        .clock-icon { fill: #6e42d3 }
                                    </style>
                                    <g id=" Final Campaigns " clip-path="url(#cp1)">
                                        <g id="1">
                                            <g id="Group 2">
                                                <path fill-rule="evenodd" class="clock-icon" d="M17 11.55C17 16.22 13.2 20 8.5 20C3.8 20 0 16.22 0 11.55C0 7.11 3.42 3.52 7.72 3.13L7.72 1.56L6.96 1.56C6.52 1.56 6.17 1.21 6.17 0.78C6.17 0.35 6.52 0 6.96 0L10.04 0C10.48 0 10.83 0.35 10.83 0.78C10.83 1.21 10.48 1.56 10.04 1.56L9.28 1.56L9.28 3.13C11 3.29 12.61 3.95 13.93 5.04L14.72 4.26C15.03 3.96 15.52 3.96 15.83 4.26C16.13 4.56 16.13 5.06 15.83 5.36L15.04 6.15C16.31 7.66 17 9.55 17 11.55ZM15.43 11.55C15.43 7.75 12.32 4.66 8.5 4.66C4.68 4.66 1.57 7.75 1.57 11.55C1.57 15.35 4.68 18.44 8.5 18.44C12.32 18.44 15.43 15.35 15.43 11.55ZM12.14 9.03L9.05 12.1C8.75 12.4 8.25 12.4 7.95 12.1C7.64 11.79 7.64 11.3 7.95 11L11.03 7.93C11.33 7.63 11.83 7.63 12.14 7.93C12.44 8.24 12.44 8.73 12.14 9.03Z" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <?php echo $data['time']; ?>
                            </li>
                            <li class="action" data-title="<?php _e( 'Action :', 'cart-lift' ); ?>">
                                <a class="details cl-cart-details" href="#" title="<?php _e( 'Details', 'cart-lift' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 13" width="18" height="13">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-1181 -274L139 -274L139 703L-1181 703Z" />
                                            </clipPath>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp2">
                                                <path d="M-1139 -34L97 -34C103.64 -34 109 -28.64 109 -22L109 34C109 40.64 103.64 46 97 46L-1139 46C-1145.64 46 -1151 40.64 -1151 34L-1151 -22C-1151 -28.64 -1145.64 -34 -1139 -34Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            .eye-icon { fill: #6e42d3 }
                                        </style>
                                        <g id="Carts 2" clip-path="url(#cp1)">
                                            <g id="1">
                                                <g id="Mask by Mask" clip-path="url(#cp2)">
                                                    <g id="duplicate">
                                                        <g id="eye">
                                                            <path id="Shape" fill-rule="evenodd" class="eye-icon" d="M17.9 6.87C17.87 6.93 17.01 8.41 15.51 9.91C13.72 11.69 11.5 13 9 13C6.5 13 4.28 11.69 2.49 9.91C0.99 8.41 0.13 6.93 0.1 6.87C-0.03 6.64 -0.03 6.36 0.1 6.13C0.13 6.07 0.99 4.59 2.49 3.09C4.28 1.31 6.5 0 9 0C11.5 0 13.72 1.31 15.51 3.09C17.01 4.59 17.87 6.07 17.9 6.13C18.03 6.36 18.03 6.64 17.9 6.87ZM16.46 6.5C16.13 6 15.45 5.04 14.5 4.11C12.99 2.61 11.11 1.44 9 1.44C6.89 1.44 5.01 2.61 3.5 4.11C2.55 5.04 1.87 6 1.54 6.5C1.87 7 2.55 7.96 3.5 8.89C5.01 10.39 6.89 11.56 9 11.56C11.11 11.56 12.99 10.39 14.5 8.89C15.45 7.96 16.13 7 16.46 6.5Z" />
                                                            <path id="Shape" fill-rule="evenodd" class="eye-icon" d="M12 6.5C12 8.43 10.65 10 9 10C7.35 10 6 8.43 6 6.5C6 4.57 7.35 3 9 3C10.65 3 12 4.57 12 6.5ZM11 6.5C11 5.21 10.1 4.17 9 4.17C7.9 4.17 7 5.21 7 6.5C7 7.79 7.9 8.83 9 8.83C10.1 8.83 11 7.79 11 6.5Z" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>

                                <a class="cl-cart-delete" href="#" title="<?php _e( 'Delete', 'cart-lift' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" width="14" height="14">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-1248 -271L72 -271L72 227L-1248 227Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            .trash-icon { fill: none;stroke: #f85d59;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                                        </style>
                                        <g id="  Campaigns " clip-path="url(#cp1)">
                                            <g id="Group 9">
                                                <g id="1">
                                                    <g id="cross">
                                                        <g id="x">
                                                            <path id="Path" class="trash-icon" d="M12 2L2 12" />
                                                            <path id="Path" class="trash-icon" d="M2 2L12 12" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            </li>
                        </ul>

                        <!-- cart modal -->
                        <div class="cl-cart-modal">
                            <div class="modal-wrapper">
                                <div class="modal-header">
                                    <div class="title">
                                        <span class="status <?php echo $status_cls; ?>"><?php echo $data['status']; ?></span>
                                        <span class="slno">#<?php echo sprintf("%02d", $i); ?></span>
                                    </div>
                                    <div class="cart-modal-close">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" width="14" height="14">
                                            <defs>
                                                <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                    <path d="M-1115 -155L205 -155L205 822L-1115 822Z" />
                                                </clipPath>
                                            </defs>
                                            <style>
                                                .cross-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.771 }
                                            </style>
                                            <g id="Carts details" clip-path="url(#cp1)">
                                                <g id="Paginations">
                                                    <g id="Group 11">
                                                        <g id="Group 9">
                                                            <g id="Close Copy">
                                                                <g id="x">
                                                                    <path id="Path" class="cross-icon" d="M11.72 2.22L2.28 11.67" />
                                                                    <path id="Path" class="cross-icon" d="M2.28 2.22L11.72 11.67" />
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                </div>

                                <div class="modal-body">
                                    <div class="single-info user-info">
                                        <ul>
                                            <li class="info-header">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 16" width="15" height="16">
                                                    <defs>
                                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                            <path d="M-205 -236L1115 -236L1115 741L-205 741Z" />
                                                        </clipPath>
                                                    </defs>
                                                    <style>
                                                        .user-icon { fill: #6e42d3;stroke: #6e42d3;stroke-width: 0.3 }
                                                    </style>
                                                    <g id="Carts details" clip-path="url(#cp1)">
                                                        <g id="Paginations">
                                                            <g id="Group 11">
                                                                <g id="user details">
                                                                    <g id="contacts">
                                                                        <path id="Fill 322" class="user-icon" d="M4.42 4.71L3.79 4.71C3.79 6.77 5.45 8.43 7.5 8.43C9.55 8.43 11.21 6.77 11.21 4.71C11.21 2.66 9.55 1 7.5 1C5.45 1 3.79 2.66 3.79 4.71L5.06 4.71C5.06 4.04 5.34 3.43 5.78 2.99C6.22 2.55 6.83 2.28 7.5 2.28C8.18 2.28 8.78 2.55 9.22 2.99C9.67 3.43 9.94 4.04 9.94 4.71C9.94 5.39 9.67 5.99 9.22 6.44C8.78 6.88 8.18 7.15 7.5 7.15C6.83 7.15 6.22 6.88 5.78 6.44C5.34 5.99 5.06 5.39 5.06 4.71L4.42 4.71" />
                                                                        <path id="Fill 323" class="user-icon" d="M14 13.4C14 10.66 11.44 8.43 8.29 8.43L6.72 8.43C3.56 8.43 1 10.66 1 13.4C1 13.73 1.31 14 1.68 14C2.06 14 2.37 13.73 2.37 13.4C2.37 12.36 2.85 11.42 3.64 10.73C4.43 10.04 5.51 9.62 6.72 9.62L8.29 9.62C9.49 9.62 10.57 10.04 11.36 10.73C12.15 11.42 12.63 12.36 12.63 13.4C12.63 13.73 12.94 14 13.32 14C13.7 14 14 13.73 14 13.4" />
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span><?php echo __( 'User Details:', 'cart-lift' ); ?></span>
                                            </li>
                                            <li>
                                                <span class="label"><?php echo __( 'Name:', 'cart-lift' ); ?>
                                                </span><span class="content"><?php echo $data['user_name']; ?></span>
                                            </li>
                                            <li>
                                                <span class="label"><?php echo __( 'Email:', 'cart-lift' ); ?></span>
                                                <span class="content"><?php echo $data['email']; ?></span>
                                            </li>

                                            <?php do_action( 'cl_cart_tab_after_email', $data );?>

                                            <li>
                                                <span class="label"><?php echo __( 'Cart Total:', 'cart-lift' ); ?></span>
                                                <span class="content primary-color"><?php echo $data['cart_total']; ?></span>
                                            </li>
                                            <li>
                                                <span class="label"><?php echo __( 'Abandoned Date:', 'cart-lift' ); ?></span>
                                                <span class="content"><?php echo $data['time']; ?></span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="single-info email-info">
                                        <ul>
                                            <li class="info-header">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 20" width="12" height="16">
                                                    <defs>
                                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                            <path d="M-264 -265L1056 -265L1056 712L-264 712Z" />
                                                        </clipPath>
                                                    </defs>
                                                    <style>
                                                        #clock-icon-cart-info { fill: #6e42d3; stroke: #6e42d3; stroke-linecap: round; stroke-linejoin: round; stroke-width: 1.2 }
                                                    </style>
                                                    <g id=" Final Campaigns " clip-path="url(#cp1)">
                                                        <g id="1">
                                                            <g id="Group 2">
                                                                <path fill-rule="evenodd" id="clock-icon-cart-info" d="M17 11.55C17 16.22 13.2 20 8.5 20C3.8 20 0 16.22 0 11.55C0 7.11 3.42 3.52 7.72 3.13L7.72 1.56L6.96 1.56C6.52 1.56 6.17 1.21 6.17 0.78C6.17 0.35 6.52 0 6.96 0L10.04 0C10.48 0 10.83 0.35 10.83 0.78C10.83 1.21 10.48 1.56 10.04 1.56L9.28 1.56L9.28 3.13C11 3.29 12.61 3.95 13.93 5.04L14.72 4.26C15.03 3.96 15.52 3.96 15.83 4.26C16.13 4.56 16.13 5.06 15.83 5.36L15.04 6.15C16.31 7.66 17 9.55 17 11.55ZM15.43 11.55C15.43 7.75 12.32 4.66 8.5 4.66C4.68 4.66 1.57 7.75 1.57 11.55C1.57 15.35 4.68 18.44 8.5 18.44C12.32 18.44 15.43 15.35 15.43 11.55ZM12.14 9.03L9.05 12.1C8.75 12.4 8.25 12.4 7.95 12.1C7.64 11.79 7.64 11.3 7.95 11L11.03 7.93C11.33 7.63 11.83 7.63 12.14 7.93C12.44 8.24 12.44 8.73 12.14 9.03Z" />
                                                            </g>
                                                        </g>
                                                    </g>
                                                </svg>
                                                <span><?php echo __( 'Time Log:', 'cart-lift' ); ?></span>
                                            </li>
                                            <?php
                                                if( !$is_pro_cart ){
                                                    $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
	                                                echo '<img src="'. CART_LIFT_URL . '/admin/images/Cart_Lift_Email_Blur.png' .'" alt="Cart-Contents" width="461" height="">';
                                                    echo '<a href="'.$pro_url.'" class="pro-link" target="_blank" title="'.__( 'Click to Upgrade Pro', 'cart-lift' ).'"><span class="pro-tag">'. __( 'Upgrade to Pro', 'cart-lift' ).'</span></a>';
                                                }
                                                else{
                                                    do_action( 'cl_render_time_logs', $data );
                                                }
                                            ?>
                                        </ul>
                                    </div>

                                    <div class="single-info cart-info">
                                        <div class="cl-cart-header">
                                            <h5 class="cart-info-title">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 19" width="17" height="19">
                                                <defs>
                                                    <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                        <path d="M-180 -497L1140 -497L1140 480L-180 480Z" />
                                                    </clipPath>
                                                </defs>
                                                <style>
                                                    .cart-icon { fill: none;stroke: #6e42d3;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                                                </style>
                                                <g id="Carts details" clip-path="url(#cp1)">
                                                    <g id="Paginations">
                                                        <g id="Group 11">
                                                            <g id="Group 7">
                                                                <path id="Stroke 1" class="cart-icon" d="M5.32 6.11C5.32 4.39 6.71 3 8.42 3C10.14 3 11.53 4.39 11.53 6.11L11.53 6.77" />
                                                                <path id="Stroke 3" class="cart-icon" d="M11.53 6.77L13.66 6.77C14.35 6.77 14.86 7.41 14.72 8.08L13.21 15.02C13.09 15.59 12.58 16 12 16L4.74 16C4.16 16 3.66 15.59 3.53 15.02L2.02 8.08C1.88 7.41 2.39 6.77 3.08 6.77L9.26 6.77" />
                                                                <path id="Stroke 5" class="cart-icon" d="M5.01 12.15L13.81 12.15" />
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </svg>
                                            <span class="title"><?php echo __( 'Cart Contents:', 'cart-lift' ); ?></span>

                                            <?php if( $is_pro_cart ){ ?>
                                                <span class="item">(<?php echo count( $data['products'] ); echo count( $data['products'] ) == 1 ? __(' Product', 'cart-lift') : __(' Products', 'cart-lift'); ?>)</span>
                                            <?php } ?>
                                        </h5>
                                        <?php
                                            if(  '1' === $manually_recovered_cart ){
                                                if(apply_filters('is_cl_premium', false) ) {
                                        ?>
                                                <a class="cl-btn manually-recovered-button" id="cl_manually_recovered" data-session-id="<?php echo isset($data['session_id'] ) ? $data['session_id'] : '' ?>" data-cart-id="<?php  echo isset($data['id'] ) ? $data['id'] : ''  ?>" data-user-email="<?php echo isset($data['email'] ) ? $data['email'] : ''   ?>"  href="#"><?php echo __( 'Send Recovery Email Now', 'cart-lift' ); ?></a>
                                                <div class="cl-recovery-loader">
                                                    <div class="ring"></div>
                                                </div>
                                        <?php
                                                
                                            }else {
                                                $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
                                        ?>
                                                    <a class="cl-btn manually-recovered-button disabled" href="<?php echo $pro_url; ?>" target="_blank" title="<?php _e( 'Click to Upgrade Pro', 'cart-lift' ); ?>">
                                                        <?php echo __( 'Send Recovery Email Now', 'cart-lift' ); ?>
                                                        <span class="pro-tag"><?php echo __( 'pro', 'cart-lift' ); ?></span>
                                                    </a>
                                                <?php
                                            }

                                         }
                                        ?>
                                        </div>
                                        <ul class="cart-list-header">
                                            <li class="image"><?php echo __( 'Image', 'cart-lift' ); ?></li>
                                            <li class="product-name"><?php echo __( 'Product Name', 'cart-lift' ); ?></li>
                                            <li class="price"><?php echo __( 'Price', 'cart-lift' ); ?></li>
                                            <li class="quantity"><?php echo __( 'Quantity', 'cart-lift' ); ?></li>
                                        </ul>

                                        <?php
                                        if( !$is_pro_cart ){
                                            $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
                                            echo '<ul class="upgrade-pro-wrapper"><li>';
	                                        echo '<img src="'. CART_LIFT_URL . '/admin/images/cart-contents-blurred.png' .'" alt="Cart-Contents" width="952" height="99">';
                                            echo '<a href="'.$pro_url.'" class="pro-link" target="_blank" title="'.__( 'Click to Upgrade Pro', 'cart-lift' ).'"><span class="pro-tag">'.__(' Upgrade to Pro', 'cart-lift').'</span></a>';
                                            echo '</li></ul>';
                                        }
                                        else{
                                            do_action( 'cl_render_cart_contents', $data );
                                        }
                                        ?>

                                    </div>

                                    <?php if( $is_pro_cart ){ ?>
                                        <?php
                                            $unsubscribe_url = wp_nonce_url(add_query_arg( array(
                                                'action'     => 'carts',
                                                'sub_action' => 'unsubscribe_cl_emails',
                                                'id'         => $data['id'],
                                            ),
                                                $page_url
                                            ),
                                                'cl_cart_nonce'
                                            );
                                            if(!$data['unsubscribed'])
                                                printf('<a href="%s" class="cl-btn unsubscribe">' . __('unsubscribe', 'cart-lift') . '</a>', esc_url($unsubscribe_url));
                                        ?>
                                    <?php }else {
                                        $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
                                        printf('<a href="'.$pro_url.'" class="cl-btn unsubscribe cl-free" target="_blank" title="'.__('Click to Upgrade Pro', 'cart-lift').'">' . __('unsubscribe', 'cart-lift') . '</a>');
                                    } ?>
                                </div>
                            </div>
                        </div>

                        <!-- cl-alert-modal -->
                        <div class="cl-alert-modal">
                            <div class="modal-wrapper">
                                <div class="cl-alert-close">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" width="14" height="14">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-1115 -155L205 -155L205 822L-1115 822Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            .cross-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.771 }
                                        </style>
                                        <g id="Carts details" clip-path="url(#cp1)">
                                            <g id="Paginations">
                                                <g id="Group 11">
                                                    <g id="Group 9">
                                                        <g id="Close Copy">
                                                            <g id="x">
                                                                <path id="Path" class="cross-icon" d="M11.72 2.22L2.28 11.67" />
                                                                <path id="Path" class="cross-icon" d="M2.28 2.22L11.72 11.67" />
                                                            </g>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>

                                <div class="modal-body">
                                    <svg width="50px" height="50px" viewBox="0 0 50 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="signs" fill="#F85D59" fill-rule="nonzero">
                                                <path d="M49.4311523,37.2843049 C49.03125,36.1343049 47.8951172,35.5741031 46.8933594,36.0331839 C45.8916016,36.4922646 45.4036133,37.7965247 45.8035156,38.9465247 C46.3119141,40.4087444 46.1452148,42.0782511 45.3575195,43.4127803 C44.5694336,44.7479821 43.3076172,45.5137892 41.8957031,45.5137892 L8.11210937,45.5137892 C6.57197266,45.5137892 5.20429687,44.5912556 4.45341797,43.0457399 C3.70136719,41.4976457 3.73671875,39.6733184 4.54794922,38.1653587 L21.4397461,6.76928251 C22.2088867,5.3396861 23.5412109,4.48621076 25.0039063,4.48621076 C26.4666016,4.48621076 27.7988281,5.3396861 28.5680664,6.76928251 L41.0609375,29.9894619 C41.6277344,31.0429372 42.83125,31.3697309 43.7487305,30.718722 C44.6663086,30.0681614 44.9508789,28.6866592 44.3839844,27.6331839 L31.8910156,4.41300448 C30.4050781,1.65112108 27.8304688,0.00224215247 25.0038086,0.00224215247 C22.1771484,0.00224215247 19.6025391,1.65123318 18.1166016,4.41300448 L1.22480469,35.8091928 C-0.341210937,38.7201794 -0.409375,42.2419283 1.04228516,45.2299327 C2.49277344,48.2153587 5.13564453,49.9976457 8.11210937,49.9976457 L41.8957031,49.9976457 C44.6240234,49.9976457 47.0643555,48.5141256 48.5910156,45.9278027 C50.10625,43.3607623 50.4202148,40.1294843 49.4311523,37.2843049 Z" id="Path"></path>
                                                <path d="M25.0047852,41.1420404 C26.0833984,41.1420404 26.9577148,40.1383408 26.9577148,38.9001121 C26.9577148,37.6618834 26.0833984,36.6581839 25.0047852,36.6581839 L25.0038086,36.6581839 C23.9251953,36.6581839 23.0513672,37.6618834 23.0513672,38.9001121 C23.0513672,40.1383408 23.9261719,41.1420404 25.0047852,41.1420404 Z" id="Path"></path>
                                                <path d="M26.9568359,31.0532511 L26.9568359,14.5748879 C26.9568359,13.3366592 26.0825195,12.3329596 25.0039063,12.3329596 C23.925293,12.3329596 23.0509766,13.3366592 23.0509766,14.5748879 L23.0509766,31.0532511 C23.0509766,32.2914798 23.925293,33.2951794 25.0039063,33.2951794 C26.0825195,33.2951794 26.9568359,32.2914798 26.9568359,31.0532511 Z" id="Path"></path>
                                            </g>
                                        </g>
                                    </svg>
                                    <h4><?php echo __( 'Are you sure?', 'cart-lift' ); ?></h4>
                                    <p><?php echo __( 'Once deleted, you will not be able to recover this Cart!', 'cart-lift' ); ?></p>
                                    <button class="cl-btn cl-alert-cancel"><?php echo __( 'Cancel', 'cart-lift' ); ?></button>

                                    <a class="cl-btn trash delete" href="<?php
                                        echo wp_nonce_url(add_query_arg( array(
                                            'action'     => 'carts',
                                            'sub_action' => 'delete_cart_data',
                                            'id'         => $data['id'],
                                        ),
                                            $page_url
                                        ),
                                            'cl_cart_nonce'
                                        )

                                        ?>" >
                                        <?php echo __( 'Delete', 'cart-lift' ); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /single-cart-wrapper -->

                    <?php
                    }
                }
                else{
                   ?>
                    <ul class="cart-list-header no-data">
                        <li><?php echo __( 'No Data Found', 'cart-lift' ); ?></li>
                    </ul>
                   <?php
                }
            ?>
        </div>

    <?php if( !empty($cart_data['cart_data']) && $cart_data['pagination'] ) { ?>
        <div class="cl-pagination">

            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?page=cart_lift&action=carts&pageno=".($pageno - 1); } ?>" class="nav-link prev <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 15" width="10" height="15">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-577 -663L743 -663L743 314L-577 314Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .prev-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.7 }
                    </style>
                    <g id="Carts" clip-path="url(#cp1)">
                        <g id="Paginations">
                            <g id="Check">
                                <g id="Check">
                                    <g id="chevron-down">
                                        <path id="Path" class="prev-icon" d="M7.5 2.5L2.5 7.5L7.5 12.5" />
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
            <?php
            for ($i = ($pageno - 1); $i <= ($pageno + 1); $i ++) {
                if ($i < 1)
                    continue;
                if ($i > $cart_data['total_pages'])
                    break;
                if ($i == $pageno) {
                    $class = "active";
                } else {
                    $class = "";
                }
                ?>
                <a href="?page=cart_lift&action=carts&pageno=<?php echo $i; ?>" class="nav-link <?php echo $class; ?>"><?php echo $i; ?></a>
            <?php }
            ?>
            <a href="<?php if($pageno >= $cart_data['total_pages']){ echo '#'; } else { echo "?page=cart_lift&action=carts&pageno=".($pageno + 1); } ?>" class="nav-link next <?php if($pageno >= $cart_data['total_pages']){ echo 'disabled'; } ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 15" width="10" height="15">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-785 -663L535 -663L535 314L-785 314Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .next-icon { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.7 }
                    </style>
                    <g id="Carts" clip-path="url(#cp1)">
                        <g id="Paginations">
                            <g id="Check Copy">
                                <g id="Check">
                                    <g id="chevron-down">
                                        <path id="Path" class="next-icon" d="M2.5 12.5L7.5 7.5L2.5 2.5" />
                                    </g>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
            </a>
        </div>
    <?php } ?>
</div>