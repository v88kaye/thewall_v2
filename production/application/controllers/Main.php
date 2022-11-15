<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	/* Shows the welcome page */
	public function index() {
		$this->load->view('welcome');
	}

	/* Main view of the user once login, redirect to /login if no session yet */
	public function dashboard() {
		if($this->session->userdata('user_data')){
			$this->load->model('Posts_model');
			$this->view_data['user'] = json_decode($this->session->userdata('user_data'), TRUE);
			$this->view_data['messages'] = $this->Posts_model->fetch_all_messages();

			$this->load->view('dashboard', $this->view_data);
		}
		else
			redirect(base_url('/login'));
	}

	/* Login user with the email and password provided */
	public function submit_login_form() {
		if($this->input->post()){
			$login_data = array(
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('password')),
			);

			$this->load->model('Users_model');
			$user = $this->Users_model->submit_login_form($login_data);

			echo json_encode($user);
		}
		else
			show_404('page', TRUE);
	}

	/* Register user with the first_name, last_name, email and password provided */
	public function submit_registration_form() {
		if($this->input->post()){
			$user_data = array(
				'email' 	 => strtolower(trim($this->input->post('email'))),
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'password' 	 => md5($this->input->post('password'))
			);

			$this->load->model('Users_model');
			$user = $this->Users_model->submit_registration_form($user_data);
			
			echo json_encode($user);
		}
		else
			show_404('page', TRUE);
	}

	/* Logout user and destroy the current session. Redirect them to /login */
	public function logout() {
		$this->session->unset_userdata('user_data');
		redirect(base_url('/login'));
	}

	/* Save user's message in the database */
	public function post_message() {
		$loggedin_user = json_decode($this->session->userdata('user_data'), TRUE);
		
		if($this->input->post() && $loggedin_user['user_id']){
			$message_data = array(
				'user_id' => $loggedin_user['user_id'],
				'message' => htmlspecialchars($this->input->post('message'))
			);

			$this->load->model('Posts_model');
			$message = $this->Posts_model->post_message($message_data);
			echo json_encode($message);
		}
		else
			show_404('page', TRUE);
	}

	/* Save user's comment to a message in the database */
	public function post_comment() {
		$loggedin_user = json_decode($this->session->userdata('user_data'), TRUE);
		
		if($this->input->post() && $loggedin_user['user_id']){
			$comment_data = array(
				'user_id' => $loggedin_user['user_id'],
				'message_id' => $this->input->post('message_id'),
				'comment' => htmlspecialchars($this->input->post('comment'))
			);

			$this->load->model('Posts_model');
			$comment = $this->Posts_model->post_comment($comment_data);
			echo json_encode($comment);
		}
		else
			show_404('page', TRUE);
	}

	public function delete_message() {
		$loggedin_user = json_decode($this->session->userdata('user_data'), TRUE);
		$message_id = $this->input->post('message_comment_id');
		
		if($loggedin_user['user_id'] && ($message_id != NULL && $message_id != '')){
			$this->load->model('Posts_model');
			$message_deleted = $this->Posts_model->delete_message($message_id, $loggedin_user['user_id']);
			echo json_encode($message_deleted);
		}
		else
			show_404('page', TRUE);
	}

	public function delete_comment() {
		$loggedin_user = json_decode($this->session->userdata('user_data'), TRUE);
		$comment_id = $this->input->post('message_comment_id');
		
		if($loggedin_user['user_id'] && ($comment_id != NULL && $comment_id != '')){
			$this->load->model('Posts_model');
			$message_deleted = $this->Posts_model->delete_comment($comment_id, $loggedin_user['user_id']);
			echo json_encode($message_deleted);
		}
		else
			show_404('page', TRUE);
	}
}
