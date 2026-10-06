<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SkillHub') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --tc-primary: #4caf50;
            --tc-primary-text: #2e7d32;
        }

        body {
            background-color: #f0f4f8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .btn-primary {
            --bs-btn-bg: #4caf50;
            --bs-btn-border-color: #4caf50;
            --bs-btn-hover-bg: #43a047;
            --bs-btn-hover-border-color: #43a047;
        }

        .btn-outline-primary {
            --bs-btn-color: #2e7d32;
            --bs-btn-border-color: #2e7d32;
            --bs-btn-hover-bg: #4caf50;
            --bs-btn-hover-border-color: #4caf50;
        }

        .btn-outline-light:hover {
            background-color: #fff;
            color: #2e7d32;
        }

        .hero-section {
            position: relative;
            background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=1600&h=900&fit=crop&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(27, 71, 40, 0.88) 0%,
                rgba(46, 125, 50, 0.78) 55%,
                rgba(76, 175, 80, 0.72) 100%
            );
        }

        .hero-section .container {
            position: relative;
            z-index: 1;
        }

        .hero {
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.25);
        }

        .hero-tagline {
            color: rgba(255, 255, 255, 0.92);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .hero-section .btn-primary {
            --bs-btn-bg: #4caf50;
            --bs-btn-border-color: #4caf50;
            --bs-btn-hover-bg: #43a047;
            --bs-btn-hover-border-color: #43a047;
            --bs-btn-active-bg: #388e3c;
            --bs-btn-active-border-color: #388e3c;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .hero-section .btn-outline-primary {
            --bs-btn-color: #ffffff;
            --bs-btn-border-color: #ffffff;
            --bs-btn-hover-bg: #ffffff;
            --bs-btn-hover-border-color: #ffffff;
            --bs-btn-hover-color: #2e7d32;
            --bs-btn-active-bg: #ffffff;
            --bs-btn-active-border-color: #ffffff;
            --bs-btn-active-color: #2e7d32;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .about-card {
            border-radius: 0.75rem;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .about-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.75rem 1.5rem rgba(76, 175, 80, 0.18) !important;
        }

        .about-card img {
            border-bottom: 3px solid #4caf50;
        }
    </style>
</head>
<body>
    <header>
        @include('layouts.navigation')
    </header>

    <main class="flex-grow-1">
        <!-- Hero -->
        <section class="hero-section">
            <div class="container py-5">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="display-5 fw-bold hero mb-3">SkillHub</h1>
                        <p class="lead hero-tagline mb-4">
                            نظام إدارة SkillHub — سجّل الدورات، تابع تقدمك، وابدأ رحلتك التعليمية اليوم.
                        </p>

                        @guest
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">تسجيل الدخول</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">إنشاء حساب</a>
                            </div>
                        @else
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg px-4">لوحة التحكم</a>
                                @endif
                                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg px-4">الذهاب للرئيسية</a>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </section>

        <!-- من نحن -->
        <section class="container py-5" id="about">
            <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-4">
                    <h2 class="h3 fw-bold" style="color: #2e7d32;">من نحن</h2>
                    <div class="mx-auto mt-2 mb-4 rounded-pill" style="width: 64px; height: 4px; background-color: #4caf50;"></div>
                </div>

                <div class="row justify-content-center text-center mb-4">
                    <div class="col-lg-9">
                        <p class="text-muted mb-3">
                           SkillHub منصة إلكترونية متكاملة لإدارة التدريب والدورات، تربط الطلاب بالمدربين المحترفين وتسهّل عليهم اكتشاف المحتوى التعليمي المناسب لهم.
                        </p>
                        <p class="text-muted mb-3">
                            نقدم مجموعة متنوعة من الدورات ضمن فئات مختلفة — من التقنية واللغات إلى المهارات الإدارية والاحترافية — لتتمكن من اختيار ما يناسب اهتمامك ومستقبلك.
                        </p>
                        <p class="text-muted mb-0">
                            مع SkillHub يمكنك التسجيل في الدورات بسهولة، ومتابعة تقدمك في أي وقت ومن أي جهاز، لتواصل التعلم بخطوات ثابتة وواضحة.
                        </p>
                    </div>
                </div>

                <div class="row g-4 justify-content-center">
                    <div class="col-sm-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm about-card">
                            <img
                                src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop&q=80"
                                class="card-img-top"
                                alt="دورات متنوعة"
                                style="height: 180px; object-fit: cover;"
                                loading="lazy"
                            >
                            <div class="card-body text-center py-3">
                                <p class="mb-0 fw-semibold" style="color: #2e7d32;">دورات متنوعة</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm about-card">
                            <img
                                src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&h=400&fit=crop&q=80"
                                class="card-img-top"
                                alt="مدربون محترفون"
                                style="height: 180px; object-fit: cover;"
                                loading="lazy"
                            >
                            <div class="card-body text-center py-3">
                                <p class="mb-0 fw-semibold" style="color: #2e7d32;">مدربون محترفون</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm about-card">
                            <img
                                src="https://images.unsplash.com/photo-1588072432836-e10032774350?w=600&h=400&fit=crop&q=80"
                                class="card-img-top"
                                alt="تعلم في أي وقت"
                                style="height: 180px; object-fit: cover;"
                                loading="lazy"
                            >
                            <div class="card-body text-center py-3">
                                <p class="mb-0 fw-semibold" style="color: #2e7d32;">تعلم في أي وقت</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <!-- تواصل معنا -->
        <section class="container pb-5" id="contact">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-4">
                        <h2 class="h3 fw-bold" style="color: #2e7d32;">تواصل معنا</h2>
                        <div class="mx-auto mt-2 mb-3 rounded-pill" style="width: 64px; height: 4px; background-color: #4caf50;"></div>
                        <p class="text-muted mb-0">
                            نسعد بتواصلك معنا! شاركنا ملاحظاتك، اقتراحاتك، أو أي استفسار — فريقنا جاهز لمساعدتك.
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm" style="border-radius: 0.75rem;">
                        <div class="card-body p-4 p-md-5">
                            <form method="POST" action="{{ route('contact.store') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="contact-name" class="form-label fw-semibold">الاسم <span class="text-danger">*</span></label>
                                        <input
                                            id="contact-name"
                                            type="text"
                                            name="name"
                                            value="{{ old('name') }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="اسمك الكامل"
                                            required
                                        >
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="contact-email" class="form-label fw-semibold">البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input
                                            id="contact-email"
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="name@example.com"
                                            required
                                        >
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="contact-message" class="form-label fw-semibold">الرسالة <span class="text-danger">*</span></label>
                                    <textarea
                                        id="contact-message"
                                        name="message"
                                        rows="5"
                                        class="form-control @error('message') is-invalid @enderror"
                                        placeholder="اكتب رسالتك هنا…"
                                        required
                                    >{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-send me-1"></i> إرسال الرسالة
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="text-center text-muted py-3">
        <small>&copy; {{ date('Y') }} SkillHub</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
