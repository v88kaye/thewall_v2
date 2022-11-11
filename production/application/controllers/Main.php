<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {
	public function index() {
		$this->load->view('welcome');
	}

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

	public function submit_registration_form() {
		if($this->input->post()){
			$user_data = array(
				'email' 	 => strtolower(trim($this->input->post('email'))),
				'first_name' => $this->input->post('first_name'),
				'last_name'  => $this->input->post('last_name'),
				'password' 	 => md5($this->input->post('password')),
				'created_at' => date('Y-m-d H:i:s')
			);

			$this->load->model('Users_model');
			$user = $this->Users_model->submit_registration_form($user_data);
			
			echo json_encode($user);
		}
		else
			show_404('page', TRUE);
	}

	public function post_message() {
		$loggedin_user = json_decode($this->session->userdata('user_data'), TRUE);

		if($this->input->post() && $loggedin_user['user_id']){
			$message_data = array(
				'user_id' 	 => $loggedin_user['user_id'],
				'message' 	 => htmlspecialchars($this->input->post('message')),
				'created_at' => date('Y-m-d H:i:s'),
			);

			$this->load->model('Posts_model');
			$message = $this->Posts_model->post_message($message_data);

			echo json_encode($message);
		}
		else
			show_404('page', TRUE);
	}

	public function post_comment() {
		$loggedin_user = json_decode($this->session->userdata('user_data'), TRUE);

		if($this->input->post() && $loggedin_user['user_id']){
			$comment_data = array(
				'user_id' 	 => $loggedin_user['user_id'],
				'message_id' => $this->input->post('message_id'),
				'comment' 	 => htmlspecialchars($this->input->post('comment')),
				'created_at' => date('Y-m-d H:i:s'),
			);

			$this->load->model('Posts_model');
			$comment = $this->Posts_model->post_comment($comment_data);

			echo json_encode($comment);
		}
		else
			show_404('page', TRUE);
	}

	public function logout() {
		$this->session->unset_userdata('user_data');
		redirect(base_url('/login'));
	}
}
