@extends('admin.layout')

@section('title', 'تعديل مدرب')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title">تعديل المدرب</h1>
        <a href="{{ route('admin.instructors.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">بيانات المدرب</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.instructors.update', $instructor) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $instructor->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="اسم المدرب"
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label fw-semibold">النبذة</label>
                            <textarea
                                id="bio"
                                name="bio"
                                rows="4"
                                class="form-control @error('bio') is-invalid @enderror"
                                placeholder="نبذة مختصرة عن المدرب (اختياري)"
                            >{{ old('bio', $instructor->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">الصورة</label>

                            <div class="mb-2">
                                @if ($instructor->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($instructor->image))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($instructor->image) }}"
                                         alt="{{ $instructor->name }}"
                                         class="rounded-circle"
                                         width="72" height="72"
                                         style="object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary bg-opacity-25 d-inline-flex align-items-center justify-content-center"
                                         style="width: 72px; height: 72px;">
                                        <i class="bi bi-person-fill text-secondary fs-4"></i>
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
                            <a href="{{ route('admin.instructors.index') }}" class="btn btn-outline-secondary">
                                إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
