@extends('admin.layout.main')
<style>
    .card-stat {
        border-left: 5px solid;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card-stat:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .card-stat.pt {
        border-left-color: #4CAF50;
    }

    .card-stat.armada {
        border-left-color: #2196F3;
    }

    .card-stat.driver {
        border-left-color: #FF9800;
    }

    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.2;
        position: absolute;
        right: 20px;
        top: 20px;
    }

    .stat-count {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stat-title {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 5px;
    }

    .stat-link {
        color: #999;
        font-size: 0.9rem;
        text-decoration: none;
        transition: color 0.2s;
    }

    .stat-link:hover {
        color: #333;
    }
</style>
@section('content')
    <div class="container">
        <div class="page-header d-mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Dashboard
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="container">
            <div class="row row-deck row-cards">
                <!-- Card PT -->
                <div class="col-sm-6 col-lg-4">
                    <div class="card card-stat pt">
                        <div class="card-body">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-title">Total PT</div>
                            <div class="stat-count">{{ $ptCount }}</div>
                            <a href="{{ route('pt.index') }}" class="stat-link">Lihat detail →</a>
                        </div>
                    </div>
                </div>

                <!-- Card Armada -->
                <div class="col-sm-6 col-lg-4">
                    <div class="card card-stat armada">
                        <div class="card-body">
                            <div class="stat-icon">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="stat-title">Total Armada</div>
                            <div class="stat-count">{{ $armadaCount }}</div>
                            <a href="{{ route('armada.index') }}" class="stat-link">Lihat detail →</a>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-4">
                    <div class="card card-stat driver">
                        <div class="card-body">
                            <div class="stat-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="stat-title">Total Driver</div>
                            <div class="stat-count">{{ $driverCount }}</div>
                            <a href="{{ route('driver.index') }}" class="stat-link">Lihat detail →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
