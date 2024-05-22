<?php
get_header();

global $wp_query;
if (isset($wp_query->query_vars['meeting'])) {
    $meeting_id = intval($wp_query->query_vars['meeting']);
    if(!empty($meeting_id)){ ?>
    <div class="tfhb-single-meeting-section">
        <?php echo do_shortcode('[hydra_booking id='.$meeting_id.']'); ?>
    </div>
    <?php }
}

get_footer();
