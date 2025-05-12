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

                <?php if (session('isLoggedIn')): ?>
                    <div class="d-flex flex-row align-items-center justify-content-between me-3">
                        <button class="btn btn-warning position-relative me-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $cartCount ?></span>
                        </button>
                        <div class="d-flex flex-row align-items-center justify-content-between">
                            <div class="dropdown text-white d-inline d-lg-block">
                                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Welcome, <?= session('name') ?>! <i class="fas fa-fw"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="<?= route_to('auth.updateProfile') ?>"><i class="fas fa-user-pen"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="<?= route_to('orders.index', session('id')) ?>"><i class="fas fa-cart-plus"></i> Orders</a></li>
                                    <li><a class="dropdown-item" href="<?= route_to('auth.logout') ?>"><i class="fas fa-right-from-bracket"></i> Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column flex-lg-row align-items-lg-center">
                            <a href="<?= route_to('auth.register') ?>" class="btn btn-outline-light me-lg-2 mb-2 mb-lg-0">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                            <a href="<?= route_to('auth.login') ?>" class="btn btn-warning">
                                <i class="fas fa-right-from-bracket"></i> Login
                            </a>
                        </div>
                    <?php endif; ?>
                    </div>
            </div>
    </nav>