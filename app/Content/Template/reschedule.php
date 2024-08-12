<?php
wp_head();

global $wp_query;



if ( isset( $wp_query->query_vars['hydra-booking'] ) ) {
	$meeting_id = intval( $wp_query->query_vars['meeting-id'] );
	$hash       = esc_attr( $wp_query->query_vars['hash'] );
	$type       = esc_attr( $wp_query->query_vars['type'] );
	if ( ! empty( $meeting_id ) ) { ?>
	<div class="tfhb-single-meeting-section">
		<?php echo do_shortcode( '[hydra_booking id=' . $meeting_id . ' hash=' . $hash . ' type=' . $type . ' ]' ); ?>
	</div>
		<?php
	}
}

wp_footer();
