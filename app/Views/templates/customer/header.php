<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PizzaHub <?= isset($title) ? "- $title" : '' ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/styles.css') ?>">
</head>

<body>
    <nav class="fixed-top w-100 navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold ps-2" href="<?= route_to('home.index') ?>">Pizza<span class="block text-black bg-warning ml-1 p-1">Hub</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                </ul>
                <div class="d-flex flex-column flex-lg-row align-items-lg-center">
                    <a href="<?= route_to('auth.register') ?>" class="btn btn-outline-light me-lg-2 mb-2 mb-lg-0">
                        <i class="fas fa-user-plus"></i> Register
                    </a>
                    <a href="<?= route_to('auth.login') ?>" class="btn btn-warning">
                        <i class="fas fa-right-from-bracket"></i> Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    