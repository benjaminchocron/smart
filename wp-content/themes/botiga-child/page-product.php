<?php
/**
 * Template Name: product
 */

get_header();
?>

<main id="primary" class="site-main <?php echo esc_attr( apply_filters( 'botiga_content_class', '' ) ); ?>" <?php botiga_schema( 'blog' ); ?>>
    <?php do_action( "smart_single_product" ); ?>
</main>

<?php

do_action( 'botiga_do_sidebar' );
get_footer();