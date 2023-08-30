<section id="smart-cart">
    <h1 class="entry-title page-title"><?php echo get_the_title(); ?></h1>
    <div class="cart-container smart-products">
        <?php get_template_part( "template-parts/smart", "product-template", [ "nobutton" => true ] ); ?>
    </div>
    <div class="checkout">
        <div class="total-price"><?php _e( "Total:", "smart" ); ?> <span class="price"></span></div>
        <button><?php _e( "Process checkout", "smart" ); ?></button>
    </div>
    <div class="messages-container">
        <?php get_template_part( "template-parts/smart", "messages" ); ?>
    </div>
</section>