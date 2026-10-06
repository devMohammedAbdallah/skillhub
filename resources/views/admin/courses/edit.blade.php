@extends('admin.layout')

@section('title', 'تعديل دورة')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title">تعديل الدورة</h1>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">بيانات الدورة</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">العنوان <span class="text-danger">*</span></label>
                            <input
                                id="title"
                                type="text"
                                name="title"
                                value="{{ old('title', $course->title) }}"
                                class="form-control @error('title') is-invalid @enderror"
                                placeholder="مثال: مقدمة في البرمجة"
                                required
                                autofocus
                            >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">الوصف <span class="text-danger">*</span></label>
                            <textarea
                                id="description"
                                name="description"
                                rows="5"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="وصف مختصر ومفيد للدورة"
                                required
                            >{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label fw-semibold">الفئة <span class="text-danger">*</span></label>
                                <select
                                    id="category_id"
                                    name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>اختر الفئة…</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string) old('category_id', $course->category_id) === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="instructor_id" class="form-label fw-semibold">المدرب <span class="text-danger">*</span></label>
                                <select
                                    id="instructor_id"
                                    name="instructor_id"
                                    class="form-select @error('instructor_id') is-invalid @enderror"
                                    required
                                >
                                    <option value="" disabled {{ old('instructor_id') ? '' : 'selected' }}>اختر المدرب…</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}" {{ (string) old('instructor_id', $course->instructor_id) === (string) $instructor->id ? 'selected' : '' }}>
                                            {{ $instructor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('instructor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label fw-semibold">السعر <span class="text-danger">*</span></label>
                            <input
                                id="price"
                                type="number"
                                name="price"
                                value="{{ old('price', $course->price) }}"
                                min="0"
                                step="0.01"
                                class="form-control @error('price') is-invalid @enderror"
                                placeholder="0.00"
                                required
                            >
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">الصورة</label>

                            <div class="mb-2">
                                @if ($course->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($course->image))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($course->image) }}"
                                         alt="{{ $course->title }}"
                                         class="rounded"
                                         width="96" height="64"
                                         style="object-fit: cover;">
                                @else
                                    <div class="rounded bg-secondary bg-opacity-25 d-inline-flex align-items-center justify-content-center"
                                         style="width: 96px; height: 64px;">
                                        <i class="bi bi-image text-secondary fs-4"></i>
                                    </div>
                                @endif
                            </div>

                            <input
                                id="image"
                                type="file"
                                name="image"
                                accept="image/jpeg,image/png"
                                class="form-control @error('image') is-invalid @enderror"
                            >
                            <div class="form-text">JPG أو PNG، بحد أقصى 2MB. اتركه فارغًا للاحتفاظ بالصورة الحالية.</div>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> تحديث
                            </button>
                            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
