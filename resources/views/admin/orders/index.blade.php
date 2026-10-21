@extends('admin.layouts.app')
@section('title', 'Daftar Pesanan')

@section('content')
<div class="space-y-6">

    {{-- Title Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Daftar Pesanan</h1>
            <p class="text-sm text-muted-foreground">Kelola seluruh riwayat pemesanan bunga, konfirmasi pembayaran, dan status pengiriman.</p>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="flex items-center justify-between">
        <form method="GET" class="flex flex-wrap items-center gap-2.5 w-full">
            <select name="status" class="h-9 rounded-md border border-input bg-background px-3 text-xs text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                <option value="">Semua Status</option>
                @foreach(['pending'=>'Menunggu Pembayaran','paid'=>'Sudah Dibayar','processing'=>'Diproses','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $v=>$l)
                <option value="{{ $v }}" @selected(request('status')===$v)>{{ $l }}</option>
                @endforeach
            </select>

            <button type="submit" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md bg-primary px-3.5 text-xs font-medium text-primary-foreground shadow-2xs hover:bg-primary/90 transition-all">
                <i data-lucide="filter" class="size-3.5"></i>
                <span>Filter</span>
            </button>

            @if(request('status'))
            <a href="{{ route('admin.orders.index') }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md border border-input bg-background px-3 text-xs font-medium text-muted-foreground hover:bg-accent hover:text-foreground transition-all">
                <i data-lucide="x" class="size-3.5"></i>
                <span>Reset</span>
            </a>
            @endif
        </form>
    </div>

    {{-- Shadcn Data Table Card --}}
    <div class="rounded-xl border border-border bg-card overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/40">
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($orders as $o)
                    <tr class="hover:bg-muted/40 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs font-medium text-foreground">
                            {{ $o->order_code }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-muted border border-border text-xs font-semibold text-foreground">
                                    {{ strtoupper(substr($o->customer_name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-foreground text-sm leading-none">{{ $o->customer_name }}</p>
                                    <p class="text-xs text-muted-foreground mt-1">{{ $o->customer_phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-foreground whitespace-nowrap">
                            {{ $o->formatted_total }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 rounded-md border px-2 py-0.5 text-xs font-medium {{ in_array($o->status, ['paid', 'completed']) ? 'bg-teal-100/30 text-teal-800 dark:text-teal-200 border-teal-200' : ($o->status === 'cancelled' ? 'bg-destructive/10 text-destructive border-destructive/20' : 'bg-amber-100/30 text-amber-800 dark:text-amber-200 border-amber-200') }}">
                                <span class="size-1.5 rounded-full {{ in_array($o->status, ['paid', 'completed']) ? 'bg-teal-600 dark:bg-teal-400' : ($o->status === 'cancelled' ? 'bg-destructive' : 'bg-amber-600 dark:bg-amber-400') }}"></span>
                                {{ $o->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-muted-foreground whitespace-nowrap">
                            {{ $o->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.orders.show', $o) }}" class="inline-flex items-center gap-1.5 rounded-md border border-input bg-background px-2.5 py-1 text-xs font-medium text-foreground shadow-2xs hover:bg-accent hover:text-accent-foreground transition-all">
                                <i data-lucide="eye" class="size-3.5"></i>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-muted-foreground">
                            <i data-lucide="shopping-bag" class="size-8 mx-auto mb-2 stroke-1"></i>
                            <p class="text-sm font-medium">Belum ada pesanan ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="flex items-center justify-between">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
