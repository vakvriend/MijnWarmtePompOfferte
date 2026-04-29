<?php
get_header();

$post = get_post();
if ($post && in_array($post->post_name, array('privacy', 'disclaimer'), true)) {
    get_template_part('template-parts/legal-page');
} else {
    get_template_part('template-parts/landing-page');
}

get_footer();
