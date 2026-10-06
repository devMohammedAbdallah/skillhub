@extends('admin.layout')

@section('title', 'المدربون')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title">المدربون</h1>
        <a href="{{ route('admin.instructors.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> إضافة مدرب
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>قائمة المدربين</span>
            <span class="badge text-bg-success">{{ $instructors->count() }}</span>
        </div>
        <div class="card-body p-0">
            @if ($instructors->isEmpty())
                <div class="p-4 text-center text-muted">
                    لا يوجد مدربون بعد. أضف أول مدرب الآن.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col" style="width: 80px;">الصورة</th>
                                <th scope="col">الاسم</th>
                                <th scope="col">النبذة</th>
                                <th scope="col" class="text-center" style="width: 180px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($instructors as $instructor)
                                <tr>
                                    <td>{{ $instructor->id }}</td>
                                    <td>
                                        @if ($instructor->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($instructor->image))
                                            <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($instructor->image) }}"
                                                 alt="{{ $instructor->name }}"
                                                 class="rounded-circle"
                                                 width="48" height="48"
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-secondary bg-opacity-25 d-inline-flex align-items-center justify-content-center"
                                                 style="width: 48px; height: 48px;">
                                                <i class="bi bi-person-fill text-secondary fs-5"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $instructor->name }}</td>
                                    <td class="text-muted small">{{ \Illuminate\Support\Str::limit($instructor->bio, 60) ?: '—' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.instructors.edit', $instructor) }}"
                                           class="btn btn-sm btn-outline-primary me-1"
                                           title="تعديل">
                                            <i class="bi bi-pencil-square"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.instructors.destroy', $instructor) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-name="{{ $instructor->name }}"
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
            if (confirm('هل أنت متأكد من حذف المدرب "' + name + '"؟ لا يمكن التراجع عن هذا الإجراء.')) {
                btn.closest('form').submit();
            }
        });
    });
</script>
@endpush
