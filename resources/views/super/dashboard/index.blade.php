@extends('layout.main')
<style>
    body {
        background: #f8fafc;
    }

    .page-header {
        margin-bottom: 1.8rem;
    }

    .page-title {
        font-size: 2.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 0;
    }

    .page-wrapper .container {
        padding: 0 0.5rem 2rem;
    }

    .card-stat {
        position: relative;
        overflow: hidden;
        min-height: 180px;
        border: none;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.05);
        border-radius: 18px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        background: #ffffff;
    }

    .card-stat:hover {
        transform: translateY(-6px);
        box-shadow: 0 28px 60px rgba(15, 23, 42, 0.08);
    }

    .card-stat.pt {
        border: 1px solid rgba(34, 197, 94, 0.15);
    }

    .card-stat.armada {
        border: 1px solid rgba(37, 99, 235, 0.15);
    }

    .card-stat.driver {
        border: 1px solid rgba(249, 115, 22, 0.15);
    }

    .stat-icon {
        position: absolute;
        right: 1.5rem;
        top: 1.5rem;
        font-size: 4rem;
        opacity: 0.08;
        color: #1f2937;
    }

    .stat-body {
        position: relative;
        z-index: 1;
    }

    .stat-title {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        text-transform: none;
        letter-spacing: 0.08em;
    }

    .stat-count {
        font-size: 3.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1rem;
        line-height: 1;
    }

    .stat-link {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #2563eb;
        font-size: 0.95rem;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .stat-link:hover {
        color: #1d4ed8;
    }

    .stat-link::after {
        content: '\2192';
        font-size: 1.05rem;
        transition: margin-left 0.2s ease;
        margin-left: 0.25rem;
    }

    .stat-link:hover::after {
        margin-left: 0.45rem;
    }
</style>
@section('content')
    <div class="container">
        <div class="page-header d-mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Dashboard Super Admin
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-wrapper">
        <div class="container">
            <div class="row row-deck row-cards">

                <div class="col-sm-6 col-lg-4">
                    <div class="card card-stat pt">
                        <div class="card-body">
                            <div class="stat-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="stat-title">TOTAL ADMIN</div>
                            <div class="stat-count">{{ $userCount }}</div>
                            <a href="{{ route('super-user.index') }}" class="stat-link">Lihat detail</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
