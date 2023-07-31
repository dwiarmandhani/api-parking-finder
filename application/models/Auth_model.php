<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function registerUsers($data)
    {
        $this->db->insert('tbl_users', $data);
        return $this->db->affected_rows();
    }
    public function registeredUsers($id)
    {
        return $this->db->get_where('tbl_users', ['user_id' => $id])->result_array();
    }
    public function checkUsers($email)
    {
        $this->db->where('user_email', $email);
        $query = $this->db->get('tbl_users');

        return $query->num_rows() > 0;
    }

    public function loginuser($email, $password)
    {
        $query = $this->db->get_where('tbl_users', ['user_email' => $email, 'user_password' => $password]);
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }
}
