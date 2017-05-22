<?php
$authorName = get_user_meta( $authorId, 'first_name', true );
?>
<html>
<body>
<div>
    <p>
        Hey <b><?= $authorName ?></b>, your stuff is kind of a big deal! <b><?= $user->user_login ?></b> liked your post <a href="<?= get_permalink($post->ID)?>"><?=$post->post_title?></a>
    </p>
    <p>
        Check out their jrrnys <a href="<?= get_author_posts_url($user->ID, $user->user_login)?>">here</a> -- maybe you can return the favor if you like their stuff too!
    </p>
    <p>
        Or make a comment if their travels spark a question or interest.
    </p>
    <p>
        Enjoy the jrrny!
    </p>
    <p>
        <a href="http://www.jrrny.com">www.jrrny.com</a>
        <br>
        Check out what's trending <a href="<?=site_url( '/trending')?>"><?=site_url( '/trending')?></a>
        <br>
        Update your profile <a href="<?=get_author_posts_url($author->ID, $author->user_login)?>"><?=get_author_posts_url($author->ID, $author->user_login)?></a>
        <br>
        Add a new jrrny <a href="<?=site_url( '/upload')?>"><?=site_url( '/upload')?></a>
    </p>
</div>
</body>
</html>