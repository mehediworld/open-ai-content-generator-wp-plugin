<?php

class OpenAI_API {
    private $api_key;

    // Constructor
    public function __construct() {
        $this->api_key = get_option('openai_api_key');
    }

    public function generate_content($prompt) {
    $response = wp_remote_post( 'https://api.openai.com/v1/completions', array(
        'headers' => array(
            'Authorization' => 'Bearer ' . get_option( 'openai_api_key' ),
            'Content-Type' => 'application/json',
        ),
        'body' => json_encode( array(
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'max_tokens' => 1000,
            'temperature' => 0,
        ) ),
		'timeout' => 30, // Increase this value if needed
    ) );

    if ( is_wp_error( $response ) ) {
        error_log( 'OpenAI API Error: ' . $response->get_error_message() );
        return 'Error: ' . $response->get_error_message();
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if (isset($data['choices'][0]['text'])) {
        return $data['choices'][0]['text'];
    } else {
        error_log('OpenAI API Unexpected Response: ' . $body);
        return 'Error: Unexpected API response.';
    }
}


}
