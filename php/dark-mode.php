<?php

function isDarkMode() {
    $cookieName = 'dark-mode';
    return isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] === 'true';
}

function dark_mode(){
    $checked = isDarkMode() ? 'checked' : '';
    $moonUrl = CHILD_THEME_URI.'assets/moon.svg';
    $sunUrl = CHILD_THEME_URI.'assets/sun.svg';
    return "<label class='dark-mode__selector-label'>
                <input type='checkbox' name='dark' class='dark-mode__selector hidden' $checked>
                <div class='dark-mode__selector-icon'>
                    <img class='dark-mode__icon-moon' src=$moonUrl alt='Enable and disable the Dark mode' width='30' height='30'>
                    <img class='dark-mode__icon-sun' src=$sunUrl alt='Enable and disable the Dark mode' width='30' height='30'>
                </div>
                <span class='hidden'>Dark mode</span>
            </label>";
}

add_shortcode('dark_mode_selector', 'dark_mode');

/** Add dark mode class into the <body> tag qhen needed */
function add_custom_dark_mode_body_class( $classes ) {
    if ( isDarkMode() ) {
        $classes[] = 'dark-mode';
    }
    return $classes;
}
add_filter( 'body_class', 'add_custom_dark_mode_body_class' );
?>