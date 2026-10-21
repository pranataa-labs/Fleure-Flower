<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Fleure Flower</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Anti-FOUC Theme Script (Light/Dark/System) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('fleure_theme') || 'system';
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = savedTheme === 'dark' || (savedTheme === 'system' && systemDark);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-background text-foreground antialiased flex flex-col md:flex-row overflow-x-hidden font-sans">

    {{-- SIDEBAR OVERLAY FOR MOBILE --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-40 hidden md:hidden transition-opacity"></div>

    {{-- SHADCN ADMIN SIDEBAR (16rem = 256px) --}}
    <aside id="sidebar" class="fixed md:sticky top-0 left-0 z-50 h-screen w-64 shrink-0 flex flex-col border-e bg-sidebar text-sidebar-foreground transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out">
        
        {{-- Shop / Team Switcher Header --}}
        <div class="p-3 border-b border-sidebar-border">
            <div class="flex items-center gap-3 p-2 rounded-lg bg-sidebar-accent/50 border border-sidebar-border/50">
                <div class="flex aspect-square size-9 items-center justify-center rounded-lg bg-primary text-primary-foreground shadow-xs">
                    <i data-lucide="flower-2" class="size-5"></i>
                </div>
                <div class="grid flex-1 text-start leading-tight">
                    <span class="truncate font-semibold text-sm">Fleure Flower</span>
                    <span class="truncate text-xs text-muted-foreground">Admin Store Panel</span>
                </div>
                <button id="close-sidebar-mobile" class="md:hidden text-muted-foreground hover:text-foreground p-1">
                    <i data-lucide="x" class="size-4"></i>
                </button>
            </div>
        </div>

        {{-- Nav Links --}}
        <nav class="flex-1 overflow-y-auto p-3 space-y-6">
            {{-- Group: Manajemen Toko --}}
            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold tracking-wider text-muted-foreground uppercase">Toko & Katalog</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-sidebar-accent text-sidebar-accent-foreground shadow-xs' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                        <i data-lucide="layout-dashboard" class="size-4"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.products*') ? 'bg-sidebar-accent text-sidebar-accent-foreground shadow-xs' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                        <i data-lucide="package" class="size-4"></i>
                        <span>Kelola Produk</span>
                    </a>

                    <a href="{{ route('admin.orders.index') }}" 
                       class="flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.orders*') ? 'bg-sidebar-accent text-sidebar-accent-foreground shadow-xs' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shopping-bag" class="size-4"></i>
                            <span>Daftar Pesanan</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Group: Preferensi --}}
            <div>
                <p class="px-3 mb-2 text-[11px] font-semibold tracking-wider text-muted-foreground uppercase">Sistem</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.settings') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin.settings') ? 'bg-sidebar-accent text-sidebar-accent-foreground shadow-xs' : 'text-muted-foreground hover:bg-sidebar-accent hover:text-foreground' }}">
                        <i data-lucide="settings" class="size-4"></i>
                        <span>Pengaturan Toko</span>
                    </a>

                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-muted-foreground hover:bg-sidebar-accent hover:text-foreground transition-colors">
                        <div class="flex items-center gap-3">
                            <i data-lucide="external-link" class="size-4"></i>
                            <span>Lihat Website</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded border border-border bg-muted text-muted-foreground">Store</span>
                    </a>
                </div>
            </div>
        </nav>

        {{-- Footer User Card --}}
        <div class="p-3 border-t border-sidebar-border">
            <div class="flex items-center justify-between p-2 rounded-md hover:bg-sidebar-accent transition-colors">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex size-8 items-center justify-center rounded-full bg-primary/10 text-primary font-semibold text-xs border border-primary/20">
                        {{ strtoupper(substr(session('admin_username', 'AD'), 0, 2)) }}
                    </div>
                    <div class="truncate text-start">
                        <p class="truncate text-xs font-semibold leading-tight">{{ session('admin_username', 'Administrator') }}</p>
                        <p class="truncate text-[10px] text-muted-foreground">Admin Store</p>
                    </div>
                </div>

                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar" class="p-1.5 text-muted-foreground hover:text-destructive rounded-md hover:bg-destructive/10 transition-colors">
                        <i data-lucide="log-out" class="size-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN WRAPPER --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- STICKY BLURRED HEADER --}}
        <header id="admin-header" class="sticky top-0 z-40 h-16 border-b border-border bg-background/80 backdrop-blur-md transition-shadow">
            <div class="flex h-full items-center justify-between px-4 sm:px-6 gap-4">
                
                {{-- Left: Toggle & Title --}}
                <div class="flex items-center gap-3">
                    <button id="open-sidebar" class="md:hidden flex items-center justify-center size-8 rounded-md border border-border hover:bg-accent text-foreground transition-colors">
                        <i data-lucide="panel-left" class="size-4"></i>
                    </button>
                    <div class="hidden md:block h-5 w-px bg-border"></div>
                    <h2 class="text-base font-semibold tracking-tight text-foreground">@yield('title', 'Dashboard')</h2>
                </div>

                {{-- Right: Actions & Tools --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    
                    {{-- Search Input Simulation --}}
                    <div class="relative hidden sm:flex items-center">
                        <div class="inline-flex h-8 w-44 lg:w-56 items-center gap-2 rounded-md border border-input bg-muted/30 px-2.5 text-xs text-muted-foreground shadow-2xs">
                            <i data-lucide="search" class="size-3.5"></i>
                            <span class="truncate">Cari data...</span>
                            <kbd class="ms-auto inline-flex h-4 items-center gap-0.5 rounded border border-border bg-muted px-1 font-mono text-[9px] font-medium text-muted-foreground">⌘K</kbd>
                        </div>
                    </div>

                    {{-- Theme Toggle Dropdown (Light / Dark / System) --}}
                    <div class="relative">
                        <button id="theme-btn" class="flex size-8 items-center justify-center rounded-md border border-border bg-background hover:bg-accent text-foreground transition-colors">
                            <i data-lucide="sun" class="size-4 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"></i>
                            <i data-lucide="moon" class="absolute size-4 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100"></i>
                        </button>

                        <div id="theme-menu" class="hidden absolute right-0 mt-2 w-36 rounded-lg border border-border bg-popover p-1 text-popover-foreground shadow-md z-50 text-xs">
                            <button onclick="setAppTheme('light')" class="flex w-full items-center justify-between rounded-md px-2 py-1.5 hover:bg-accent hover:text-accent-foreground text-start">
                                <span class="flex items-center gap-2"><i data-lucide="sun" class="size-3.5"></i> Terang</span>
                                <i data-lucide="check" class="size-3.5 theme-check-light hidden"></i>
                            </button>
                            <button onclick="setAppTheme('dark')" class="flex w-full items-center justify-between rounded-md px-2 py-1.5 hover:bg-accent hover:text-accent-foreground text-start">
                                <span class="flex items-center gap-2"><i data-lucide="moon" class="size-3.5"></i> Gelap</span>
                                <i data-lucide="check" class="size-3.5 theme-check-dark hidden"></i>
                            </button>
                            <button onclick="setAppTheme('system')" class="flex w-full items-center justify-between rounded-md px-2 py-1.5 hover:bg-accent hover:text-accent-foreground text-start">
                                <span class="flex items-center gap-2"><i data-lucide="laptop" class="size-3.5"></i> Sistem</span>
                                <i data-lucide="check" class="size-3.5 theme-check-system hidden"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Public Store Link --}}
                    <a href="{{ route('home') }}" target="_blank" title="Buka Toko Bunga" class="hidden sm:flex size-8 items-center justify-center rounded-md border border-border bg-background hover:bg-accent text-foreground transition-colors">
                        <i data-lucide="store" class="size-4"></i>
                    </a>
                </div>
            </div>
        </header>

        {{-- FLASH ALERTS --}}
        @if(session('success'))
        <div class="mx-4 sm:mx-6 mt-4 p-4 rounded-xl border border-teal-200/80 bg-teal-50 dark:bg-teal-950/30 text-teal-900 dark:text-teal-200 flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="size-5 text-teal-600 dark:text-teal-400"></i>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-teal-700 dark:text-teal-400 hover:opacity-75">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="mx-4 sm:mx-6 mt-4 p-4 rounded-xl border border-destructive/20 bg-destructive/10 text-destructive flex items-center justify-between shadow-2xs">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-circle" class="size-5"></i>
                <p class="text-sm font-medium">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="hover:opacity-75">
                <i data-lucide="x" class="size-4"></i>
            </button>
        </div>
        @endif

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto space-y-6">
            @yield('content')
        </main>
    </div>

    {{-- INTERACTIVITY SCRIPTS --}}
    <script>
        // Init Lucide Icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            updateThemeCheckmark();
        });

        // Mobile Sidebar Controls
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar-mobile');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function toggleSidebar(open) {
            if (open) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        if (openBtn) openBtn.addEventListener('click', () => toggleSidebar(true));
        if (closeBtn) closeBtn.addEventListener('click', () => toggleSidebar(false));
        if (overlay) overlay.addEventListener('click', () => toggleSidebar(false));

        // Theme Toggle Dropdown Logic
        const themeBtn = document.getElementById('theme-btn');
        const themeMenu = document.getElementById('theme-menu');

        if (themeBtn && themeMenu) {
            themeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                themeMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!themeMenu.contains(e.target) && e.target !== themeBtn) {
                    themeMenu.classList.add('hidden');
                }
            });
        }

        function setAppTheme(theme) {
            localStorage.setItem('fleure_theme', theme);
            const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = theme === 'dark' || (theme === 'system' && systemDark);

            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            updateThemeCheckmark();
            if (themeMenu) themeMenu.classList.add('hidden');

            // Dispatch event for charts to redraw if needed
            window.dispatchEvent(new Event('theme-changed'));
        }

        function updateThemeCheckmark() {
            const current = localStorage.getItem('fleure_theme') || 'system';
            document.querySelectorAll('.theme-check-light').forEach(el => el.classList.toggle('hidden', current !== 'light'));
            document.querySelectorAll('.theme-check-dark').forEach(el => el.classList.toggle('hidden', current !== 'dark'));
            document.querySelectorAll('.theme-check-system').forEach(el => el.classList.toggle('hidden', current !== 'system'));
        }

        // Header shadow on scroll
        const header = document.getElementById('admin-header');
        window.addEventListener('scroll', () => {
            if (header) {
                if (window.scrollY > 10) {
                    header.classList.add('shadow-xs');
                } else {
                    header.classList.remove('shadow-xs');
                }
            }
        }, { passive: true });
    </script>
    @stack('scripts')
</body>
</html>
