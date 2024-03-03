<?php
/**
 * Displays a link to a survey using ID. Used [iCODE_link id=1]Click Here[/iCODE_link]
 *
 * @since 5.1.0
 * @param array $atts Attributes from add_shortcode function
 * @param string $content The text to be used for the link
 * @return string The HTML the shortcode will be replaced with
 */
function iCODE_survey_link_shortcode( $atts, $content = '' ) {
    extract(shortcode_atts(array(
        'id' => 0,
        'class' => '',
        'target' => ''
    ), $atts));
    $id = intval( $id );

    // Find the permalink by finding the post with the meta_key 'survey_id' of supplied survey
    $permalink = '';
	$my_query = new WP_Query( array( 'post_type' => 'survey', 'meta_key' => 'survey_id', 'meta_value' => $id, 'posts_per_page' => 1, 'post_status' => 'publish' ) );
	if ( $my_query->have_posts() ) {
	  while ( $my_query->have_posts() ) {
		$my_query->the_post();
		$permalink = get_permalink();
	  }
	}
    wp_reset_postdata();
    
    // Craft the target attribute if one is passed to shortcode
    $target_html = '';
    if ( ! empty( $target ) ) {
        $target_html = "target='" . esc_attr( $target ) . "'";
    }
    return "<a href='" . esc_url( $permalink ) . "' class='" . esc_attr( $class ) . "' $target_html>" . esc_html( $content ) . "</a>"; 
}
add_shortcode( 'iCODE_link', 'iCODE_survey_link_shortcode' );
?>