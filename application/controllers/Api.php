<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class Api extends CI_Controller {

    private $key;

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->key = getenv('JWT_KEY');
        $this->load->database();
    }

    public function login() {
        $input = json_decode(trim(file_get_contents('php://input')), true);

        $username = $input['username'] ? $input['username'] : '';
        $password = $input['password'] ? $input['password'] : '';

        if ($username === 'baginda' && $password === '12345') {
            $payload = [
                'iss' => 'http://localhost',
                'aud' => 'http://localhost',
                'iat' => time(),            
                'exp' => time() + (60 * 60),
                'data' => [
                    'username' => $username,
                    'role' => 'admin'
                ]
            ];

            $token = JWT::encode($payload, $this->key, 'HS256');

            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => true, 'token' => $token]));
        } else {
            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => false, 'message' => 'Login gagal']));
        }
    }

    public function profile() {
        $headers = $this->input->request_headers();

        if (!isset($headers['Authorization'])) {
            $this->output->set_status_header(401)
                         ->set_content_type('application/json')
                         ->set_output(json_encode(['status' => false, 'message' => 'Token tidak ditemukan']));
            return;
        }

        $authHeader = $headers['Authorization'];
        $token = trim(str_replace('Bearer', '', $authHeader));

        try {
            $decoded = JWT::decode($token, new Key($this->key, 'HS256'));
            $tes = [
                'ini' => '1',
                'ini' => '2'
            ];

            $this->output
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => true, 'user' => $decoded->data, 'data' => $tes]));

        } catch (Exception $e) {
            $this->output
                 ->set_status_header(401)
                 ->set_content_type('application/json')
                 ->set_output(json_encode(['status' => false, 'message' => $e->getMessage()]));
        }
    }
}
