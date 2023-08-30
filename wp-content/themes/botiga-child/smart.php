<?php

class Smart {

    public function __construct()
    {
        /** create url for single product */
        add_action( "init", [ $this, "custom_post_type_rewrite_rules" ] );
        
        /** use template product page (page-product) for single product  */
        add_filter( "query_vars", [ $this, "prefix_register_query_var" ] );
        add_action( "template_redirect", [ $this, "prefix_url_rewrite_templates" ] );
        
        if( !is_admin() )
        {
            add_action( "botiga_before_page_the_content",  [ $this, "show_products" ] );
            add_action( "smart_products_by_category", [ $this, "show_products" ], 10, 1 );
            add_action( "smart_single_product", [ $this, "show_single_product" ] );
            add_action( "smart_cart", [ $this, "show_cart" ] );
        }
    }

    /**
     * get html of all the products
     */
    public function show_products( $category_name=NULL )
    {
        get_template_part( 'template-parts/smart', 'products' );
    }

    /**
     * get html for a single product
     */
    public function show_single_product()
    {
        global $wp;

        /**
         * matched_query = post_type=post&template=product&product_id={id}
         * get the {id} from the matched_query
         *  */ 
        $uri = explode( "&", $wp->matched_query );
        $product_id = explode( "=", $uri[ count($uri)-1 ] )[1];
        get_template_part( 'template-parts/smart', 'single-product', [ "product_id" => $product_id ] );
    }

    public function show_cart()
    {
        get_template_part( 'template-parts/smart', 'cart' );
    }

    /**
     * create url for single product
     */
    public function custom_post_type_rewrite_rules()
    {
        add_rewrite_rule(
            '^product/([^/]+)/?$',
            'index.php?post_type=post&template=product&product_id=$matches[1]',
            'top'
        );
    }

    /** add custom variable to query_vars */
    public function prefix_register_query_var( $vars )
    {
        $vars[] = "template";
        return $vars;
    }

    /** use page-product.php for single product */
    public function prefix_url_rewrite_templates()
    {
        if ( get_query_var( 'template' ) === "product" ) {
            add_filter( 'template_include', function() {
                return get_stylesheet_directory() . '/page-product.php';
            });
        }
    }
}

$smart = new Smart;