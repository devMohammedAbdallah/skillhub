<x-guest-layout>
    <div class="card card-auth">
        <div class="card-header text-center py-3">
            <h1 class="h4 mb-0 fw-bold">تأكيد البريد الإلكتروني</h1>
        </div>

        <div class="card-body p-4">
            <p class="text-muted text-center mb-4">
                شكراً لتسجيلك! قبل البدء، يرجى تأكيد بريدك الإلكتروني بالنقر على الرابط الذي أرسلناه إليك.
                إذا لم تصلك الرسالة، يمكننا إرسالها مرة أخرى.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success" role="alert">
                    تم إرسال رابط تحقق جديد إلى البريد الإلكتروني الذي قدمته أثناء التسجيل.
                </div>
            @endif

            <div class="d-flex flex-wrap justify-content-between gap-2">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        إعادة إرسال بريد التحقق
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">تسجيل الخروج</button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
