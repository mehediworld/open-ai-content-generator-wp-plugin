<?php
/*
Plugin Name: OpenAI Content Generator
Description: A plugin to generate content using OpenAI API
Version: 1.0
Author: Mehedi
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'OPENAI_CONTENT_GENERATOR_DIR', plugin_dir_path( __FILE__ ) );

// Enqueue plugin CSS
function openai_content_generator_styles() {
    wp_enqueue_style( 'openai-content-generator', plugins_url( 'css/openai-content-generator.css', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'openai_content_generator_styles' );

// Enqueue plugin JS
function openai_content_generator_scripts() {
    wp_enqueue_script( 'openai-content-generator', plugins_url( 'js/openai-content-generator.js', __FILE__ ), array('jquery'), '1.0.0', true );
    wp_localize_script( 'openai-content-generator', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'admin_enqueue_scripts', 'openai_content_generator_scripts' );

// Include the class files
require_once OPENAI_CONTENT_GENERATOR_DIR . 'inc/openai-api.php';
require_once OPENAI_CONTENT_GENERATOR_DIR . 'inc/class-openai-content-generator.php';

// Initialize our plugin
OpenAI_Content_Generator::get_instance();
