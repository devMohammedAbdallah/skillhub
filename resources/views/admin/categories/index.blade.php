@extends('admin.layout')

@section('title', 'الفئات')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title">الفئات</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> إضافة فئة
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>قائمة الفئات</span>
            <span class="badge text-bg-success">{{ $categories->count() }}</span>
        </div>
        <div class="card-body p-0">
            @if ($categories->isEmpty())
                <div class="p-4 text-center text-muted">
                    لا توجد فئات بعد. أضف أول فئة الآن.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">اسم الفئة</th>
                                <th scope="col">تاريخ الإنشاء</th>
                                <th scope="col" class="text-center" style="width: 180px;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td class="fw-semibold">{{ $category->name }}</td>
                                    <td>{{ $category->created_at?->format('Y-m-d') ?? '—' }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="btn btn-sm btn-outline-primary me-1"
                                           title="تعديل">
                                            <i class="bi bi-pencil-square"></i> تعديل
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}"
                                              method="POST"
                                              class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-name="{{ $category->name }}"
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
            if (confirm('هل أنت متأكد من حذف الفئة "' + name + '"؟ لا يمكن التراجع عن هذا الإجراء.')) {
                btn.closest('form').submit();
            }
        });
    });
</script>
@endpush
