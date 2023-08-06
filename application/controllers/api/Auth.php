<?php

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/JWT/JWT.php';
require APPPATH . 'libraries/JWT/Key.php';
require APPPATH . 'libraries/JWT/JWK.php';
require APPPATH . 'libraries/JWT/ExpiredException.php';
require APPPATH . 'libraries/JWT/SignatureInvalidException.php';
require APPPATH . 'libraries/JWT/BeforeValidException.php';
require APPPATH . 'libraries/JWT/CachedKeySet.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */

class Auth extends REST_Controller
{
    private $key;
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Auth_model', 'auth');
        $this->load->model('User_model', 'userdata');
        $this->key = '1234567890';
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        // $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        // $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        // $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function register_post()
    {
        if ($this->post('user_name') === "" || $this->post('user_name') === null) {
            $this->response([
                'status' => false,
                'message' => 'Masukan username dengan benar!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }
        $data = [
            'user_name' => $this->post('user_name'),
            'user_phone' => $this->post('user_phone'),
            'user_email' => $this->post('user_email'),
            'user_password' => hash('sha512', $this->post('user_password') . $this->key),
            'user_is_admin' => $this->post('user_is_admin'),
        ];

        if ($this->auth->checkUsers($this->post('user_email'))) {
            $this->response([
                'status' => false,
                'message' => 'Your ' . $this->post('user_email') . ' already exist!'
            ], REST_Controller::HTTP_NOT_FOUND);
        }

        if ($this->auth->registerUsers($data) > 0) {
            $insert_id = $this->db->insert_id();
            $insertedData = $this->auth->registeredUsers($insert_id);

            // $dataResult = json_decode($insertedData, true);


            $this->response([
                'status' => true,
                'message' => 'You are registered!',
                'user' => [
                    'name' => $insertedData[0]['user_name'],
                    'email' => $insertedData[0]['user_email'],
                    'admin' => $insertedData[0]['user_is_admin'],
                ]
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Your registration failed!'
            ], REST_Controller::HTTP_FORBIDDEN);
        }
    }
    public function login_post()
    {
        $date = new DateTime();
        $email = $this->post('user_email');
        $password = $this->post('user_password');
        if ($email === "" || $email === null) {
            $this->response([
                'status' => false,
                'message' => 'Email tidak boleh kosong'
            ], REST_Controller::HTTP_FORBIDDEN);
        }
        if ($password === "" || $password === null) {
            $this->response([
                'status' => false,
                'message' => 'Password tidak boleh kosong'
            ], REST_Controller::HTTP_FORBIDDEN);
        }
        $encryptedPassword = hash('sha512', $password . $this->key);

        $datauser = $this->auth->loginuser($email, $encryptedPassword);
        if ($datauser) {
            $payload = [
                'id' => $datauser[0]->user_id,
                'name' => $datauser[0]->user_name,
                'email' => $datauser[0]->user_email,
                'iat' => $date->getTimestamp(),
                'exp' => $date->getTimestamp() + (3600 * 24) // token 1 hari
            ];
            $admin = false;
            if ((int)$datauser[0]->user_is_admin === 1) {
                $admin = true;
            } elseif ((int) $datauser[0]->user_is_admin === 0) {
                $admin = false;
            }
            $userInfo = [
                'user_id' => $datauser[0]->user_id,
                'name' => $datauser[0]->user_name,
                'email' => $datauser[0]->user_email,
                'admin' => $admin
            ];

            $token = JWT::encode($payload, $this->key, 'HS256');
            $this->response([
                'status' => true,
                'message' => 'Login successful!',
                'result' => $userInfo,
                'token' => $token
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Login failed!'
            ], REST_Controller::HTTP_FORBIDDEN);
        }
    }

    protected function cekToken()
    {
        $jwt = $this->input->get_request_header('Authorization');
        try {
            JWT::decode($jwt, new Key($this->key, 'HS256'));
        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Invalid Token!',
                'error' => $e
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    protected function getLoggedId()
    {
        $jwt = $this->input->get_request_header('Authorization');
        try {
            $data = JWT::decode($jwt, new Key($this->key, 'HS256'));

            return $data->id;
        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Invalid Token!',
                'error' => $e
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    protected function getEmail()
    {
        $jwt = $this->input->get_request_header('Authorization');
        try {
            $data = JWT::decode($jwt, new Key($this->key, 'HS256'));

            return $data->email;
        } catch (Exception $e) {
            $this->response([
                'status' => false,
                'message' => 'Invalid Token!',
                'error' => $e
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function generatePublicKey_post()
    {
        $key = $this->post('create_key');
        $token = hash('sha512', $key);

        $data = [
            'user_id' => 0,
            'key' => $token,
            'level' => 1
        ];

        $this->db->insert('keys', $data);
        $this->db->affected_rows();

        $this->response([
            'status' => true,
            'message' => 'Token Created',
            'token' => $token
        ], REST_Controller::HTTP_CREATED);
    }
}
