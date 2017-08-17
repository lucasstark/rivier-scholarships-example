<?php
get_header();

if ( have_posts() ) :

	while ( have_posts() ) : the_post();
		$profile = Rivier_Scholarships()->server->riv_scholarship->get( get_the_ID() );

		?>

		<?php echo $profile->get_display_title(); ?>

		<?php
	endwhile;

endif;

get_footer();