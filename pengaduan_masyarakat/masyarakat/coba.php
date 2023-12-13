<?php 
	
	if(isset($_POST['kirim'])){
		$nik = $_SESSION['data']['nik'];
		$tgl = date('Y-m-d');

		// Mendapatkan informasi file foto
		$foto = $_FILES['foto']['name'];
		$fotoSource = $_FILES['foto']['tmp_name'];
		$fotoFolder = './../img/'; // Direktori tujuan untuk menyimpan foto
		$allowedImageExtensions = array('jpg', 'png', 'jpeg'); // Ekstensi foto yang diizinkan
		$imageExtension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));
		$imageSize = $_FILES['foto']['size'];
		$imageName = date('dmYis') . $foto;

		// Mendapatkan informasi file video
		$video = $_FILES['video']['name'];
		$videoSource = $_FILES['video']['tmp_name'];
		$videoFolder = './../video/'; // Direktori tujuan untuk menyimpan video
		$allowedVideoExtensions = array('mp4', 'avi', 'mov'); // Ekstensi video yang diizinkan
		$videoExtension = strtolower(pathinfo($video, PATHINFO_EXTENSION));
		$videoSize = $_FILES['video']['size'];
		$videoName = date('dmYis') . $video;

		if($foto != "" && $video != ""){
			// Validasi ekstensi foto dan video
			if(in_array($imageExtension, $allowedImageExtensions) && in_array($videoExtension, $allowedVideoExtensions)){
				// Validasi ukuran foto dan video (misalnya, 5MB untuk foto dan 100MB untuk video)
				if($imageSize <= 5000000 && $videoSize <= 100000000){
					move_uploaded_file($fotoSource, $fotoFolder . $imageName);
					move_uploaded_file($videoSource, $videoFolder . $videoName);
					$query = mysqli_query($koneksi, "INSERT INTO pengaduan VALUES (NULL, '$tgl', '$nik', '" . $_POST['laporan'] . "', '" . $_POST['lokasi'] . "', '" . $_POST['kronologi'] . "', '$imageName', '$videoName', 'proses')");

					if($query){
						echo "<script>alert('Pengaduan Akan Segera di Proses')</script>";
						echo "<script>location='index.php';</script>";
					}
				}
				else{
					echo "<script>alert('Ukuran Foto Tidak Lebih Dari 5 MB atau Ukuran Video Tidak Lebih Dari 100 MB')</script>";
				}
			}
			else{
				echo "<script>alert('Format Foto atau Video Tidak Di Dukung')</script>";
			}
		}
		else{
			$query = mysqli_query($koneksi, "INSERT INTO pengaduan VALUES (NULL, '$tgl', '$nik', '" . $_POST['laporan'] . "', 'noImage.png', 'proses')");
			if($query){
				echo "<script>alert('Pengaduan Akan Segera Ditanggapi')</script>";
				echo "<script>location='index.php';</script>";
			}
		}
	}

?>
