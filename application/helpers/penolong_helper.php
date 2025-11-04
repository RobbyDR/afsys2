<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('dateTextMySQL2ID')) {
    function dateID($date)
    {
        $th = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl = substr($date, 8, 2);

        $tglDepan = substr($tgl, 0, 1);
        $tgldiambil = substr($tgl, 1, 1);

        if ($tglDepan == "0") {
            $tglID = $tgldiambil;
        } else {
            $tglID = $tgl;
        }

        if ($bulan == "01") {
            $dateID = "$tglID Januari $th";
            return $dateID;
        } elseif ($bulan == "02") {
            $dateID = "$tglID Februari $th";
            return $dateID;
        } elseif ($bulan == "03") {
            $dateID = "$tglID Maret $th";
            return $dateID;
        } elseif ($bulan == "04") {
            $dateID = "$tglID April $th";
            return $dateID;
        } elseif ($bulan == "05") {
            $dateID = "$tglID Mei $th";
            return $dateID;
        } elseif ($bulan == "06") {
            $dateID = "$tglID Juni $th";
            return $dateID;
        } elseif ($bulan == "07") {
            $dateID = "$tglID Juli $th";
            return $dateID;
        } elseif ($bulan == "08") {
            $dateID = "$tglID Agustus $th";
            return $dateID;
        }
        if ($bulan == "09") {
            $dateID = "$tglID September $th";
            return $dateID;
        } elseif ($bulan == "10") {
            $dateID = "$tglID Oktober $th";
            return $dateID;
        } elseif ($bulan == "11") {
            $dateID = "$tglID November $th";
            return $dateID;
        } elseif ($bulan == "12") {
            $dateID = "$tglID Desember $th";
            return $dateID;
        }
    }
}

if (!function_exists('resize_image_old')) {
    function resize_image_old($source_path, $max_width, $max_height)
    {
        // Buka gambar asli
        $source_image = imagecreatefromstring(file_get_contents($source_path));

        // Dapatkan ukuran asli
        $original_width = imagesx($source_image);
        $original_height = imagesy($source_image);

        // Tentukan aspek rasio asli
        $aspect_ratio = $original_width / $original_height;

        // Hitung ukuran baru berdasarkan aspek rasio
        if ($original_width > $max_width || $original_height > $max_height) {
            // Tentukan lebar dan tinggi yang proporsional
            if ($aspect_ratio > 1) { // Landscape
                $new_width = $max_width;
                $new_height = $max_width / $aspect_ratio;
            } else { // Portrait
                $new_height = $max_height;
                $new_width = $max_height * $aspect_ratio;
            }

            // Buat gambar baru dengan ukuran yang diinginkan
            $resized_image = imagecreatetruecolor($new_width, $new_height);

            // Transparansi untuk PNG
            $ekstensi = pathinfo($source_path, PATHINFO_EXTENSION);
            $ekstensi = strtolower($ekstensi);
            if ($ekstensi === 'png') {
                imagealphablending($resized_image, false);
                imagesavealpha($resized_image, true);
            }

            // Ubah ukuran gambar
            imagecopyresampled(
                $resized_image,
                $source_image,
                0,
                0,
                0,
                0,
                $new_width,
                $new_height,
                $original_width,
                $original_height
            );

            // Simpan gambar yang diubah ukurannya
            if ($ekstensi === 'jpg' || $ekstensi === 'jpeg') {
                imagejpeg($resized_image, $source_path, 90); // Simpan hasil resized ke path asli
            } else if ($ekstensi === 'png') {
                imagepng($resized_image, $source_path);
            } else if ($ekstensi === 'gif') {
                imagegif($resized_image, $source_path);
            }

            // Bersihkan sumber daya gambar
            imagedestroy($resized_image);
        }

        // Bersihkan sumber daya gambar asli
        imagedestroy($source_image);

        return true; // Resize dilakukan
    }
}

