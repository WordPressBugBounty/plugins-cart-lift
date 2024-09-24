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

$add_new_template_url = wp_nonce_url( add_query_arg( array(
            'page' => 'cart_lift',
            'action' => 'email_templates',
            'sub_action' => 'new_email_template',
        ),
            admin_url( '/admin.php' ) ),
        'email_template_nonce'
);
?>

<div class="cl-campaigns">
    <div class="campaign-header">
        <h4 class="title"><?php echo __( 'Campaigns', 'cart-lift' ); ?></h4>

        <?php if($enable_add_new_btn){ ?>
        <a class="add-campaign-btn cl-btn" href="<?php echo esc_url( $add_new_template_url ); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                <defs>
                    <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                        <path d="M-1140 -136L180 -136L180 841L-1140 841Z" />
                    </clipPath>
                </defs>
                <style>
                    .plus-icon { fill: #ffffff;stroke: #ffffff }
                </style>
                <g id="FinalCampaigns " clip-path="url(#cp1)">
                    <g id="Calender">
                        <g id="Group4">
                            <g id="more">
                                <path id="Path" class="plus-icon" d="M14.3 7.3L8.7 7.3L8.7 1.7C8.7 1.31 8.39 1 8 1C7.61 1 7.3 1.31 7.3 1.7L7.3 7.3L1.7 7.3C1.31 7.3 1 7.61 1 8C1 8.39 1.31 8.7 1.7 8.7L7.3 8.7L7.3 14.3C7.3 14.69 7.61 15 8 15C8.39 15 8.7 14.69 8.7 14.3L8.7 8.7L14.3 8.7C14.69 8.7 15 8.39 15 8C15 7.61 14.69 7.3 14.3 7.3Z" />
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
            <?php echo __( 'add campaign', 'cart-lift' ); ?>
        </a>
        <?php } else { ?>
            <?php
                $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
            ?>
            <a class="add-campaign-btn cl-btn disabled" href="<?php echo $pro_url; ?>" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" target="_blank" >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-1140 -136L180 -136L180 841L-1140 841Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .plus-icon { fill: #ffffff;stroke: #ffffff }
                    </style>
                    <g id="FinalCampaigns " clip-path="url(#cp1)">
                        <g id="Calender">
                            <g id="Group4">
                                <g id="more">
                                    <path id="Path" class="plus-icon" d="M14.3 7.3L8.7 7.3L8.7 1.7C8.7 1.31 8.39 1 8 1C7.61 1 7.3 1.31 7.3 1.7L7.3 7.3L1.7 7.3C1.31 7.3 1 7.61 1 8C1 8.39 1.31 8.7 1.7 8.7L7.3 8.7L7.3 14.3C7.3 14.69 7.61 15 8 15C8.39 15 8.7 14.69 8.7 14.3L8.7 8.7L14.3 8.7C14.69 8.7 15 8.39 15 8C15 7.61 14.69 7.3 14.3 7.3Z" />
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                <?php echo __( 'add campaign', 'cart-lift' ); ?>
            </a>
        <?php } ?>
    </div>

    <div class="campaign-wrapper">
        <ul class="campaign-list-header">
            <li class="sl">#</li>
            <li class="status"><?php echo __( 'status', 'cart-lift' ); ?></li>
            <li class="name"><?php echo __( 'Name', 'cart-lift' ); ?></li>
            <li class="sent"><?php echo __( 'Email Sent', 'cart-lift' ); ?></li>
            <?php if ( cl_is_twilio_enabled() ): ?>
            <li class="sent"><?php echo __( 'SMS Sent', 'cart-lift' ); ?></li>
            <?php endif; ?>
            <li class="conversion"><?php echo __( 'CONVERSIONS', 'cart-lift' ); ?></li>
            <li class="revenue"> <?php echo __( 'REVENUE', 'cart-lift' ); ?></li>
            <li class="action"><?php echo __( 'ACTION', 'cart-lift' ); ?></li>
        </ul>
        <?php
            if($campaign_data) {
                $i = 0;
                foreach ($campaign_data as $campaign) { $i++;?>
                    <div class="single-campaign-wrapper">

                    <ul class="single-campaign">
                        <li class="sl" data-title="<?php _e('SL No :', 'cart-lift'); ?>"><?php echo sprintf("%02d", $i); ?></li>
                        <li class="status" data-title="<?php _e('status :', 'cart-lift'); ?>">
                            <?php
                            $is_activated = $campaign['active'];
                            $checked = '';
                            $status_disable = '';
                            $status = 'off';
                            if($is_activated) {
                                $checked = 'checked';
                                $status = 'on';
                            }
                            ?>
                            <span class="cl-switcher">
                                <?php if( !$pro_campaign_list && $i > 2 ){
                                    $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
                                    $status_disable = 'disabled';
                                    $checked = '';
                                    ?>
                                    <a class="pro-tag" href="<?php echo $pro_url; ?>" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" target="_blank" ><?php echo __( 'pro', 'cart-lift' ); ?></a>
                                <?php } ?>
                                <input class="cl-toggle-email-template-status-list" type="checkbox" id="status_id_<?php echo $campaign['id']; ?>" data-status="<?php echo $status; ?>" data-template-id="<?php echo $campaign['id']; ?>" name="active_email" value="" <?php echo $checked .' '.$status_disable; ?>/>
                                <label for="status_id_<?php echo $campaign['id']; ?>" class="<?php echo $status_disable; ?>"></label>
                            </span>
                        </li>
                        <li class="name" data-title="<?php _e('Name :', 'cart-lift'); ?>">
                            <span class="email-name">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 20" width="17" height="20">
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
                                <span class="heading"><?php echo $campaign['campaign_name']; ?></span>
                                <?php
                                echo sprintf(__('<span class="text">Send %s %s after cart is abandoned</span>', 'cart-lift'), $campaign['frequency'], $campaign['unit']);
                                ?>
                            </span>
                        </li>
                        <li class="sent" data-title="<?php _e('Email Sent :', 'cart-lift'); ?>">
                            <?php if( !$pro_campaign_list ){
                                $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
                            ?>
                                <a class="pro-tag" href="<?php echo $pro_url; ?>" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" target="_blank" ><?php echo __( 'pro', 'cart-lift' ); ?></a>
                            <?php }else{ 
                                echo $campaign['total_emails'];
                            }
                            ?>
                        </li>
                        <?php if ( cl_is_twilio_enabled() ): ?>
                        <li class="sent" data-title="<?php _e('SMS Sent :', 'cart-lift'); ?>">
                            <?php if( !$pro_campaign_list ){
                                $pro_url = add_query_arg( 'cl-dashboard', '1', 'https://rextheme.com/cart-lift' );
                            ?>
                                <a class="pro-tag" href="<?php echo $pro_url; ?>" title="<?php _e('Click to Upgrade Pro', 'cart-lift'); ?>" target="_blank" ><?php echo __( 'pro', 'cart-lift' ); ?></a>
                            <?php }else{
                                echo $campaign['total_sms'];
                            }
                            ?>
                        </li>
                        <?php endif; ?>
                        <li class="conversion" data-title="<?php _e('Conversions :', 'cart-lift'); ?>">
                            <span class="conversion-no"><?php echo $campaign['recovered']; ?></span>
                            <span class="conversion-rate"><?php echo $campaign['percentage']; ?></span>
                        </li>
                        <li class="revenue" data-title="<?php _e('Revenue :', 'cart-lift'); ?>">
                            <?php echo $campaign['recovered_total']; ?>
                        </li>
                        <li class="action"  data-title="<?php _e('Action :', 'cart-lift'); ?>">
                            <?php if($enable_add_new_btn) { ?>
                                <!-- duplicate icon -->
                                <a class="duplicate duplicate-control" data-id="<?php echo $campaign['id']; ?>" title="<?php _e('Duplicate', 'cart-lift'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 17" width="17" height="17">
                                    <defs>
                                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                            <path d="M-1182 -275L138 -275L138 702L-1182 702Z" />
                                        </clipPath>
                                    </defs>
                                    <style>
                                        .duplicate-icon { fill: none;stroke: #ee8134;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.7 }
                                    </style>
                                    <g clip-path="url(#cp1)">
                                        <g id="1">
                                            <g>
                                                <g>
                                                    <path class="duplicate-icon" d="M5.58 5.46L10.15 5.46C10.73 5.46 11.21 5.94 11.21 6.52L11.21 11.21" />
                                                    <path id="Stroke 3" class="duplicate-icon" d="M5.58 5.46L3.05 5.46C2.47 5.46 2 5.94 2 6.52L2 13.62C2 14.2 2.47 14.67 3.05 14.67L10.15 14.67C10.73 14.67 11.21 14.2 11.21 13.62L11.21 11.21" />
                                                    <path id="Stroke 5" class="duplicate-icon" d="M5.58 5.46L5.58 3.05C5.58 2.47 6.06 2 6.64 2L13.74 2C14.32 2 14.79 2.47 14.79 3.05L14.79 10.15C14.79 10.73 14.32 11.21 13.74 11.21L11.21 11.21" />
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </a>
                            <?php } ?>

                            <!-- edit icon -->
                            <?php if( !$pro_campaign_list && $i > 2 ){ ?>
                                <a class="edit disabled" href="#"  title="<?php _e('Edit', 'cart-lift'); ?>" >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 17" width="17" height="17">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-1214 -270L106 -270L106 228L-1214 228Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            .edit-shp0 { fill: none;stroke: #6e42d3;stroke-linejoin:round;stroke-width: 1.5 }
                                            .edit-shp1 { fill: none;stroke: #6e42d3;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                                        </style>
                                        <g id="  Campaigns " clip-path="url(#cp1)">
                                            <g id="Group 9">
                                                <g id="1">
                                                    <g id="view">
                                                        <g id="edit">
                                                            <path id="Path" class="edit-shp0" d="M12.8 9.6L12.8 12.8C12.8 13.46 12.26 14 11.6 14L3.2 14C2.54 14 2 13.46 2 12.8L2 4.4C2 3.74 2.54 3.2 3.2 3.2L6.4 3.2" />
                                                            <path id="Path" class="edit-shp1" d="M11.6 2L14 4.4L8 10.4L5.6 10.4L5.6 8L11.6 2L11.6 2Z" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            <?php }else{ ?> 
                                <a class="edit"  title="<?php _e('Edit', 'cart-lift'); ?>" href="<?php
                                echo wp_nonce_url(add_query_arg( array(
                                    'action'     => 'email_templates',
                                    'sub_action' => 'edit_email_template',
                                    'id'         => $campaign['id'],
                                ),
                                    $page_url
                                ),
                                    'email_template_nonce'
                                );
                                
                                ?>" <?php echo $status_disable; ?> >
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 17" width="17" height="17">
                                        <defs>
                                            <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                                                <path d="M-1214 -270L106 -270L106 228L-1214 228Z" />
                                            </clipPath>
                                        </defs>
                                        <style>
                                            .edit-shp0 { fill: none;stroke: #6e42d3;stroke-linejoin:round;stroke-width: 1.5 }
                                            .edit-shp1 { fill: none;stroke: #6e42d3;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                                        </style>
                                        <g id="  Campaigns " clip-path="url(#cp1)">
                                            <g id="Group 9">
                                                <g id="1">
                                                    <g id="view">
                                                        <g id="edit">
                                                            <path id="Path" class="edit-shp0" d="M12.8 9.6L12.8 12.8C12.8 13.46 12.26 14 11.6 14L3.2 14C2.54 14 2 13.46 2 12.8L2 4.4C2 3.74 2.54 3.2 3.2 3.2L6.4 3.2" />
                                                            <path id="Path" class="edit-shp1" d="M11.6 2L14 4.4L8 10.4L5.6 10.4L5.6 8L11.6 2L11.6 2Z" />
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </a>
                            <?php } ?>

                            <!-- trash icon -->
                            <?php if( !$pro_campaign_list && $i > 2 ){ ?>
                                <a class="trash disabled" href="#"  title="<?php _e('Delete', 'cart-lift'); ?>">
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
                            <?php }else{ ?> 
                                <a class="trash cl-campaign-delete" href="#" title="<?php _e('Delete', 'cart-lift'); ?>">
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
                            <?php } ?>

                        </li>
                    </ul>

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
                                <p><?php echo __( 'Once deleted, you will not be able to recover this Campaign!', 'cart-lift' ); ?></p> 
                                <button class="cl-btn cl-alert-cancel"><?php echo __( 'Cancel', 'cart-lift' ); ?></button>

                                <a class="cl-btn trash delete" href="<?php
                                    echo wp_nonce_url(add_query_arg( array(
                                        'action'     => 'email_templates',
                                        'sub_action' => 'delete_email_template',
                                        'id'         => $campaign['id'],
                                    ),
                                        $page_url
                                    ),
                                        'email_template_nonce'
                                    );

                                    ?>"> 
                                     <?php echo __( 'Delete', 'cart-lift' ); ?>
                                </a>   
                            </div>
                        </div>
                    </div>

                    </div>
                    <!-- /single-campaign-wrapper -->

                <?php }
            }
            else {?>
                <ul class="no-campaign">
                    <li><?php echo __( 'No Campaign Found', 'cart-lift' ); ?></li>
                </ul>
            <?php }
            ?>
    </div>
</div>
