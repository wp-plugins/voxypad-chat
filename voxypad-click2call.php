<?php
/*
Plugin Name: VoxyPAD Click2Call (Chat/Call)
Plugin URI: https://voxypad.com/
Description: VoxyPAD Click2Call service, built on collaborative technology, is an platform for sharing data, images with annotation while on chat/call. 
Version: 1.0
Author: VoxyPAD
Author URI: https://www.voxypad.com/
*/

/**
 * VoxyPAD Admin Settings
 */
add_action('admin_menu', 'voxypad_click2call_plugin_menu');
add_action('admin_init', 'voxypad_click2call_admin_init');

// Define Menu Setting in WP Site
function voxypad_click2call_plugin_menu() {
    add_options_page('VoxyPAD Click2Call Options', 'VoxyPAD Click2Call', 'manage_options',
        'voxypad_click2call', 'voxypad_click2call_plugin_options');
}

// Define a Variable to save Setting
function voxypad_click2call_admin_init() {
    register_setting('voxypad-click2call-services', 'voxypad_box_code');
}

// Create form to get setting option
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
                    <th scope="row"><label for="voxypad_box_code">Enter chat or voice box code:</label> </th>
                    <td>
                        <textarea rows="5" class="large-text code" id="voxypad_box_code" name="voxypad_box_code"
                                  placeholder="Paste code here..."><?php echo get_option('voxypad_box_code');?></textarea>
                        <br/>
                        <ul style="list-style:inside none disc;">
                            <li><small>Insert the generated code. 
                    To get the code, login into <a title="VoxyPAD Website" href="https://voxypad.com">Voxypad.com</a> web site. 
                    Upon logged in, in the <b>Dashboard</b>, cut and paste the <b>"Chat box"</b> or <b>"Call box"</b> code.<br> If the code is not existing, select <b>"New Order"</b> to generate the code. 
                            Please, insert only one code (either chat or call box). 
                            The call box code will also include the chat service.</small></li>
                        </ul>
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
        // Render Box Code
        $box_code = get_option('voxypad_box_code');
        $content .= $box_code;       

        $content .= '</aside>';
        
        echo $content;
    }
}