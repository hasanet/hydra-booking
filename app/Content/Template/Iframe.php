<?php

wp_head();


global $wp_query;
if ( isset( $wp_query->query_vars['hydra-booking'] ) ) {
	$meeting_id = intval( $wp_query->query_vars['meeting-id'] );
	$type       = esc_attr( $wp_query->query_vars['type'] );
	if ( ! empty( $meeting_id ) && $type == 'iframe' ) {
		?>
		<div class="tfhb-single-meeting-section">
			<?php echo do_shortcode( '[hydra_booking id=' . $meeting_id . ']' ); ?>
		</div>
		<?php
	}
}

wp_footer();
