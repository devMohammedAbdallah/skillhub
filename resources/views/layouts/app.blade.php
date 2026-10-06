<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SkillHub') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --tc-primary: #4caf50;
            --tc-primary-text: #2e7d32;
        }

        body {
            background-color: #f0f4f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .tc-navbar {
            background-color: var(--tc-primary) !important;
        }

        a {
            color: var(--tc-primary-text);
        }

        .btn-primary {
            --bs-btn-bg: #4caf50;
            --bs-btn-border-color: #4caf50;
            --bs-btn-hover-bg: #43a047;
            --bs-btn-hover-border-color: #43a047;
            --bs-btn-active-bg: #388e3c;
            --bs-btn-active-border-color: #388e3c;
            --bs-btn-disabled-bg: #4caf50;
            --bs-btn-disabled-border-color: #4caf50;
        }

        .btn-outline-primary {
            --bs-btn-color: #2e7d32;
            --bs-btn-border-color: #2e7d32;
            --bs-btn-hover-bg: #4caf50;
            --bs-btn-hover-border-color: #4caf50;
            --bs-btn-active-bg: #43a047;
            --bs-btn-active-border-color: #43a047;
        }

        .btn-outline-light:hover {
            background-color: #fff;
            color: #2e7d32;
        }

        .form-control:focus,
        .form-check-input:focus {
            border-color: #4caf50;
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.15);
        }

        .card {
            border: 0;
            border-radius: 0.75rem;
            box-shadow: 0 0.35rem 1rem rgba(76, 175, 80, 0.1);
        }

        .card-header {
            background-color: var(--tc-primary);
            color: #fff;
            font-weight: 600;
        }

        .page-title {
            color: var(--tc-primary-text);
        }
    </style>
</head>
<body>
    <header>
        @include('layouts.navigation')
    </header>

    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
            </div>
        </div>
    @endif

    @isset($header)
        <div class="bg-white border-bottom">
            <div class="container py-3">
                {{ $header }}
            </div>
        </div>
    @endisset

    <main class="container py-4">
        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
