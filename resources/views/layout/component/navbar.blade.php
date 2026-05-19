<nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item d-block d-xl-none">
            <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
            </a>
        </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

            <li class="nav-item dropdown">
                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                    aria-expanded="false">

                    <img src="{{ asset('adminview/assets/images/profile/user-1.jpg') }}" alt="Profile" width="42"
                        height="42" class="rounded-circle border-2 border-light shadow-sm">
                </a>

                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-3" aria-labelledby="drop2"
                    style="min-width: 250px;">

                    <div class="text-center mb-3">
                        <img src="{{ asset('adminview/assets/images/profile/user-1.jpg') }}" alt="Profile"
                            width="65" height="65" class="rounded-circle mb-2 shadow-sm">

                        <h6 class="mb-0 fw-bold">
                            {{ Auth::user()->name }}
                        </h6>

                        <small class="text-muted text-capitalize">
                            {{ Auth::user()->role }}
                        </small>
                    </div>

                    <hr class="my-2">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="btn btn-danger w-100 rounded-3 d-flex align-items-center justify-content-center gap-2 py-2">

                            <i class="ti ti-logout"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
