<?php
/**
 * Cart lift tabs
 */

$tab_view->show_messages();

?>

<div class="wrap cl-tabs">
    <?php 
        $action = $tab_view->get_action(); 
        $get_pro_tab = $tab_view->is_cl_pro_active();
    ?>

    <div class="tab-header">
        <div class="logo">
            <?php
            $report_tab_url = add_query_arg( array(
                'page' => 'cart_lift',
                'action' => 'dashboard'
            ), admin_url( '/admin.php' ) );

            ?>
            <a href="<?php echo $report_tab_url; ?>">
                <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 346.44 440.97"><defs><style>.cls-1{fill:#6e42d3;}.cls-2{fill:#6645ce;}</style></defs><path class="cls-1" d="M313.33,79.74l-22.55-4a4.77,4.77,0,0,1-.16.89c-1.43,6.68-7.35,11.08-13.23,9.82s-9.48-7.7-8-14.38a.83.83,0,0,1,0-.15l-61.49-11a11.79,11.79,0,0,1-.24,1.64c-1.33,6.21-7.07,10.24-12.83,9s-9.35-7.27-8-13.47c.07-.31.14-.61.23-.9l-26.77-4.76a8.09,8.09,0,0,0-9.08,5.37l-18.27,53.79c28.67,20.6,89.85,53.4,157.25,21.23,0,0-56,68.09-164.28-.51l-22.76,67a9.92,9.92,0,0,0,7.65,13l190.68,34A9.91,9.91,0,0,0,313.07,237l1.49-32L297,202.38a7.57,7.57,0,0,1,1.09-15.07,7.16,7.16,0,0,1,1.13.08l16,2.42.71-15.23-2.7-.44a7.57,7.57,0,0,1,1.12-15,7.16,7.16,0,0,1,1.13.08l1.15.17.71-15.31-2.94-.44a7.57,7.57,0,0,1,1.13-15,7.11,7.11,0,0,1,1.12.08l1.4.21,1.9-40.79A8.1,8.1,0,0,0,313.33,79.74Zm-97.56,23.4c-.63,11-8.22,19.46-16.94,19s-15.29-9.81-14.66-20.79,8.23-19.46,17-19,15.24,9.79,14.6,20.79Zm58.66,11.11c-1.59,10.87-9.89,18.67-18.54,17.41s-14.37-11.11-12.78-22S253,91,261.64,92.25,276,103.35,274.43,114.25Z" transform="translate(0.01 0)"/><path class="cls-1" d="M268.84,112.47c-.62,6.19-5.72,10.75-11.39,10.18s-9.75-6-9.13-12.24S254,99.66,259.7,100.23a9.48,9.48,0,0,1,4.59,1.73,3.83,3.83,0,0,0,2,6.8,3.74,3.74,0,0,0,2.23-.46A12.27,12.27,0,0,1,268.84,112.47Z" transform="translate(0.01 0)"/><path class="cls-1" d="M210.07,102.73c-.62,6.2-5.71,10.76-11.38,10.19s-9.76-6.05-9.14-12.24,5.72-10.76,11.38-10.19a9.62,9.62,0,0,1,4.61,1.73,3.83,3.83,0,0,0,4.23,6.36A12.15,12.15,0,0,1,210.07,102.73Z" transform="translate(0.01 0)"/><path class="cls-1" d="M195.93,66.47h0c2.18.39,4.22-.52,4.68-2.09,1.68-5.62,6.3-18.54,15.46-27.82C237.88,14.47,275.44,14,274,77c0,1.91,1.84,3.7,4.38,4.15h0c3,.53,5.62-1,5.7-3.19.34-10.12-.44-36.43-16.16-53.32-19.79-21.28-45.24-5-45.24-5s-21.39,12.85-30.36,42.76C191.82,64.14,193.47,66,195.93,66.47Z" transform="translate(0.01 0)"/><path class="cls-1" d="M291.84,171l25.81,3.89a7.57,7.57,0,0,1,6.36,8.61h0a7.57,7.57,0,0,1-8.61,6.36L289.59,186a7.57,7.57,0,0,1-6.36-8.61h0A7.57,7.57,0,0,1,291.84,171Z" transform="translate(0.01 0)"/><path class="cls-1" d="M298.6,202.67l25.81,3.89a7.57,7.57,0,0,1,6.36,8.61h0a7.57,7.57,0,0,1-8.61,6.36l-25.81-3.89A7.57,7.57,0,0,1,290,209h0A7.57,7.57,0,0,1,298.6,202.67Z" transform="translate(0.01 0)"/><path class="cls-1" d="M315.36,143.86l16.51,2.49a7.57,7.57,0,0,1,6.36,8.61h0a7.57,7.57,0,0,1-8.61,6.36l-16.55-2.49a7.57,7.57,0,0,1-6.36-8.61h0a7.57,7.57,0,0,1,8.61-6.37Z" transform="translate(0.01 0)"/><path class="cls-1" d="M337.89,177.34h0a7.57,7.57,0,0,1,6.36,8.61h0a7.57,7.57,0,0,1-8.61,6.36h0a7.57,7.57,0,0,1-6.36-8.61h0A7.57,7.57,0,0,1,337.89,177.34Z" transform="translate(0.01 0)"/><path class="cls-1" d="M299.24,265.51,95.14,227.14a12.59,12.59,0,0,1-10.31-11.58L96.3,24A12.69,12.69,0,0,0,86.07,11.48L15.93.26A11.3,11.3,0,0,0,2.24,10.49h0A14.49,14.49,0,0,0,13.57,25.68l58,8.8L60.07,226.14A25.4,25.4,0,0,0,79.7,249.25a11.62,11.62,0,0,0,4.22,1.67l210.37,39.47a12.93,12.93,0,0,0,15-9.54,12.69,12.69,0,0,0-9.49-15.23h0Z" transform="translate(0.01 0)"/><circle class="cls-1" cx="117.81" cy="293.37" r="20.07" transform="translate(-185.24 383.37) rotate(-84.66)"/><circle class="cls-1" cx="235.07" cy="314.19" r="20.07" transform="translate(-99.62 519) rotate(-84.66)"/><path class="cls-1" d="M59.42,73.28,20.67,67.72c-6.63-1-11.34-6.34-10.51-12h0C11,50.08,17,46.29,23.67,47.26l36.74,5.39Z" transform="translate(0.01 0)"/><path class="cls-1" d="M57.07,113.81l-29.25-4.17c-5-.73-8.39-5.91-7.56-11.55h0c.83-5.64,5.56-9.63,10.56-8.89l27.73,4.07Z" transform="translate(0.01 0)"/><path class="cls-2" d="M55.64,417.29q-.8,11-8.13,17.34T28.17,441Q15,441,7.52,432.12T0,407.86v-4.17a40.9,40.9,0,0,1,3.47-17.34,26,26,0,0,1,9.91-11.51,27.77,27.77,0,0,1,15-4q11.81,0,19,6.33t8.34,17.77H41.67q-.52-6.61-3.68-9.59t-9.63-3q-7,0-10.52,5T14.25,403v5.16q0,11.06,3.35,16.17t10.57,5.11q6.51,0,9.73-3t3.68-9.21Z" transform="translate(0.01 0)"/><path class="cls-2" d="M93.38,440A15,15,0,0,1,92,435.47,16.39,16.39,0,0,1,79.22,441a18,18,0,0,1-12.35-4.31A13.87,13.87,0,0,1,62,425.77q0-8.06,6-12.37T85.22,409h6.23v-2.91a8.36,8.36,0,0,0-1.8-5.62q-1.8-2.11-5.7-2.11A8.07,8.07,0,0,0,78.59,400a5.57,5.57,0,0,0-1.95,4.5H63.07a13.61,13.61,0,0,1,2.72-8.16,18,18,0,0,1,7.69-5.88,28,28,0,0,1,11.16-2.13q9.38,0,14.88,4.71t5.55,13.26v22q0,7.22,2,10.92v.8Zm-11.2-9.42a11.67,11.67,0,0,0,5.53-1.34,8.77,8.77,0,0,0,3.75-3.59v-8.72H86.39q-10.17,0-10.83,7v.8a5.42,5.42,0,0,0,1.78,4.17,6.92,6.92,0,0,0,4.83,1.68Z" transform="translate(0.01 0)"/><path class="cls-2" d="M144,402a36.8,36.8,0,0,0-4.87-.37q-7.69,0-10.08,5.2V440H115.45V389.3h12.8l.38,6q4.08-7,11.3-7a14.19,14.19,0,0,1,4.22.61Z" transform="translate(0.01 0)"/><path class="cls-2" d="M169.07,376.83V389.3h8.67v9.94h-8.67v25.31a6,6,0,0,0,1.08,4q1.08,1.22,4.13,1.22a21.6,21.6,0,0,0,4-.33v10.27a27.89,27.89,0,0,1-8.2,1.22q-14.25,0-14.53-14.39v-27.3h-7.48V389.3h7.41V376.83Z" transform="translate(0.01 0)"/><path class="cls-2" d="M224.07,428.72H254V440h-43.9V371.77h14Z" transform="translate(0.01 0)"/><path class="cls-2" d="M261.07,376.18a6.67,6.67,0,0,1,2-5,8.79,8.79,0,0,1,11.09,0,7.16,7.16,0,0,1,0,10.08,8.69,8.69,0,0,1-11,0A6.65,6.65,0,0,1,261.07,376.18ZM275.44,440h-13.6V389.3h13.59Z" transform="translate(0.01 0)"/><path class="cls-2" d="M290.3,440V399.24h-7.55V389.3h7.55V385q0-8.53,4.9-13.24T308.91,367a31.67,31.67,0,0,1,6.89.94l-.14,10.5a17.27,17.27,0,0,0-4.12-.42q-7.64,0-7.64,7.17v4.08H314v9.94H303.89V440Z" transform="translate(0.01 0)"/><path class="cls-2" d="M337.22,376.83V389.3h8.67v9.94h-8.67v25.31a6,6,0,0,0,1.08,4q1.08,1.22,4.13,1.22a21.6,21.6,0,0,0,4-.33v10.27a27.89,27.89,0,0,1-8.2,1.22q-14.25,0-14.53-14.39v-27.3h-7.43V389.3h7.41V376.83Z" transform="translate(0.01 0)"/></svg>
            </a>        
        </div>

        <div class="nav-tab-wrapper for-free-tab">
            <!-- dashboard tab nav -->
            <?php
            $tab_url = add_query_arg( array(
                'page' => 'cart_lift',
                'action' => 'dashboard'
            ), admin_url( '/admin.php' ) );
            ?>
            <a href="<?php echo $tab_url; ?>" class="nav-tab <?php if($tab_view->is_dashboard_tab_active) echo 'nav-tab-active'?>">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22" width="20" height="22">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-328 -29L1116 -29L1116 868L-328 868Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .report { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="Report" clip-path="url(#cp1)">
                        <g id="Group 13">
                            <path id="Stroke 1" class="report cl-shp" d="M9.5 11.32L9.5 4.3C9.5 3.58 10.02 3 10.65 3C11.29 3 11.81 3.58 11.81 4.3L11.81 11.32" />
                            <path id="Stroke 3" class="report cl-shp" d="M9.5 11.96L9.5 17.7C9.5 18.42 8.98 19 8.35 19C7.71 19 7.19 18.42 7.19 17.7L7.19 11.96" />
                            <path id="Stroke 5" class="report cl-shp" d="M3.15 11.32L3.35 11.32C4.12 11.32 4.74 10.64 4.74 9.81L4.74 7.53C4.74 6.8 5.29 6.2 5.97 6.2C6.64 6.2 7.19 6.8 7.19 7.53L7.19 11.32" />
                            <path id="Stroke 7" class="report cl-shp" d="M15.85 11.96L15.85 11.96C15.03 11.96 14.33 12.64 14.33 13.48L14.33 15.14C14.33 15.86 13.77 16.44 13.07 16.44C12.37 16.44 11.81 15.86 11.81 15.14L11.81 11.96" />
                            <path id="Stroke 9" class="report cl-shp" d="M2 11.64L3.15 11.64" />
                            <path id="Stroke 11" class="report cl-shp" d="M17 11.64L15.85 11.64" />
                        </g>
                    </g>
                </svg>
                <?php _e( 'Reports', 'cart-lift' ); ?>
            </a>

            <!-- leads tab nav -->
            <?php
            $tab_url = add_query_arg( array(
                'page' => 'cart_lift',
                'action' => 'carts'
            ), admin_url( '/admin.php' ) );
            ?>
            <a href="<?php echo $tab_url; ?>" class="nav-tab <?php if($tab_view->is_leads_tab_active) echo 'nav-tab-active'?>">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 22" width="20" height="22">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-453 -28L867 -28L867 956L-453 956Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .leads { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="Report-leads" clip-path="url(#cp1)">
                        <g id="Group6">
                            <g id="1copy4">
                                <g id="Group7">
                                    <path id="Stroke1" class="leads cl-shp" d="M6.08 6.82C6.08 4.71 7.8 3 9.91 3C12.02 3 13.73 4.71 13.73 6.82L13.73 7.64" />
                                    <path id="Stroke3" class="leads cl-shp" d="M13.73 7.64L16.35 7.64C17.2 7.64 17.83 8.43 17.65 9.26L15.8 17.8C15.65 18.5 15.03 19 14.31 19L5.38 19C4.66 19 4.04 18.5 3.89 17.8L2.03 9.26C1.85 8.43 2.48 7.64 3.33 7.64L10.93 7.64" />
                                    <path id="Stroke5" class="leads cl-shp" d="M5.71 14.26L16.53 14.26" />
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>

                <?php _e( 'Carts', 'cart-lift' ); ?>
            </a>

            <!-- analytics tab nav -->
            <?php
            // $tab_url = add_query_arg( array(
            //     'page' => 'cart_lift',
            //     'action' => 'analytics'
            // ), admin_url( '/admin.php' ) );
            ?>
            <!-- <a href="<?php echo $tab_url; ?>"
               class="nav-tab <?php if($tab_view->is_analytics_tab_active) echo 'nav-tab-active'?>">
                <?php _e( 'Analytics', 'cart-lift' ); ?>
            </a> -->

            <!-- email templates tab nav -->
            <?php
            $tab_url = add_query_arg( array(
                'page' => 'cart_lift',
                'action' => 'email_templates'
            ), admin_url( '/admin.php' ) );
            ?>
            <a href="<?php echo $tab_url; ?>" class="nav-tab <?php if($tab_view->is_email_tab_active) echo 'nav-tab-active'?>">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-666 -29L778 -29L778 868L-666 868Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .email { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="Report-email" clip-path="url(#cp1)">
                        <g id="Group6">
                            <g id="1copy5">
                                <g id="Group5">
                                    <path id="Stroke1" class="email cl-shp" d="M18 7.9L18 15.84C18 17.02 17.2 17.98 16.22 17.98L3.78 17.98C2.8 17.98 2 17.02 2 15.84L2 4.61C2 3.72 2.6 3 3.33 3L18 3L17.45 3.65" />
                                    <path id="Stroke 3" class="email cl-shp" d="M17.45 3.69L10 10.98L2.58 3.69" />
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                <?php _e( 'Campaigns', 'cart-lift' ); ?>
            </a>

            <!-- settings tab nav -->
            <?php
            $tab_url = add_query_arg( array(
                'page' => 'cart_lift',
                'action' => 'settings'
            ), admin_url( '/admin.php' ) );
            ?>
            <a href="<?php echo $tab_url; ?>" class="nav-tab <?php if($tab_view->is_settings_tab_active) echo 'nav-tab-active'?>">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 21" width="20" height="21">
                    <defs>
                        <clipPath clipPathUnits="userSpaceOnUse" id="cp1">
                            <path d="M-865 -28L579 -28L579 869L-865 869Z" />
                        </clipPath>
                    </defs>
                    <style>
                        .settings { fill: none;stroke: #a8a7be;stroke-linecap:round;stroke-linejoin:round;stroke-width: 1.5 }
                    </style>
                    <g id="Report" clip-path="url(#cp1)">
                        <g id="Group6">
                            <g id="1copy6">
                                <g id="Group21">
                                    <path id="Stroke1" class="settings cl-shp" d="M4.34 11C4.34 14.12 6.88 16.66 10 16.66C13.12 16.66 15.66 14.12 15.66 11C15.66 7.88 13.12 5.34 10 5.34C6.88 5.34 4.34 7.88 4.34 11Z" />
                                    <path id="Stroke3" class="settings cl-shp" d="M8.3 11C8.3 11.94 9.06 12.7 10 12.7C10.94 12.7 11.7 11.94 11.7 11C11.7 10.06 10.94 9.3 10 9.3C9.06 9.3 8.3 10.06 8.3 11Z" />
                                    <path id="Stroke5" class="settings cl-shp" d="M10 3L10 4.79" />
                                    <path id="Stroke7" class="settings cl-shp" d="M10 17.21L10 19" />
                                    <path id="Stroke9" class="settings cl-shp" d="M2 11L3.79 11" />
                                    <path id="Stroke11" class="settings cl-shp" d="M16.21 11L18 11" />
                                    <path id="Stroke13" class="settings cl-shp" d="M15.66 5.34L14.39 6.61" />
                                    <path id="Stroke15" class="settings cl-shp" d="M5.61 15.39L4.34 16.66" />
                                    <path id="Stroke17" class="settings cl-shp" d="M4.34 5.34L5.61 6.61" />
                                    <path id="Stroke19" class="settings cl-shp" d="M14.39 15.39L15.66 16.66" />
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>

                <?php _e( 'Settings', 'cart-lift' ); ?>
            </a>
            
            <!-- compare tab nav -->
            <?php if( ! $get_pro_tab ){
                $tab_url = add_query_arg( array(
                    'page' => 'cart_lift',
                    'action' => 'compare'
                ), admin_url( '/admin.php' ) );

                ?>
                <a href="<?php echo $tab_url; ?>" class="nav-tab <?php if($tab_view->is_compare_tab_active) echo 'nav-tab-active'?>">
                    <svg width="17px" height="17px" viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                            <g id="Report--Copy" class="cl-shp" transform="translate(-938.000000, -31.000000)" stroke="#A8A7BE" stroke-width="1.5">
                                <g id="Group-6" transform="translate(279.000000, 31.000000)">
                                    <g id="1-copy-6" transform="translate(535.000000, 0.000000)">
                                        <g id="Group-11" transform="translate(124.000000, 1.000000)">
                                            <path d="M8,0 C8.1196415,0 8.48394001,0 9.09289551,0 C9.69422516,0 10.5962196,0 11.798879,0 C12.4618978,0 13,0.565497495 13,1.26321108 L13,5.04863363 C13,7.66221736 10.9849193,10 8.49979981,10 C6.01468037,10 4,7.66221736 4,5.04863363 L4,0.722556739" id="Stroke-1"></path>
                                            <path d="M12,12.5 L12,12.6017305 C12,13.6494494 11.6074065,14.5 11.1233208,14.5 L4.87619509,14.5 C4.3921094,14.5 4,13.6494494 4,12.6017305 L4,12.5" id="Stroke-3"></path>
                                            <path d="M8,10 L8,14" id="Stroke-5"></path>
                                            <path d="M16.2042042,2 L16.2042042,6" id="Stroke-7"></path>
                                            <path d="M1,6 L1,2" id="Stroke-9"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>

                    <?php _e( 'free vs pro', 'cart-lift' ); ?>
                </a>
            <?php } ?>

            <?php if( $get_pro_tab ){
                $tab_url = add_query_arg( array(
                    'page' => 'cart_lift',
                    'action' => 'license'
                ), admin_url( '/admin.php' ) );

                ?>
                <a href="<?php echo $tab_url; ?>" class="nav-tab <?php if($tab_view->is_license_tab_active) echo 'nav-tab-active'?>">
                    <svg width="17px" height="17px" viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
                            <g id="Report--Copy" class="cl-shp" transform="translate(-938.000000, -31.000000)" stroke="#A8A7BE" stroke-width="1.5">
                                <g id="Group-6" transform="translate(279.000000, 31.000000)">
                                    <g id="1-copy-6" transform="translate(535.000000, 0.000000)">
                                        <g id="Group-11" transform="translate(124.000000, 1.000000)">
                                            <path d="M8,0 C8.1196415,0 8.48394001,0 9.09289551,0 C9.69422516,0 10.5962196,0 11.798879,0 C12.4618978,0 13,0.565497495 13,1.26321108 L13,5.04863363 C13,7.66221736 10.9849193,10 8.49979981,10 C6.01468037,10 4,7.66221736 4,5.04863363 L4,0.722556739" id="Stroke-1"></path>
                                            <path d="M12,12.5 L12,12.6017305 C12,13.6494494 11.6074065,14.5 11.1233208,14.5 L4.87619509,14.5 C4.3921094,14.5 4,13.6494494 4,12.6017305 L4,12.5" id="Stroke-3"></path>
                                            <path d="M8,10 L8,14" id="Stroke-5"></path>
                                            <path d="M16.2042042,2 L16.2042042,6" id="Stroke-7"></path>
                                            <path d="M1,6 L1,2" id="Stroke-9"></path>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </g>
                    </svg>

                    <?php _e( 'License', 'cart-lift' ); ?>
                </a>
            <?php } ?>
        </div>
        
        <div class="documentation">
            <?php 
                $doc_link = '';
                if ( $action == 'email_templates' ){
                    $doc_link = 'https://rextheme.com/docs/cart-lift/set-up-recovery-campaign/';
                }elseif ( $action == 'settings' ){
                    $doc_link = 'https://rextheme.com/docs/configure-and-use-cart-lift/';
                }elseif ( $action == 'carts' ){
                    $doc_link = 'https://rextheme.com/docs/cart-lift/abandoned-carts/';
                }else{
                    $doc_link = 'https://rextheme.com/docs/cart-lift/getting-started/cart-lift-dashboard-overview/';
                }
            ?>
            <a href="<?php echo $doc_link; ?>" class="cl-btn" target="_blank"><?php _e( 'documentation', 'cart-lift' ); ?></a>
        </div>
        
    </div>

    <div id="content" style="margin-top: 20px; ">
        <?php
            if($action == 'analytics') {
                $tab_view->print_analytics_tab_content();
            }elseif ($action == 'dashboard' || $action == '') {
                $tab_view->print_dashboard_tab_contents();
            }elseif ($action == 'email_templates') {
                $tab_view->print_email_tab_contents();
            }elseif ($action == 'settings') {
                $tab_view->print_settings_tab_content();
            }elseif ($action == 'carts') {
                $tab_view->print_carts_tab_content();
            }elseif ($action == 'compare' && !$get_pro_tab) {
                $tab_view->print_compare_tab_contents();
            }

            do_action('cl_pro_tabs_render', $action);
        ?>
    </div>
</div>
