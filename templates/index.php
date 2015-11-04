<?php get_header(); ?>

<main class="<?php echo $main_class; ?>">

	<?php get_template_part('parts/headers'); ?>

	<section class="row single gutter pad-ends">

		<div class="column one">

			<h2>CSANR Grants</h2>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<thead>
      		<tr>
          	<th>ID</th>
          	<th>Title</th>
          	<th>Principal Investigator(s)</th>
						<?php if ( is_tax( 'investigator' ) ) : ?>
						<th>Additional Personnel</th>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
				<?php
				global $query_string;
				query_posts( $query_string . '&posts_per_page=-1&order=ASC' ); ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<?php
						$investigator = wp_get_post_terms( $post_id, 'investigator' );
					?>
					<tr>
						<td>
							<?php $grant_id = get_post_meta( get_the_ID(), '_csanr_grant_project_id', true ); ?>
							<p><?php echo esc_html( $grant_id ); ?></p>
						</td>
						<td><p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
            <?php
						//delete_post_meta( get_the_ID(), '_grant' );
						?>

						</td>
						<td>
						<?php
							
						?>
						</td>
						<?php if ( is_tax( 'investigator' ) ) : ?>

						<?php endif; ?>
					</tr>
				<?php endwhile; // end of the loop. ?>
				<?php wp_reset_query(); ?>
				</tbody>
			</table>

		</div><!--/column-->

	</section>

	<?php get_template_part( 'parts/footers' ); ?>

</main>

<?php

get_footer();