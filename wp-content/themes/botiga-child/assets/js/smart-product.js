/** get product id based on url  */
let smart_pid = 0;
if( window.location.pathname.includes( "product/" ) )
{
    smart_pid = parseInt( window.location.pathname.split( "/" )[2] );
}
smart_api.get_single_product( smart_pid ).then( product => {
    
    // get containers
    const product_container = document.querySelector( "#smart-single-product .product-container" );
    const product_template = product_container.querySelector( ".product" );
    const product_details = product_container.querySelector( ".details" );
    
    // clone containers
    let product_template_clone = product_template.cloneNode(true);
    let product_details_clone = product_details.cloneNode(true);
    
    // remove empty containers
    product_template.remove();
    product_details.remove();

    /**
     * fill product data and append to the main container
     */
    if( product["id"] === smart_pid )
    {
        product_template_clone = smart_content.fill_product( product_template_clone, product, false );
        product_details_clone = smart_content.fill_product_details( product_details_clone, product );
        document.querySelector( "#smart-single-product .product-container" ).appendChild( product_template_clone );
        document.querySelector( "#smart-single-product .product-container" ).appendChild( product_details_clone );

        smart_content.activate_button_add_to_cart( product_template_clone, product["id"] );
    }
} )