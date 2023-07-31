<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Auth.php';

class Place extends Auth
{
    function __construct()
    {
        parent::__construct();
        $this->cekToken();

        $this->load->model('Place_model', 'place');
    }
    // untuk fitur tambah tempat
    public function addplace_post()
    {
        $locationName = $this->post('place_name');
        $locationAddress = $this->post('place_address');
        $longitude = $this->post('place_longitude');
        $latitude = $this->post('place_latidute');
        $car = $this->post('place_car');
        $moto = $this->post('place_motor');
        $pic = $this->post('place_pic');
        $picContact = $this->post('place_pic_contact');
        $facilityDesc = $this->post('place_desc');

        $user_id = $this->getLoggedId();

        $data = [
            'place_user_id' => $user_id,
            'place_name' => $locationName,
            'place_address' => $locationAddress,
            'place_car' => $car,
            'place_motor' => $moto,
            'place_description' => $facilityDesc,
            'place_longitude' => $longitude,
            'place_latitude' => $latitude,
            'place_pic' => $pic,
            'place_pic_contact' => $picContact,
            'place_active' => 1,
        ];

        $this->db->insert('tbl_place', $data);
        $placeInsert = $this->db->affected_rows();


        if ($placeInsert > 0) {
            $insert_id = $this->db->insert_id();
            $place = $this->db->get_where('tbl_place', ['place_id' => $insert_id])->result_array();

            // upload image
            $imageUploaded = array();
            $dataImage = array();
            $path = "uploads/image_place/";
            if (!is_dir($path)) {
                mkdir($path, 077, true);
            }
            $count_image = count($_FILES['place']['name']);

            for ($i = 0; $i < $count_image; $i++) {
                if (!empty($_FILES['place']['name'][$i])) {
                    $config['upload_path'] = './' . $path;
                    $config['allowed_types'] = "jpg|jpeg|png|gif";
                    $config['file_name'] = time();
                    $config['max_size'] = 1024;
                    $this->upload->initialize($config);

                    $_FILES['image']['name'] = $_FILES['place']['name'][$i];
                    $_FILES['image']['type'] = $_FILES['place']['type'][$i];
                    $_FILES['image']['tmp_name'] = $_FILES['place']['tmp_name'][$i];
                    $_FILES['image']['error'] = $_FILES['place']['error'][$i];
                    $_FILES['image']['size'] = $_FILES['place']['size'][$i];

                    if ($this->upload->do_upload('image')) {
                        $uploadData = $this->upload->data();
                        $dataImage[] = array(
                            'image_place_id' => $insert_id,
                            'image_name' => $uploadData['file_name'],
                            'image_type' => $uploadData['file_type'],
                            'image_size' => $uploadData['file_size'],
                            'image_path' => "./" . $path . $uploadData['file_name'],
                        );
                    }
                }
            }

            if ($dataImage) {
                $this->db->insert_batch('tbl_image_place', $dataImage);
                $save = $this->db->affected_rows();


                // eof upload image


                if ($save > 0) {
                    $queryImage = $this->db->get_where('tbl_image_place', ['image_place_id' => $insert_id])->result_array();
                    foreach ($queryImage as $imageFinal) {
                        $imageUploaded[] = $imageFinal["image_path"];
                    }

                    $imageToString = json_encode($imageUploaded);

                    $this->db->update('tbl_place', ['place_image' => $imageToString], ['place_id' => $insert_id]);
                    $updatePlace = $this->db->affected_rows();

                    if ($updatePlace === 0) {
                        $this->response([
                            'status' => false,
                            'message' => 'Failed to upload image! Error bad request'
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {

                    $this->response([
                        'status' => false,
                        'message' => 'Failed to upload image!'
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }


                $this->response([
                    'status' => true,
                    'message' => 'Success! Place is added!',
                    'place' => [
                        'location name' => $place[0]['place_name'],
                        'location address' => $place[0]['place_address'],
                        'pic' => $place[0]['place_pic'],
                        'pic_contact' => $place[0]['place_pic_contact']
                    ]
                ], REST_Controller::HTTP_CREATED);
            } else {

                $this->response([
                    'status' => false,
                    'message' => 'Failed! place failed to added!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {

            $this->response([
                'status' => false,
                'message' => 'Failed! place failed to added!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    // untuk fitur edit temmpat
    public function editplace_put()
    {
        $id = $this->put('place_id');
        $locationName = $this->put('place_name');
        $locationAddress = $this->put('place_address');
        $longitude = $this->put('place_longitude');
        $latitude = $this->put('place_latidute');
        $car = $this->put('place_car');
        $moto = $this->put('place_motor');
        $pic = $this->put('place_pic');
        $picContact = $this->put('place_pic_contact');
        $facilityDesc = $this->put('place_desc');

        $user_id = $this->getLoggedId();

        $data = [
            'place_user_id' => $user_id,
            'place_name' => $locationName,
            'place_address' => $locationAddress,
            'place_car' => $car,
            'place_motor' => $moto,
            'place_description' => $facilityDesc,
            'place_longitude' => $longitude,
            'place_latitude' => $latitude,
            'place_pic' => $pic,
            'place_pic_contact' => $picContact,
            'place_active' => 1,
        ];

        $this->db->update('tbl_place', $data, ['place_id' => $id]);
        $placeUpdate = $this->db->affected_rows();

        if ($placeUpdate > 0) {
            $this->response([
                'status' => true,
                'message' => 'Success! Place is edited!',
                'place' => [
                    'location name' => $locationName,
                    'location address' => $locationAddress,
                    'pic' => $pic,
                    'pic_contact' => $picContact
                ]
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed! place failed to edited!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function deleteplace_delete()
    {
        $id = $this->delete('place_id');
        if ($id === null) {
            $this->response([
                'status' => false,
                'message' => 'provide an id!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
        $delete = $this->place->deletePlace($id);
        if ($delete === true) {
            // ok
            $this->response([
                'status' => true,
                'message' => 'Success to delete place!',

            ], REST_Controller::HTTP_OK);
        } else {
            // id not found
            $this->response([
                'status' => false,
                'message' => 'Failed to delete place! Id not found.'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    // untuk fitur search tempat (fuzzy tsukamoto)
    public function searchplace_get()
    {
    }
    // untuk fitur get tempat (fuzzy tsukamoto)
    public function getplace_get()
    {
    }
    // untuk fitur get tempat berdasarkan like (fuzzy tsukamoto)
    public function getplacebylike_get()
    {
    }
    // untuk fitur add like/review
    public function addlike_post()
    {
        $user_id = $this->getLoggedId();
        $place_id = $this->post('place_id');
        $dataRate = [
            'rating_user_id' => $user_id,
            'rating_place_id' => $place_id
        ];
        $checkuserrate = $this->place->checkUserRating($user_id, $place_id);
        $checkplace = $this->place->checkplace($place_id);


        if (($checkuserrate == 0) && ($checkplace > 0)) {
            $getPlace = $this->place->getPlaceByID($place_id);
            $lastRating = (int) $getPlace[0]->place_rating;
            $newRate = $lastRating + 1;

            $this->db->insert('tbl_ratings', $dataRate);
            $this->db->update('tbl_place', ['place_rating' => $newRate], ['place_id' => $place_id]);

            $insertRate = $this->db->affected_rows();

            if ($insertRate > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Success! Place is edited!',
                    'place' => [
                        'place_ratig' => $newRate,
                    ]
                ], REST_Controller::HTTP_CREATED);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'You`ve Already like this place!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function getplacebyuser_get()
    {
        $user_id = $this->getLoggedId();

        $data = $this->place->getPlaceByUser($user_id);

        if ($data) {
            $this->response([
                'status' => true,
                'message' => 'Success',
                'place' => $data
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data not found!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
