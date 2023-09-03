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
    public function getplaceold_get()
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
                }
            }


            $dataForyou = array();
            foreach ($filtered_places as $dataFuzzy) {
                $place_image = json_decode($dataFuzzy['place_image']);
                $nilaiKapasitas = (float)$dataFuzzy['place_car'];
                $nilaiRating = (float)$dataFuzzy['place_rating'];

                // ini merupakan nilai konstan, nilai pakar fuzzyfikasi
                $kapasitasRendah = 10;
                $kapasitasTinggi = 40;
                $ratingTinggi = 100;
                $ratingRendah = 50;

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
                // nilai kapasitas tinggi
                if ($nilaiKapasitas <= $kapasitasRendah) {
                    $nilaiKapasitasTinggi = 0;
                } else if ($nilaiKapasitas >= $kapasitasTinggi) {
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
                // 2. inferensiasi


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
                $a4 = min($nilaiKapasitasTinggi, $nilaiRatingTinggi); // maka ideal
                $r4 = ($nilaiIdeal - $nilaiKurangIdeal) * $a4 + $nilaiKurangIdeal;

                $dataInferensiasi = [
                    'Nilai Inferensiasi' => [
                        'Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a1) & maka nilai kurang ideal (r1)' => $a1 . ' & ' . $r1,
                        'Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating tinggi: ' . $nilaiRatingTinggi . ' (a2) & maka nilai ideal (r2)' => $a2 . ' & ' . $r2,
                        'Nilai kapasitas tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' (a3) & maka tidak ideal (r3)' => $a3 . ' & ' . $r3,
                        'Nilai kapasitas tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating tinggi: ' . $nilaiRatingTinggi . ' (a4) & maka nilai ideal (r4)' => $a4 . ' & ' . $r4,
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

                        'Hasil Defuzzyfikasi Total A / Total B' => $finalResult
                    ],
                ];
                // var_dump($dataFuzzyfikasi, $dataInferensiasi, $dataDefuzzyfikasi);

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
                    // $place['jarak'] = $distance;
                    $place['jarak'] = round($distance, 2);
                    $filtered_places[] = $place;
                }
            }


            $dataForyou = array();
            foreach ($filtered_places as $dataFuzzy) {
                $place_image = json_decode($dataFuzzy['place_image']);

                $nilaiJarak = (float) $dataFuzzy['jarak'];
                $nilaiKapasitas = (float)$dataFuzzy['place_car'];
                $nilaiRating = (float)$dataFuzzy['place_rating'];

                // ini merupakan nilai konstan, nilai pakar fuzzyfikasi
                $JarakRendah = 0.1;
                $JarakTinggi = 1;
                $kapasitasRendah = 10;
                $kapasitasTinggi = 40;
                $ratingTinggi = 100;
                $ratingRendah = 50;

                // hitung fuzzifikasi
                $nilaiJarakRendah = 0;
                $nilaiJarakTinggi = 0;
                $nilaiKapasitasRendah = 0;
                $nilaiKapasitasTinggi = 0;
                $nilaiRatingRendah = 0;
                $nilaiRatingTinggi = 0;

                $nilaiIdeal = 100;
                $nilaiKurangIdeal = 50;

                //nilaiJarakRendah
                if ($nilaiJarak >= $JarakTinggi) {
                    $nilaiJarakRendah = 0;
                } else if ($nilaiJarak <= $JarakRendah) {
                    $nilaiJarakRendah = 1;
                } else {
                    $nilaiJarakRendah = ($JarakTinggi - $nilaiJarak) / ($JarakTinggi - $JarakRendah);
                }

                //nilaiJarakTinggi
                if ($nilaiJarak >= $JarakTinggi) {
                    $nilaiJarakTinggi = 1;
                } else if ($nilaiJarak <= $JarakRendah) {
                    $nilaiJarakTinggi = 0;
                } else {
                    $nilaiJarakTinggi = ($nilaiJarak - $JarakRendah) / ($JarakTinggi - $JarakRendah);
                }
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
                } else if ($nilaiKapasitas >= $kapasitasTinggi) {
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
                        'Jarak rendah' => $JarakRendah,
                        'Jarak tinggi' => $JarakTinggi,
                        'Kapasitas rendah' => $kapasitasRendah,
                        'Kapasitas tinggi' => $kapasitasTinggi,
                        'Rating rendah' => $ratingRendah,
                        'Rating tinggi' => $ratingTinggi,
                        'Nilai Ideal' => $nilaiIdeal,
                        'Nilai Kurang Ideal' => $nilaiKurangIdeal,
                    ],
                    'Nilai Fuzzi' => [
                        'Nilai jarak dekat' => $nilaiJarakRendah,
                        'Nilai jarak jauh' => $nilaiJarakTinggi,
                        'Nilai kapasitas rendah' => $nilaiKapasitasRendah,
                        'Nilai kapasitas tinggi' => $nilaiKapasitasTinggi,
                        'Nilai rating rendah' => $nilaiRatingRendah,
                        'Nilai rating tinggi' => $nilaiRatingTinggi,
                    ]
                ];
                // 2. inferensiasi
                $r1 = 0;
                $r2 = 0;
                $r3 = 0;
                $r4 = 0;
                $r5 = 0;
                $r6 = 0;
                $r7 = 0;
                $r8 = 0;
                $a1 = min($nilaiJarakTinggi, $nilaiKapasitasRendah, $nilaiRatingRendah); // maka kurang ideal
                $r1 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a1;

                $a2 = min($nilaiJarakTinggi, $nilaiKapasitasRendah, $nilaiRatingTinggi); // maka ideal
                $r2 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a2;

                $a3 = min($nilaiJarakTinggi, $nilaiKapasitasTinggi, $nilaiRatingRendah); // maka tidak idela
                $r3 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a3;

                $a4 = min($nilaiJarakTinggi, $nilaiKapasitasTinggi, $nilaiRatingTinggi); // maka ideal
                $r4 = ($nilaiIdeal - $nilaiKurangIdeal) * $a4 + $nilaiKurangIdeal;

                $a5 = min($nilaiJarakRendah, $nilaiKapasitasRendah, $nilaiRatingRendah);
                $r5 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a5;
                $a6 = min($nilaiJarakRendah, $nilaiKapasitasRendah, $nilaiRatingTinggi);
                $r6 = ($nilaiIdeal - $nilaiKurangIdeal) * $a6 + $nilaiKurangIdeal;
                $a7 = min($nilaiJarakRendah, $nilaiKapasitasTinggi, $nilaiRatingRendah);
                $r7 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a7;
                $a8 = min($nilaiJarakRendah, $nilaiKapasitasTinggi, $nilaiRatingTinggi);
                $r8 = ($nilaiIdeal - $nilaiKurangIdeal) * $a8 + $nilaiKurangIdeal;


                $dataInferensiasi = [
                    'Nilai Inferensiasi' => [
                        'Nilai jarak jauh: ' . $nilaiJarakTinggi . ', Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a1) & maka nilai kurang ideal (r1)' => $a1 . ' & ' . $r1,
                        'Nilai jarak jauh: ' . $nilaiJarakTinggi . ', Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating tinggi: ' . $nilaiRatingTinggi . ' = (a2) & maka nilai kurang ideal (r2)' => $a2 . ' & ' . $r2,
                        'Nilai jarak jauh: ' . $nilaiJarakTinggi . ', Nilai kapasitas Tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a3) & maka nilai kurang ideal (r3)' => $a3 . ' & ' . $r3,
                        'Nilai jarak jauh: ' . $nilaiJarakTinggi . ', Nilai kapasitas Tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating tinggi: ' . $nilaiRatingTinggi . ' = (a4) & maka nilai kurang ideal (r4)' => $a4 . ' & ' . $r4,
                        'Nilai jarak dekat: ' . $nilaiJarakRendah . ', Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a5) & maka nilai kurang ideal (r5)' => $a5 . ' & ' . $r5,
                        'Nilai jarak dekat: ' . $nilaiJarakRendah . ', Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating Tinggi: ' . $nilaiRatingTinggi . ' = (a6) & maka nilai kurang ideal (r6)' => $a6 . ' & ' . $r6,
                        'Nilai jarak dekat: ' . $nilaiJarakRendah . ', Nilai kapasitas Tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a7) & maka nilai kurang ideal (r7)' => $a7 . ' & ' . $r7,
                        'Nilai jarak dekat: ' . $nilaiJarakRendah . ', Nilai kapasitas Tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating tinggi: ' . $nilaiRatingTinggi . ' = (a8) & maka nilai kurang ideal (r8)' => $a8 . ' & ' . $r8,

                    ]
                ];
                /**defuzzyfikasi */
                $totalA = ($a1 * $r1) + ($a2 * $r2) + ($a3 * $r3) + ($a4 * $r4) + ($a5 * $r5) + ($a6 * $r6) + ($a7 * $r7) + ($a8 * $r8);
                $totalB = $a1 + $a2 + $a3 + $a4 + $a5 + $a6 + $a7 + $a8;
                $finalResult = $totalA / $totalB;
                $dataDefuzzyfikasi = [
                    'Data Defuzzyfikasi' => [
                        'Total A' => $totalA,
                        'Total B' => $totalB,

                        'Hasil Defuzzyfikasi Total A / Total B' => $finalResult
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
    public function getplaceall_get()
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
        // $distance_threshold = 1.0;
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

                // if ($distance <= $distance_threshold) {
                $place['isRated'] = $like;
                $place['jarak'] = round($distance, 2);
                $filtered_places[] = $place;
                // }
            }


            $dataForyou = array();
            foreach ($filtered_places as $dataFuzzy) {
                $place_image = json_decode($dataFuzzy['place_image']);
                $nilaiJarak = (float) $dataFuzzy['jarak'];
                $nilaiKapasitas = (float)$dataFuzzy['place_car'];
                $nilaiRating = (float)$dataFuzzy['place_rating'];

                // ini merupakan nilai konstan, nilai pakar fuzzyfikasi
                $JarakRendah = 0.1;
                $JarakTinggi = 1;
                $kapasitasRendah = 10;
                $kapasitasTinggi = 40;
                $ratingTinggi = 100;
                $ratingRendah = 50;

                // hitung fuzzifikasi
                $nilaiJarakRendah = 0;
                $nilaiJarakTinggi = 0;
                $nilaiKapasitasRendah = 0;
                $nilaiKapasitasTinggi = 0;
                $nilaiRatingRendah = 0;
                $nilaiRatingTinggi = 0;

                $nilaiIdeal = 100;
                $nilaiKurangIdeal = 50;

                //nilaiJarakRendah
                if ($nilaiJarak >= $JarakTinggi) {
                    $nilaiJarakRendah = 0;
                } else if ($nilaiJarak <= $JarakRendah) {
                    $nilaiJarakRendah = 1;
                } else {
                    $nilaiJarakRendah = ($JarakTinggi - $nilaiJarak) / ($JarakTinggi - $JarakRendah);
                }

                //nilaiJarakTinggi
                if ($nilaiJarak >= $JarakTinggi) {
                    $nilaiJarakTinggi = 1;
                } else if ($nilaiJarak <= $JarakRendah) {
                    $nilaiJarakTinggi = 0;
                } else {
                    $nilaiJarakTinggi = ($nilaiJarak - $JarakRendah) / ($JarakTinggi - $JarakRendah);
                }

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
                        'Jarak rendah' => $JarakRendah,
                        'Jarak tinggi' => $JarakTinggi,
                        'Kapasitas rendah' => $kapasitasRendah,
                        'Kapasitas tinggi' => $kapasitasTinggi,
                        'Rating rendah' => $ratingRendah,
                        'Rating tinggi' => $ratingTinggi,
                        'Nilai Ideal' => $nilaiIdeal,
                        'Nilai Kurang Ideal' => $nilaiKurangIdeal,
                    ],
                    'Nilai Fuzzi' => [
                        'Nilai jarak dekat' => $nilaiJarakRendah,
                        'Nilai jarak jauh' => $nilaiJarakTinggi,
                        'Nilai kapasitas rendah' => $nilaiKapasitasRendah,
                        'Nilai kapasitas tinggi' => $nilaiKapasitasTinggi,
                        'Nilai rating rendah' => $nilaiRatingRendah,
                        'Nilai rating tinggi' => $nilaiRatingTinggi,
                    ]
                ];

                // 2. inferensiasi
                $r1 = 0;
                $r2 = 0;
                $r3 = 0;
                $r4 = 0;
                $r5 = 0;
                $r6 = 0;
                $r7 = 0;
                $r8 = 0;
                $a1 = min($nilaiJarakTinggi, $nilaiKapasitasRendah, $nilaiRatingRendah); // maka kurang ideal
                $r1 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a1;

                $a2 = min($nilaiJarakTinggi, $nilaiKapasitasRendah, $nilaiRatingTinggi); // maka ideal
                $r2 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a2;

                $a3 =  min($nilaiJarakTinggi, $nilaiKapasitasTinggi, $nilaiRatingRendah); // maka tidak idela
                $r3 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a3;
                $a4 = min($nilaiJarakTinggi, $nilaiKapasitasTinggi, $nilaiRatingTinggi); // maka ideal
                $r4 = ($nilaiIdeal - $nilaiKurangIdeal) * $a4 + $nilaiKurangIdeal;

                $a5 = min($nilaiJarakRendah, $nilaiKapasitasRendah, $nilaiRatingRendah);
                $r5 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a5;
                $a6 = min($nilaiJarakRendah, $nilaiKapasitasRendah, $nilaiRatingTinggi);
                $r6 = ($nilaiIdeal - $nilaiKurangIdeal) * $a6 + $nilaiKurangIdeal;
                $a7 = min($nilaiJarakRendah, $nilaiKapasitasTinggi, $nilaiRatingRendah);
                $r7 = $nilaiIdeal - ($nilaiIdeal - $nilaiKurangIdeal) *  $a7;
                $a8 = min($nilaiJarakRendah, $nilaiKapasitasTinggi, $nilaiRatingTinggi);
                $r8 = ($nilaiIdeal - $nilaiKurangIdeal) * $a8 + $nilaiKurangIdeal;
                $dataInferensiasi = [
                    'Nilai Inferensiasi' => [
                        'Nilai jarak jauh: ' . $nilaiJarakTinggi . ', Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a1) & maka nilai kurang ideal (r1)' => $a1 . ' & ' . $r1,
                        'Nilai jarak jauh: ' . $nilaiJarakTinggi . ', Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating tinggi: ' . $nilaiRatingTinggi . ' = (a2) & maka nilai kurang ideal (r2)' => $a2 . ' & ' . $r2,
                        'Nilai jarak jauh: ' . $nilaiJarakTinggi . ', Nilai kapasitas Tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a3) & maka nilai kurang ideal (r3)' => $a3 . ' & ' . $r3,
                        'Nilai jarak jauh: ' . $nilaiJarakTinggi . ', Nilai kapasitas Tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating tinggi: ' . $nilaiRatingTinggi . ' = (a4) & maka nilai kurang ideal (r4)' => $a4 . ' & ' . $r4,
                        'Nilai jarak dekat: ' . $nilaiJarakRendah . ', Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a5) & maka nilai kurang ideal (r5)' => $a5 . ' & ' . $r5,
                        'Nilai jarak dekat: ' . $nilaiJarakRendah . ', Nilai kapasitas rendah: ' . $nilaiKapasitasRendah . ' dan nilai rating Tinggi: ' . $nilaiRatingTinggi . ' = (a6) & maka nilai kurang ideal (r6)' => $a6 . ' & ' . $r6,
                        'Nilai jarak dekat: ' . $nilaiJarakRendah . ', Nilai kapasitas Tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating rendah: ' . $nilaiRatingRendah . ' = (a7) & maka nilai kurang ideal (r7)' => $a7 . ' & ' . $r7,
                        'Nilai jarak dekat: ' . $nilaiJarakRendah . ', Nilai kapasitas Tinggi: ' . $nilaiKapasitasTinggi . ' dan nilai rating tinggi: ' . $nilaiRatingTinggi . ' = (a8) & maka nilai kurang ideal (r8)' => $a8 . ' & ' . $r8,

                    ]
                ];
                /**defuzzyfikasi */
                $totalA = ($a1 * $r1) + ($a2 * $r2) + ($a3 * $r3) + ($a4 * $r4) + ($a5 * $r5) + ($a6 * $r6) + ($a7 * $r7) + ($a8 * $r8);
                $totalB = $a1 + $a2 + $a3 + $a4 + $a5 + $a6 + $a7 + $a8;
                $finalResult = $totalA / $totalB;
                $dataDefuzzyfikasi = [
                    'Data Defuzzyfikasi' => [
                        'Total A' => $totalA,
                        'Total B' => $totalB,

                        'Hasil Defuzzyfikasi Total A / Total B' => $finalResult
                    ],
                ];
                // var_dump($dataFuzzyfikasi, $dataInferensiasi, $dataDefuzzyfikasi);

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
