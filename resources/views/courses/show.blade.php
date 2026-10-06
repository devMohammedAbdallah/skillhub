<x-app-layout>
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('courses.index') }}">الدورات</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $course->title }}</li>
        </ol>
    </nav>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card overflow-hidden">
                @if ($course->image)
                    <img
                        src="{{ asset('storage/' . $course->image) }}"
                        class="card-img-top"
                        alt="{{ $course->title }}"
                        style="max-height: 360px; object-fit: cover;"
                    >
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                         style="min-height: 260px; background: linear-gradient(135deg, #4caf50, #2e7d32);">
                        <span class="fw-bold fs-4">SkillHub</span>
                    </div>
                @endif

                <div class="card-body p-4">
                    <div class="mb-2">
                        @if ($course->category)
                            <span class="badge" style="background-color: #4caf50;">{{ $course->category->name }}</span>
                        @endif
                    </div>

                    <h1 class="h3 fw-bold mb-3" style="color: #2e7d32;">{{ $course->title }}</h1>

                    <h2 class="h6 fw-bold mb-2" style="color: #2e7d32;">وصف الدورة</h2>
                    <p class="text-muted mb-0" style="white-space: pre-wrap;">{{ $course->description ?: 'لا يوجد وصف متاح.' }}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h2 class="h6 fw-bold mb-3" style="color: #2e7d32;">معلومات الدورة</h2>

                    <ul class="list-unstyled mb-0">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">الفئة</span>
                            <span class="fw-semibold">{{ $course->category?->name ?? '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">المدرب</span>
                            <span class="fw-semibold">{{ $course->instructor?->name ?? '—' }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">السعر</span>
                            <span class="fw-bold" style="color: #2e7d32;">
                                @if ((float) $course->price === 0.0)
                                    مجاني
                                @else
                                    ${{ number_format((float) $course->price, 2) }}
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            @if ($course->instructor && $course->instructor->bio)
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h6 fw-bold mb-3" style="color: #2e7d32;">عن المدرب</h2>
                        <p class="fw-semibold mb-1">{{ $course->instructor->name }}</p>
                        <p class="text-muted small mb-0">{{ $course->instructor->bio }}</p>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-body p-4 text-center">
                    @guest
                        <form method="POST" action="{{ route('courses.enroll', $course) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                التسجيل بالدورة
                            </button>
                        </form>
                        <p class="small text-muted mt-2 mb-0">سيتم تحويلك لتسجيل الدخول أولاً.</p>
                    @else
                        @if (auth()->user()->isAdmin())
                            <p class="text-muted mb-0 fw-semibold">
                                <i class="bi bi-info-circle me-1"></i>
                                الإدارة لا تقوم بالتسجيل في الدورات.
                            </p>
                        @elseif ($enrolled)
                            <div class="alert alert-success mb-0" role="alert">
                                أنت مسجل في هذه الدورة.
                            </div>
                        @else
                            <form method="POST" action="{{ route('courses.enroll', $course) }}">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    التسجيل بالدورة
                                </button>
                            </form>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
