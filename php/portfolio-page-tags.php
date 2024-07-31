
<?php

function get_page_id() {
    global $post;
    $post_id = $post->ID;
    return $post_id;
}

function get_project_tags(){
    $page_id = get_page_id();
    $query_getTags = "SELECT wp_terms.name as name, wp_terms.slug as slug FROM wp_terms, wp_term_taxonomy, wp_term_relationships WHERE wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'portfolio-tag' AND wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_relationships.object_id = $page_id";
    return $GLOBALS['wpdb']->get_results($query_getTags);
}

function show_project_tags () {
    $tagsList = get_project_tags();
    $content = '';
    foreach ($tagsList as $tag) {
        $content .= "<span class='btn-secondary'><a href='/portfolio-tag/$tag->slug' target='_blank' class='custom-button'>$tag->name</a></span>";
    }

    return "<h3 class='elementor-heading-title project__tags-title'>Tech involved</h3>
            <p class='project__tags-text'>This project is created by means of:</p>
            <div class='project__tags'>$content</div>";
}

add_shortcode('current_project_tags', 'show_project_tags');

?>