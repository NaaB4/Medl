<?php get_header(); ?>

<div id="main-content" class="category-page">
	<div class="container">
		<div id="content-area" class="clearfix">
			<?php if ( have_posts() ) : ?>
					<header class="category-page-headline">
						<!--<h2>Kategorie:</h2>-->
					<h1><?php printf( __( '%s', 'Divi' ), '<span>' . single_cat_title() . '</span>' ); ?></h1>
					</header>
			<?php endif; ?>

			<div id="left-area">
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
					$post_format = et_pb_post_format(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>

				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_pb_post_main_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					et_divi_post_format_content();

					printf( '<div class="row et_pb_row et_pb_equal_columns"><div class="col-left et_pb_column et_pb_column_1_2">');

					if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
						if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) :
							printf(
								'<div class="et_main_video_container">
									%1$s
								</div>',
								et_core_esc_previously( $first_video )
							);
						elseif ( ! in_array( $post_format, array( 'gallery' ) ) && 'on' === et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : ?>
							<a class="entry-featured-image-url" href="<?php the_permalink(); ?>">
								<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
							</a>
					<?php
						elseif ( 'gallery' === $post_format ) :
							et_pb_gallery_images();
						endif;
					}
					printf( '</div><div class="col-right et_pb_column et_pb_column_1_2">');?>

				<?php if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) : ?>
					<?php if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) : ?>
						<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>

					<?php
						et_divi_post_meta();
						if ( has_excerpt() && 'off' !== $args['use_manual_excerpt'] ) {
							the_excerpt();
						} else {
							truncate_post( 270 );
						}
							printf( '<div class="et_pb_button_module_wrapper et_pb_module mt-1">
												<a href="%1$s" class="more-link et_pb_button" >%2$s</a>
											 </div>' , esc_url( get_permalink() ), esc_html__( 'Weiter lesen', 'et_builder' ) );
											 printf( '</div></div>');
					?>
				<?php endif; ?>

					</article> <!-- .et_pb_post -->
			<?php
					endwhile;

					if ( function_exists( 'wp_pagenavi' ) )
						wp_pagenavi();
					else
						get_template_part( 'includes/navigation', 'index' );
				else :
					get_template_part( 'includes/no-results', 'index' );
				endif;
			?>
			</div> <!-- #left-area -->
			<?php echo do_shortcode('[showmodule id="1093"]'); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->

<?php

get_footer();
