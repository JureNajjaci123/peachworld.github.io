@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <li>
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
                    <div class="sidebar-brand-text mx-3">L-RP Admin<sup>v3</sup></div>
                </a>
            </li>

            <!-- Divider -->
            <li><hr class="sidebar-divider my-0"></li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li><hr class="sidebar-divider"></li>

            <!-- Heading -->
            <li>
                <div class="sidebar-heading">
                    Players
                </div>
            </li>

            <!-- Nav Item - Players -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Players</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Logs</span>
                </a>
            </li>

            <!-- Divider -->
            <li><hr class="sidebar-divider my-0"></li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0" placeholder="Search players..." aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Divider -->
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        @if ($user = auth()->user())
                            <li class="nav-item dropdown no-arrow">
                                <!-- User part -->
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name }}</span>
                                    <img class="img-profile rounded-circle" alt="Avatar" src="{{ $user->avatar }}">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <!-- Profile -->
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>

                                    <!-- Settings -->
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Settings
                                    </a>

                                    <!-- Activity -->
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Activity Log
                                    </a>

                                    <!-- Divider -->
                                    <div class="dropdown-divider"></div>

                                    <!-- Logging out -->
                                    <form class="dropdown-item" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="btn btn-sm btn-danger w-100" type="submit">Logout</button>
                                    </form>
                                </div>
                            </li>
                        @endif
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Page main content -->
                <main class="container-fluid">
                    <!-- Page Heading -->
                    <div class="mb-4">
                        <h3 class="text-dark">@yield('title')</h3>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        @yield('main')
                    </div>
                </main>

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>ExpDev07 - <a href="https://github.com/ExpDev07/legacy-rp-admin-v3">https://github.com/ExpDev07/legacy-rp-admin-v3</a></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
@endsection