if (!function_exists('upload_file')) {
    /**
     * Upload file dan resize gambar jika file tersebut adalah gambar
     *
     * @param string $field_name Nama field file input
     * @param int $maxid ID terakhir dari data yang disimpan
     * @param string $upload_path Path tempat penyimpanan file
     * @param string $allowed_types Tipe file yang diizinkan
     * @param int $max_size Ukuran maksimal file (dalam KB)
     * @param int|null $width Lebar gambar setelah resize (null jika tidak ingin resize)
     * @param int|null $height Tinggi gambar setelah resize (null jika tidak ingin resize)
     * @return mixed Nama file baru jika berhasil, false jika gagal
     */
    function upload_file($field_name, $namafile, $upload_path = './upload/', $allowed_types = 'gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx', $max_size = 32768, $width = 640, $height = 480)
    {
        // Mendapatkan instance CI
        $CI = &get_instance();

        if (!empty($_FILES[$field_name]['name'])) {
            // Mendapatkan informasi file
            $file_tmp = $_FILES[$field_name]['tmp_name'];
            $file_mime = mime_content_type($file_tmp);
            $allowed_mimes = array('image/gif', 'image/jpeg', 'image/png', 'image/jpg');

            // Memeriksa apakah file adalah gambar
            $is_image = in_array($file_mime, $allowed_mimes);

            // Mendapatkan ekstensi file
            // $ekstensi = pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION);
            // $nama_baru = uniqid() . '.' . $ekstensi;

            // Mendapatkan nama file asli dan menghasilkan nama baru
            if ($namafile) $original_name = $namafile;
            else $original_name = $_FILES[$field_name]['name'];
            $nama_baru = generate_unique_filename($original_name, $field_name);
            // var_dump($original_name);
            // var_dump($nama_baru);
            // die;
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = $allowed_types;
            $config['file_name'] = $nama_baru;
            $config['max_size'] = $max_size; // dalam KB

            // Memuat library Upload dengan konfigurasi yang telah ditentukan
            $CI->load->library('upload', $config);
            $CI->upload->initialize($config);

            if ($CI->upload->do_upload($field_name)) {
                $upload_data = $CI->upload->data();
                $source_path = $upload_data['full_path'];

                // Resize gambar hanya jika file adalah gambar
                // if ($is_image && !is_null($width) && !is_null($height)) {
                //     if (!resize_image($source_path, $width, $height)) {
                //         // Menangani error resizing
                //         log_message('error', 'Resize gagal: ' . $CI->image_lib->display_errors());
                //         // return false;
                //         return 'Resize gagal' . $CI->image_lib->display_errors();
                //     }
                // }

                return $nama_baru; // Mengembalikan nama file baru untuk memperbarui database
            } else {
                // Menangani error upload
                log_message('error', 'Upload gagal: ' . $CI->upload->display_errors());
                // return false;
                return 'upload gagal' . $CI->upload->display_errors();
            }
        }
        return false;
    }
}

if (!function_exists('resize_image')) {
    /**
     * Resize gambar menggunakan library Image Manipulation CI3
     *
     * @param string $source_path Path lengkap ke file gambar
     * @param int $width Lebar gambar setelah resize
     * @param int $height Tinggi gambar setelah resize
     * @return bool True jika berhasil, false jika gagal
     */
    function resize_image($source_path, $width, $height)
    {
        // Mendapatkan instance CI
        $CI = &get_instance();

        // Memuat library Image Manipulation jika belum dimuat
        $CI->load->library('image_lib');

        // Konfigurasi untuk resizing
        $config['image_library'] = 'gd2';
        $config['source_image'] = $source_path;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = $width;
        $config['height'] = $height;

        $CI->image_lib->clear(); // Membersihkan konfigurasi sebelumnya
        $CI->image_lib->initialize($config);

        if ($CI->image_lib->resize()) {
            return true;
        } else {
            // Menangani error resizing
            log_message('error', 'Resize gagal: ' . $CI->image_lib->display_errors());
            return false;
        }
    }
}


if (!function_exists('delete_file')) {
    /**
     * Delete a file from the specified directory
     * 
     * @param string $directory The directory where the file is located
     * @param string $filename The name of the file to delete
     * @return bool True if file was successfully deleted, False otherwise
     */
    function delete_file($directory, $filename)
    {
        $filepath = FCPATH . $directory . $filename;

        if (file_exists($filepath)) {
            if (unlink($filepath)) {
                return true; // File deleted successfully
            } else {
                return false; // File couldn't be deleted
            }
        } else {
            return false; // File not found
        }
    }
}

if (!function_exists('generate_unique_filename')) {
    function generate_unique_filename($original_name, $field_name)
    {
        // Menghilangkan simbol dan karakter non-alfanumerik
        $clean_name = preg_replace('/[^a-zA-Z0-9]/', '', pathinfo($original_name, PATHINFO_FILENAME));

        // Membatasi panjang nama file menjadi 10 karakter
        $limited_name = strtolower(substr($clean_name, 0, 10));

        // Menambahkan kode unik
        $unique_code = uniqid();

        // Mengambil ekstensi file
        // $extension = pathinfo($original_name, PATHINFO_EXTENSION);
        $extension = pathinfo($_FILES[$field_name]['name'], PATHINFO_EXTENSION);

        // Menghasilkan nama file baru
        return $limited_name . $unique_code . '.' . $extension;
        // return $limited_name . $unique_code;
    }
}


if (!function_exists('get_dropdown_with_preserved_value')) {
    /**
     * Mengembalikan array hasil query yang difilter + nilai lama jika tidak masuk filter
     *
     * @param CI_DB_query_builder $db
     * @param string $table Nama tabel
     * @param array $filter Kondisi WHERE, contoh: ['status' => 1]
     * @param string|int|null $preserve_id ID yang harus tetap muncul meskipun tidak masuk filter
     * @param string $order_by Kolom untuk order (misal: 'id ASC')
     * @return array
     */
    function get_dropdown_with_preserved_value($db, $table, $filter = [], $preserve_id = null, $order_by = 'id ASC')
    {
        // Ambil data aktif
        if (!empty($filter)) {
            $db->where($filter);
        }

        if (!empty($order_by)) {
            $db->order_by($order_by);
        }

        $result = $db->get($table)->result_array();

        // Cek apakah preserve_id sudah ada
        $ids = array_column($result, 'id');
        if ($preserve_id !== null && !in_array($preserve_id, $ids)) {
            $preserve_row = $db->where('id', $preserve_id)->get($table)->row_array();
            if ($preserve_row) {
                $result[] = $preserve_row;
            }
        }

        return $result;
    }
}

