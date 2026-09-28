<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SPK TOPSIS</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
            overflow-x: hidden;
        }

        /* SIDEBAR */

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #123b73;
            position: fixed;
            top: 0;
            left: 0;
            color: #fff;
            transition: .3s;
            z-index: 999;
            box-shadow: 3px 0 10px rgba(0, 0, 0, .15);
        }


        .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, .2);
        }

        .logo h4 {
            margin: 0;
            font-weight: bold;
        }

        .logo small {
            color: #d9d9d9;
        }

        .sidebar-menu {
            margin-top: 10px;
        }

        .sidebar-menu a {
            display: block;
            padding: 14px 20px;
            color: #fff;
            text-decoration: none;
            transition: .3s;
        }

        .sidebar-menu a:hover {
            background: #0d6efd;
            padding-left: 28px;
        }

        .sidebar-menu a.active {
            background: #0d6efd;
        }

        .sidebar-menu i {
            width: 25px;
        }

        /* CONTENT */

        .content {
            margin-left: 250px;
            transition: .3s;
        }


        /* TOPBAR */

        .topbar {
            height: 60px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        }

        .topbar .navbar-brand {
            font-weight: bold;
        }

        /* CARD */

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        }

        .card-header {
            background: #0d6efd;
            color: #fff;
            font-weight: bold;
        }

        .dashboard-card {
            cursor: pointer;
            transition: all .2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--bs-box-shadow-lg) !important;
        }

        .dashboard-card:active {
            transform: translateY(0);
        }

        .table th {
            background: #0d6efd;
            color: #fff;
            text-align: center;
        }

        .table td {
            vertical-align: middle;
        }

        .ttd {

            width: 250px;

            margin-left: auto;

            margin-top: 80px;

            text-align: center;

        }
    </style>

</head>

<body>