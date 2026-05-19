<!-- App Topstrip -->
<div class="app-topstrip bg-dark py-6 px-3 w-100 d-lg-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center justify-content-center gap-5 mb-2 mb-lg-0">
        <a class="d-flex justify-content-center pb-4" href="#">
            {{-- <h5 class="text-white mb-0">
                {{ auth()->user()->role === 'superadmin' ? 'Dashboard Super Admin' : 'Dashboard Admin' }}
            </h5> --}}
        </a>
    </div>
</div>

<!-- Sidebar Start -->
<aside class="left-sidebar">
    <div>

        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="" class="text-nowrap logo-img">
                <h4 style="font-weight:700;">
                    {{ auth()->user()->role === 'superadmin' ? 'Super Admin Panel' : 'Admin Panel' }}
                </h4>
            </a>

            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-6"></i>
            </div>
        </div>

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

                <li class="sidebar-item">
                    <a class="sidebar-link"
                        href="{{ auth()->user()->role === 'superadmin' ? route('super-dashboard') : route('admin-dashboard') }}"
                        aria-expanded="false">

                        <i class="ti ti-home fs-4"></i>

                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                @if (auth()->user()->role === 'admin')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('pengiriman.index') }}" aria-expanded="false">

                            <i class="ti ti-truck fs-4"></i>

                            <span class="hide-menu">Data Pengiriman</span>
                        </a>
                    </li>

                    <li class="sidebar-item">

                        <a class="sidebar-link justify-content-between has-arrow" href="javascript:void(0)"
                            aria-expanded="false">

                            <div class="d-flex align-items-center gap-3">

                                <span class="d-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24">

                                        <path fill="currentColor"
                                            d="M9 19v-2h12v2zm0-6v-2h12v2zm0-6V5h12v2zM5 20q-.825 0-1.412-.587T3 18t.588-1.412T5 16t1.413.588T7 18t-.587 1.413T5 20m0-6q-.825 0-1.412-.587T3 12t.588-1.412T5 10t1.413.588T7 12t-.587 1.413T5 14m0-6q-.825 0-1.412-.587T3 6t.588-1.412T5 4t1.413.588T7 6t-.587 1.413T5 8" />
                                    </svg>
                                </span>

                                <span class="hide-menu">Menu Admin</span>
                            </div>

                        </a>

                        <ul aria-expanded="false" class="collapse first-level">

                            <li class="sidebar-item">
                                <a class="sidebar-link justify-content-between" href="{{ route('pt.index') }}">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-building fs-5"></i>
                                        </div>

                                        <span class="hide-menu">Data Perusahaan</span>
                                    </div>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link justify-content-between" href="{{ route('armada.index') }}">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-car fs-5"></i>
                                        </div>

                                        <span class="hide-menu">Data Armada</span>
                                    </div>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link justify-content-between" href="{{ route('driver.index') }}">

                                    <div class="d-flex align-items-center gap-3">

                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-steering-wheel fs-5"></i>
                                        </div>

                                        <span class="hide-menu">Data Driver</span>
                                    </div>
                                </a>
                            </li>

                        </ul>
                    </li>
                @endif

                @if (auth()->user()->role === 'superadmin')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('super-user.index') }}" aria-expanded="false">

                            <i class="ti ti-users fs-4"></i>

                            <span class="hide-menu">Management User</span>
                        </a>
                    </li>
                @endif

                <li>
                    <span class="sidebar-divider lg"></span>
                </li>

            </ul>
        </nav>
        <!-- End Sidebar navigation -->

    </div>
</aside>
