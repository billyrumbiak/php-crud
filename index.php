<?php

$host       = 'localhost';
$user       = 'root';
$pass       = '';
$db         = 'akademik';

$koneksi    = mysqli_connect($host,$user,$pass,$db);

if(!$koneksi) { // cek koneksi
    die('Tidak bisa terkoneksi ke database');
}
$nim        = '';
$nama       = '';
$alamat     = '';
$fakultas   = '';
$sukses     = '';
$error      = '';

if(isset($_GET['op'])){
    $op = $_GET['op'];
} else {
    $op = "";
}

if($op == 'delete'){ // hapus data
    $id         = $_GET['id'];
    $sql1       = "DELETE FROM mahasiswa WHERE id = '$id'";
    $q1         =  mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $error  = "Gagal melakukan delete data";
    }
}

if($op == 'edit'){ // ubah data
    $id         =   $_GET['id'];
    $sql1       =   "SELECT * FROM mahasiswa WHERE id = '$id'";
    $q1         =   mysqli_query($koneksi,$sql1);
    $r1         =   mysqli_fetch_array($q1);
    $nim        =   $r1['nim'];
    $nama       =   $r1['nama'];
    $alamat     =   $r1['alamat'];
    $fakultas   =   $r1['fakultas'];

    if($nim == ''){
        $error  = "Data tidak ditemukan";
    }
}

if(isset($_POST['simpan'])){ // untuk create
    $nim        = $_POST['nim'];
    $nama       = $_POST['nama'];
    $alamat     = $_POST['alamat'];
    $fakultas   = $_POST['fakultas'];

    if($nim && $nama && $alamat && $fakultas){
        if($op == 'edit'){ 
            $sql1       =   "UPDATE mahasiswa SET nim = '$nim',nama = '$nama',alamat = '$alamat',fakultas = '$fakultas' WHERE id = 'id'";
            $q1         =   mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses =   "Data berhasil diupdate";
            } else {
                $error  =   "Data gagal diupdate";
            }
        } else {
            $sql1 = "INSERT INTO mahasiswa(nim,nama,alamat,fakultas) VALUES ('$nim','$nama','$alamat','$fakultas')";
            $q1   = mysqli_query($koneksi,$sql1);
            if($q1){
                $sukses     = 'Berhasil memasukkan data baru';
            }else{
                $error      = 'Gagal memasukkan data';
            }
        }
    }else {
        $error = 'Silahkan masukkan semua data';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <!-- Style CSS -->
    <style>
        .mx-auto {
            width : 800px;
        }
        .card {
            margin-top:10px;
        }
    </style>

</head>
<body>

    <div class="mx-auto">
        <!-- memamsukkan data -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php 
                    if($error){
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");//5 detik
                }
                ?>
                <?php 
                    if($sukses){
                ?>
                    <div class="alert alert-success" role="alert">
                        <?= $sukses ?>
                    </div>
                <?php
                    header("refresh:5;url=index.php");//5 detik
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?= $nim?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= $nama?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $alamat?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="fakultas" id="fakultas">
                                <option value=""> - Pilih Fakultas -</option>
                                <option value="saintek" <?php if($fakultas == 'saintek') echo 'selected'?>>saintek</option>
                                <option value="" <?php if($fakultas == 'soshum') echo 'selected'?>>soshum</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

        <!-- mengeluarkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Fakultas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   =   "SELECT * FROM mahasiswa order by id desc";
                        $q2     =   mysqli_query($koneksi,$sql2);
                        $urut   =   1;
                        while($r2 = mysqli_fetch_array($q2)){
                            $id         =   $r2['id'];
                            $nim        =   $r2['nim'];
                            $nama       =   $r2['nama'];
                            $alamat     =   $r2['alamat'];
                            $fakultas   =   $r2['fakultas'];

                            ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?= $nim ?></td>
                                <td scope="row"><?= $nama ?></td>
                                <td scope="row"><?= $alamat ?></td>
                                <td scope="row"><?= $fakultas ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?= $id ?>">
                                    <button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&id=<?= $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>