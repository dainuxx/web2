<?php
include 'conn/koneksi.php';
// menyimpan data kedalam variabel
$nik         = $_POST['nik'];
$nama             = $_POST['nama'];
$username    = $_POST['username'];
$password         = md5($_POST['password']);
$telp            = $_POST['telp'];

// query SQL untuk insert data
$query="INSERT INTO masyarakat SET nik='$nik',nama='$nama',username='$username',password='$password',telp='$telp'";
mysqli_query($koneksi, $query);
// mengalihkan ke halaman index.php
echo "<script> 
            alert('Data berhasil ditambah!');
            document.location.href = 'index.php';
        </script>
    ";
?>