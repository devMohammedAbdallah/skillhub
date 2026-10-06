<x-guest-layout>
    <div class="card card-auth">
        <div class="card-header text-center py-3">
            <h1 class="h4 mb-0 fw-bold">استعادة كلمة المرور</h1>
        </div>

        <div class="card-body p-4">
            <p class="text-muted text-center mb-4">
                نسيت كلمة المرور؟ لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين.
            </p>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
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

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">إرسال رابط الاستعادة</button>
                </div>
            </form>
        </div>

        <div class="card-footer bg-white text-center py-3">
            <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">
                العودة لتسجيل الدخول
            </a>
        </div>
    </div>
</x-guest-layout>
