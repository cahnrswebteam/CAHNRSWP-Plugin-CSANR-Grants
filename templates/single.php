<?php get_header(); ?>

<main>

	<?php get_template_part('parts/headers'); ?>

	<section class="row single gutter pad-ends">

		<div class="column one">

			<?php while ( have_posts() ) : the_post(); ?>

			<?php
      	// Grant meta data.
      	$grant_id               = get_post_meta( get_the_ID(), '_csanr_grant_project_id', true );
				$grant_annual_entries   = get_post_meta( get_the_ID(), '_csanr_grant_annual_entries', true );
				$grant_publications     = get_post_meta( get_the_ID(), '_csanr_grant_publications', true );
				$grant_additional_funds = get_post_meta( get_the_ID(), '_csanr_grant_additional_funds', true );
				$grant_impacts          = get_post_meta( get_the_ID(), '_csanr_grant_impacts', true );
				$grant_admin_comments   = get_post_meta( get_the_ID(), '_csanr_grant_admin_comments', true );
				// Grant taxonomic data.
				$status        = wp_get_post_terms( get_the_ID(), 'status', array( 'fields' => 'names' ) );
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<header class="article-header">
					<hgroup>
						<h1 class="article-title"><?php the_title(); ?></h1>
						<h2 class="grant-id">CSANR Project <?php echo esc_html( $grant_id ); ?></h2>
						<h2 class="grant-status">Status: <?php foreach( $status as $status_value ) { echo $status_value; } ?></h2>
					</hgroup>
				</header>

				<div class="article-body">
					<?php if ( get_the_content() != '' ) : ?>
						<h2>Project Summary</h2>
						<div style="padding-left:2rem;">
							<?php the_content(); ?>
						</div>
					<?php endif; ?>

					<?php if ( $grant_annual_entries ) : ?>
						<h2>Annual Entries</h2>
						<?php $disclosed = ( 3 > count( $grant_annual_entries ) ) ? 'disclosed' : ''; ?>
						<?php foreach ( $grant_annual_entries as $year => $entry ) : ?>
						<?php
							$investigator_groups = array(
								'Principal Investigator'  => $entry['principal_investigators'],
								'Additional Investigator' => $entry['additional_investigators'],
								'Graduate Student'        => $entry['student_investigators'],
							);
							$progress_report            = $entry['progress_report'];
							$additional_progress_report = $entry['additional_progress_report'];
							$amount                     = $entry['amount'];
						?>
						<dl class="cahnrs-accordion slide <?php echo $disclosed; ?>">
							<dt>
								<h3><?php echo esc_html( $year ); ?></h3>
							</dt>
							<dd>
								<table>
									<?php
                  if ( $investigator_groups ) :
										foreach ( $investigator_groups as $group => $investigators ) :
											if ( $investigators ) :
												$investigator_count = count( $investigators );
												?><tr>
													<td><?php echo $group; if ( $investigator_count > 1 ) { echo 's'; } ?>:</td>
													<td><?php
														$investigator_counter = 1;
														foreach ( $investigators as $investigator ) {
															$investigator_object = get_term_by( 'slug', $investigator, 'investigator' );
															echo $investigator_object->description;
															if ( $investigator_counter != $investigator_count ) {
																echo '<br />';
															}
															$investigator_counter++;
														}
													?></td>
												</tr><?php
											endif;
										endforeach;
									endif;
									?>
									<?php if ( $amount ) : ?>
									<tr>
										<td>Grant Amount:</td>
										<td>$<?php echo esc_html( $amount ); ?></td>
									</tr>
									<?php endif; ?>
									<?php if ( $progress_report ) : ?>
									<tr>
										<td colspan="2"><a href="<?php echo esc_url( $progress_report ); ?>">Progress Report &raquo;</a></td>
									</tr>
									<?php endif; ?>
									<?php if ( $additional_progress_report ) : ?>
									<tr>
										<td colspan="2"><a href="<?php echo esc_url( $additional_progress_report ); ?>">Additional Progress Report &raquo;</a></td>
									</tr>
									<?php endif; ?>
								</table>
							</dd>
						</dl>
						<?php endforeach; ?>
					<?php endif; ?>

					<?php if ( $grant_publications ) : ?>
          <h2>Publications</h2>
          <div style="padding-left:2rem;"><?php echo wp_kses_post( apply_filters( 'the_content', $grant_publications ) ); ?></div>
					<?php endif; ?>

					<?php if ( $grant_additional_funds ) : ?>
					<h2>Additional Funds Leveraged</h2>
					<div style="padding-left:2rem;"><?php echo wp_kses_post( apply_filters( 'the_content', $grant_additional_funds ) ); ?></div>
					<?php endif; ?>

					<?php if ( $grant_impacts ) : ?>
					<h2>Impacts and Outcomes</h2>
					<div style="padding-left:2rem;"><?php echo wp_kses_post( apply_filters( 'the_content', $grant_impacts ) ); ?></div>
					<?php endif; ?>

				</div>

			</article>

			<?php endwhile; ?>

		</div><!--/column-->

	</section>

	<?php get_template_part( 'parts/footers' ); ?>

</main><!--/#page-->

<?php get_footer(); ?>