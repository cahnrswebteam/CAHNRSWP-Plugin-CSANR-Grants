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
					query_posts( $query_string . '&posts_per_page=-1&order=ASC&orderby=meta_value_num&meta_key=_csanr_grant_project_id' );
				?>
				<?php while ( have_posts() ) : the_post(); ?>
					<tr>
						<td>
							<?php $grant_id = get_post_meta( get_the_ID(), '_csanr_grant_project_id', true ); ?>
							<?php echo esc_html( $grant_id ); ?>
						</td>
						<td>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</td>
						<td><?php
							$grant_annual_entries = get_post_meta( get_the_ID(), '_csanr_grant_annual_entries', true );
							if ( $grant_annual_entries ) {
								$unique_pis = array();
								foreach ( $grant_annual_entries as $year => $entry ) {
									if ( $entry['principal_investigators'] ) {
										foreach ( $entry['principal_investigators'] as $pi ) {
											if ( ! in_array( $pi, $unique_pis ) ) {
												$unique_pis[] = $pi;
												$investigator_object = get_term_by( 'slug', $pi, 'investigator' );
												echo $investigator_object->name . '<br />';
											}
										}
									}
								}
							}
						?></td>
						<?php if ( is_tax( 'investigator' ) && $grant_annual_entries ) : ?>
						<td><?php
								$unique_ais = array();
								foreach ( $grant_annual_entries as $year => $entry ) {
									if ( $entry['additional_investigators'] ) {
										foreach ( $entry['additional_investigators'] as $ai ) {
											if ( ! in_array( $ai, $unique_ais ) ) {
												$unique_ais[] = $ai;
												$investigator_object = get_term_by( 'slug', $ai, 'investigator' );
												echo $investigator_object->name . '<br />';
											}
										}
									}
								}
							?></td>
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