@extends('admin.layout')

@section('title', 'الدورات')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title">الدورات</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> إضافة دورة
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>قائمة الدورات</span>
            <span class="badge text-bg-success">{{ $courses->count() }}</span>
        </div>
        <div class="card-body p-0">
            @if ($courses->isEmpty())
                <div class="p-4 text-center text-muted">
                    لا توجد دورات بعد. أضف أول دورة الآن.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col" style="width: 80px;">الصورة</th>
                                <th scope="col">العنوان</th>
                                <th scope="col">الفئة</th>
                                <th scope="col">المدرب</th>
                                <th scope="col">السعر</th>
                                <th scope="col" class="text-center" style="width: 180px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course->id }}</td>
                                    <td>
                                        @if ($course->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->image))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($course->image) }}"
                                                 alt="{{ $course->title }}"
                                                 class="rounded"
                                                 width="56" height="40"
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="rounded bg-secondary bg-opacity-25 d-inline-flex align-items-center justify-content-center"
                                                 style="width: 56px; height: 40px;">
                                                <i class="bi bi-image text-secondary"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $course->title }}</td>
                                    <td>
                                        <span class="badge text-bg-light border">{{ $course->category?->name ?? '—' }}</span>
                                    </td>
                                    <td>{{ $course->instructor?->name ?? '—' }}</td>
                                    <td class="fw-semibold text-nowrap">{{ number_format((float) $course->price, 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.courses.edit', $course) }}"
                                           class="btn btn-sm btn-outline-primary me-1"
                                           title="تعديل">
                                            <i class="bi bi-pencil-square"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.courses.destroy', $course) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-name="{{ $course->title }}"
                                                    title="حذف">
                                                <i class="bi bi-trash"></i> حذف
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.delete-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var name = btn.getAttribute('data-name') || '';
            if (confirm('هل أنت متأكد من حذف الدورة "' + name + '"؟ لا يمكن التراجع عن هذا الإجراء.')) {
                btn.closest('form').submit();
            }
        });
    });
</script>
@endpush
