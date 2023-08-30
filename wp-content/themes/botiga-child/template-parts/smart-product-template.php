<?php
    $category = isset( $args[ "category_name" ] ) ? "data-cat=".$args[ "category_name" ] : "";
?>
<div class="product" <?php echo $category; ?>>
    <div class="brand"></div>
    <div class="title"></div>
    <div class="description"></div>
    <div class="thumbnail"></div>
    <div class="rating"></div>
    <div class="stock"></div>
    <div class="price"></div>
    
    <?php 
        if( isset( $args[ "nobutton" ] ) && $args[ "nobutton" ] ){}
        else {
     ?>
        <button class="add-to-cart"><?php _e( "Add to cart", "smart" ); ?></button>
    <?php } ?>

    <?php get_template_part( "template-parts/smart", "messages" ); ?>
</div>