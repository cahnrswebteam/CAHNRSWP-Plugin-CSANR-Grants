<?php get_header(); ?>

<main>

	<?php get_template_part('parts/headers'); ?>

	<section class="row single gutter pad-ends">

		<div class="column one">

			<?php while ( have_posts() ) : the_post(); ?>

			<?php // Grant meta data.
      	$grant_id               = get_post_meta( get_the_ID(), '_csanr_grant_project_id', true );
				$grant_annual_entries   = get_post_meta( get_the_ID(), '_csanr_grant_annual_entries', true );
				$grant_publications     = get_post_meta( get_the_ID(), '_csanr_grant_publications', true );
				$grant_additional_funds = get_post_meta( get_the_ID(), '_csanr_grant_additional_funds', true );
				$grant_impacts          = get_post_meta( get_the_ID(), '_csanr_grant_impacts', true );
				$grant_admin_comments   = get_post_meta( get_the_ID(), '_csanr_grant_admin_comments', true );
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<header class="article-header">
					<hgroup>
						<h1 class="article-title"><?php the_title(); ?></h1>
						<h2 class="grant-id">CSANR Project <?php echo esc_html( $grant_id ); ?></h2>
						<h2 class="grant-status">Status: <?php //foreach( $status as $status_value ) { echo $status_value; } ?></h2>
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
					<?php foreach ( $grant_annual_entries as $year => $entry ) : ?>
						<?php
							$principal_investigators    = $entry['principal_investigators'];
							$additional_investigators   = $entry['additional_investigators'];
							$student_investigators      = $entry['student_investigators'];
							$progress_report            = $entry['progress_report'];
							$additional_progress_report = $entry['additional_progress_report'];
							$amount                     = $entry['amount'];
						?>
						<dl class="cahnrs-accordion slide">
              <dt>
                <h3><?php echo esc_html( $year ); ?></h3>
              </dt>
							<dd>
								<table>
									<?php if ( $principal_investigators ) : ?>
								<tr>
	  							<td>Principal Investigator<?php if ( count( $principal_investigators ) > 1 ) echo 's'; ?>:</td>
	  							<td>&nbsp;</td>
								</tr>
								<?php endif; ?>
								<?php if ( $additional_investigators ) : ?>
								<tr>
									<td>Additional Investigator<?php if ( count( $additional_investigators ) > 1 ) echo 's'; ?>:</td>
									<td>&nbsp;</td>
								</tr>
								<?php endif; ?>
								<?php if ( $student_investigators ) : ?>
								<tr>
									<td>Student Investigator<?php if ( count( $student_investigators ) > 1 ) echo 's'; ?>:</td>
									<td>&nbsp;</td>
								</tr>
								<?php endif; ?>
								<?php if ( $amount ) : ?>
								<tr>
									<td>Grant Amount:</td>
									<td><?php echo esc_html( $amount ); ?></td>
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
					<?php	/*
						$new_entries = array();
						$pi_array = array();
						$ai_array = array();
						$si_array = array();
						$grant_annual_entries = get_post_meta( get_the_ID(), '_grant' );
						$entries = $grant_annual_entries[0]['annual_entries'];
						if ( $entries ) {
							foreach ( $entries as $entry ) {
								$year = $entry['year'];
								$pis = $entry['pi'];
								if ( $pis ) { foreach ( $pis as $pi ) { $pi_array[] = $pi; } }
								$ais = $entry['additional'];
								if ( $ais ) { foreach ( $ais as $ai ) { $ai_array[] = $ai; } }
								$sis = $entry['students'];
								if ( $sis ) { foreach ( $sis as $si ) { $si_array[] = $si; } }
								
								$new_entries[ $year ] = array(
									'principal_investigators' => $pi_array,
									'additional_investigators' => $ai_array,
									'student_investigators' => $si_array,
									'progress_report' => $entry['progress'],
									'additional_progress_report' => $entry['additional_progress'],
									'amount' => $entry['amount']
								);
							}
							add_post_meta( get_the_ID(), '_csanr_grant_annual_entries', $new_entries );
						}*/
					?>
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