<section id="smart-products" class="smart-products">
    <?php get_template_part( "template-parts/smart", "product-template", [ "category_name" => $category_name ] ); ?>
</section>
<section id="smart-pagination">
    <div class="numbers">
        <button class="first"><?php _e( "first page", "smart" ); ?></button>
        <button class="last"><?php _e( "last page", "smart" ); ?></button>
    </div>
</section>