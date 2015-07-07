<?php
define ( BLOCK_CSS,'
    <style type="text/css">
        .voxypad-click2call-block div {
            display: inline-block;
            margin-right: 20px;
        }

        .voxypad-click2call-block div img {
            cursor: pointer;
        }
    </style>
');

define ( WIDGET_CSS,'
    <style type="text/css">
        .voxypad-click2call-widget div {
            margin: 0 0 30px 0;
            text-align: left !important;
        }

        .voxypad-click2call-widget div img {
            width: auto !important;
            height: 38px !important;
            margin: 0 0 !important;
            cursor: pointer;
        }
    </style>
');

/*
Plugin Name: VoxyPAD Click To Call
Plugin URI: https://voxypad.com/
Description: Add Button Call, Chat with VoxyPAD services (send file, image, notepad draw,...) in your Wordpress site
Version: 1.0
Author: TuanNguyen
Author URI: https://www.tuannguyen.archar.vn/
*/

/**
 * VoxyPAD Shortcodes
 */
add_action('init', 'voxypad_click2call_shortcodes');

function voxypad_click2call_shortcodes() {
    // Add CSS file
    //wp_register_style('voxypad-click2call-css', plugins_url('/voxypad-click2call-style.css', __FILE__));
    //wp_enqueue_style('voxypad-click2call-css');
    // Register ShortCode
    add_shortcode('voxypad-button', 'voxypad_click2call_button');
}

function voxypad_click2call_button($args, $content) {    
    $content = '<div class="voxypad-click2call-block">';
    // Render Button Voxy Chat Service: IF Attribute "voxychat" != "false" (default is "true")
    if(isset($args['voxychat']) == FALSE || (isset($args['voxychat']) && $args['voxychat'] == 'true') ) {
        $code_chat = get_option('voxypad_chat_service'); // get variable setting in form setting create above
        $content .= $code_chat;
    }
    // Render Button Voxy Call Service: IF Attribute "voxycall" != "false" (default is "true")
    if(isset($args['voxycall']) == FALSE || (isset($args['voxycall']) && $args['voxycall'] == 'true') ) {
        $code_call = get_option('voxypad_call_service');// get variable setting in form setting create above
        $content .= $code_call;
    }
    $content .= '</div>';
    $content .= BLOCK_CSS;    

    return $content;
}
/**
 * VoxyPAD Admin Settings
 */
add_action('admin_menu', 'voxypad_click2call_plugin_menu');
add_action('admin_init', 'voxypad_click2call_admin_init');

function voxypad_click2call_plugin_menu() {
    add_options_page('VoxyPAD Click2Call Options', 'VoxyPAD Click2Call', 'manage_options',
        'voxypad_click2call', 'voxypad_click2call_plugin_options');
}

function voxypad_click2call_admin_init() {
    register_setting('voxypad-click2call-services', 'voxypad_chat_service');
    register_setting('voxypad-click2call-services', 'voxypad_call_service');
}

function voxypad_click2call_plugin_options() {
    ?>
    <section class="voxypad-click2call-settings">
        <?php screen_icon();?>
        <h2>VoxyPAD Click2Call</h2>
        <form action="options.php" method="post">
            <?php settings_fields('voxypad-click2call-services');?>
            <?php @do_settings_fields('voxypad-click2call-services');?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="voxypad_chat_service">VoxyPAD Chat</label> </th>
                    <td>
                        <textarea rows="5" class="large-text code" id="voxypad_chat_service" name="voxypad_chat_service"
                                  placeholder="Paste code here..."><?php echo get_option('voxypad_chat_service');?></textarea>
                        <br/><small>Code is supported by <a href="https://voxypad.com/business">VoxyPAD Service</a> </small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="voxypad_call_service">VoxyPAD Call</label> </th>
                    <td>
                        <textarea rows="5" class="large-text code" id="voxypad_call_service" name="voxypad_call_service"
                                  placeholder="Paste code here..."><?php echo get_option('voxypad_call_service');?></textarea>
                        <br/><small>Code is supported by <a href="https://voxypad.com/business">VoxyPAD Service</a> </small>
                    </td>
                </tr>
            </table><?php @submit_button();?>
        </form>
    </section>
    <?php
}
/**
 * VoxyPAD Widget
 */
add_action('widgets_init', 'voxypad_widget_init');
// Register Widget
function voxypad_widget_init() {
    register_widget(Voxypad_Widget);
}

class Voxypad_Widget extends WP_Widget {
    function Voxypad_Widget() {
        $widget_options = array(
            'classname' => 'voxypad-widget',
            'description' => 'VoxyPAD Click2Call Widget'
        );
        $this->WP_Widget('voxypad_id', 'VoxyPAD Click2Call', $widget_options);
    }
    /**
     * Show widget in Post/Page
     */
    function widget($args, $instance) {
        $content = '<aside class="voxypad-click2call-widget widget widget_voxypad">';
        // Render Button Voxy Chat Service
        $code_chat = get_option('voxypad_chat_service');
        $content .= $code_chat;
        // Render Button Voxy Call Service
        $code_call = get_option('voxypad_call_service');
        $content .= $code_call;

        $content .= '</aside>';

        $content .= WIDGET_CSS;
        
        echo $content;
    }
}