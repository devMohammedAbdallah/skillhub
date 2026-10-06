<x-guest-layout>
    <div class="card card-auth">
        <div class="card-header text-center py-3">
            <h1 class="h4 mb-0 fw-bold">تأكيد كلمة المرور</h1>
        </div>

        <div class="card-body p-4">
            <p class="text-muted text-center mb-4">
                هذه منطقة آمنة في التطبيق. يرجى تأكيد كلمة المرور قبل المتابعة.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

                <div class="mb-4">
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

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">تأكيد</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
