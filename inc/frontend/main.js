jQuery(document).ready(function ($) {
    var deleteJrrnyAjax = function(postId){
        $.ajax({
            url: defineURL('ajaxurl'),
            method: "post",
            data: {
                action: "delete_post",
                "post-id": postId
            },
            success: function (response) {
                if(response.status == "ok"){
                    $('[id^="post-'+ response['post-id'] + '"]').each(function (){
                        $(this).remove();
                    });
                }else {
                    console.log(response.msg);
                }
            }
        });
    };
    $("body").on("click", ".jrrny-bucket-completed-post", function (event) {
        event.preventDefault();
        
        var that = $(this);
        var post = that.attr("data-post");
        var user = that.attr('data-user');
        var txt  = that.attr('data-txt');
        
        if (!that.hasClass('login_modal')) {
            $.ajax({
                url: defineURL('ajaxurl'),
                method: "post",
                data: {
                    action: "change_completed_bucket_list",
                    post: post,
                    user: user,
                    txt: txt
                },
                success: function (response) {                    
                    $('.jrrny-bucket-completed-post').each(function () {
                        postID = $(this).attr('data-post');
                        if(post === postID){                                
                            $(this).find('.fa').removeClass(response.removeIcon);
                            $(this).find('.fa').addClass(response.addIcon);
                            
                            if(txt){
                                $(this).find('.list-text').html(response.msg);                                 
                            }
                            else{
                                $(this).find('.list-text').attr('data-original-title',response.msg);   
                            }                   
                        }
                    });
                    $('#bucket_list_completed_wrapper').html(response.list);
                    $('#bucket_list_completed_wrapper .ts-fade-in').each(function () {
                        $(this).removeClass('ts-fade-in');
                        $(this).css('opacity', 1);
                    });
                }
            });
        }
    });
    $("body").on("click", ".jrrny-bucket-post", function (event) {
        event.preventDefault();
        
        var that = $(this);
        
        var title = that.attr("data-title");
        var post = that.attr("data-post");
        var user = that.attr('data-user');
        var txt  = that.attr('data-txt');
        
        if (!that.hasClass('login_modal')) {
            $.ajax({
                url: defineURL('ajaxurl'),
                method: "post",
                data: {
                    action: "change_bucket_list",
                    post: post,
                    user: user,
                    txt: txt
                },
                success: function (response) {                                 
                    $('.sp-bucket-list').each(function () {
                        postID = $(this).find('.jrrny-bucket-post').attr('data-post');
                        if(post === postID){                                
                            $(this).find('.fa').removeClass(response.removeIcon);
                            $(this).find('.fa').addClass(response.addIcon);
                            
                            if(txt){
                                $(this).find('.list-text').html(response.msg);                                 
                            }
                            else{
                                $(this).find('.list-text').attr('data-original-title',response.msg);   
                            }
                            if(response.completed){
                                $( response.completed ).insertAfter( this );
                                
                            }
                        }
                    });
                    if(response.addIcon === 'fa-calendar-plus-o' || response.addIcon === 'fa-square-o'){
                        $('.sp-bucket-completed-post').each(function () {
                            $(this).remove();                            
                        });
                    }
                    if(response.action === 'add'){                        
                        ga('send', 'event', 'Posts', 'Like', title);
                    }
                    $('#bucket_list_wrapper').html(response.list);
                    $('#bucket_list_wrapper .ts-fade-in').each(function () {
                        $(this).removeClass('ts-fade-in');
                        $(this).css('opacity', 1);
                    });
                    if($('#bucket_wrapper').length){
                        var post_html = $('#post-' + post).clone().html();
                        $('#bucket_wrapper .loop').append('<div class="hentry entry span4">' + post_html + '</div>');
                    
                    }
                }
            });
        }
    });
    
    $("body").on("click", ".meta-item-like", function (event) {
        event.preventDefault();
        var jrrny = $(this).attr("data-on-post");
        var title = $(this).attr("data-title");
        var author = $(this).attr('data-author');
        var that = $(this);
        if (!that.hasClass('login_modal')) {
            $.ajax({
                url: defineURL('ajaxurl'),
                method: "post",
                data: {
                    action: "like_the_jrrny",
                    jrrny: jrrny,
                    "event": "like",
                    author: author
                },
                success: function (response) {
                    if (response.liked == "liked" && response.quantity > 0) {
                        that.parent('span').addClass("liked");
                        that.find(".likes-quant").text(response.quantity);
                        that.find(".likes-quant").show();
                        if(that.find('.fa').length){
                            that.find('.fa').removeClass('fa-heart-o');
                            that.find('.fa').addClass('fa-heart');
                            that.find('span.like-text').attr('data-original-title','unlike');                            
                        }
                        else{
                            that.find('span.like-text').html('unlike');
                        }
                        
                        ga('send', 'event', 'Posts', 'Like', title);
                    }else if(response.liked == "unliked"){
                        that.parent('span').removeClass("liked");
                        that.find(".likes-quant").text(response.quantity);
                        if(response.quantity <= 0){
                            that.find(".likes-quant").hide();
                        }
                        if(that.find('.fa').length){
                            that.find('.fa').removeClass('fa-heart');
                            that.find('.fa').addClass('fa-heart-o');
                            that.find('span.like-text').attr('data-original-title','like');
                        }
                        else{
                            that.find('span.like-text').html('like');
                        }
                    }
                }
            });
        }
    });
    $("body").on("click", ".meta-item-follow", function (event) {
        event.preventDefault();
        var user_id = $(this).attr('data-author');
        var following = $(this).attr('data-following');
        var that = $('.meta-item-follow');
        var action = 'follow';
        if(following > 0){ 
            action = 'unfollow'; 
        }
        if (!$(this).hasClass('disabled')) {
            $.ajax({
                url: defineURL('ajaxurl'),
                method: "post",
                data: {
                    action: action,
                    'user-id': user_id
                },
                success: function (response) {
                    if (response.status == "ok" ) {                                         
                        that.each(function () {
                            that_user_id = $(this).attr('data-author');
                            if(user_id === that_user_id){
                                $(this).addClass("disabled");
                                $(this).attr('data-following', response.following);
                                $(this).html(response.text);                               
                            }
                        });                        
                        if(action === 'unfollow'){
                            $('#jrrny-author-'+user_id).fadeOut("slow", function() {
                                $(this).remove();
                            });
                        } 
                    }
                }
            });
        }
    });
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
})
    $('body').on('click', '#login-out-lnk', function (event) {
        event.preventDefault();
        if( !$(this).hasClass('login_modal') ){
            $.ajax({
                url: defineURL('ajaxurl'),
                type: 'post',
                data: {
                    action: 'login_out_user',
                    "event": "_header_top_login_out_user"
                },
                success: function (response) {
                    if (response && typeof $.parseJSON(response) == 'object') {
                        var result = $.parseJSON(response);
                        if (result["loggedin"] == "no") {
                            window.location.href = defineURL('home') + "#login-form";
                        }
                        else {
                            window.location.href = defineURL('home');
                        }
                    }
                }
            });
        }
    });

    $("body").on("click", ".jrrny-delete-post", function (event) {
        event.preventDefault();

        var postId = $(this).attr("data-on-post");
        var html = '';
        html +='<div id="jrrnyDelConfirm"  class="modal" tabindex="-1" role="dialog">';
        html +='<div class="modal-dialog">';
        html +='<div class="modal-content">';
        html +='<div class="modal-header">';
        html +='<h4 class="modal-title">Are you sure?</h4>';
        html +='</div>';
        html +='<div class="modal-footer">';
        html +='<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
        html +='<button id="jrrny-del-ajax-btn" type="button" class="btn btn-primary">Delete</button>';
        html +='</div>';
        html +='</div>';
        html +='</div>';
        html +='</div>';
        $('body').append(html);
        $('#jrrnyDelConfirm').modal('show');
        $('#jrrny-del-ajax-btn').click(function(e){
            e.preventDefault();
            deleteJrrnyAjax(postId);
            $('#jrrnyDelConfirm').modal('hide');
            $('#jrrnyDelConfirm').remove();
        });

    });
    //Show login form
    /*if(window.location.hash == '#login-form'){
        setTimeout(function(){
            $('#login-out-lnk').trigger( "click" );
        }, 2000);
    }*/
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    });
});
var jrrny_check_like = function(postLike){
    var postId = postLike.data('on-post');
    if($.inArray( postId.toString(), window.jrrnyCheckLikeArr ) != -1){
        postLike.parent('span').addClass('liked');
        /*postLike.find('.like-text').html('unlike');*/
        postLike.find('.fa').removeClass('fa-heart-o');
        postLike.find('.fa').addClass('fa-heart');
        postLike.find('span.like-text').attr('data-original-title','unlike');
    }else {
        postLike.parent('span').removeClass('liked');
        /*postLike.find('.like-text').html('like');*/
        
        postLike.find('.fa').removeClass('fa-heart');
        postLike.find('.fa').addClass('fa-heart-o');
        postLike.find('span.like-text').attr('data-original-title','like');
    }
};
var jrrny_check_followed = function(postFollow){
    var authorId = postFollow.data('author');
    if($.inArray( authorId.toString(), window.jrrnyCheckFollowedArr ) != -1){
        postFollow.data('following', 1);
        postFollow.html('following');
    }else {
        postFollow.data('following', 0);
        postFollow.html('follow');
    }
};
