<?php
    function filterCategories() {
        // Request portfolio used categories in the DB
        $getUsedCategories = "SELECT DISTINCT wp_terms.name AS categoryName, wp_terms.slug AS categorySlug FROM `wp_posts`,`wp_term_relationships`,`wp_terms`,`wp_term_taxonomy` WHERE wp_posts.ID = wp_term_relationships.object_id AND wp_term_relationships.term_taxonomy_id = wp_terms.term_id AND wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id AND wp_posts.post_type = 'portfolio' AND wp_term_taxonomy.taxonomy = 'portfolio-cat' AND wp_posts.post_status = 'publish' ORDER BY categoryName ASC";
        $usedCategories = $GLOBALS['wpdb']->get_results($getUsedCategories);

        // Convert the raw SQL results into a PHP plain Array
        $categories = array_map(function($element) {
            return array(
                'name' => $element->categoryName,
                'slug' => $element->categorySlug
            );
        }, $usedCategories);

        // Create the output for the filter list
        $output = '<div class="projects__filter-category--filterlist">
            <span class="projects__filter-category active" data-filter="all">All works</span>';
        foreach ($categories as $category) {
            $output .=  '<span class="projects__filter-category" data-filter="'.$category['slug'].'">'.$category['name'].'</span>';
        }
        $output .= '</div>';

        // Send the final output
        return $output;
    }
?>