if (!function_exists('get_masakerja')) {
    function get_masakerja($nippegawai)
    {
        // // Ambil nilai nippegawai
        // $nippegawai = '198712142015021001'; // Gantilah dengan nilai $get['nippegawai']

        // Ambil bagian tanggal (201502) dari nippegawai
        $tanggal = substr($nippegawai, 8, 6);

        // Ekstrak tahun dan bulan
        $tahun = substr($tanggal, 0, 4);
        $bulan = substr($tanggal, 4, 2);

        // Buat objek DateTime untuk tanggal mulai dan tanggal saat ini
        $tanggal_mulai = new DateTime("$tahun-$bulan-01");
        $tanggal_sekarang = new DateTime();

        // Hitung selisih waktu
        $interval = $tanggal_mulai->diff($tanggal_sekarang);

        // Format hasilnya menjadi "X Tahun Y Bulan"
        $masakerja = $interval->y . " Tahun " . $interval->m . " Bulan";

        // Menampilkan hasil
        // echo $masakerja;

        return $masakerja;
    }
}

if (!function_exists('get_jumlahcuti')) {
    function get_jumlahcuti($tgl1, $tgl2)
    {
        $start = new DateTime($tgl1);
        $end = new DateTime($tgl2);
        $end->modify('+1 day'); // agar tanggal akhir ikut dihitung

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($start, $interval, $end);

        // Daftar hari libur nasional (format: YYYY-MM-DD)
        $liburNasional = [
            '2025-01-01', // Tahun Baru
            '2025-03-31', // Nyepi
            '2025-04-18', // Wafat Isa Almasih
            '2025-05-01', // Hari Buruh
            '2025-05-29', // Kenaikan Isa Almasih
            '2025-06-01', // Hari Lahir Pancasila
            '2025-06-05', // Idul Adha
            '2025-07-28', // Tahun Baru Islam
            '2025-08-17', // Hari Kemerdekaan
            '2025-10-06', // Maulid Nabi
            '2025-12-25', // Natal
        ];

        $jumlahCuti = 0;

        foreach ($dateRange as $date) {
            $day = $date->format('N'); // 1 = Senin, ..., 7 = Minggu
            $tanggal = $date->format('Y-m-d');

            if ($day < 6 && !in_array($tanggal, $liburNasional)) {
                $jumlahCuti++;
            }
        }

        return $jumlahCuti;
    }
}


if (!function_exists('get_angkaketulisan')) {
    function get_angkaketulisan($angka)
    {
        $angka = abs($angka);
        $bilangan = [
            "",
            "satu",
            "dua",
            "tiga",
            "empat",
            "lima",
            "enam",
            "tujuh",
            "delapan",
            "sembilan",
            "sepuluh",
            "sebelas"
        ];

        $tulisan = "";

        if ($angka < 12) {
            $tulisan = $bilangan[$angka];
        } elseif ($angka < 20) {
            $tulisan = get_angkaketulisan($angka - 10) . " belas";
        } elseif ($angka < 100) {
            $tulisan = get_angkaketulisan(floor($angka / 10)) . " puluh";
            if ($angka % 10 != 0) {
                $tulisan .= " " . get_angkaketulisan($angka % 10);
            }
        } elseif ($angka < 200) {
            $tulisan = "seratus";
            if ($angka - 100 != 0) {
                $tulisan .= " " . get_angkaketulisan($angka - 100);
            }
        } elseif ($angka < 1000) {
            $tulisan = get_angkaketulisan(floor($angka / 100)) . " ratus";
            if ($angka % 100 != 0) {
                $tulisan .= " " . get_angkaketulisan($angka % 100);
            }
        } elseif ($angka < 2000) {
            $tulisan = "seribu";
            if ($angka - 1000 != 0) {
                $tulisan .= " " . get_angkaketulisan($angka - 1000);
            }
        } elseif ($angka < 1000000) {
            $tulisan = get_angkaketulisan(floor($angka / 1000)) . " ribu";
            if ($angka % 1000 != 0) {
                $tulisan .= " " . get_angkaketulisan($angka % 1000);
            }
        } elseif ($angka < 1000000000) {
            $tulisan = get_angkaketulisan(floor($angka / 1000000)) . " juta";
            if ($angka % 1000000 != 0) {
                $tulisan .= " " . get_angkaketulisan($angka % 1000000);
            }
        } else {
            $tulisan = "Angka terlalu besar";
        }

        return trim($tulisan);
    }
}
