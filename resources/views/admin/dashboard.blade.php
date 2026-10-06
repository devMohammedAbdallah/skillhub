@extends('admin.layout')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title">لوحة التحكم</h1>
        <span class="text-muted small">نظرة عامة على منصة SkillHub</span>
    </div>

    <div class="row g-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $students }}</div>
                        <div class="stat-label">إجمالي الطلاب</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $courses }}</div>
                        <div class="stat-label">إجمالي الدورات</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon">
                        <i class="bi bi-folder-fill"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $categories }}</div>
                        <div class="stat-label">إجمالي الفئات</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div>
                        <div class="stat-value">{{ $enrollments }}</div>
                        <div class="stat-label">إجمالي التسجيلات</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">إجراءات سريعة</div>
        <div class="card-body d-flex flex-wrap gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">
                <i class="bi bi-folder2 me-1"></i> إدارة الفئات
            </a>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg me-1"></i> إضافة فئة جديدة
            </a>
        </div>
    </div>
@endsection
