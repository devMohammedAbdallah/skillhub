@extends('admin.layout')

@section('title', 'إضافة مدرب')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h1 class="h3 mb-0 page-title">إضافة مدرب جديد</h1>
        <a href="{{ route('admin.instructors.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">بيانات المدرب</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.instructors.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
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
                            >{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">الصورة</label>
                            <input
                                id="image"
                                type="file"
                                name="image"
                                accept="image/jpeg,image/png"
                                class="form-control @error('image') is-invalid @enderror"
                            >
                            <div class="form-text">JPG أو PNG، بحد أقصى 2MB (اختياري).</div>
                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i> حفظ
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
