<?php
get_header();


$meeting_id = get_post_meta( get_the_ID(), '__tfhb_meeting_id', true );

if ( ! empty( $meeting_id ) ) {
	?>
<div class="tfhb-single-meeting-section">
	<?php echo do_shortcode( '[hydra_booking id=' . $meeting_id . ']' ); ?>
</div>
	<?php
}

get_footer();
