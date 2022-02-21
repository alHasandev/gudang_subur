<?php

require_once 'app/koneksi.php';

$target_dir = "assets/img/profiles/";
if ($_FILES['foto']['temp_name'] !== "") {
  $target_file = $target_dir . basename($_FILES["foto"]["name"]);
  $filetype = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
  $filename = $target_dir . $_POST['username'] . ".$filetype";
  $uploadOk = 1;

  // Check if image file is a actual image or fake image
  $check = getimagesize($_FILES["foto"]["tmp_name"]);
  if ($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }

  // Check if file already exists
  // if (file_exists($target_file)) {
  //   echo "Sorry, file already exists.";
  //   $uploadOk = 0;
  // }

  // Check file size
  if ($_FILES["foto"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if (
    $filetype != "jpg" && $filetype != "png" && $filetype != "jpeg"
    && $filetype != "gif"
  ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    $filename = $target_dir . "default.png";
    // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["foto"]["tmp_name"], "$filename")) {
      echo "The file " . htmlspecialchars(basename($_POST['username'] . "")) . " has been uploaded.";
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
} else {
  $filename = $target_dir . "default.png";
}

$password = $_POST['password'];

// hash password
// $hashed = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO admin (username, password, nama, alamat, kontak, foto, hak_akses) VALUES ('$_POST[username]', '$password', '$_POST[nama]', '$_POST[alamat]', '$_POST[kontak]', '$filename', '$_POST[hak_akses]')";

$hasil = $conn->query($query);

if ($hasil) {
  header('Location: data_admin.php');
} else {
  die('Gagal menambah data admin: ' . $conn->error);
}
