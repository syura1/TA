<?php

session_start();
include "koneksi/koneksi.php";

// Ambil data dari form
$username = trim($_POST['username']);
$password = $_POST['password'];

// Cek apakah username ada
$query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

if(mysqli_num_rows($query) == 1){

    $data = mysqli_fetch_assoc($query);

    // Cek password
    if(password_verify($password, $data['password'])){

        $_SESSION['login'] = true;
        $_SESSION['id_admin'] = $data['id_admin'];
        $_SESSION['username'] = $data['username'];

        header("Location: admin/dashboard.php");
        exit;

    }else{

        echo "<script>
                alert('Password salah!');
                window.location='login.php';
              </script>";

    }

}else{

    echo "<script>
            alert('Username tidak ditemukan!');
            window.location='login.php';
          </script>";

}

?>