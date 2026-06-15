<?php 


class Bison {

    public function __construct() {
        add_action('login_enqueue_scripts', array($this, 'enqueue_login_styles'));
        add_shortcode('built_by_bison', array($this, 'built_by_bison_shortcode'));
        add_action( 'vc_before_init', array($this, 'bison_custom_widgets') );
    }

    public function enqueue_login_styles() {
        wp_enqueue_style('bison-studio-login', get_stylesheet_directory_uri() . '/css/login.css', array(), '1', 'screen');
    }

    public function built_by_bison_shortcode()
    {
        ob_start();

        echo '<a class="built-by-bison" style="font-size: 10px;letter-spacing: 1px;text-transform: uppercase" target="_blank" href="https://bison.studio">Built by Bison</a>';

        return ob_get_clean();
    }

    public function bison_custom_widgets() {
        vc_map( array(
            "name" => __("Promotion Slider", "salient"),
            "base" => "built_by_bison", 
            "category" => __("Bison Studio", "salient"),
            "params" => array(
                // array(
                //     'type' => 'param_group',
                //     'heading' => __('Slides', 'salient'),
                //     'param_name' => 'slides',
                //     'params' => array(
                //         array(
                //             "type" => "textfield",
                //             "heading" => __("Promotion Number", "salient"),
                //             "param_name" => "promotion-number",
                //              "description" => __("Enter the Promotion number.", "salient")
                //         ),
                //     )
                // )
            )
        ));
    }

}

new Bison();