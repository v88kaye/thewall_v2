<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Posts_model extends CI_Model {
    function __construct() {
        $this->load->database();
    }

    public function post_message($message_data = array()){
        $message_posted['status'] = FALSE;
        $message_posted['message'] = "Unable to post message.";

        if($message_data){
            $message_posted['status'] = $this->db->insert('messages', $message_data);
            $message_posted['message_id'] = $this->db->insert_id();
            $message_posted['message'] = "Message successfully posted.";
        }

        return $message_posted;
    }

    public function post_comment($comment_data = array()){
        $comment_posted['status'] = FALSE;
        $comment_posted['message'] = "Unable to post comment.";

        if($comment_data){
            $comment_posted['status'] = $this->db->insert('comments', $comment_data);
            $comment_posted['comment_id'] = $this->db->insert_id();
            $comment_posted['message'] = "Comment successfully posted.";
        }

        return $comment_posted;
    }

    public function fetch_all_messages(){
        $messages_query = $this->db->query('SELECT JSON_ARRAYAGG(message_data) AS messages
        FROM (
            SELECT 
                JSON_OBJECT(
                    "message_id", messages.id,
                    "message", ANY_VALUE(messages.message),
                    "user_name", CONCAT(ANY_VALUE(users.first_name), " ", ANY_VALUE(users.last_name)),
                    "message_created_at", ANY_VALUE(messages.created_at),
                    "comments", ANY_VALUE(message_comments.comments)
                ) AS message_data
            FROM messages
            LEFT JOIN (
                SELECT message_id, JSON_ARRAYAGG(comment_data) AS comments
                FROM (
                    SELECT message_id,
                        JSON_OBJECT(
                            "message_id", message_id,
                            "comment_id", comments.id,
                            "comment", comment,
                            "user_name", CONCAT(users.first_name, " ", users.last_name),
                            "comment_created_at", comments.created_at         
                        ) AS comment_data
                    FROM comments
                    INNER JOIN users ON users.id = comments.user_id
                    ORDER BY comments.id ASC
                ) AS comment1
                GROUP BY message_id
            ) AS message_comments ON message_comments.message_id = messages.id
            INNER JOIN users ON users.id = messages.user_id
            GROUP BY messages.id
            ORDER BY messages.id DESC
        ) AS all_messages');

        $messages_result = $messages_query->result_array();
        $messages_query->free_result();
        $all_messages = json_decode($messages_result[0]['messages'], TRUE);

        return $all_messages;
    }
}