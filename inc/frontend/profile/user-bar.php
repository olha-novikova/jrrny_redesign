<?php
/**
 * Created by JetBrains PhpStorm.
 * User: olga
 * Date: 12.04.17
 * Time: 15:12
 * To change this template use File | Settings | File Templates.
 */
global $current_user, $post;
$author = get_userdata(intval($current_user->ID));
$author_url = '/author/' . $author->user_login;
?>
<div id = "user-bar">
    <div class="container">
        <div class="row">


            <div class="col-xs-7 col-md-4">
                <?php
                if ( is_user_logged_in()  ){?>
                    <a href="<?php echo $author_url; ?>">
                        <?php echo get_avatar($author->ID, 55, '', $author->user_login); ?>
                    </a>
                    <a class="author-url" href="<?php echo $author_url; ?>"><?php echo ($author->user_firstname && $author->user_lastname ? $author->user_firstname . " " . $author->user_lastname : $author->user_login); ?></a>
                    <?php
                }
                ?>
            </div>

            <div class="col-xs-5 col-md-2 col-md-push-6">
                <div id="edit-profile_badge" class="text-right">
                    <?php if ( is_user_logged_in() ) : ?>
                        <div class="dropdown">
                            <a data-toggle="dropdown" href="#"><i class="fa fa-bars" aria-hidden="true"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="<?php echo get_author_posts_url($current_user->ID);?>" class="user-action">view my jrrnys</a></li>
                                <li><a href="<?php echo $author_url; ?>" class="user-action">view my profile</a></li>
                                <?php if ( current_user_can('edit_user', $author->ID) ){ ?>
                                    <li><a id="jrrny-author-edit-btn" class="user-action" href="" data-toggle="modal" data-target="#jrrny-auhor-edit">edit my profile</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-xs-12 col-md-6 col-md-pull-2">
                <form action="<?php echo esc_url(home_url('/')); ?>"  method="get" role="search">
                    <div class="input-group">
                        <input class="form-control" type="text" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search for...">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <ul class="dropdown-menu pull-right">
                                <li><button type="submit" class="btn btn-link btn-block">Location</button></li>
                                <li><button type="submit" value="u" name="t" class="btn btn-link btn-block">User</button></li>
                            </ul>
                        </div><!-- /btn-group -->
                    </div><!-- /input-group -->
                </form>
            </div>

        </div>
    </div>
</div>