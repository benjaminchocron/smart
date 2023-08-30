/**
 * get current page category
 */
let category = "";
if( document.querySelector( "#smart-products .product" ) !== null )
{
    category = document.querySelector( "#smart-products .product" ).dataset.cat;
}
const params = new URLSearchParams( window.location.search );
let smart_page = 0;
if( params.size > 0 && params.get( "smart_page" ) )
{
    smart_page = params.get( "smart_page" );
}
/**
 * get and show products list on page load
 */
smart_api.get_products( category, smart_page ).then( products_list => {
    /* get all products container */
    const main_container = document.querySelector( "#smart-products" );
    
    /* save the product template in variable product_clone */
    const product_clone = main_container.querySelector( ".product" ).cloneNode(true);
    
    /* clear main container */
    main_container.innerHTML = "";

    /* add pagination */
    smart_content.add_pagination( products_list );
    for( const product of products_list.products ) {
        /*
         * clone the template for each iteration
         * Fill the product template
         * Append the product
        */
        let product_container = product_clone.cloneNode(true);
        product_container = smart_content.fill_product( product_container, product );
        main_container.appendChild( product_container );
        
        /** add event listener to add to cart button */
        smart_content.activate_button_add_to_cart( product_container );
    };
} );

