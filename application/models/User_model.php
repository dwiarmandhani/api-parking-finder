<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function isAdmin($email)
    {
        $user = $this->db->get_where('tbl_users', ['user_email' => $email, 'user_is_admin' => 1])->num_rows();

        return $user;
    }
    public function getUserByEmail($email)
    {
        $user = $this->db->get_where('tbl_users', ['user_email' => $email])->row_array();

        return $user;
    }

    public function getAllUser()
    {
        $user = $this->db->get('tbl_users')->result_array();

        return $user;
    }
    public function editUsers($user_id, $data)
    {
        $this->db->update('tbl_users', $data, ['user_id' => $user_id]);
        return $this->db->affected_rows();
    }
}
