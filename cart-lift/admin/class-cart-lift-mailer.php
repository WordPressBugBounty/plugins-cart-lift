<?php

/**
 * Class Cart_Lift_Mailer
 */
class Cart_Lift_Mailer
{
    /**
     *
     */
    public function cart_lift_smtp( $phpmailer )
    {

        $cl_mail  = get_option( 'cl_other_mailer' );
        $fromname = $cl_mail[ 'fromname' ];
        $fromname = str_replace( '+', ' ', $fromname );


        if( !is_email( $cl_mail[ "from" ] ) || empty( $cl_mail[ "host" ] ) ) {
            return;
        }

        $phpmailer->Mailer   = "smtp";
        $phpmailer->From     = $cl_mail[ "from" ];
        $phpmailer->FromName = $fromname;
        $phpmailer->Sender   = $phpmailer->From;                          //Return-Path
        $phpmailer->AddReplyTo( $phpmailer->From, $phpmailer->FromName ); //Reply-To
        $phpmailer->Host       = $cl_mail[ "host" ];
        $phpmailer->SMTPSecure = $cl_mail[ "smtpsecure" ];
        $phpmailer->Port       = $cl_mail[ "port" ];
        $phpmailer->SMTPAuth   = ( $cl_mail[ "smtpauth" ] == "yes" ) ? TRUE : FALSE;

        if( $phpmailer->SMTPAuth ) {
            $phpmailer->Username = $cl_mail[ "username" ];
            $phpmailer->Password = $cl_mail[ "password" ];
        }
    }

    /**
     *
     */
    public function cart_lift_submit_form( $options )
    {
        $options[ "from" ]       = sanitize_email( trim( $options[ "from" ] ) );
        $options[ "fromname" ]   = sanitize_text_field( trim( $options[ "fromname" ] ) );
        $options[ "host" ]       = sanitize_text_field( trim( $options[ "host" ] ) );
        $options[ "smtpsecure" ] = sanitize_text_field( trim( $options[ "smtpsecure" ] ) );
        $options[ "port" ]       = is_numeric( trim( $options[ "port" ] ) ) ? trim( $options[ "port" ] ) : '';
        $options[ "smtpauth" ]   = sanitize_text_field( trim( $options[ "smtpauth" ] ) );
        $options[ "username" ]   = sanitize_text_field( trim( $options[ "username" ] ) );
        $options[ "password" ]   = sanitize_text_field( trim( $options[ "password" ] ) );
        update_option( "cl_other_mailer", $options );
        return array(
            'status'  => 'success',
            'message' => __('Successfully Saved', 'cart-lift'),
        );
    }

    /**
     *
     */
    public function cart_lift_mail_test( $to )
    {

        global $wpdb;
        $cl_email_table = $wpdb->prefix . CART_LIFT_EMAIL_TEMPLATE_TABLE;
        $templates      = $wpdb->get_results( 'SELECT * FROM ' . $cl_email_table . ' WHERE active = 1 ORDER BY unit ASC, `frequency` ASC' );
        foreach( $templates as $tmvalue ) {
            $to      = sanitize_text_field( trim( $to ) );
            $subject = $tmvalue->email_subject;
            $message = $tmvalue->email_body;
            $payload = array(
                'email_subject' => $subject,
                'email_body'    => $message,
                'send_to'       => $to
            );

            if( !empty( $to ) && is_email( $to ) && !empty( $subject ) && !empty( $message ) ) {
                try {
                    $response = cl_send_email_templates( null, $payload, true );
                }
                catch( Exception $e ) {
                    $error = $e->getMessage();
                }
            }
        }

        if( !$error ) {
            if( $response === true ) {
                return array(
                    'status'  => 'success',
                    'message' => 'Mail Sent',
                );
            } else {
                return array(
                    'status'  => 'error',
                    'message' => 'Failed to sent mail',
                );
            }
        }
    }

}
