<?php
function my_theme_enqueue_styles() {
    global $wp;
    
    // add theme parent and child style file 
    $parent_style = 'botiga-style-min'; // This is 'parent-style' for the oceanwp theme.
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/assets/css/styles.min.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );


    if( !is_admin() )
    {
        /** enqueue scripts for home page and categories  */
        if( is_front_page() || is_archive() )
            smart_enqueue_scripts();

        /** enqueue scripts for single product  */
        if( !is_bool( strpos( $wp->request, "product/" ) ) )
            smart_enqueue_scripts( "product" );

        /** enqueue scripts for cart  */
        if( !is_bool( strpos( $wp->request, "cart" ) ) )
            smart_enqueue_scripts( "cart" );
    }
}

/**  enqueue main srcipts */
function smart_enqueue_scripts( $name = "" )
{
    $file_name = "smart";
    $js_name = $file_name."-js";
    if( $name !== "" )
    {
        $file_name .= "-".$name;
        $js_name .= "-".$name;
    }
    wp_enqueue_script("smart-js-api", get_stylesheet_directory_uri()."/assets/js/smart-api.js", [], false, true);
    wp_enqueue_script("smart-js-content", get_stylesheet_directory_uri()."/assets/js/smart-content.js", [ "smart-js-api" ], false, true);
    wp_enqueue_script($js_name, get_stylesheet_directory_uri()."/assets/js/".$file_name.".js", [ "smart-js-api", "smart-js-content" ], false, true);
    wp_localize_script( $js_name, 'smart',
        array( 
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'is_archive' => is_archive()
        )
    );
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

include( "smart.php" );