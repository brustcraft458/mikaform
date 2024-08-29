<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; min-height: 100vh">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi me-2" width="40" height="32">
            <use xlink:href="#bootstrap" />
        </svg>
        <span class="fs-4">Mika Form</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ url('/form/template') }}" class="nav-link {{ ($selected == 'form_template') ? 'active' : 'text-white'}}" aria-current="page">
                <i class="icon"></i>
                Formulir
            </a>
            <a href="{{ url('/form/user') }}"class="nav-link {{ ($selected == 'kelola-user') ? 'active' : 'text-white'}}" aria-current="page">
                <i class="icon"></i>
                Kelola User
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="./img/user-icon.png" alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>Username</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="./backend/logout.php">Log Out</a></li>
        </ul>
    </div>
</div>