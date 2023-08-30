let products_ids = localStorage.getItem( "products_ids" );
if( products_ids )
{
    /** get products based on local storage cart */
    smart_api.get_cart_products( products_ids ).then( products => {
        const products_container = document.querySelector( "#smart-cart .cart-container" );
        const messages_container = document.querySelector( "#smart-cart .messages-container" );
        
        let product_template = products_container.querySelector( ".product" );
        products_container.querySelector( ".product" ).remove();
        
        /** loop through products
         * fill products and show them
         * calculate the total price
         */
        let products_total_price = 0;
        for( let product of products )
        {
            let current_product = product_template.cloneNode(true);
            product = JSON.parse( product );
            products_total_price += product["price"];
            current_product = smart_content.fill_product( current_product, product );
            products_container.appendChild( current_product );
        }

        // show total price
        const checkout_container = document.querySelector( "#smart-cart .checkout" );
        const price_container = checkout_container.querySelector( ".total-price .price" );
        const ptp = document.createTextNode( products_total_price+" NIS" );
        price_container.appendChild( ptp );
        
        // add event listener to checkout
        const checkout_button = checkout_container.querySelector( "button" );
        smart_content.activate_checkout( checkout_button, messages_container,  products );
    } );
}