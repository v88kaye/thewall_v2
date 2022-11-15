<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>The Wall</title>

    <style>.hidden{ display: none; } </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
    <!-- Custom JS -->
    <script type="text/javascript">
        $(document).ready(function(){
            $('body')
                .on('submit', '#message_form', function(e){
                    e.preventDefault();
                    let message_form = $('#message_form');

                    if(message_form.find('textarea[name=message]').val() != ''){
                        $.post(message_form.attr('action'), message_form.serialize(), function(message_posted){
                            if(message_posted.status)
                                window.location = '/dashboard';
                            else
                                message_form.find('.error_message').removeClass('hidden').text(message_posted.message);
                        }, 'json');
                    }
                    else
                        message_form.find('.error_message').removeClass('hidden').text('Message cannot be empty.');
                })
                .on('click', '.post_comment', function(e){
                    e.preventDefault();
                    let comment_form = $('#comment_form');
                    let comment_textarea = $(this).siblings('.comment');
                    let message_id = comment_textarea.attr('data-message-id');

                    if(comment_textarea.val() != ''){
                        comment_form.find('input[name=message_id]').val(message_id);
                        comment_form.find('textarea[name=comment]').val(comment_textarea.val());

                        $.post(comment_form.attr('action'), comment_form.serialize(), function(comment_posted){
                            if(comment_posted.status)
                                window.location = '/dashboard';
                            else
                                comment_form.find('.error_message').removeClass('hidden').text(comment_posted.message);
                        }, 'json');
                    }
                    else
                        comment_form.find('.error_message').removeClass('hidden').text('Comment cannot be empty.');
                })
                .on('click', '.delete_message_btn', function(e){
                    e.preventDefault();
                    let message_id = $(this).attr('data-id');
                    $('#delete_form').find('input[name=message_comment_id]').val(message_id);
                    submit_delete_form('/delete_message');
                })
                .on('click', '.delete_comment_btn', function(e){
                    e.preventDefault();
                    let comment_id = $(this).attr('data-id');
                    $('#delete_form').find('input[name=message_comment_id]').val(comment_id);
                    submit_delete_form('/delete_comment');
                })
                
                /* Submit deletion of message or comments */
                function submit_delete_form(form_action){
                    $.post(form_action, $('#delete_form').serialize(), function(is_deleted){
                        if(is_deleted.status)
                            window.location = '/dashboard';
                        else
                            alert(is_deleted.message);
                    }, 'json');
                }
        });
    </script>
</head>
<body>
    <div class="welcome">
        <h1>The Wall</h1>
        <p>Welcome, <?= $user['first_name'] ?>. <a href="/logout">Logout</a></p>

        <!-- Message Form -->
        <form id="message_form" action="/post_message" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <textarea name="message" placeholder="Enter your message." cols="30" rows="5"></textarea>
            <input type="submit" value="Post Message">
            <p class="error_message hidden"></p>
        </form>

        <!-- Comment Form -->
        <form id="comment_form" class="hidden" action="/post_comment" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="message_id">
            <textarea name="comment" placeholder="Enter your comment." cols="30" rows="5"></textarea>
            <input type="submit" value="Post Comment">
            <p class="error_message hidden"></p>
        </form>

        <!-- Delete Form -->
        <form id="delete_form" class="hidden" action="/dashboard" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="message_comment_id">
            <input type="submit" value="Delete">
        </form>

        <!-- Dashboard Wall -->
        <div class="messages">
    <?php if(!empty($messages)){
        foreach($messages AS $all_message => $message){ ?>
        <p><?= $message['user_name'] ." - ". date('F j, Y g:i', strtotime($message['message_created_at'])) ?></p>
        <p><?= $message['message'] ?></p>
        <button class="delete_message_btn <?= ($message['message_user_id'] == json_decode($this->session->userdata('user_data'), TRUE)['user_id']) ? '' : 'hidden' ?>" data-id="<?= $message['message_id'] ?>">Delete Message</button>

        <div class="comments">
    <?php if(isset($message['comments']) && !empty($message['comments'])){
        foreach($message['comments'] AS $comment){ ?>
            <p><?= $comment['user_name'] ." - ". date('F j, Y g:i', strtotime($comment['comment_created_at'])) ?></p>
            <p><?= $comment['comment'] ?></p>
            <button class="delete_comment_btn <?= ($comment['comment_user_id'] == json_decode($this->session->userdata('user_data'), TRUE)['user_id']) ? '' : 'hidden' ?>" data-id="<?= $comment['comment_id'] ?>">Delete Comment</button>
    <?php }
        } else { ?>
            <p>No comments.</p>
    <?php } ?>

            <textarea class="comment" placeholder="Add your comment." data-message-id="<?= $message['message_id'] ?>" cols="30" rows="5"></textarea>
            <button class="post_comment">Post Comment</button>
            <p class="error_message hidden"></p>
        </div>
    <?php } 
    } else { ?>
        <p>No messages at this time.</p>
    <?php } ?>
        </div>
    </div>
</body>
</html>