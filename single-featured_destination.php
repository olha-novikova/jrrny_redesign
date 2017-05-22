<?php

$made_possible_by = get_post_meta($post->ID, 'made_possible_by', true);

if($made_possible_by):
    get_template_part("inc/frontend/single/collection", 'branded');
else:
    get_template_part("inc/frontend/single/collection");
endif;
    
