<div class="user-profile">
    {{-- <div class="display-avatar animated-avatar">
        <img class="profile-img img-lg rounded-circle" src="../assets/images/profile/male/image_1.png" alt="profile image">
    </div>
    <div class="info-wrapper">
        <p class="user-name">Allen Clerk</p>
        <h6 class="display-income">$3,400,00</h6>
    </div> --}}
</div>
<ul class="navigation-menu">
    <li class="nav-category-divider">MAIN</li>
    <li class="@yield('item-principal')">
        <a href="{{ route('home') }}">
            <span class="link-title">Dashboard</span>
            <i class="mdi mdi-home-variant link-icon"></i>
        </a>
    </li>
    <li class="nav-category-divider">GESTIÓN</li>
    <li class="@yield('item-usuarios')">
        <a href="{{ route('usuarios') }}">
            <span class="link-title">Usuarios</span>
            <i class="mdi mdi-account-multiple link-icon"></i>
        </a>
    </li>
    <li class="@yield('item-empresas')">
        <a href="{{ route('empresas') }}">
            <span class="link-title">Empresas</span>
            <i class="mdi mdi-buffer link-icon"></i>
        </a>
    </li>
</ul>