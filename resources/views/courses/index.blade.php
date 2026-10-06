<x-app-layout>
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title fw-bold">الدورات</h1>

        <form method="GET" action="{{ route('courses.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
            <label for="category-filter" class="form-label mb-0 fw-semibold text-muted">الفئة:</label>
            <select name="category" id="category-filter" class="form-select" style="min-width: 180px;" onchange="this.form.submit()">
                <option value="">كل الفئات</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <noscript><button type="submit" class="btn btn-primary btn-sm">تصفية</button></noscript>
        </form>
    </div>

    @if ($courses->isEmpty())
        <div class="card">
            <div class="card-body p-5 text-center">
                <p class="text-muted mb-4">
                    @if ($categoryId)
                        لا توجد دورات في هذه الفئة حالياً.
                    @else
                        لا توجد دورات متاحة حالياً. عد قريباً!
                    @endif
                </p>
                @if ($categoryId)
                    <a href="{{ route('courses.index') }}" class="btn btn-outline-primary">عرض كل الدورات</a>
                @endif
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach ($courses as $course)
                <div class="col-sm-6 col-lg-4">
                    <div class="card h-100 course-card">
                        @if ($course->image)
                            <img
                                src="{{ asset('storage/' . $course->image) }}"
                                class="card-img-top"
                                alt="{{ $course->title }}"
                                style="height: 180px; object-fit: cover;"
                                loading="lazy"
                            >
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center text-white"
                                 style="height: 180px; background: linear-gradient(135deg, #4caf50, #2e7d32);">
                                <span class="fw-bold fs-5">SkillHub</span>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                @if ($course->category)
                                    <span class="badge" style="background-color: #4caf50;">{{ $course->category->name }}</span>
                                @endif
                            </div>

                            <h2 class="h5 fw-bold mb-2" style="color: #2e7d32;">{{ $course->title }}</h2>

                            <p class="text-muted small mb-2">
                                المدرب: {{ $course->instructor?->name ?? '—' }}
                            </p>

                            <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                                <span class="fw-bold" style="color: #2e7d32;">
                                    @if ((float) $course->price === 0.0)
                                        مجاني
                                    @else
                                        ${{ number_format((float) $course->price, 2) }}
                                    @endif
                                </span>
                                <a href="{{ route('courses.show', $course) }}" class="btn btn-primary btn-sm">
                                    التفاصيل
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
