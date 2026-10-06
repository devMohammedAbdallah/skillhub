<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card mb-4">
                <div class="card-body p-4 p-md-5 text-center">
                    <h1 class="h3 page-title fw-bold mb-3">
                        مرحباً {{ auth()->user()->name }} 👋
                    </h1>
                    <p class="text-muted mb-4">
                        أهلاً بك في لوحة الطالب في SkillHub. من هنا يمكنك تصفح الدورات ومتابعة تسجيلاتك.
                    </p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-primary">استكشاف الدورات</a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">الملف الشخصي</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>دوراتي</span>
                    <span class="badge text-bg-light">{{ $enrollments->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if ($enrollments->isEmpty())
                        <div class="p-5 text-center">
                            <p class="text-muted mb-4">
                                لم تسجل في أي دورة بعد. ابدأ بتصفح الدورات المتاحة وسجّل في ما يناسبك!
                            </p>
                            <a href="{{ route('courses.index') }}" class="btn btn-primary">
                                تصفح الدورات
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">الدورة</th>
                                        <th scope="col">المدرب</th>
                                        <th scope="col">تاريخ التسجيل</th>
                                        <th scope="col" style="width: 120px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrollments as $enrollment)
                                        @continue(! $enrollment->course)
                                        <tr>
                                            <td class="fw-semibold">
                                                <a href="{{ route('courses.show', $enrollment->course) }}">
                                                    {{ $enrollment->course->title }}
                                                </a>
                                                @if ($enrollment->course->category)
                                                    <span class="badge ms-2" style="background-color: #4caf50;">
                                                        {{ $enrollment->course->category->name }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $enrollment->course->instructor?->name ?? '—' }}</td>
                                            <td class="text-nowrap">{{ $enrollment->enrolled_at?->format('Y-m-d H:i') ?? '—' }}</td>
                                            <td>
                                                <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-outline-primary btn-sm">
                                                    العرض
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
