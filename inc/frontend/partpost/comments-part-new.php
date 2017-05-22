<?php

if (comments_open()) :
    $comment_number = get_comments_number();
    echo '<a href="' . esc_url(get_permalink()) . '#comments" class="btn btn-blue to-comments-link reviews-smoothscroll">(' . $comment_number . ') ';
    echo ($comment_number == 1) ? __('Comment', 'ThemeStockyard') : __('Comments', 'ThemeStockyard');
    echo '</a>' . "\n";
endif;
?>