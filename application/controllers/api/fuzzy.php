<?php

defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'controllers/api/Auth.php';

class Place extends Auth
{
    public function getplace_get()
    {
        /** 1. Define user_id */
        $user_id = $this->getLoggedId();
        /** EOF Define user_id */

        /** 2. Cek titik koordinat */
        // titik koordinat diambil dari database user //
        $userLastLocation = $this->db->get_where('tbl_user_lastlocation', ['lastlocation_user_id' => $user_id])->result();

        if (!$userLastLocation) {
            $latitude_last = 0;
            $longitude_last = 0;
        } else {
            $latitude_last = (float)$userLastLocation[0]->lastlocation_latitude;
            $longitude_last = (float)$userLastLocation[0]->lastlocation_longitude;
        }
        /** EOF cek titik koordinat */

        /** 3. query tempat*/
        $distance_threshold = 1.0;
        $dataPlace = $this->db->get('tbl_place')->result_array();
        /** EOF query tempat*/
        /** 4. Filter tempat yang berada 1 KM dari user */
        if ($dataPlace) {
            $filtered_places = array();

            $like = false;
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
                    $place['jarak'] = $distance;
                    $filtered_places[] = $place;
                }
            }
            /** EOF Filter tempat yang berada 1 KM dari user */

            /** 5. Proses Fuzzy */
            $dataForyou = array();
            foreach ($filtered_places as $dataFuzzy) {
                $place_image = json_decode($dataFuzzy['place_image']);
                /** 5.0 Variable tempat yang akan di hitung fuzzynya */
                $nilaiKapasitas = (float)$dataFuzzy['place_car'];
                $nilaiRating = (float)$dataFuzzy['place_rating'];
                /** 5.1 Tentukan nilai ideal Fuzzy */
                $kapasitasRendah = 10;
                $kapasitasTinggi = 40;
                $ratingTinggi = 100;
                $ratingRendah = 50;
                $nilaiIdeal = 100;
                $nilaiKurangIdeal = 50;

                /** 5.2 hitung fuzzifikasi */

                $nilaiKapasitasRendah = 0;
                $nilaiKapasitasTinggi = 0;
                $nilaiRatingRendah = 0;
                $nilaiRatingTinggi = 0;

                if ($nilaiKapasitas >= $kapasitasTinggi) {
                    $nilaiKapasitasRendah = 0;
                } else if ($nilaiKapasitas <= $kapasitasRendah) {
                    $nilaiKapasitasRendah = 1;
                } else {
                    $nilaiKapasitasRendah = ($kapasitasTinggi - $nilaiKapasitas) / ($kapasitasTinggi - $kapasitasRendah);
                }

                if ($nilaiKapasitas <= $kapasitasRendah) {
                    $nilaiKapasitasTinggi = 0;
                } else if ($nilaiKapasitas >= 40) {
                    $nilaiKapasitasTinggi = 1;
                } else {
                    $nilaiKapasitasTinggi = ($nilaiKapasitas - $kapasitasRendah) / ($kapasitasTinggi - $kapasitasRendah);
                }

                if ($nilaiRating >= $ratingTinggi) {
                    $nilaiRatingRendah = 0;
                } elseif ($nilaiRating <= $ratingRendah) {
                    $nilaiRatingRendah = 1;
                } else {
                    $nilaiRatingRendah = ($ratingTinggi - $nilaiRating) / ($ratingTinggi - $ratingRendah);
                }

                if ($nilaiRating <= $ratingRendah) {
                    $nilaiRatingTinggi = 0;
                } else if ($nilaiRating >= $ratingTinggi) {
                    $nilaiRatingTinggi = 1;
                } else {
                    $nilaiRatingTinggi = ($nilaiRating - $ratingRendah) / ($ratingTinggi - $ratingRendah);
                }
                /** 5.3 inferensiasi */

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

                /** 5.4 Hitung Average nilai inferensiasi */
                $totalA = ($a1 * $r1) + ($a2 * $r2) + ($a3 * $r3) + ($a4 * $r4);
                $totalB = $a1 + $a2 + $a3 + $a4;
                $finalResult = $totalA / $totalB;


                /** 5.5 Kategorikan tempat berdasarkan nilai fuzzy */
                if ($finalResult <= 50) {
                    $dataFuzzy['place_image'] = $place_image;
                    $dataFuzzy['status'] = 'Tidak Disarankan';
                    $dataFuzzy['nilai'] = $finalResult;

                    $dataForyou[] = $dataFuzzy;
                } else {
                    $dataFuzzy['place_image'] = $place_image;
                    $dataFuzzy['status'] = 'Disarankan untuk Anda';
                    $dataFuzzy['nilai'] = $finalResult;
                    $dataForyou[] = $dataFuzzy;
                }
            }
            /** 5.6 Urutkan tempatnya */
            $sorted_places = $this->place->sort_places_by_status_and_jarak($dataForyou);

            /** 6 Menampilkan urutan tempatny */
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
}
