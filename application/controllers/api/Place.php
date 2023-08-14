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
    public function editplace_post()
    {
        $id = $this->post('place_id');
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
    public function deleteplace_post()
    {
        $id = $this->post('place_id');
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
        $user_id = $this->getLoggedId();
        $keyword = $this->get('keyword');
        // query jarak user saat ini
        $userLastLocation = $this->db->get_where('tbl_user_lastlocation', ['lastlocation_user_id' => $user_id])->result();
        $latitude_last = (float)$userLastLocation[0]->lastlocation_latitude;
        $longitude_last = (float)$userLastLocation[0]->lastlocation_longitude;

        $distance_threshold = 300.0;

        // query data place rentang 1 KM terdekat
        $dataPlace = $this->db->get('tbl_place')->result_array();
        if ($dataPlace) {
            $filtered_places = array();

            // jadikan array
            foreach ($dataPlace as $place) {
                $lat2 = (float) $place['place_latitude'];
                $lon2 = (float) $place['place_longitude'];

                $distance = $this->place->haversineDistance($latitude_last, $longitude_last, $lat2, $lon2);

                if ($distance <= $distance_threshold) {
                    $place['jarak'] = $distance;
                    $filtered_places[] = $place;
                }
            }
            // foreach 

            // kemudian proses fuzzy

            // 1. Fuzzifikasi
            // TIngkat kapasitas 10 40
            // tinglat rating 50 100
            // tingkat ideal

            $dataForyou = array();
            foreach ($filtered_places as $dataFuzzy) {
                $image = json_decode($dataFuzzy['place_image']);
                $nilaiKapasitas = (float)$dataFuzzy['place_car'];
                $nilaiRating = (float)$dataFuzzy['place_rating'];

                // ini merupakan nilai konstan, nilai pakar fuzzyfikasi
                $kapasitasRendah = 10;
                $kapasitasTinggi = 40;
                $ratingTinggi = 100;
                $ratingRendah = 50;
                $nilaiIdeal = 100;
                $nilaiKurangIdeal = 50;

                // hitung fuzzifikasi
                $nilaiKapasitasRendah = 0;
                $nilaiKapasitasTinggi = 0;
                $nilaiRatingRendah = 0;
                $nilaiRatingTinggi = 0;

                // nilaiKapasitasRendah
                if ($nilaiKapasitas >= $kapasitasTinggi) {
                    $nilaiKapasitasRendah = 0;
                } else if ($nilaiKapasitas <= $kapasitasRendah) {
                    $nilaiKapasitasRendah = 1;
                } else {
                    $nilaiKapasitasRendah = ($kapasitasTinggi - $nilaiKapasitas) / ($kapasitasTinggi - $kapasitasRendah);
                }
                // nilai kapasitas tinggi
                if ($nilaiKapasitas <= $kapasitasRendah) {
                    $nilaiKapasitasTinggi = 0;
                } else if ($nilaiKapasitas >= 40) {
                    $nilaiKapasitasTinggi = 1;
                } else {
                    $nilaiKapasitasTinggi = ($nilaiKapasitas - $kapasitasRendah) / ($kapasitasTinggi - $kapasitasRendah);
                }
                // nilai rating rndah
                if ($nilaiRating >= $ratingTinggi) {
                    $nilaiRatingRendah = 0;
                } elseif ($nilaiRating <= $ratingRendah) {
                    $nilaiRatingRendah = 1;
                } else {
                    $nilaiRatingRendah = ($ratingTinggi - $nilaiRating) / ($ratingTinggi - $ratingRendah);
                }

                // nilai rating tinggi
                if ($nilaiRating <= $ratingRendah) {
                    $nilaiRatingTinggi = 0;
                } else if ($nilaiRating >= $ratingTinggi) {
                    $nilaiRatingTinggi = 1;
                } else {
                    $nilaiRatingTinggi = ($nilaiRating - $ratingRendah) / ($ratingTinggi - $ratingRendah);
                }

                // 2. inferensiasi
                // Rules :
                // kapasitas rendah && rating rendah = kurang ideal
                // kapasitas rendah && rating tinggi = ideal
                // kapasitas tinggi && rating rendah = kurang idela
                // kapasitas tinggi && rating tinggi = ideal

                $r1 = 0;
                $r2 = 0;
                $r3 = 0;
                $r4 = 0;
                $a1 = min($nilaiKapasitasRendah, $nilaiRatingRendah); // maka kurang ideal
                $r1 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a1;

                $a2 = min($nilaiKapasitasRendah, $nilaiRatingTinggi); // maka ideal
                $r2 = $a2 * ($nilaiIdeal - $nilaiKurangIdeal) + $nilaiKurangIdeal;

                $a3 =  min($nilaiKapasitasTinggi, $nilaiRatingRendah); // maka tidak idela
                $r3 = $nilaiIdeal - $a3 * ($nilaiIdeal - $nilaiKurangIdeal);
                $a4 = min($nilaiKapasitasTinggi, $nilaiRatingTinggi);
                $r4 = ($nilaiIdeal - $nilaiKurangIdeal) * $a4 + $nilaiKurangIdeal;

                $totalA = ($a1 * $r1) + ($a2 * $r2) + ($a3 * $r3) + ($a4 * $r4);
                $totalB = $a1 + $a2 + $a3 + $a4;
                $finalResult = $totalA / $totalB;

                if ($finalResult <= 50) {
                    $dataFuzzy['place_image'] = $image;
                    $dataFuzzy['status'] = 'Tidak Disarankan';
                    $dataFuzzy['nilai'] = $finalResult;

                    $dataForyou[] = $dataFuzzy;
                } else {
                    $dataFuzzy['place_image'] = $image;
                    $dataFuzzy['status'] = 'Disarankan untuk Anda';
                    $dataFuzzy['nilai'] = $finalResult;
                    $dataForyou[] = $dataFuzzy;
                }
            }
            function searchByKeyword($data, $keyword)
            {
                return strpos(strtolower($data['place_name']), strtolower($keyword)) !== false ||
                    strpos(strtolower($data['place_address']), strtolower($keyword)) !== false;
            }

            // Gunakan array_filter untuk menyaring data berdasarkan kata kunci
            $filtered_by_keyword = array_filter($dataForyou, function ($data) use ($keyword) {
                return searchByKeyword($data, $keyword);
            });
            $sorted_places = $this->place->sort_places_by_status_and_jarak($filtered_by_keyword);

            $this->response([
                'status' => true,
                'message' => 'Data ditemukan!',
                'place' => $sorted_places
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], REST_Controller::HTTP_NO_CONTENT);
        }
    }
    // untuk fitur get tempat (fuzzy tsukamoto)
    public function getplace_get()
    {
        $user_id = $this->getLoggedId();
        // query jarak user saat ini
        $userLastLocation = $this->db->get_where('tbl_user_lastlocation', ['lastlocation_user_id' => $user_id])->result();

        if (!$userLastLocation) {
            $latitude_last = 0;
            $longitude_last = 0;
        } else {
            $latitude_last = (float)$userLastLocation[0]->lastlocation_latitude;
            $longitude_last = (float)$userLastLocation[0]->lastlocation_longitude;

            // ["lastlocation_longitude"]=>
            // string(9) "-6.905895"
            // ["lastlocation_latitude"]=>
            // string(10) "107.574530"
        }
        $like = false;
        $distance_threshold = 1.0;
        // query data place rentang 1 KM terdekat
        $dataPlace = $this->db->get('tbl_place')->result_array();

        if ($dataPlace) {
            $filtered_places = array();

            // jadikan array
            foreach ($dataPlace as $place) {
                /** cek like */
                $dataLike = $this->db->get_where('tbl_ratings', ['rating_user_id' => $user_id, 'rating_place_id' => $place['place_id']])->result();
                if ($dataLike) {
                    $like = true;
                } else {
                    $like = false;
                }
                // die;
                /** EOF cek like */
                $lat2 = (float) $place['place_latitude'];
                $lon2 = (float) $place['place_longitude'];

                $distance = $this->place->haversineDistance($latitude_last, $longitude_last, $lat2, $lon2);

                if ($distance <= $distance_threshold) {
                    $place['isRated'] = $like;
                    $place['jarak'] = round($distance, 2);
                    $filtered_places[] = $place;

                    // array(4) {
                    //     [0]=>
                    //     array(16) {
                    //       ["place_id"]=>
                    //       string(2) "32"
                    //       ["place_user_id"]=>
                    //       string(2) "27"
                    //       ["place_name"]=>
                    //       string(9) "Superindo"
                    //       ["place_address"]=>
                    //       string(14) "Jalan rajawali"
                    //       ["place_car"]=>
                    //       string(1) "9"
                    //       ["place_motor"]=>
                    //       string(2) "20"
                    //       ["place_description"]=>
                    //       string(11) "mantaappppp"
                    //       ["place_image"]=>
                    //       string(86) "[".\/uploads\/image_place\/1690903526.png",".\/uploads\/image_place\/16909035261.png"]"
                    //       ["place_longitude"]=>
                    //       string(18) "-6.912447812693243"
                    //       ["place_latitude"]=>
                    //       string(18) "107.57540765115746"
                    //       ["place_pic"]=>
                    //       string(3) "Dwi"
                    //       ["place_pic_contact"]=>
                    //       string(12) "085721813979"
                    //       ["place_active"]=>
                    //       string(1) "1"
                    //       ["place_rating"]=>
                    //       string(2) "52"
                    //       ["isRated"]=>
                    //       bool(false)
                    //       ["jarak"]=>
                    //       float(0.24)
                    //     }
                    //     [1]=>
                    //     array(16) {
                    //       ["place_id"]=>
                    //       string(2) "33"
                    //       ["place_user_id"]=>
                    //       string(2) "27"
                    //       ["place_name"]=>
                    //       string(20) "Rumah Sakit Rajawali"
                    //       ["place_address"]=>
                    //       string(77) "Jl. Rajawali Barat No.38, Maleber, Kec. Andir, Kota Bandung, Jawa Barat 40184"
                    //       ["place_car"]=>
                    //       string(2) "40"
                    //       ["place_motor"]=>
                    //       string(2) "60"
                    //       ["place_description"]=>
                    //       string(11) "mantaappppp"
                    //       ["place_image"]=>
                    //       string(86) "[".\/uploads\/image_place\/1690903586.png",".\/uploads\/image_place\/16909035861.png"]"
                    //       ["place_longitude"]=>
                    //       string(19) "-6.9118087612516055"
                    //       ["place_latitude"]=>
                    //       string(18) "107.57347646092317"
                    //       ["place_pic"]=>
                    //       string(3) "Dwi"
                    //       ["place_pic_contact"]=>
                    //       string(12) "085721813979"
                    //       ["place_active"]=>
                    //       string(1) "1"
                    //       ["place_rating"]=>
                    //       string(2) "70"
                    //       ["isRated"]=>
                    //       bool(true)
                    //       ["jarak"]=>
                    //       float(0.23)
                    //     }
                    //     [2]=>
                    //     array(16) {
                    //       ["place_id"]=>
                    //       string(2) "34"
                    //       ["place_user_id"]=>
                    //       string(2) "27"
                    //       ["place_name"]=>
                    //       string(50) "Sate AMBAL, Sate TAICHAN, Sate MARANGGI Mas Ikhsan"
                    //       ["place_address"]=>
                    //       string(17) "Jl. Maleber Utara"
                    //       ["place_car"]=>
                    //       string(1) "5"
                    //       ["place_motor"]=>
                    //       string(3) "100"
                    //       ["place_description"]=>
                    //       string(11) "mantaappppp"
                    //       ["place_image"]=>
                    //       string(86) "[".\/uploads\/image_place\/1690903668.png",".\/uploads\/image_place\/16909036681.png"]"
                    //       ["place_longitude"]=>
                    //       string(18) "-6.907580348942091"
                    //       ["place_latitude"]=>
                    //       string(18) "107.57848585864954"
                    //       ["place_pic"]=>
                    //       string(3) "Dwi"
                    //       ["place_pic_contact"]=>
                    //       string(12) "085721813979"
                    //       ["place_active"]=>
                    //       string(1) "1"
                    //       ["place_rating"]=>
                    //       string(1) "5"
                    //       ["isRated"]=>
                    //       bool(true)
                    //       ["jarak"]=>
                    //       float(0.44)
                    //     }
                    //     [3]=>
                    //     array(16) {
                    //       ["place_id"]=>
                    //       string(2) "36"
                    //       ["place_user_id"]=>
                    //       string(2) "27"
                    //       ["place_name"]=>
                    //       string(14) "Bandara husein"
                    //       ["place_address"]=>
                    //       string(12) "Jalan husein"
                    //       ["place_car"]=>
                    //       string(2) "22"
                    //       ["place_motor"]=>
                    //       string(3) "100"
                    //       ["place_description"]=>
                    //       string(11) "mantaappppp"
                    //       ["place_image"]=>
                    //       string(86) "[".\/uploads\/image_place\/1690903858.png",".\/uploads\/image_place\/16909038581.png"]"
                    //       ["place_longitude"]=>
                    //       string(17) "-6.90316179339655"
                    //       ["place_latitude"]=>
                    //       string(18) "107.57999013903179"
                    //       ["place_pic"]=>
                    //       string(3) "Dwi"
                    //       ["place_pic_contact"]=>
                    //       string(12) "085721813979"
                    //       ["place_active"]=>
                    //       string(1) "1"
                    //       ["place_rating"]=>
                    //       string(2) "76"
                    //       ["isRated"]=>
                    //       bool(false)
                    //       ["jarak"]=>
                    //       float(0.61)
                    //     }
                    //   }
                }
            }
            // var_dump($filtered_places);
            // foreach 

            // kemudian proses fuzzy

            // 1. Fuzzifikasi
            // TIngkat kapasitas 10 40
            // tinglat rating 50 100
            // tingkat ideal
            $lowestPlaceCar = PHP_INT_MAX;
            $highestPlaceCar = 0;

            $lowestPlaceRating = PHP_FLOAT_MAX;
            $highestPlaceRating = 0;

            foreach ($filtered_places as $entry) {
                if ($entry['place_car'] === "") {
                    $placeCar = 0; // Mengonversi ke integer
                } else {
                    $placeCar = intval($entry['place_car']); // Mengonversi ke integer
                }

                if ($entry['place_rating'] === "") {
                    $placeRate = 0; // Mengonversi ke integer
                } else {
                    $placeRate = intval($entry['place_rating']); // Mengonversi ke integer
                }

                if ($placeCar < $lowestPlaceCar) {
                    $lowestPlaceCar = $placeCar;
                }

                if ($placeCar > $highestPlaceCar) {
                    $highestPlaceCar = $placeCar;
                }
                if ($placeRate < $lowestPlaceRating) {
                    $lowestPlaceRating = $placeRate;
                }

                if ($placeRate > $highestPlaceRating) {
                    $highestPlaceRating = $placeRate;
                }
            }

            $dataForyou = array();
            foreach ($filtered_places as $dataFuzzy) {
                $place_image = json_decode($dataFuzzy['place_image']);
                if ($dataFuzzy['place_car'] === "") {
                    $nilaiKapasitas = 0;
                } else {
                    $nilaiKapasitas = (float)$dataFuzzy['place_car'];
                }

                if ($dataFuzzy['place_rating'] === "") {
                    $nilaiRating = 0;
                } else {
                    $nilaiRating = (float)$dataFuzzy['place_rating'];
                }

                // ini merupakan nilai konstan, nilai pakar fuzzyfikasi
                // $kapasitasRendah = 10;
                // $kapasitasTinggi = 40;
                // $ratingTinggi = 100;
                // $ratingRendah = 50;
                $kapasitasRendah = $lowestPlaceCar;
                $kapasitasTinggi = $highestPlaceCar;
                $ratingTinggi = $highestPlaceRating;
                $ratingRendah = $lowestPlaceRating;

                // hitung fuzzifikasi
                $nilaiKapasitasRendah = 0;
                $nilaiKapasitasTinggi = 0;
                $nilaiRatingRendah = 0;
                $nilaiRatingTinggi = 0;

                $nilaiIdeal = 100;
                $nilaiKurangIdeal = 50;

                // nilaiKapasitasRendah
                if ($nilaiKapasitas >= $kapasitasTinggi) {
                    $nilaiKapasitasRendah = 0;
                } else if ($nilaiKapasitas <= $kapasitasRendah) {
                    $nilaiKapasitasRendah = 1;
                } else {
                    $nilaiKapasitasRendah = ($kapasitasTinggi - $nilaiKapasitas) / ($kapasitasTinggi - $kapasitasRendah);
                }
                // 
                // nilai kapasitas tinggi
                if ($nilaiKapasitas <= $kapasitasRendah) {
                    $nilaiKapasitasTinggi = 0;
                } else if ($nilaiKapasitas >= 40) {
                    $nilaiKapasitasTinggi = 1;
                } else {
                    $nilaiKapasitasTinggi = ($nilaiKapasitas - $kapasitasRendah) / ($kapasitasTinggi - $kapasitasRendah);
                }
                // nilai rating rndah
                if ($nilaiRating >= $ratingTinggi) {
                    $nilaiRatingRendah = 0;
                } elseif ($nilaiRating <= $ratingRendah) {
                    $nilaiRatingRendah = 1;
                } else {
                    $nilaiRatingRendah = ($ratingTinggi - $nilaiRating) / ($ratingTinggi - $ratingRendah);
                }

                // nilai rating tinggi
                if ($nilaiRating <= $ratingRendah) {
                    $nilaiRatingTinggi = 0;
                } else if ($nilaiRating >= $ratingTinggi) {
                    $nilaiRatingTinggi = 1;
                } else {
                    $nilaiRatingTinggi = ($nilaiRating - $ratingRendah) / ($ratingTinggi - $ratingRendah);
                }

                $dataFuzzyfikasi = [
                    'Persiapan Data ideal' => [
                        'Kapasitas rendah' => $kapasitasRendah,
                        'Kapasitas tinggi' => $kapasitasTinggi,
                        'Rating rendah' => $ratingRendah,
                        'Rating tinggi' => $ratingTinggi,
                        'Nilai Ideal' => $nilaiIdeal,
                        'Nilai Kurang Ideal' => $nilaiKurangIdeal,
                    ],
                    'Nilai Fuzzi' => [
                        'Nilai kapasitas rendah' => $nilaiKapasitasRendah,
                        'Nilai kapasitas tinggi' => $nilaiKapasitasTinggi,
                        'Nilai rating rendah' => $nilaiRatingRendah,
                        'Nilai rating tinggi' => $nilaiRatingTinggi,
                    ]
                ];
                // var_dump($nilaiRatingTinggi);

                // 2. inferensiasi
                // Rules :
                // kapasitas rendah && rating rendah = kurang ideal
                // kapasitas rendah && rating tinggi = ideal
                // kapasitas tinggi && rating rendah = kurang idela
                // kapasitas tinggi && rating tinggi = ideal


                $r1 = 0;
                $r2 = 0;
                $r3 = 0;
                $r4 = 0;
                $a1 = min($nilaiKapasitasRendah, $nilaiRatingRendah); // maka kurang ideal
                $r1 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a1;
                $a2 = min($nilaiKapasitasRendah, $nilaiRatingTinggi); // maka ideal
                $r2 = $a2 * ($nilaiIdeal - $nilaiKurangIdeal) + $nilaiKurangIdeal;

                $a3 =  min($nilaiKapasitasTinggi, $nilaiRatingRendah); // maka tidak idela
                $r3 = $nilaiIdeal - $a3 * ($nilaiIdeal - $nilaiKurangIdeal);
                $a4 = min($nilaiKapasitasTinggi, $nilaiRatingTinggi);
                $r4 = ($nilaiIdeal - $nilaiKurangIdeal) * $a4 + $nilaiKurangIdeal;

                $dataInferensiasi = [
                    'Nilai Inferensiasi' => [
                        'Nilai a1 & r1' => $a1 . ' & ' . $r1,
                        'Nilai a2 & r2' => $a2 . ' & ' . $r2,
                        'Nilai a3 & r3' => $a3 . ' & ' . $r3,
                        'Nilai a4 & r4' => $a4 . ' & ' . $r4,
                    ]
                ];
                /**defuzzyfikasi */
                $totalA = ($a1 * $r1) + ($a2 * $r2) + ($a3 * $r3) + ($a4 * $r4);
                $totalB = $a1 + $a2 + $a3 + $a4;
                $finalResult = $totalA / $totalB;

                $dataDefuzzyfikasi = [
                    'Data Defuzzyfikasi' => [
                        'Total A' => $totalA,
                        'Total B' => $totalB,

                        'Hasil Defuzzyfikasi' => $finalResult
                    ],
                ];

                if ($finalResult <= 50) {
                    $dataFuzzy['place_image'] = $place_image;
                    $dataFuzzy['status'] = '';
                    $dataFuzzy['Hasil Perhitungan'] = [
                        $dataFuzzyfikasi, $dataInferensiasi, $dataDefuzzyfikasi
                    ];

                    $dataForyou[] = $dataFuzzy;
                } else {
                    $dataFuzzy['place_image'] = $place_image;
                    $dataFuzzy['status'] = 'Disarankan untuk Anda';
                    $dataFuzzy['Hasil Perhitungan'] = [
                        $dataFuzzyfikasi, $dataInferensiasi, $dataDefuzzyfikasi
                    ];
                    $dataForyou[] = $dataFuzzy;
                }
            }
            $sorted_places = $this->place->sort_places_by_status_and_jarak($dataForyou);
            // var_dump($dataForyou);

            $this->response([
                'status' => true,
                'message' => 'Data ditemukan!',
                'place' => $sorted_places
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], REST_Controller::HTTP_NO_CONTENT);
        }
    }
    // untuk fitur get tempat berdasarkan like (fuzzy tsukamoto)
    public function getplacebylike_get()
    {
        $user_id = $this->getLoggedId();
        // query jarak user saat ini
        $userLastLocation = $this->db->get_where('tbl_user_lastlocation', ['lastlocation_user_id' => $user_id])->result();
        if (!$userLastLocation) {
            $latitude_last = 0;
            $longitude_last = 0;
        } else {
            $latitude_last = (float)$userLastLocation[0]->lastlocation_latitude;
            $longitude_last = (float)$userLastLocation[0]->lastlocation_longitude;
        }
        $like = false;
        $distance_threshold = 1.0;

        // query data place rentang 1 KM terdekat
        $dataPlace = $this->db->get('tbl_place')->result_array();
        if ($dataPlace) {
            $filtered_places = array();
            // jadikan array
            foreach ($dataPlace as $place) {
                $dataLike = $this->db->get_where('tbl_ratings', ['rating_user_id' => $user_id, 'rating_place_id' => $place['place_id']])->result();
                if ($dataLike) {
                    $like = true;
                } else {
                    $like = false;
                }
                $place_image = json_decode($place['place_image']);
                $lat2 = (float) $place['place_latitude'];
                $lon2 = (float) $place['place_longitude'];

                $distance = $this->place->haversineDistance($latitude_last, $longitude_last, $lat2, $lon2);

                if ($distance <= $distance_threshold) {
                    $place['isRated'] = $like;
                    $place['jarak'] = round($distance, 2);
                    $place['place_image'] = $place_image;
                    $filtered_places[] = $place;
                }
            }
            $sorted_places = $this->place->sort_places_by_like($filtered_places);
            $this->response([
                'status' => true,
                'message' => 'Data ditemukan!',
                'place' => $sorted_places
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], REST_Controller::HTTP_NO_CONTENT);
        }
    }
    // untuk fitur get tempat berdasarkan like (fuzzy tsukamoto)
    public function getplacebydistance_get()
    {
        $user_id = $this->getLoggedId();
        // query jarak user saat ini
        $userLastLocation = $this->db->get_where('tbl_user_lastlocation', ['lastlocation_user_id' => $user_id])->result();
        if (!$userLastLocation) {
            $latitude_last = 0;
            $longitude_last = 0;
        } else {
            $latitude_last = (float)$userLastLocation[0]->lastlocation_latitude;
            $longitude_last = (float)$userLastLocation[0]->lastlocation_longitude;
        }
        $like = false;
        $distance_threshold = 1.0;

        // query data place rentang 1 KM terdekat
        $dataPlace = $this->db->get('tbl_place')->result_array();
        if ($dataPlace) {
            $filtered_places = array();
            // jadikan array
            foreach ($dataPlace as $place) {
                $dataLike = $this->db->get_where('tbl_ratings', ['rating_user_id' => $user_id, 'rating_place_id' => $place['place_id']])->result();
                if ($dataLike) {
                    $like = true;
                } else {
                    $like = false;
                }
                $place_image = json_decode($place['place_image']);
                $lat2 = (float) $place['place_latitude'];
                $lon2 = (float) $place['place_longitude'];

                $distance = $this->place->haversineDistance($latitude_last, $longitude_last, $lat2, $lon2);

                if ($distance <= $distance_threshold) {
                    $place['isRated'] = $like;
                    $place['jarak'] = round($distance, 2);
                    $place['place_image'] = $place_image;
                    $filtered_places[] = $place;
                }
            }
            $sorted_places = $this->place->sort_places_by_distance($filtered_places);
            $this->response([
                'status' => true,
                'message' => 'Data ditemukan!',
                'place' => $sorted_places
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ], REST_Controller::HTTP_NO_CONTENT);
        }
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
    public function getplacebyuseradmin_get()
    {
        $user_id = $this->getLoggedId();

        $isAdmin = $this->userdata->isAdmin($this->getEmail());

        if ($isAdmin > 0) {
            $data = $this->place->getPlaceByUserAdmin();
            $dataFinal = array();
            foreach ($data as $place) {
                $place['place_image'] = json_decode($place['place_image']);
                $dataFinal[] = $place;
            }

            if ($data) {
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'place' => $dataFinal
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Data not found!'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Access not Allowed'
            ], REST_Controller::HTTP_FORBIDDEN);
        }
    }
    public function getplacebyuser_get()
    {
        $user_id = $this->getLoggedId();

        $data = $this->place->getPlaceByUser($user_id);
        $dataFinal = array();
        foreach ($data as $place) {
            $place['place_image'] = json_decode($place['place_image']);
            $dataFinal[] = $place;
        }

        if ($data) {
            $this->response([
                'status' => true,
                'message' => 'Success',
                'place' => $dataFinal
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data not found!'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function getLastLoc_post()
    {
        $user_id = $this->getLoggedId();

        $lastLongitude = $this->post('last_longitude');
        $lastLatitude = $this->post('last_latitude');

        $querydata = $this->db->get_where('tbl_user_lastlocation', ['lastlocation_user_id' => $user_id])->num_rows();
        if ($querydata > 0) {
            $this->db->update('tbl_user_lastlocation', ['lastlocation_longitude' => $lastLongitude, 'lastlocation_latitude' => $lastLatitude], ['lastlocation_user_id' => $user_id]);
        } else {
            $this->db->insert('tbl_user_lastlocation', ['lastlocation_user_id' => $user_id, 'lastlocation_longitude' => $lastLongitude, 'lastlocation_latitude' => $lastLatitude]);
        }

        $insertData = $this->db->affected_rows();
        if ($insertData > 0) {
            $this->response([
                'status' => true,
                'message' => 'Success! Place is edited!',
                'location' => [
                    'longitude' => $lastLongitude,
                    'latitude' => $lastLatitude
                ]
            ], REST_Controller::HTTP_CREATED);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Failed to insert last location'
            ], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
