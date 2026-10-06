<x-guest-layout>
    <div class="card card-auth">
        <div class="card-header text-center py-3">
            <h1 class="h4 mb-0 fw-bold">إنشاء حساب جديد</h1>
        </div>

        <div class="card-body p-4">
            <p class="text-muted text-center mb-4">انضم إلينا كطالب وابدأ رحلتك التعليمية</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">الاسم</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control @error('name') is-invalid @enderror"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="أدخل اسمك الكامل"
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
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required
                        autocomplete="username"
                        placeholder="أدخل بريدك الإلكتروني"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">كلمة المرور</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required
                        autocomplete="new-password"
                        placeholder="أنشئ كلمة مرور قوية"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">تأكيد كلمة المرور</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        required
                        autocomplete="new-password"
                        placeholder="أعد إدخال كلمة المرور"
                    >
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">إنشاء الحساب</button>
                </div>
            </form>
        </div>

        <div class="card-footer bg-white text-center py-3">
            <span class="text-muted">لديك حساب بالفعل؟</span>
            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                تسجيل الدخول
            </a>
        </div>
    </div>
</x-guest-layout>
