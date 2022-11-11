<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>The Wall</title>

    <style>.hidden{ display: none; } </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
                    let comment_textarea = $(this).siblings('.comment');
                    let message_id = comment_textarea.attr('data-message-id');
                    let comment_form = $('#comment_form');

                    if(comment_textarea.val() != ''){
                        comment_form.find('input[name=message_id]').val(message_id);
                        comment_form.find('textarea[name=comment]').val(comment_textarea.val());

                        $.post(comment_form.attr('action'), comment_form.serialize(), function(message_posted){
                            if(message_posted.status)
                                window.location = '/dashboard';
                            else
                            comment_textarea.siblings('.error_message').removeClass('hidden').text(message_posted.message);
                        }, 'json');
                    }
                    else
                    comment_textarea.siblings('.error_message').removeClass('hidden').text('Comment cannot be empty.');
                });
        });
    </script>
</head>
<body>
    <div class="welcome">
        <h1>The Wall</h1>
        <p>Welcome, <?= $user['first_name'] ?>. <a href="/logout">Logout</a></p>

        <form id="message_form" action="/post_message" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <textarea name="message" cols="30" rows="5" placeholder="Post message"></textarea>
            <input type="submit" value="Post Message">
            <p class="error_message hidden"></p>
        </form>

        <div class="message">
        <?php if(!empty($messages)){ 
            foreach($messages AS $all_message => $message) { ?>
                <p><?= $message['user_name'] ." - ". date('F j, Y g:i', strtotime($message['message_created_at'])) ?></p>
                <p><?= $message['message'] ?></p>

                <div class="comments">
            <?php if(isset($message['comments']) && !empty($message['comments'])){
                foreach($message['comments'] AS $comment){ ?>
                    <p><?= $comment['user_name'] ." - ". date('F j, Y g:i a', strtotime($comment['comment_created_at'])) ?></p>
                    <p><?= $comment['comment'] ?></p>
            <?php }
            } ?>

                    <textarea class="comment" data-message-id="<?= $message['message_id'] ?>" rows="4" cols="50" placeholder="Write your comment."></textarea>
                    <button class="post_comment">Post Comment</button>
                    <p class="error_message hidden"></p>
                </div>
        <?php } 
            }
        else { ?>
                <p>No messages at this moment.</p>
        <?php } ?>
        </div>

        <form id="comment_form" class="hidden" action="/post_comment" method="POST">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <textarea name="comment" cols="30" rows="5"></textarea>
            <input name="message_id"></input>
            <input type="submit" value="Post Comment">
        </form>
    </div>
</body>
</html>