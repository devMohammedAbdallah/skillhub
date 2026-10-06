<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #4caf50;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">SkillHub</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="القائمة">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('courses.*')) active @endif"
                       href="{{ route('courses.index') }}">الدورات</a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('home')) active @endif"
                           href="{{ route('home') }}">الرئيسية</a>
                    </li>

                    @if (auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link @if (request()->routeIs('admin.*')) active @endif"
                               href="{{ route('admin.dashboard') }}">لوحة التحكم</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('profile.*')) active @endif"
                           href="{{ route('profile.edit') }}">الملف الشخصي</a>
                    </li>

                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm mt-1 mt-lg-0">
                                تسجيل الخروج
                            </button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('login')) active @endif"
                           href="{{ route('login') }}">تسجيل الدخول</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if (request()->routeIs('register')) active @endif"
                           href="{{ route('register') }}">إنشاء حساب</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
