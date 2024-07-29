<?php
/**
 * Custtom file to define custom portfolio layout according to Figma design for eduvallve.com
 * A PHP function must be specified with all customizations
 * A shortcode must be added so that it can be easily placed in any parto of the page
 */

if ( !function_exists( 'customLayoutPortfolio' ) ):
    function customLayoutPortfolio() {
        include "custom-layout-portfolio.filterCategory.php";
        include "custom-layout-portfolio.filteredProjects.php";

        $output = '<section class="projects">
                     <main class="projects__main projects__filter">
                        <div class="projects__main-title">'.filterCategories().'</div>
                        <div class="projects__list">'.filteredProjects().'</div>
                      </main>
                   </section>';

        return $output;
    }

    // register shortcode
    add_shortcode( 'customLayoutPortfolio-001', 'customLayoutPortfolio' );
endif;
?>