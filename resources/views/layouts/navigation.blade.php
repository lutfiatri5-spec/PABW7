<nav x-data="{ open: false }" class="site-nav">
    <div class="site-nav-inner">
        <a href="{{ route('lapor.form') }}" class="brand">
            <span class="brand-mark" aria-hidden="true">🚨</span>
            <span>LaporBanjir</span>
        </a>

        <div class="site-nav-links">
            <a href="{{ route('lapor.form') }}#form" @class(['nav-link', 'is-active' => request()->routeIs('lapor.form')])>Form Laporan</a>
            @auth
                @if (auth()->user()->isPetugas())
                    <a href="{{ route('laporan.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('laporan.*')])>Daftar Laporan</a>
                    <a href="{{ route('profile.edit') }}" @class(['nav-link', 'is-active' => request()->routeIs('profile.*')])>Profil</a>
                @else
                    <a href="{{ route('riwayat.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('riwayat.*')])>Riwayat Saya</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="nav-link" type="submit">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" @class(['nav-link', 'is-active' => request()->routeIs('login')])>Login</a>
                <a href="{{ route('register') }}" @class(['nav-link', 'is-active' => request()->routeIs('register')])>Register</a>
            @endauth
        </div>

        <button type="button" class="mobile-menu-button" @click="open = !open" :aria-expanded="open.toString()" aria-label="Buka menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-width="2" d="m6 6 12 12M18 6 6 18"/>
            </svg>
        </button>
    </div>
    <div x-cloak x-show="open" class="mobile-nav">
        <a href="{{ route('lapor.form') }}#form">Form Laporan</a>
        @auth
            @if (auth()->user()->isPetugas())
                <a href="{{ route('laporan.index') }}">Daftar Laporan</a>
                <a href="{{ route('profile.edit') }}">Profil</a>
            @else
                <a href="{{ route('riwayat.index') }}">Riwayat Saya</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
</nav>
