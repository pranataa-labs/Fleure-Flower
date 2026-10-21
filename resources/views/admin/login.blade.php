<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Fleure Admin</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Anti-FOUC Theme Script -->
    <script>
        (function() {
            try {
                const theme = localStorage.getItem('theme') || 'system';
                const isDark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/admin.css'])
</head>
<body class="min-h-screen bg-muted/40 text-foreground flex flex-col justify-between p-4 sm:p-6 antialiased selection:bg-primary selection:text-primary-foreground">

    {{-- Top Bar (Brand on left, Theme switch on right) --}}
    <header class="flex items-center justify-between w-full max-w-5xl mx-auto">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-medium text-muted-foreground hover:text-foreground transition-colors">
            <i data-lucide="arrow-left" class="size-3.5"></i>
            <span>Kembali ke Website</span>
        </a>

        {{-- Theme Mode Toggle --}}
        <div class="relative" id="themeDropdownContainer">
            <button id="themeToggleBtn" type="button" aria-label="Toggle theme" class="inline-flex size-9 items-center justify-center rounded-md border border-input bg-background text-foreground shadow-2xs hover:bg-accent hover:text-accent-foreground transition-colors">
                <i data-lucide="sun" class="size-4 rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"></i>
                <i data-lucide="moon" class="absolute size-4 rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100"></i>
            </button>
            <div id="themeMenu" class="hidden absolute right-0 mt-2 w-32 rounded-md border border-border bg-popover p-1 text-popover-foreground shadow-md z-50">
                <button type="button" onclick="setTheme('light')" class="w-full flex items-center gap-2 rounded-sm px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground transition-colors">
                    <i data-lucide="sun" class="size-3.5 text-muted-foreground"></i>
                    <span>Light</span>
                </button>
                <button type="button" onclick="setTheme('dark')" class="w-full flex items-center gap-2 rounded-sm px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground transition-colors">
                    <i data-lucide="moon" class="size-3.5 text-muted-foreground"></i>
                    <span>Dark</span>
                </button>
                <button type="button" onclick="setTheme('system')" class="w-full flex items-center gap-2 rounded-sm px-2.5 py-1.5 text-xs hover:bg-accent hover:text-accent-foreground transition-colors">
                    <i data-lucide="laptop" class="size-3.5 text-muted-foreground"></i>
                    <span>System</span>
                </button>
            </div>
        </div>
    </header>

    {{-- Main Auth Card Container --}}
    <main class="w-full max-w-[400px] mx-auto my-auto py-8">
        <div class="rounded-xl border border-border bg-card p-6 sm:p-8 shadow-xs space-y-6">

            {{-- Card Header --}}
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="size-11 rounded-xl bg-primary text-primary-foreground flex items-center justify-center shadow-xs">
                    <i data-lucide="flower-2" class="size-6"></i>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-foreground">Fleure Studio Admin</h1>
                <p class="text-xs text-muted-foreground">Masukkan kredensial Anda untuk mengakses dashboard manajemen toko bunga.</p>
            </div>

            {{-- Error Flash --}}
            @if($errors->any())
            <div class="rounded-lg border border-destructive/20 bg-destructive/10 p-3 text-xs text-destructive flex items-center gap-2">
                <i data-lucide="alert-circle" class="size-4 shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            {{-- Login Form --}}
            <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label for="username" class="text-xs font-medium text-foreground">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus placeholder="admin"
                           class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground shadow-2xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                </div>

                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-xs font-medium text-foreground">Password</label>
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" required placeholder="••••••••"
                               class="h-9 w-full rounded-md border border-input bg-background pl-3 pr-10 text-sm text-foreground shadow-2xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute right-2.5 top-2.5 text-muted-foreground hover:text-foreground transition-colors" title="Lihat password">
                            <i id="passwordEye" data-lucide="eye" class="size-4"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="inline-flex h-9 w-full items-center justify-center gap-2 rounded-md bg-primary px-4 text-xs font-medium text-primary-foreground shadow-2xs hover:bg-primary/90 transition-all mt-2">
                    <span>Masuk ke Dashboard</span>
                    <i data-lucide="arrow-right" class="size-3.5"></i>
                </button>
            </form>

            <div class="pt-2 text-center border-t border-border">
                <p class="text-[11px] text-muted-foreground">
                    Fleure Flower Atelier &bull; Management Suite
                </p>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="w-full max-w-5xl mx-auto text-center py-4 text-xs text-muted-foreground">
        &copy; {{ date('Y') }} Fleure Flower Atelier. All rights reserved.
    </footer>

    {{-- Theme Switcher & Password Visibility Script --}}
    <script>
        function togglePasswordVisibility() {
            const input = document.getElementById('password');
            const eye = document.getElementById('passwordEye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                eye.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const themeMenu = document.getElementById('themeMenu');

        themeToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            themeMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!document.getElementById('themeDropdownContainer').contains(e.target)) {
                themeMenu.classList.add('hidden');
            }
        });

        function setTheme(mode) {
            localStorage.setItem('theme', mode);
            const isDark = mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            themeMenu.classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
