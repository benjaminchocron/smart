/**
 * Handle Api requests
 */
class Smart_Api {

    /**
     * get products list as json
     * 
     * @param string category
     * 
     * @returns json
     */
    get_products( category, page )
    {
        const data = new FormData();
        data.append( 'action', 'smart_get_products' );
        if( category !== "" )
        {
            data.append( 'category', category );
        }
        if( page > 0 )
        {
            data.append( 'page', page );
        }
        return this.send( data );
    }

    /**
     * search for a specific product
     * 
     * @param string search 
     * @returns 
     */
    get_product( search )
    {
        const data = new FormData();
        data.append( 'action', 'smart_get_product' );
        data.append( 'search', search );
        return this.send( data );
    }

    /**
     * get products to show on cart page
     * 
     * @param string products_ids (eg 1,2,3) 
     * @returns 
     */
    get_cart_products( products_ids )
    {
        const data = new FormData();
        data.append( 'action', 'smart_get_cart_products' );
        data.append( 'select', products_ids );
        return this.send( data );
    }

    /**
     * process checkout with a new cart
     * 
     * @param object products 
     * @param int price 
     * @returns 
     */
    add_cart( products )
    {
        const data = new FormData();
        data.append( 'action', 'smart_add_cart' );
        
        let products_ids = []; 
        for( let product of products )
        {
            product = JSON.parse( product );
            
            if( products_ids.hasOwnProperty( product["id"] ) )
            {    
                products_ids[ product["id"] ]["quantity"] += 1;
            } else {
                products_ids[ product["id"] ] = [];
                products_ids[ product["id"] ]["quantity"] = 1;
                products_ids[ product["id"] ]["id"] = product["id"];
            }
            products_ids[ product["id"] ] = { ...products_ids[ product["id"] ] };
        }
        data.append( 'products', JSON.stringify( { ...products_ids } ) );
        return this.send( data );
    }

    /**
     * get product by id
     * 
     * @param int product_id 
     * @returns 
     */
    get_single_product( product_id )
    {
        const data = new FormData();
        data.append( 'action', 'smart_get_product' );
        data.append( 'product_id', product_id );
        return this.send( data );
    }

    /**
     * update page custom field with product id
     * 
     * @param int product_id 
     */
    update_product_id( product_id )
    {
        const data = new FormData();
        data.append( 'action', 'smart_update_product_id' );
        data.append( 'product_id', product_id );
        return this.send( data );
    }

    async send( data )
    {
        return await fetch( smart.ajax_url,
            {
                method: "POST",
                credentials: 'same-origin',
                body: data
            }
        )
        .then( response => response.json() );
    }
}

const smart_api = new Smart_Api;