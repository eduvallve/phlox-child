<?php
/**
 * Apply filters before showing results
 */
function getListAllProjects() {
    $query_getListAllProjects = "SELECT wp_posts.ID as id, wp_posts.post_title as title, wp_posts.post_date, wp_posts.guid as post_url, post_excerpt, '' as meta_key, '' as meta_value FROM wp_posts WHERE post_type = 'portfolio' AND post_status = 'publish' UNION SELECT wp_posts.ID as id, '' as title, '' as post_date, '' as post_url, '' as post_excerpt, wp_postmeta.meta_key, wp_postmeta.meta_value FROM wp_posts, wp_postmeta WHERE wp_posts.ID = wp_postmeta.post_id AND post_type = 'portfolio' AND post_status = 'publish' AND (meta_key = '_auxin_meta_client' OR meta_key = '_auxin_meta_url' OR meta_key = '_overview' OR meta_key = '_lunch_button_url' OR meta_key = '_thumbnail_id2' OR meta_key = '_auxin_meta_release_date') UNION SELECT object_id as id, '' as title, '' as post_date, '' as post_excerpt, '' as post_url, taxonomy as meta_key, name as meta_value FROM wp_posts, wp_terms, wp_term_taxonomy, wp_term_relationships where (wp_term_taxonomy.taxonomy = 'portfolio-tag' OR wp_term_taxonomy.taxonomy = 'portfolio-cat') AND wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_taxonomy.term_taxonomy_id = wp_terms.term_id AND object_id = wp_posts.id AND post_type = 'portfolio' AND post_status = 'publish' ORDER BY post_date DESC, meta_key ASC";
    return $GLOBALS['wpdb']->get_results($query_getListAllProjects);
}

function getStructuredProjects($listAllProjects) {
    $structure = [];

    foreach ($listAllProjects as $projectRow) {
        $id = $projectRow->id;

        if ($projectRow->title !== '') {
            $structure[$id]['title'] = $projectRow->title;
            $structure[$id]['date'] = $projectRow->post_date;
            $structure[$id]['post_url'] = $projectRow->post_url;
            $structure[$id]['post_excerpt'] = $projectRow->post_excerpt;
        } else if ($projectRow->meta_key !== '' && $projectRow->meta_key !== 'portfolio-tag' && $projectRow->meta_key !== 'portfolio-cat') {
            $key = $projectRow->meta_key;
            $value = $projectRow->meta_value;
            $structure[$id][$key] = $value;
        } else if ($projectRow->meta_key === 'portfolio-tag') {
            $value = $projectRow->meta_value;
            if (!isset($structure[$id]['tags'])) {
                $structure[$id]['tags'] = [];
            }
            array_push($structure[$id]['tags'],$value);
        } else if ($projectRow->meta_key === 'portfolio-cat') {
            $value = $projectRow->meta_value;
            if (!isset($structure[$id]['cats'])) {
                $structure[$id]['cats'] = [];
            }
            array_push($structure[$id]['cats'],$value);
        }
    }

    return $structure;
}

function showProjectTemplate($projectId, $projectData) {
    $title = isset($projectData['title']) ?  $projectData['title'] : '' ;
    $thumbnail_id2 = isset($projectData['_thumbnail_id2']) ?  $projectData['_thumbnail_id2'] : '' ;
    $thumbnail_url = wp_get_attachment_url( $thumbnail_id2 );
    $client = isset($projectData['_auxin_meta_client']) ?  $projectData['_auxin_meta_client'] : '' ;
    $description = isset($projectData['post_excerpt']) ?  $projectData['post_excerpt'] : '' ;
    $tags = isset($projectData['tags']) ?  $projectData['tags'] : [] ;
    $lunch_url = isset($projectData['_lunch_button_url']) ?  $projectData['_lunch_button_url'] : '' ;
    $meta_url = isset($projectData['_auxin_meta_url']) ?  $projectData['_auxin_meta_url'] : '' ;
    $release_date = isset($projectData['_auxin_meta_release_date']) ?  ", {$projectData['_auxin_meta_release_date']}" : '' ;
    $post_url = isset($projectData['post_url']) ?  $projectData['post_url'] : '' ;

    $cats = isset($projectData['cats']) ?  $projectData['cats'] : [] ;
    $categories = implode(" ",array_map(function($cat) {
        $cat = str_replace(" ","-",$cat);
        return strtolower('category__filter-'.$cat);
    }, $cats));

    $tagIconUrl = CHILD_THEME_URI.'assets/tag.svg';

    $output = "<div id='proj__$projectId' class='col-12 cols projects__item $categories'>
        <div class='col-6 projects__item--img'>
            <picture>
                <img src='$thumbnail_url' alt='$title' width='1024' height='768'>
            </picture>
        </div>
        <div class='col-5 projects__item--content'>
            <h3 class='projects__item--title'>
                $title
            </h3>
            <p class='projects__item--tagline'>
                <b>$client$release_date</b>
            </p>
            <p class='projects__item--description'>
                $description
            </p>
            <span class='projects__item--tags'>
                <img class='projects__item--tags-icon' src=$tagIconUrl alt='tag_icon' widht='15' height='15'>";
    foreach ($tags as $tagKey => $tag) {
        $output .= $tag;
        if ( $tagKey < count($tags) - 1 ) {
            $output .= ' · ';
        }
    }
    $output .= "</span>
                <div class='projects__item--btn-line'>";
    if ($post_url !== '') {
        $output .= "<div class='btn-primary'><a class='custom-button' href='$post_url'><span class='custom-button-text'>Read more</span></a></div>";
    } if ($lunch_url !== '') {
        $output .= "<div class='btn-secondary icon icon__link-external'><a class='custom-button' href='$lunch_url' target='_blank'><span class='custom-button-text'>Launch Project</span></a></div>";
    } if ($meta_url !== '') {
        $output .= "<div class='btn-secondary'><a class='custom-button' href='$meta_url' target='_blank'><span class='custom-button-icon icon icon__link-external'></span><span class='custom-button-text'>Visit the website</span></a></div>";
    }
    $output .= "</div>
            </div>
        </div>";

    return $output;
}

function filteredProjects() {
    $structuredProjects = getStructuredProjects(getListAllProjects());
    // print_r($structuredProjects);
    $output = '';
    foreach ($structuredProjects as $projectId => $projectData) {
        $output .= showProjectTemplate($projectId, $projectData);
    }
    return $output;
}
?>