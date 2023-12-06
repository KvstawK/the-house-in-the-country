<?php


        get_template_part('template-parts/post/content', get_post_format());

        //            get_template_part( 'template-parts/single/author' );

        get_template_part('template-parts/single/navigation');

        if(comments_open() || get_comments_number()) {
            comments_template();
        }


