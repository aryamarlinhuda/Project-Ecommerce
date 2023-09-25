@extends('component')
@section('sidebar')
<style>
    .sidebar {
        background-color: #fc9332;
        text-decoration: white;
        width: 280px;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        padding: 20px;
        overflow-y: auto;
    }
</style>
<div class="d-flex flex-column flex-shrink-0 p-3 sidebar">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <img src="{{ asset('ecommerce-app.png') }}" alt="LOGO" width="50" height="50">
        <span class="fw-bold"><h5>E-Commerce App</h5></span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ url('product/list') }}" class="nav-link link-body-emphasis @yield('product')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-dash-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10.5 3.5a2.5 2.5 0 0 0-5 0V4h5v-.5zm1 0V4H15v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4h3.5v-.5a3.5 3.5 0 1 1 7 0zM6 9.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1H6z"/>
                </svg>
                Product
            </a>
        </li>
        <li>
            <a href="{{ url('category/list') }}" class="nav-link link-body-emphasis @yield('category')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filter-square-fill" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm.5 5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1 0-1zM4 8.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm2 3a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5z"/>
                </svg>
                Categories
            </a>
        </li>
        <li>
            <a href="{{ url('user/list') }}" class="nav-link link-body-emphasis @yield('user')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7Zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm-5.784 6A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216ZM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                </svg>
                Users
            </a>
        </li>
        <li>
            <a href="{{ url('admin/list') }}" class="nav-link link-body-emphasis @yield('admin')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-workspace" viewBox="0 0 16 16">
                    <path d="M4 16s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H4Zm4-5.95a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/>
                    <path d="M2 1a2 2 0 0 0-2 2v9.5A1.5 1.5 0 0 0 1.5 14h.653a5.373 5.373 0 0 1 1.066-2H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v9h-2.219c.554.654.89 1.373 1.066 2h.653a1.5 1.5 0 0 0 1.5-1.5V3a2 2 0 0 0-2-2H2Z"/>
                </svg>
                Admins
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none dropdown-toggle ms-3" data-bs-toggle="dropdown" aria-expanded="false">
        @if(session()->has('photo'))
            <img src="{{ asset('storage/'.session('photo')) }}" alt="mdo" width="45" height="45" class="rounded-circle">
        @else
            <img src="{{ asset('default.jpg') }}" alt="mdo" width="45" height="45" class="rounded-circle">
        @endif
        <strong class="ms-3">{{ session('name') }}</strong>
        </a>
        <ul class="dropdown-menu text-small shadow">
            <li><a class="dropdown-item" href="{{ url('profile') }}">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ url('logout') }}">Log out</a></li>
        </ul>
    </div>
</div>
<div style="margin-left: 22%">
    {{-- Product --}}
    @yield('list-product')
    @yield('detail-product')
    @yield('create-product')
    @yield('restock-product')
    @yield('edit-product')

    {{-- Category --}}
    @yield('list-category')
    @yield('create-category')
    @yield('edit-category')

    {{-- User --}}
    @yield('list-user')
    @yield('create-user')
    @yield('edit-user')

    {{-- Admin --}}
    @yield('list-admin')
    @yield('create-admin')
    @yield('edit-admin')

    {{-- Profile --}}
    @yield('profile')
    @yield('edit-profile')
    @yield('change-password')
</div>
@endsection