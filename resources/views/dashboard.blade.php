<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body p-4 p-md-5 text-center">
                    <h1 class="h3 page-title fw-bold mb-3">مرحباً {{ auth()->user()->name }}</h1>
                    <p class="text-muted mb-4">أنت الآن داخل نظام SkillHub.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">الذهاب إلى الرئيسية</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
