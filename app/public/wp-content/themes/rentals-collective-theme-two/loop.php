<?php
if(have_posts()) : while(have_posts()) : the_post();

        get_template_part('template-parts/post/content', get_post_format());

endwhile;

else :

    get_template_part('template-parts/post/content', 'none');

endif; wp_reset_postdata();

the_posts_pagination(
    array(
    'prev_text'    => wp_get_attachment_image(1962, 'full'),
    'next_text'    => wp_get_attachment_image(1963, 'full')
    )
);


