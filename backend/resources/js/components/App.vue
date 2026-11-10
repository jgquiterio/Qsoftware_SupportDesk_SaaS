<template>
    <div class="app-wrapper">

        <!-- Navbar -->
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            href="#"
                            data-lte-toggle="sidebar"
                            role="button"
                            aria-label="Toggle sidebar"
                        >
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link fw-bold">
                            SupportDesk SaaS
                        </span>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside
            class="app-sidebar bg-dark shadow"
            data-bs-theme="dark"
        >
            <div class="sidebar-brand">
                <a href="#" class="brand-link text-decoration-none">
                    <span class="brand-text fw-light">
                        SupportDesk
                    </span>
                </a>
            </div>

            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul
                        class="nav sidebar-menu flex-column"
                        data-lte-toggle="treeview"
                        role="menu"
                    >
                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="app-main">

            <div class="app-content-header">
                <div class="container-fluid">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Overview
                            </h5>
                        </div>

                        <div class="card-body">
                            Welcome to SupportDesk SaaS!
                        </div>
                    </div>

                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <strong>SupportDesk SaaS</strong>
            <div class="float-end d-none d-sm-inline">
                Version 1.0.0
            </div>
        </footer>

    </div>
</template>