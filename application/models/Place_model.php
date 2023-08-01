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

    public function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        $R = 6371; // Radius bumi dalam kilometer
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $R * $c;
        return $distance;
    }

    // Fungsi untuk mengurutkan data berdasarkan status dan jarak terdekat
    public function sort_places_by_status_and_jarak($dataForyou)
    {
        function compareByStatusAndJarak($place1, $place2)
        {
            if ($place1['status'] === 'Disarankan untuk Anda' && $place2['status'] !== 'Disarankan untuk Anda') {
                return -1;
            } elseif ($place1['status'] !== 'Disarankan untuk Anda' && $place2['status'] === 'Disarankan untuk Anda') {
                return 1;
            } else {
                // Jika status sama, bandingkan berdasarkan jarak
                return $place1['jarak'] <=> $place2['jarak'];
            }
        }

        usort($dataForyou, 'compareByStatusAndJarak');
        return $dataForyou;
    }
    public function sort_places_by_like($dataForyou)
    {
        function compareByLike($place1, $place2)
        {
            return $place2['place_rating'] <=> $place1['place_rating'];
        }

        usort($dataForyou, 'compareByLike');
        return $dataForyou;
    }
    public function sort_places_by_distance($dataForyou)
    {
        function compareByDistance($place1, $place2)
        {
            return $place2['jarak'] <=> $place1['jarak'];
        }

        usort($dataForyou, 'compareByDistance');
        return $dataForyou;
    }
}
