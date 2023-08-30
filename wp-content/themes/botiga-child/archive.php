<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Botiga-child
 */

get_header();
$category =  get_category( get_query_var( 'cat' ) );
?>

<main id="primary" class="site-main <?php echo esc_attr( apply_filters( 'botiga_content_class', '' ) ); ?>" <?php botiga_schema( 'blog' ); ?>>
    <?php do_action( "smart_products_by_category", [ $category->name ] ); ?>
</main>

<?php

do_action( 'botiga_do_sidebar' );
get_footer();