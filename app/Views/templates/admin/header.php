<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PizzaHub <?= isset($title) ? "- $title" : '' ?></title>
    <link rel="stylesheet" type="text/css" href="<?= base_url('css/styles.css') ?>">
    <script src="<?= base_url('js/chartjs.js') ?>"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-4 fw-bold" href="<?= route_to('admin.index') ?>">Pizza<span class="text-black bg-warning p-1">Hub</span></a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="<?= route_to('auth.logout') ?>"><i class="fas fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Resources</div>
                        <a class="nav-link <?= uri_string() === 'admin/dashboard' ? 'active' : '' ?>" href="<?= route_to('admin.index') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <a class="nav-link 
                        <?= str_starts_with(uri_string(), 'admin/users') ? 'active' : '' ?>"
                            href="<?= route_to('admin.users.index') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Users
                        </a>
                        <a class="nav-link 
                        <?= str_starts_with(uri_string(), 'admin/categories') ? 'active' : '' ?>" 
                        href="<?= route_to('admin.categories.index') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-layer-group"></i></div>
                            Categories
                        </a>
                        <a class="nav-link 
                        <?= str_starts_with(uri_string(), 'admin/pizzas') ? 'active' : '' ?>" 
                        href="<?= route_to('admin.pizzas.index') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-pizza-slice"></i></div>
                            Pizzas
                        </a>
                        <a class="nav-link
                         <?= str_starts_with(uri_string(), 'admin/orders') ? 'active' : '' ?>"
                          href="<?= route_to('admin.orders.index') ?>">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Orders
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <?= session('name') ?>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">