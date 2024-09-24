<?php


/**
 * Class Cart_Lift_DB
 */

class Cart_Lift_DB
{
    /**
     * Member Variable
     *
     * @var object instance
     */
    private static $instance;


    /**
     *  Initiator
     */
    public static function get_instance()
    {
        if( !isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * create the necessary tables
     */
    public function create_tables()
    {
        $this->create_abandon_cart_table();
        $this->create_email_templates_table();
        $this->create_campaign_history_table();
        $this->init_default_email_templates();
    }

    /**
     * Update database on plugin update
     */
    public function cl_update_database()
    {
        $this->cl_modify_abandoned_cart_table();
        $this->cl_modify_email_templates_table();
        $this->cl_modify_campaign_history_table();

        update_option( 'cl_db_version', CART_LIFT_DB_VERSION );
    }


    /**
     * create table for carts
     */
    private function create_abandon_cart_table()
    {
        global $wpdb;
        $cl_cart_table   = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $cl_cart_table (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			email VARCHAR(100),
			session_id VARCHAR(60) NOT NULL,
			cart_contents LONGTEXT,
			order_id BIGINT(20),
			cart_total DECIMAL(10,2),
			cart_meta LONGTEXT NULL,
			status ENUM( 'processing', 'abandoned', 'recovered', 'completed', 'discard', 'lost' ) NOT NULL DEFAULT 'processing',
			schedular_status ENUM( 'active', 'paused' ) NOT NULL DEFAULT 'active',
			coupon_code VARCHAR(60) DEFAULT NULL,
			last_sent_email int DEFAULT 0,
			last_sent_sms int DEFAULT 0,
   			time DATETIME DEFAULT NULL,
   			unsubscribed boolean DEFAULT 0,
   			provider ENUM( 'edd', 'wc', 'lp' ) NOT NULL DEFAULT 'edd',
			PRIMARY KEY  (`id`, `session_id`),
			UNIQUE KEY (`session_id`)
		) $charset_collate;\n";

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        $res = dbDelta( $sql );
    }


    /**
     * create table for campaign
     */
    private function create_email_templates_table()
    {
        global $wpdb;
        $cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
        $charset_collate          = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$cl_email_templates_table} (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			template_name text NOT NULL,
			email_subject text NOT NULL,
			email_body mediumtext NOT NULL,
			twilio_sms ENUM( 'enabled','disabled') NOT NULL DEFAULT 'disabled',
			twilio_sms_body mediumtext NOT NULL,
			frequency int(10) NOT NULL,
			unit ENUM( 'minute','hour','day') NOT NULL DEFAULT 'minute',
			active boolean DEFAULT 1,
			email_meta longtext,
			created_at DATETIME DEFAULT NULL,
			PRIMARY KEY  (`id`)
		) $charset_collate;\n";

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }


    /**
     * create table for campaign history
     */
    private function create_campaign_history_table()
    {
        global $wpdb;
        $cl_cart_table             = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $cl_email_templates_table  = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;
        $charset_collate           = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $cl_campaign_history_table (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			campaign_id BIGINT(20) NOT NULL,
			session_id VARCHAR(60) NOT NULL,
			email_sent boolean DEFAULT 0,   
			sms_sent boolean DEFAULT 0,   
			coupon_code VARCHAR(60) DEFAULT NULL,
			schedule_time DATETIME DEFAULT NULL,
			recovered_cart boolean DEFAULT 0,   
			PRIMARY KEY  (`id`),
			FOREIGN KEY (`campaign_id`) REFERENCES $cl_email_templates_table(`id`) ON DELETE CASCADE,
			FOREIGN KEY (`session_id`) REFERENCES $cl_cart_table(`session_id`) ON DELETE CASCADE
		) $charset_collate;\n";

        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }


    /**
     * Init default email templates
     * when activated
     */
    private function init_default_email_templates()
    {
        global $wpdb;
        $cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;

        $is_campaign_exists = $wpdb->get_var( "SELECT COUNT(*) FROM $cl_email_templates_table" ); // phpcs:ignore

        if( !$is_campaign_exists ) {
            $sample_templates = array(
                array(
                    'template_name'   => __('Cart reminder', 'cart-lift'),
                    'email_subject'   => 'Finish your {{cart.product.names}} purchase',
                    'email_body'      => "<p>Looks like you left something behind!</p><p>We noticed you haven't completed your purchase for {{cart.product.names}}. Not to worry, there is still time to finish your checkout!</p><p>{{cart.product.table}}</p><p>{{cart.checkout_btn}}</p><p>More Products you might want to explore:</p><p>{{cart.related_products}}</p><p>Thanks</p><p>{{site.title}}</p>",
                    'twilio_sms'      => "disabled",
                    'twilio_sms_body' => "<p>Your {{cart.product.names}} is waiting for you over at {{site.title}}! Complete your purchase before someone else snags them! Checkout the link to finish your order now! {{cart.checkout_url}}</p>",
                    'frequency'       => 1,
                    'unit'            => 'hour',
                    'active'          => 1,
                    'email_meta'      => serialize( array(
                        'coupon'                => '',
                        'type'                  => '',
                        'amount'                => '',
                        'coupon_frequency'      => '',
                        'coupon_frequency_unit' => '',
                        'email_header_text'     => __( 'Please consider this cart', 'cart-lift' ),
                        'email_header_color'    => '#6e42d3',
                        'email_checkout_color'  => '#6e42d3',
                        'email_checkout_text'   => 'Checkout',
                    ) )
                ),
                array(
                    'template_name'   => __('Follow up', 'cart-lift'),
                    'email_subject'   => 'The {{cart.product.names}} you choose are selling fast!',
                    'email_body'      => "<p>Hey {{customer.fullname}},</p><p>Maybe you forgot, but {{cart.product.names}} is still waiting in the cart.</p><p>Get your favorite items now before they sell out!</p><p>{{cart.product.table}}</p><p>{{cart.checkout_btn}}</p><p>More Products you might want to explore:</p><p>{{cart.related_products}}</p><p>Thanks</p><p>{{site.title}}</p>",
                    'twilio_sms'      => "disabled",
                    'twilio_sms_body' => "<p>Your {{cart.product.names}} is waiting for you over at {{site.title}}! Complete your purchase before someone else snags them! Checkout the link to finish your order now! {{cart.checkout_url}}</p>",
                    'frequency'       => 1,
                    'unit'            => 'day',
                    'active'          => 1,
                    'email_meta'      => serialize( array(
                        'coupon'                => '',
                        'type'                  => '',
                        'amount'                => '',
                        'coupon_frequency'      => '',
                        'coupon_frequency_unit' => '',
                        'email_header_text'     => __( 'Please consider this cart', 'cart-lift' ),
                        'email_header_color'    => '#6e42d3',
                        'email_checkout_color'  => '#6e42d3',
                        'email_checkout_text'   => 'Checkout',
                    ) )
                ),
                array(
                    'template_name'   => __('Promotional discount', 'cart-lift'),
                    'email_subject'   => 'Final reminder to finish your {{cart.product.names}} purchase & save 10%',
                    'email_body'      => "<p>Hey {{customer.fullname}}, your cart is about to expire!!</p><p>Complete your order for {{cart.product.names}} today and get 10% off!!</p><p>Save on your favorite products now!!</p><p{{cart.product.table}}</p><p>{{cart.checkout_btn}}</p><p>More Products you might want to explore:</p><p>{{cart.related_products}}</p><p>Thanks</p><p>{{site.title}}</p>",
                    'twilio_sms'      => "disabled",
                    'twilio_sms_body' => "<p>Your {{cart.product.names}} is waiting for you over at {{site.title}}! Complete your purchase before someone else snags them! Checkout the link to finish your order now! {{cart.checkout_url}}</p>",
                    'frequency'       => 3,
                    'unit'            => 'day',
                    'active'          => 0,
                    'email_meta'      => serialize( array(
                        'coupon'                => 1,
                        'type'                  => 'percent',
                        'amount'                => '10',
                        'coupon_frequency'      => '',
                        'coupon_frequency_unit' => '',
                        'email_header_text'     => __( 'Please consider this cart', 'cart-lift' ),
                        'email_header_color'    => '#6e42d3',
                        'email_checkout_color'  => '#6e42d3',
                        'email_checkout_text'   => 'Checkout',
                    ) )
                ),
            );
            $index            = 1;

            foreach( $sample_templates as $template ) {

                $current_time = current_time( CART_LIFT_DATETIME_FORMAT );
                $wpdb->query(
                    $wpdb->prepare(
                        "INSERT INTO $cl_email_templates_table (`id`, `template_name`, `email_subject`, `email_body`, `twilio_sms`, `twilio_sms_body`, `frequency`, `unit`, `active`, `email_meta`, `created_at`) 
				        VALUES ( %d, %s, %s, %s, %s, %s, %d, %s, %d, %s, %s)",
                        $index++,
                        $template[ 'template_name' ],
                        $template[ 'email_subject' ],
                        $template[ 'email_body' ],
                        $template[ 'twilio_sms' ],
                        $template[ 'twilio_sms_body' ],
                        $template[ 'frequency' ],
                        $template[ 'unit' ],
                        $template[ 'active' ],
                        $template[ 'email_meta' ],
                        $current_time
                    )
                );
            }
        }
    }


    /**
     * Modify Cart Table
     */
    private function cl_modify_abandoned_cart_table()
    {
        global $wpdb;
        $cl_cart_table = $wpdb->prefix . CART_LIFT_CART_TABLE;
        $result        = array();
        $result        = $wpdb->get_results( "SELECT `id`, `status`, `provider` FROM  {$cl_cart_table}" );

        // Modify 'status' column
        $wpdb->query( "ALTER TABLE {$cl_cart_table} MODIFY COLUMN `status` ENUM( 'processing', 'abandoned', 'recovered', 'completed', 'discard', 'lost' ) NOT NULL DEFAULT 'processing'" );
        // Modify 'provider' column
        $wpdb->query( "ALTER TABLE {$cl_cart_table} MODIFY COLUMN `provider` ENUM( 'edd', 'wc', 'lp' ) NOT NULL DEFAULT 'edd'" );

        // Restore previous data for 'status' and 'provider' columns
        if( !empty( $result ) ) {
            foreach( $result as $res ) {
                $wpdb->update(
                    $cl_cart_table,
                    array(
                        'status'   => $res->status,
                        'provider' => $res->provider
                    ),
                    array( 'id' => $res->id )
                );
            }
        }

        // Add 'last_sent_sms' column after 'last_sent_email' column [if already not exists]
        $columns       = array();
        $columns       = $wpdb->get_col( "DESC {$cl_cart_table}", 0 );
        $column_exists = !empty( $columns ) ? array_search( 'last_sent_sms', $columns ) : false;

        if( !$column_exists ) {
            $query = "ALTER TABLE {$cl_cart_table} ADD COLUMN `last_sent_sms` int DEFAULT 0 AFTER `last_sent_email`";
            $wpdb->query( $query );
        }

        $is_schedular_status_exist = $wpdb->get_results("SHOW COLUMNS FROM {$cl_cart_table} LIKE 'schedular_status'");
        if (empty($is_schedular_status_exist)) {
            $wpdb->query("ALTER TABLE {$cl_cart_table} ADD COLUMN schedular_status ENUM('active', 'paused') NOT NULL DEFAULT 'active' AFTER status");
        }
    }


    /**
     * Modify Email Templates Table
     */
    private function cl_modify_email_templates_table()
    {
        global $wpdb;
        $cl_email_templates_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;

        // Add 'last_sent_sms' column after 'last_sent_email' column [if already not exists]
        $columns                       = array();
        $columns                       = $wpdb->get_col( "DESC {$cl_email_templates_table}", 0 );
        $twilio_sms_column_exists      = !empty( $columns ) ? array_search( 'twilio_sms', $columns ) : false;
        $twilio_sms_body_column_exists = !empty( $columns ) ? array_search( 'twilio_sms_body', $columns ) : false;

        // Add 'twilio_sms' column after 'email_body' column [if already not exists]
        if( !$twilio_sms_column_exists ) {
            $query = "ALTER TABLE {$cl_email_templates_table} ADD COLUMN `twilio_sms` ENUM( 'enabled','disabled') NOT NULL DEFAULT 'disabled' AFTER `email_body`";
            $wpdb->query( $query );

        }
        // Add 'twilio_sms_body' column after 'twilio_sms' OR 'email_body' column [if already not exists]
        if( !$twilio_sms_body_column_exists ) {
            $query    = "ALTER TABLE {$cl_email_templates_table} ADD COLUMN `twilio_sms_body` mediumtext NOT NULL AFTER `email_body`";
            $wpdb->query( $query );

            $default_sms_body_texts = "Your {{cart.product.names}} is waiting for you over at {{site.title}}! Complete your purchase before someone else snags them! Checkout the link to finish your order now! {{cart.checkout_url}}";
            $ids = array();
            $ids = $wpdb->get_results( "SELECT `id` FROM {$cl_email_templates_table}" );

            if( !empty($ids) ) {
                foreach( $ids as $id ) {
                    $wpdb->update(
                        $cl_email_templates_table,
                        array( 'twilio_sms_body' => $default_sms_body_texts ),
                        array( 'id' => (int)$id->id )
                    );
                }
            }
        }
    }


    /**
     * Modify Campaign History Table
     */
    private function cl_modify_campaign_history_table()
    {
        global $wpdb;
        $cl_campaign_history_table = $wpdb->prefix . CART_LIFT_CAMPAIGN_HISTORY_TABLE;

        $columns       = array();
        $columns       = $wpdb->get_col( "DESC {$cl_campaign_history_table}", 0 );
        $column_exists = !empty( $columns ) ? array_search( 'sms_sent', $columns ) : false;

        // Add 'sms_sent' column after 'email_sent' column [if already not exists]
        if( !$column_exists ) {
            $query = "ALTER TABLE {$cl_campaign_history_table} ADD COLUMN `sms_sent` boolean DEFAULT 0 AFTER `email_sent`";
            $wpdb->query( $query );
        }
    }
}


Cart_Lift_DB::get_instance();