<?php

session_start();

require_once 'app/koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];
$hak_akses = $_POST['hak_akses'];

$users = $conn->query("SELECT * FROM admin WHERE username = '$username' AND hak_akses = '$hak_akses'");

// cek apakah ada username
if ($users->num_rows > 0) {
  $user = $users->fetch_assoc();

  // cek password
  if ($password === $user['password']) {
    $_SESSION['user'] = $user;
    header("Location: index.php");
  } else {
    $_SESSION['err_message'] = "Username atau password salah!";
    echo "<script>alert('Data Akun Tidak Sesuai, Silahkan Login Ulang');window.location='login.php'</script>";
  }
} else {
  $_SESSION['err_message'] = "Username atau password salah!";
    echo "<script>alert('Data Akun Tidak Sesuai, Silahkan Login Ulang');window.location='login.php'</script>";
}
