<?php
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'kriptografi_hasan';

// koneksi ke database
$conn = mysqli_connect($hostname, $username, $password, $database);

// Memeriksa koneksi database
if (mysqli_connect_errno()) {
    echo "Koneksi database gagal : " . mysqli_connect_error();
}

// Memeriksa apakah tombol submit sudah diklik
if (isset($_POST['submit'])) {
    $nama = $_POST["nama_lengkap"];
    $kelas = $_POST["kelas"];
    $email = $_POST["email"];
    $noHP = $_POST["no_handphone"];
    $alamat = $_POST["alamat"];

    // Kunci enkripsi
    $method = 'aes-256-cbc';
    $password = 'Kampus_STTI';

    // IV must be exact 16 chars (128 bit)
    $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

    // Melakukan enkripsi data    
    $encrypted_nama = base64_encode(openssl_encrypt($nama, $method, $password, OPENSSL_RAW_DATA, $iv));
    $encrypted_kelas = base64_encode(openssl_encrypt($kelas, $method, $password, OPENSSL_RAW_DATA, $iv));
    $encrypted_email = base64_encode(openssl_encrypt($email, $method, $password, OPENSSL_RAW_DATA, $iv));
    $encrypted_noHP = base64_encode(openssl_encrypt($noHP, $method, $password, OPENSSL_RAW_DATA, $iv));
    $encrypted_alamat = base64_encode(openssl_encrypt($alamat, $method, $password, OPENSSL_RAW_DATA, $iv));

    // Menyusun query untuk menyimpan data ke database
    $query = "INSERT INTO mahasiswa 
                VALUES 
                ('', '$encrypted_nama', '$encrypted_kelas', '$encrypted_email', '$encrypted_noHP', '$encrypted_alamat') ";

    // Menjalankan query
    mysqli_query($conn, $query);

    // Memeriksa apakah data berhasil disimpan
    if (mysqli_affected_rows($conn) > 0) {
        echo "<script>
                  alert('Berhasil!');
              </script>";
    } else {
        echo "<script>
                alert('Gagal!');
              </script>";
        echo "<br>";
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Kriptografi</title>
</head>

<body>
    <div class="container col-md-4 float-start mt-3">
        <div class="alert alert-primary text-center">
            <h2>Data Mahasiswa</h2>
        </div>
        <div class="form-gorup">
            <form action="" method="post">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" id="nama" placeholder="Nama Lengkap" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <input type="text" class="form-control" name="kelas" id="kelas" placeholder="Kelas" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="hp">No. Handphone</label>
                    <input type="text" class="form-control" name="no_handphone" id="hp" placeholder="N0. Handphone" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat Lengkap" autocomplete="off" required></textarea>
                </div>
                <br>
                <button class="btn btn-primary" type="submit" name="submit">Submit</button>
            </form>
        </div>
    </div>

    <?php
    // ambil data di database
    $query = "SELECT * FROM mahasiswa";
    $result = mysqli_query($conn, $query);
    ?>
    <div class="container col-md-8 float-end mt-3">
        <div class="alert alert-primary text-center">
            <h2>Data Mahasiswa</h2>
        </div>
        <div class="form-group">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col">Kelas</th>
                        <th scope="col">Email</th>
                        <th scope="col">No. Handphone</th>
                        <th scope="col">Alamat</th>
                    </tr>
                </thead>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {

                        // Kunci enkripsi
                        $method = 'aes-256-cbc';
                        $password = 'Kampus_STTI';

                        // IV must be exact 16 chars (128 bit)
                        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

                        // Mendekripsi data yang terenkripsi dari database
                        $decrypted_nama = openssl_decrypt(base64_decode($row['nama']), $method, $password, OPENSSL_RAW_DATA, $iv);
                        $decrypted_kelas = openssl_decrypt(base64_decode($row['kelas']), $method, $password, OPENSSL_RAW_DATA, $iv);
                        $decrypted_email = openssl_decrypt(base64_decode($row['email']), $method, $password, OPENSSL_RAW_DATA, $iv);
                        $decrypted_noHP = openssl_decrypt(base64_decode($row['hp']), $method, $password, OPENSSL_RAW_DATA, $iv);
                        $decrypted_alamat = openssl_decrypt(base64_decode($row['alamat']), $method, $password, OPENSSL_RAW_DATA, $iv);

                ?>
                        <tbody>
                            <tr>
                                <td><?= $no; ?></td>
                                <td><?= $decrypted_nama; ?></td>
                                <td><?= $decrypted_kelas; ?></td>
                                <td><?= $decrypted_email; ?></td>
                                <td><?= $decrypted_noHP; ?></td>
                                <td><?= $decrypted_alamat; ?></td>
                            </tr>
                        </tbody>
                <?php
                        $no++;
                    }
                }
                ?>
            </table>
        </div>
</body>

</html>