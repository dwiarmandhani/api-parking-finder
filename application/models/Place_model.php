<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Place_model extends CI_Model
{
    public function deletePlace($id)
    {
        $isDelete = false;
        $queryImage = $this->db->get_where('tbl_image_place', ['image_place_id' => $id])->result_array();
        if ($queryImage) {
            $this->db->delete('tbl_image_place', ['image_place_id' => $id]);
        }
        $this->db->delete('tbl_place', ['place_id' => $id]);
        $place_delete = $this->db->affected_rows();
        if ($place_delete > 0) {
            $isDelete = true;
        }

        return $isDelete;
    }

    public function checkUserRating($user_id, $place_id)
    {
        return $this->db->get_where('tbl_ratings', ['rating_user_id' => $user_id, 'rating_place_id' => $place_id])->num_rows();
    }
    public function checkplace($place_id)
    {
        return $this->db->get_where('tbl_place', ['place_id' => $place_id])->num_rows();
    }
    public function getPlaceByID($place_id)
    {
        return $this->db->get_where('tbl_place', ['place_id' => $place_id])->result();
    }
    public function getPlaceByUser($user_id)
    {
        return $this->db->get_where('tbl_place', ['place_user_id' => $user_id])->result_array();
    }
}
