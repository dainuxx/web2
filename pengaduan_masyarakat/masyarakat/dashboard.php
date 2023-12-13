<table class="responsive-table" border="2" style="width: 100%;">
	<tr>
		<td><h4 class="orange-text hide-on-med-and-down">Tulis Laporan</h4></td>
		<td><h4 class="orange-text hide-on-med-and-down">Daftar Laporan</h4></td>
	</tr>
	<tr>
		<td style="padding: 20px;">
			<form method="POST" enctype="multipart/form-data">	
			<textarea class="materialize-textarea" name="laporan" placeholder="Tulis Laporan"></textarea><br><br>
			<div class="form-group">
                        <label for="lokasi">Lokasi</label>
                        <input type="text" class="form-control" id="lokasi" name="lokasi" placeholder="Masukkan Lokasi Kejadian">
                    </div>
					<div class="form-group">
                        <label for="kronologi">Kronologi</label>
                        <input type="text" class="form-control" id="kronologi" name="kronologi" placeholder="Masukkan Kronologi Kejadian">
                    </div>	
					<div class="form-group">
				<label>Gambar</label><br>
				<input type="file" name="foto"><br><br>
					</div>
					<div class="form-group">
				<label>Video</label><br>
				<input type="file" name="video"><br><br>
					</div>
				<input type="submit" name="kirim" value="Kirim" class="btn">
			</form>
		</td>

		<td>
			
			<table border="3" class="responsive-table striped highlight">
				<tr>
					<td>No</td>
					<td>NIK</td>
					<td>Nama</td>
					<td>Tanggal Masuk</td>
					<td>Lokasi</td>
					<td>Video</td>
					<td>Status</td>
					<td>Opsi</td>
				</tr>
				<?php 
					$no=1;
					$pengaduan = mysqli_query($koneksi,"SELECT * FROM pengaduan INNER JOIN masyarakat ON pengaduan.nik=masyarakat.nik INNER JOIN tanggapan ON pengaduan.id_pengaduan=tanggapan.id_pengaduan WHERE pengaduan.nik='".$_SESSION['data']['nik']."' ORDER BY pengaduan.id_pengaduan DESC");
					while ($r=mysqli_fetch_assoc($pengaduan)) { ?>
					<tr>
						<td><?php echo $no++; ?></td>
						<td><?php echo $r['nik']; ?></td>
						<td><?php echo $r['nama']; ?></td>
						<td><?php echo $r['tgl_pengaduan']; ?></td>
						<td><?php echo $r['lokasi']; ?></td>
						<td>
			<video width="150" height="180" controls>
 			 <source src="../video/<?php echo $r['video']; ?>" type="video/mp4"></td>
						<td><?php echo $r['status']; ?></td>
						<td>
							<a class="btn blue modal-trigger" href="#tanggapan&id_pengaduan=<?php echo $r['id_pengaduan'] ?>">More</a> 
							<a class="btn red" onclick="return confirm('Anda Yakin Ingin Menghapus Y/N')" href="index.php?p=pengaduan_hapus&id_pengaduan=<?php echo $r['id_pengaduan'] ?>">Hapus</a></td>

<!-- ditanggapi -->
        <div id="tanggapan&id_pengaduan=<?php echo $r['id_pengaduan'] ?>" class="modal">
          <div class="modal-content">
		  <img src="../img.jpg"  class="img-responsive" alt="Cinque Terre" width="100" height="150">
            <h4 class="orange-text">Detail Pengaduan</h4>
            <div class="col s12">
				<p>NIK : <?php echo $r['nik']; ?></p>
            	<p>Dari : <?php echo $r['nama']; ?></p>
            	<p>Petugas : <?php echo $r['id_petugas']; ?></p>
				<p>Tanggal Masuk : <?php echo $r['tgl_pengaduan']; ?></p>
				<p>Tanggal Ditanggapi : <?php echo $r['tgl_tanggapan']; ?></p>
				<p>Foto</p>
				<?php 
					if($r['foto']=="kosong"){ ?>
						<img src="../img/noImage.png" width="100">
				<?php	}else{ ?>
					<img width="100" src="../img/<?php echo $r['foto']; ?>">
				<?php }
				 ?>
				<br><b>Pesan</b>
				<p><?php echo $r['isi_laporan']; ?></p>
				<br><b>Respon</b>
				<p><?php echo $r['tanggapan']; ?></p>
            </div>

          </div>
          <div class="modal-footer col s12">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
          </div>
        </div>
<!-- ditanggapi -->

					</tr>
				<?php	}
				 ?>
			</table>

		</td>
	</tr>
</table>
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