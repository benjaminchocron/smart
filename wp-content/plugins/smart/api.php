<?php

class Smart_Api {

    /**
     * the api endpoint
     */
    private $endpoint = "https://dummyjson.com/";

    /**
     * Request products
     */
    public function get_products()
    {
        $suffix = "products";
        if( isset( $_POST["category"] ) )
        {
            $suffix .= "/category/".htmlspecialchars( $_POST["category"] );
        }
        if( isset( $_POST["page"] ) )
        {
            $suffix .= "?skip=".htmlspecialchars( $_POST["page"] );
        }
        $products = $this->send( $suffix );
        $this->set_categories();
        echo $products;
        wp_die();
    }

    /**
     * get a specific product
     */
    public function get_product()
    {
        // search product
        if( isset( $_POST["search"] ) )
        {
            echo $this->send( "products/search?q=".rawurlencode( htmlspecialchars( $_POST["search"] ) ) );
        }
        // get product by id
        if( isset( $_POST["product_id"] ) )
        {
            echo $this->send( "product/".rawurlencode( htmlspecialchars( $_POST["product_id"] ) ) );
        }
        wp_die();
    }

    /**
     * update product page custom field
     */
     public function update_product_id()
     {
        if( isset( $_POST["product_id"] ) )
        {
            $pid = sanitize_text_field( $_POST["product_id"] );
            $args = [ "meta_key" => "product_id", "meta_value" => $pid ];
            $posts = get_posts($args);
            if( count( $posts ) === 0 )
            {
                $post_id = wp_insert_post( [ 
                        "post_title" => "Product ".$pid,
                        "post_content" => "Product",
                        "post_parent" => 13,
                        "post_status" => "publish",
                        "post_name" => $pid,
                        "page_template" => "product",
                        "meta_input" => [
                            "product_id" => $pid
                        ]
                    ] 
                );
            }
            else
            {
                $post_id = $posts[0]->ID;
            }

            // update_post_meta( 13, "product_id", "p".sanitize_text_field( $_POST["product_id"] ) );
        }
        wp_die();
     }

     /**
      *  get products to show in cart page
      */
     public function get_cart_products()
     {
        if( isset( $_POST["select"] ) )
        {
            $ids = explode( ",", htmlspecialchars( $_POST["select"] ) );
            $products = [];
            foreach( $ids as $id )
            {
                $products[] = $this->send( "product/".$id );
            }
            echo wp_json_encode( $products );
        }
        wp_die();
     }

    public function process_checkout()
    {
        if( isset( $_POST["products"] ) )
        {
            $products_list = json_decode( stripslashes( $_POST["products"] ), true ) ;
            echo $this->send( "carts/add", json_encode( [
                    // "userId" => get_current_user_id() || 1,
                    "userId" => 1,
                    "products" => array_values( $products_list )
                ] )
            );
        }
        wp_die();
    }

    /**
     * Add categories in category list based on brand name
     * 
     * @param string $products 
     */
    private function set_categories()
    {
        if( apply_filters( "smart_redo_factory",  get_option( "smart_categories_did_factory" ) === false ) )
        {
            /** get api categories */
            $api_categories = $this->send( "products/categories" );
            $categories = json_decode( $api_categories );

            /** add missing categories to post categories list */
            $sc = get_categories();
            $site_categories = wp_list_pluck( $sc, "name" );
            foreach( $categories as $category )
            {
                if( !in_array( $category, $site_categories ) )
                {
                    wp_insert_category( [
                        "cat_name" => $category,
                        "category_nicename" => $category
                    ] );
                }
            }
            add_option( "smart_categories_did_factory", true );
        }
    }

    /**
     * curl api requests
     * 
     * @return string
     */
    public function send( $url_suffix, $data=NULL )
    {
        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $this->endpoint.$url_suffix );
        curl_setopt($ch, CURLOPT_HEADER, false);
        if( !is_null( $data ) )
        {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
        }
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        $response = curl_exec($ch);
        curl_close($ch);

        if( !curl_errno( $ch ) )
        {
            return $response;
        } else {
            return wp_json_encode( ["error" => curl_error($ch) ] );
        }
    }

    /**
     * Retrieve the endpoint
     * 
     * @return string
     */
    public function get_endpoint()
    {
        return $this->endpoint;
    }
}

$smart_api = new Smart_Api;