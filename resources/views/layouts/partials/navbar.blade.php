<div class="t-header-brand-wrapper">
    <a href="index.html">
        <img class="logo" src="{{ asset('assets/images/logo.svg') }}" alt="">
        <img class="logo-mini" src="{{ asset('assets/images/logo_mini.svg') }}" alt="">
    </a>
</div>
<div class="t-header-content-wrapper">
    <div class="t-header-content">
        <button class="t-header-toggler t-header-mobile-toggler d-block d-lg-none">
            <i class="mdi mdi-menu"></i>
        </button>
        <ul class="nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="settingDropdown" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-settings mdi-1x"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="settingDropdown">
                    <a class="dropdown-item text-dark" href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="mdi mdi-logout mdi-1x"></i> Cerrar Sesión
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>