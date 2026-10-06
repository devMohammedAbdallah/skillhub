<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if (session('status') == 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    تم تحديث معلومات الملف الشخصي بنجاح.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">معلومات الحساب</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">الاسم</label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">البريد الإلكتروني</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">الدور</label>
                            <input type="text" class="form-control" value="{{ $user->role === 'admin' ? 'مسؤول' : 'طالب' }}" disabled>
                        </div>

                        <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">تغيير كلمة المرور</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-semibold">كلمة المرور الحالية</label>
                            <input
                                id="current_password"
                                type="password"
                                name="current_password"
                                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                            >
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">كلمة المرور الجديدة</label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                            >
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">تأكيد كلمة المرور الجديدة</label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                            >
                        </div>

                        <button type="submit" class="btn btn-primary">تحديث كلمة المرور</button>
                    </form>
                </div>
            </div>

            <div class="card border-danger">
                <div class="card-header bg-danger text-white">حذف الحساب</div>
                <div class="card-body">
                    <p class="text-muted">عند حذف حسابك، ستُزال جميع بياناتك ومحتوياتك نهائيًا.</p>

                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="mb-3">
                            <label for="delete_password" class="form-label fw-semibold">كلمة المرور للتأكيد</label>
                            <input
                                id="delete_password"
                                type="password"
                                name="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                required
                            >
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger">حذف الحساب نهائياً</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
