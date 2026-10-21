@extends('admin.layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Title & Action Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Dashboard</h1>
            <p class="text-sm text-muted-foreground">Ringkasan performa penjualan, pesanan, dan katalog produk Fleure Flower.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center justify-center gap-2 rounded-md border border-input bg-background px-3 py-2 text-xs font-medium text-foreground shadow-2xs hover:bg-accent hover:text-accent-foreground transition-all">
                <i data-lucide="shopping-bag" class="size-3.5"></i>
                <span>Kelola Pesanan</span>
            </a>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-3 py-2 text-xs font-medium text-primary-foreground shadow-2xs hover:bg-primary/90 transition-all">
                <i data-lucide="plus" class="size-3.5"></i>
                <span>Tambah Produk</span>
            </a>
        </div>
    </div>

    {{-- 4 Primary KPI Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        
        {{-- KPI 1: Pendapatan Bulan Ini --}}
        <div class="rounded-xl border border-border bg-card p-6 text-card-foreground shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between space-y-0 pb-2">
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Pendapatan Bulan Ini</span>
                <div class="p-1.5 rounded-md bg-muted text-muted-foreground">
                    <i data-lucide="banknote" class="size-4"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold tracking-tight text-foreground">Rp {{ number_format($revenues['monthly'], 0, ',', '.') }}</div>
                <p class="text-xs text-muted-foreground mt-1">Hari ini: Rp {{ number_format($revenues['daily'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- KPI 2: Pesanan Dibayar --}}
        <div class="rounded-xl border border-border bg-card p-6 text-card-foreground shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between space-y-0 pb-2">
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Pesanan Dibayar</span>
                <div class="p-1.5 rounded-md bg-muted text-muted-foreground">
                    <i data-lucide="check-circle-2" class="size-4"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold tracking-tight text-foreground">{{ $stats['paid_orders'] }}</div>
                <p class="text-xs text-muted-foreground mt-1">Dari total {{ $stats['total_orders'] }} pesanan masuk</p>
            </div>
        </div>

        {{-- KPI 3: Produk Tersedia --}}
        <div class="rounded-xl border border-border bg-card p-6 text-card-foreground shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between space-y-0 pb-2">
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Produk Aktif</span>
                <div class="p-1.5 rounded-md bg-muted text-muted-foreground">
                    <i data-lucide="flower-2" class="size-4"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold tracking-tight text-foreground">{{ $stats['buket'] + $stats['fresh_flower'] }}</div>
                <p class="text-xs text-muted-foreground mt-1">{{ $stats['buket'] }} Buket • {{ $stats['fresh_flower'] }} Fresh Flower</p>
            </div>
        </div>

        {{-- KPI 4: Stok Habis --}}
        <div class="rounded-xl border border-border bg-card p-6 text-card-foreground shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between space-y-0 pb-2">
                <span class="text-xs font-medium text-muted-foreground uppercase tracking-wider">Sold Out</span>
                <div class="p-1.5 rounded-md bg-muted text-muted-foreground">
                    <i data-lucide="package-x" class="size-4"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-bold tracking-tight text-foreground">{{ $stats['sold_out'] }}</div>
                <p class="text-xs {{ $stats['sold_out'] > 0 ? 'text-amber-600 dark:text-amber-400 font-medium' : 'text-muted-foreground' }} mt-1">
                    {{ $stats['sold_out'] > 0 ? 'Perlu restok bunga segera' : 'Semua katalog tersedia' }}
                </p>
            </div>
        </div>
    </div>

    {{-- 7-Column Layout: Overview Chart (4 cols) & Recent Sales (3 cols) --}}
    <div class="grid grid-cols-1 gap-4 lg:grid-cols-7">
        
        {{-- Col 4/7: Revenue Chart Card --}}
        <div class="col-span-1 lg:col-span-4 rounded-xl border border-border bg-card p-6 text-card-foreground shadow-xs flex flex-col">
            <div class="mb-4">
                <h3 class="text-base font-semibold leading-none tracking-tight text-foreground">Grafik Pendapatan</h3>
                <p class="text-xs text-muted-foreground mt-1">Tren pemasukan toko dalam 7 hari terakhir.</p>
            </div>
            <div class="w-full flex-1 min-h-[300px] relative">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Col 3/7: Recent Orders Card --}}
        <div class="col-span-1 lg:col-span-3 rounded-xl border border-border bg-card p-6 text-card-foreground shadow-xs flex flex-col">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold leading-none tracking-tight text-foreground">Pesanan Terbaru</h3>
                    <p class="text-xs text-muted-foreground mt-1">5 transaksi pembeli terkini.</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-muted-foreground hover:text-foreground hover:underline">
                    Semua
                </a>
            </div>

            @if($latestOrders->count() > 0)
            <div class="space-y-6 flex-1">
                @foreach($latestOrders as $o)
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-muted border border-border text-xs font-semibold text-foreground">
                            {{ strtoupper(substr($o->customer_name, 0, 2)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-sm font-medium leading-none truncate text-foreground">{{ $o->customer_name }}</p>
                            <p class="text-xs text-muted-foreground truncate mt-1 font-mono">{{ $o->order_code }}</p>
                        </div>
                    </div>
                    <div class="text-end shrink-0">
                        <p class="text-sm font-semibold text-foreground">{{ $o->formatted_total }}</p>
                        <span class="inline-flex items-center rounded-md border px-1.5 py-0.5 text-[10px] font-medium mt-0.5 {{ in_array($o->status, ['paid', 'completed']) ? 'bg-teal-100/30 text-teal-800 dark:text-teal-200 border-teal-200' : ($o->status === 'cancelled' ? 'bg-destructive/10 text-destructive border-destructive/20' : 'bg-amber-100/30 text-amber-800 dark:text-amber-200 border-amber-200') }}">
                            {{ $o->status_label }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center flex-1 py-12 text-center text-muted-foreground">
                <i data-lucide="shopping-bag" class="size-8 mb-2 stroke-1"></i>
                <p class="text-xs">Belum ada pesanan terbaru.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chartData = @json($chartData);
    
    const labels = chartData.labels.reverse();
    const data = chartData.data.reverse();

    function getThemeStyles() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            textColor: isDark ? '#94a3b8' : '#64748b',
            gridColor: isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)',
            borderColor: isDark ? '#f8fafc' : '#0f172a',
            bgColor: isDark ? 'rgba(248, 250, 252, 0.08)' : 'rgba(15, 23, 42, 0.05)'
        };
    }

    let styles = getThemeStyles();

    const chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: data,
                borderColor: styles.borderColor,
                backgroundColor: styles.bgColor,
                borderWidth: 2,
                pointBackgroundColor: styles.borderColor,
                pointHoverRadius: 6,
                pointRadius: 4,
                fill: true,
                tension: 0.35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: styles.borderColor,
                    titleColor: styles.textColor,
                    bodyColor: styles.textColor,
                    padding: 10,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: styles.textColor,
                        font: { family: 'Inter', size: 11 }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: styles.gridColor },
                    ticks: {
                        color: styles.textColor,
                        font: { family: 'Inter', size: 11 },
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                            if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });

    window.addEventListener('theme-changed', function() {
        const s = getThemeStyles();
        chartInstance.data.datasets[0].borderColor = s.borderColor;
        chartInstance.data.datasets[0].backgroundColor = s.bgColor;
        chartInstance.data.datasets[0].pointBackgroundColor = s.borderColor;
        chartInstance.options.scales.x.ticks.color = s.textColor;
        chartInstance.options.scales.y.ticks.color = s.textColor;
        chartInstance.options.scales.y.grid.color = s.gridColor;
        chartInstance.update();
    });
});
</script>
@endpush
