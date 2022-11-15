<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {
    function __construct() {
        $this->load->database();
    }

    public function submit_registration_form($user_data = array()) {
        $is_registered = array('status' => FALSE, 'message' => "Failed to register user.");

        if($user_data['email'] != '' && $user_data['password'] != ''){
            /* Check if user email already exists */
            $email_exists = $this->db->select('id AS user_id')->from('users')->where('email', $user_data['email'])->get()->row_array();

            if(!$email_exists) {
                $is_registered['status'] = $this->db->insert('users', $user_data);

                if($is_registered['status']){
                    $is_registered['message'] = "User sucessfully created.";

                    /* Set user session */
                    $this->session->set_userdata('user_data', json_encode(array('user_id' => $this->db->insert_id(), 'first_name' => $user_data['first_name'])));
                }
            }
            else 
                $is_registered['message'] = "User already exists.";
        }

        return $is_registered;
    }

    public function submit_login_form($user_data = array()) {
        $is_login = array('status' => FALSE, 'message' => "Failed to login user.");

        if($user_data['email'] != '' && $user_data['password'] != ''){
            /* Check if user exists */
            $user = $this->db->select('id AS user_id, first_name, password')
                            ->from('users')
                            ->where('email', $user_data['email'])
                            ->get()->row_array();

            if($user) {
                if($user['password'] == $user_data['password']){
                    $is_login['status'] = TRUE;
    
                    /* Set user session */
                    $this->session->set_userdata('user_data', json_encode(array('user_id' => $user['user_id'], 'first_name' => $user['first_name'])));
                }
                else
                    $is_login['message'] = "Password is incorrect.";
            }
            else 
                $is_login['message'] = "No user found.";
        }

        return $is_login;
    }
}