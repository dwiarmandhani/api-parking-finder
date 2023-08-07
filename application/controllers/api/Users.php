<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require_once APPPATH . 'controllers/api/Auth.php';


class Users extends Auth
{
    function __construct()
    {
        parent::__construct();
        $this->cekToken();
        $this->load->model('Auth_model', 'auth');
        $this->load->model('User_model', 'userdata');
    }
    // utuk fitur get users
    public function getusers_get()
    {
        $this->getLoggedId();

        $isAdmin = $this->userdata->isAdmin($this->getEmail());

        if ($isAdmin > 0) {
            $user = $this->userdata->getAllUser();
            $this->response([
                'status' => true,
                'message' => 'Success',
                'data' => $user
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Access not Allowed'
            ], REST_Controller::HTTP_FORBIDDEN);
        }
    }
    // untuk fitur edit users di admin
    public function editusersadmin_post()
    {
        $this->getLoggedId();

        $isAdmin = $this->userdata->isAdmin($this->getEmail());

        if ($isAdmin > 0) {
            $user_id = $this->post('user_id');
            $data = [
                'user_name' => $this->post('user_name'),
                'user_phone' => $this->post('user_phone'),
            ];

            $updateUser = $this->userdata->editUsers($user_id, $data);
            if ($updateUser > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Successed to update users!',
                    'data' => $data
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Failed to update users!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Access not Allowed'
            ], REST_Controller::HTTP_FORBIDDEN);
        }
    }
    public function editusers_post()
    {
        $this->getLoggedId();

        $user_id = $this->post('user_id');
        $data = [
            'user_name' => $this->post('user_name'),
            'user_phone' => $this->post('user_phone'),
        ];

        $updateUser = $this->userdata->editUsers($user_id, $data);
        if ($updateUser > 0) {
            $this->response([
                'status' => true,
                'message' => 'Successed to update users!',
                'data' => $data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed to update users!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    // untuk fitur changes password users
    public function changespasswords_post()
    {
        $user_id = $this->getLoggedId();
        $key = '1234567890';

        if ($this->post('password') === '' || $this->post('confirm_password') === '') {
            $this->response([
                'status' => false,
                'message' => 'Failed to changed password! Field can`t be null'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }

        $password = hash('sha512', $this->post('password') . $key);
        $confirmpassword = hash('sha512', $this->post('confirm_password') . $key);

        if ($password === $confirmpassword) {
            $this->db->update('tbl_users', ['user_password' => $password], ['user_id' => $user_id]);
            $update = $this->db->affected_rows();

            if ($update > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Password has been changed!'
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Failed to changed password!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Password does`nt match!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
