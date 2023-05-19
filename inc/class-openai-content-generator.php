<?php

class OpenAI_Content_Generator {
    private static $instance;
    private $api;

    // Singleton
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Constructor
    private function __construct() {
        $this->api = new OpenAI_API();
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_post_openai_content_generator', array( $this, 'save_api_key' ) );
        add_action( 'wp_ajax_generate_content', array( $this, 'ajax_generate_content' ) );
    }

    // Add menu item
    public function admin_menu() {
        add_options_page( 'OpenAI Content Generator', 'OpenAI Generator', 'manage_options', 'openai-content-generator', array( $this, 'settings_page' ) );
    }

    // Save API Key
    public function save_api_key() {
        if ( isset( $_POST['openai_api_key'] ) && current_user_can( 'manage_options' ) ) {
            update_option( 'openai_api_key', sanitize_text_field( $_POST['openai_api_key'] ) );
        }
        wp_redirect( $_POST['_wp_http_referer'] );
    }

    // AJAX Handler
    public function ajax_generate_content() {
        if ( isset( $_POST['prompt'] ) && current_user_can( 'manage_options' ) ) {
            $content = $this->api->generate_content( sanitize_text_field( $_POST['prompt'] ) );
            wp_send_json_success( $content );
        } else {
            wp_send_json_error( 'Invalid prompt' );
        }
    }

    // Settings page
    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>OpenAI Content Generator</h1>
            <form method="POST" action="admin-post.php">
                <input type="hidden" name="action" value="openai_content_generator">
                <?php wp_nonce_field( 'openai_content_generator' ); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">
                            <label for="openai_api_key">OpenAI API Key</label>
                        </th>
                        <td>
                            <input type="text" id="openai_api_key" name="openai_api_key" value="<?php echo esc_attr( get_option( 'openai_api_key' ) ); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>

            <hr>

            <div class="openai-content-generator-wrapper">
                <form id="openai-generator-form">
                    <textarea id="openai-generator-prompt" placeholder="Enter your prompt..."></textarea>
                    <button type="submit" id="openai-generator-button">Generate Content</button>
                </form>
                <div id="openai-generator-result"></div>
            </div>
        </div>
        <?php
    }
}
