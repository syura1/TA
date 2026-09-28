<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login Administrator</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body{
            background:#0d6efd;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .card{
            width:420px;
            border:none;
            border-radius:18px;
            box-shadow:0 15px 35px rgba(0,0,0,.2);
        }

        .logo{
            width:90px;
            height:90px;
            background:#e9f2ff;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto;
        }

        .logo i{
            font-size:45px;
            color:#0d6efd;
        }
    </style>

</head>

<body>

<div class="card">

    <div class="card-body p-4">

        <div class="text-center">

            <div class="logo mb-3">
                <i class="bi bi-person-lock"></i>
            </div>

            <h3 class="fw-bold">
                Login Administrator
            </h3>

            <p class="text-muted mb-1">
                SPK TOPSIS
            </p>

            <small class="text-secondary">
                Toko Sentral Asesoris Motor
            </small>

        </div>

        <hr>

        <form action="proseslogin.php" method="POST">

            <div class="mb-3">

                <label class="form-label">
                    Username
                </label>

                <div class="input-group">

                    <span class="input-group-text">
                        <i class="bi bi-person-fill"></i>
                    </span>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Masukkan username"
                        required>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <div class="position-relative">

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control pe-5"
                        placeholder="Masukkan password"
                        required>

                    <i
                        class="bi bi-eye-fill"
                        id="iconPassword"
                        style="
                        position:absolute;
                        top:50%;
                        right:15px;
                        transform:translateY(-50%);
                        cursor:pointer;
                        font-size:20px;
                        color:#6c757d;">
                    </i>

                </div>

            </div>

            <button class="btn btn-primary w-100">

                <i class="bi bi-box-arrow-in-right"></i>

                Login Administrator

            </button>

        </form>

        <div class="text-center mt-4">

            <small class="text-muted d-block mb-3">
                Halaman ini hanya dapat diakses oleh administrator.
            </small>

            <a href="index.php" class="btn btn-outline-secondary btn-sm">

                <i class="bi bi-arrow-left"></i>

                Kembali ke Beranda

            </a>

        </div>

    </div>

</div>

<script>

const password=document.getElementById("password");
const iconPassword=document.getElementById("iconPassword");

iconPassword.addEventListener("click",function(){

    if(password.type==="password"){

        password.type="text";

        iconPassword.classList.replace("bi-eye-fill","bi-eye-slash-fill");

    }else{

        password.type="password";

        iconPassword.classList.replace("bi-eye-slash-fill","bi-eye-fill");

    }

});

</script>

</body>
</html>