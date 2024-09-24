<?php
/**
 * Create Contact to MailMint
 *
 * @since 3.1.15
 */
class Cart_Lift_Create_Contact {

    protected $webHookUrl = [CART_LIFT_WEBHOOK_URL];

    /**
     * Email
     *
     * @var string
     * @since 3.1.15
     */
    protected $email = '';

    /**
     * Name
     *
     * @var string
     * @since 3.1.15
     */
    protected $name = '';


    /**
     * Industry
     *
     * @var string
     * @since 3.1.15
     */
    protected $industry = '';


    /**
     * Constructor
     *
     * @param string $email
     * @param string $name
     * @since 3.1.15
     */
    public function __construct( $email, $name ) {
        $this->email = $email;
        $this->name  = $name;
    }


    /**
     * Create contact to MailMint via webhook
     *
     * @return array
     * @since 3.1.15
     */
    public function create_contact_via_webhook(){
        if( !$this->email ){
            return [
                'suceess' => false,
            ];
        }

        $response = [
            'suceess' => true,
        ];

        $json_body_data = json_encode([
            'email'         => $this->email,
            'first_name'    => $this->name,
            'meta_fields'   => [
                'industry' => $this->industry,
            ],
        ]);

        try{
            if( !empty($this->webHookUrl ) ){
                foreach( $this->webHookUrl as $url ){
                    $response = wp_remote_request($url, [
                        'method'    => 'POST',
                        'headers' => [
                            'Content-Type' => 'application/json',
                        ],
                        'body' => $json_body_data
                    ]);
                }
            }
        } catch(\Exception $e){
            error_log('Error sending contact data to MailMint');
            $response = [
                'suceess' => false,
            ];
        }

        return $response;
    }
}
?>