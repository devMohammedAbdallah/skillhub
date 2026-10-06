<x-guest-layout>
    <div class="card card-auth">
        <div class="card-header text-center py-3">
            <h1 class="h4 mb-0 fw-bold">تسجيل الدخول</h1>
        </div>

        <div class="card-body p-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">البريد الإلكتروني</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control @error('email') is-invalid @enderror"
                        required
                        autofocus
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
                        autocomplete="current-password"
                        placeholder="أدخل كلمة المرور"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label" for="remember_me">تذكرني</label>
                </div>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <button type="submit" class="btn btn-primary px-4">تسجيل الدخول</button>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card-footer bg-white text-center py-3">
            <span class="text-muted">ليس لديك حساب؟</span>
            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">
                إنشاء حساب جديد
            </a>
        </div>
    </div>
</x-guest-layout>
