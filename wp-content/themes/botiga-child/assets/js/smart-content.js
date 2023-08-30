/**
 * Handle html content
 */
class Smart_Content {

    /**
     * fill a product data in html
     * 
     * @param string container
     * @param {*} product
     * 
     * @return html
     */
    fill_product( container, product, with_link=true )
    {
        /* 
            if in category page fill products that are in that category
            else fill all products
        */
        const texts_no_prefix = [ "brand", "title", "description" ];
        const texts =  [ ...texts_no_prefix, "rating", "stock", "price" ];
        for( let text of texts ){
            let content_text = "";
            /** Add prefix to text */
            if( !texts_no_prefix.includes( text ) )
            {
                content_text = text[0].toUpperCase() + text.slice(1,text.length) + ": ";
            }
            content_text += product[text];
            
            /** Manually add currency */
            if( text === 'price' )
            {
                content_text += " NIS";
            }


            let content = document.createTextNode( content_text );
            /** if needed add a link to single product for title or description */
            if( with_link && [ 'title', 'description' ].includes( text ) )
            {
                let content_link = document.createElement( "a" );
                content_link.title = product["title"];
                content_link.href = "/product/"+product["id"];
                content_link.classList.add( "no-underline" );
                content_link.appendChild( content );

                // add event listener to link to update custom field
                this.update_product_id( content_link, product["id"] );
                
                content = content_link;
            }
            container.querySelector( "."+text ).appendChild( content );
        }

        /** add the main image */
        let image = document.createElement( "img" );
        image.src = product.thumbnail;
        image.alt = product.title;
        container.querySelector( ".thumbnail" ).appendChild( image );
        
        return container;
    }

    fill_product_details( container, product )
    {
        const images_container = container.querySelector( ".images" );
        for( let image of product.images )
        {
            const img = document.createElement( "img" );
            img.src = image;
            img.alt = product["title"];
            images_container.appendChild( img );
        }
        return container;
    }

    update_product_id( content_link, product_id )
    {
        content_link.addEventListener( "click", (event) => {
            smart_api.update_product_id( product_id ).then( () => {
                console.log(event.currentTarget);
            } );
        } );
    }

    /**  after showing the product add event to the add to cart button */
    activate_button_add_to_cart( container, product_id=0 )
    {
        const button = container.querySelector( "button" );
        // const $this = this;
        // button.addEventListener( "click", function(){ $this.active( container ); } );
        button.addEventListener( "click", () => this.add_to_cart( container, product_id ) );
    }

    /**  process add to cart */
    async add_to_cart( container, product_id )
    {
        // get the cart products_ids
        let products_ids = localStorage.getItem( "products_ids" );
        products_ids = products_ids === null ? [] : products_ids.split( "," );

        if( product_id !== 0 )
        {
            products_ids.push( product_id );    
        }
        else
        {
            /**
             * look for product id by searching is title
             * add product_id to cart array
             */
            let products = await smart_api.get_product( container.querySelector( ".title" ).textContent );
            products.products.forEach( product => {
                products_ids.push( product.id ); 
            });
        }

        // store the cart products ids
        localStorage.setItem( "products_ids", products_ids.join( "," ) );

        this.show_message( container, "success" );
    }

    /** add listener to checkout button  */
    activate_checkout( button, container, products )
    {
        button.addEventListener( "click", () => this.process_checkout( products, container ) );
    }

    process_checkout( products, container )
    {
        smart_api.add_cart( products ).then( ( response ) => {
            if( !response.error ) {
                this.show_message( container, "success" );
                localStorage.removeItem( "products_ids" );
            }
        } );
    }

    /** show message after action completed like add to cart */
    show_message( container, message_id )
    {
        let message = container.querySelector( ".messages ."+message_id );
        message.classList.remove( "smart-hide" );
        setTimeout( () => { 
            message.classList.add( "smart-hide" );
         }, 5000 );
    }

    /** add pagination to the view */
    add_pagination( products_list )
    {
        const container = document.querySelector( "#smart-pagination .numbers" );
        const max_page = Math.floor( parseInt( products_list.total ) / parseInt( products_list.limit ) );
        
        /** get the url param smart_page */
        const params = new URLSearchParams( window.location.search );
        
        /** set default active_page to 1 */
        let active_page = 1;
        
        /** get current active page */ 
        if( params.size > 0 && params.get( "smart_page" ) )
        {
            const sp = parseInt( params.get( "smart_page" ) );
            if( sp !== 0 )
            {
                active_page = ( sp / parseInt( products_list.limit ) ) + 1;
            }
        }

        for( let i = 1; i <= max_page; i++ )
        {
            /** create button for each page */
            const button = document.createElement( "button" );
            button.classList.add( "page" );
            const button_text = document.createTextNode( i );
            button.appendChild( button_text );
            button.dataset.page = i;

            /**  highlight current active page button */
            if( i === active_page )
            {
                button.classList.add( "active" );
            }

            /** insert button before last */
            container.insertBefore( button, container.querySelector( ".last" ) );

            /** add event listener to button */
            this.activate_button_page( button, products_list );
        }

        const first = container.querySelector( ".first" );
        first.dataset.page=1;
        this.activate_button_page( first, products_list );

        const last = container.querySelector( ".last" );
        last.dataset.page=max_page;
        this.activate_button_page( last, products_list );
    }

    /** add event listener to pagination buttons */
    activate_button_page( button, products_list )
    {
        button.addEventListener( "click", () => this.go_to_page( button, products_list ) );
    }

    /** retrive products for specific page on the current category */
    go_to_page( button, products_list )
    {   
        const page_number = button.dataset.page;
        let skip = parseInt( products_list.limit ) * ( page_number-1 );
        skip = parseInt( products_list.total <= skip ) ? 0 : skip;
        
        /* redirect with parameter */
        const parser = new URL( window.location );
        parser.searchParams.set( "smart_page", skip );
        window.location = parser.href;
    }
}

const smart_content = new Smart_Content;