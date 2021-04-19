<div class="user-profile">
    <div class="info-wrapper p-2">
        <p class="user-name text-wrap text-break text-uppercase">{{ getUsuario()->getNombreCompleto() }}</p>
        <h6 class="display-income">{{ getUsuario()->getRoleNames()->first() }}</h6>
    </div>
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
    @hasrole('Administrador')
    <li class="@yield('item-usuarios')">
        <a href="{{ route('usuarios') }}">
            <span class="link-title">Usuarios</span>
            <i class="mdi mdi-account-multiple link-icon"></i>
        </a>
    </li>
    @endhasrole
    <li class="@yield('item-empresas')">
        <a href="{{ route('empresas') }}">
            <span class="link-title">Empresas</span>
            <i class="mdi mdi-buffer link-icon"></i>
        </a>
    </li>
    @hasrole('Administrador')
    <li class="@yield('item-asignacion')">
        <a href="{{ route('asignaciones') }}">
            <span class="link-title">Asignaciones</span>
            <i class="mdi mdi-human-handsup link-icon"></i>
        </a>
    </li>
    @endhasrole
</ul>