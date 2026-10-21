@extends('admin.layouts.app')
@section('title', 'Detail Pesanan — ' . $order->order_code)

@section('content')
<div class="max-w-4xl space-y-6">

    {{-- Header & Back Link --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.orders.index') }}" class="flex size-8 items-center justify-center rounded-md border border-input bg-background hover:bg-accent text-foreground transition-colors shadow-2xs">
            <i data-lucide="arrow-left" class="size-4"></i>
        </a>
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-xl font-bold tracking-tight text-foreground">{{ $order->order_code }}</h1>
                <span class="inline-flex items-center gap-1.5 rounded-md border px-2 py-0.5 text-xs font-medium {{ in_array($order->status, ['paid', 'completed']) ? 'bg-teal-100/30 text-teal-800 dark:text-teal-200 border-teal-200' : ($order->status === 'cancelled' ? 'bg-destructive/10 text-destructive border-destructive/20' : 'bg-amber-100/30 text-amber-800 dark:text-amber-200 border-amber-200') }}">
                    <span class="size-1.5 rounded-full {{ in_array($order->status, ['paid', 'completed']) ? 'bg-teal-600 dark:bg-teal-400' : ($order->status === 'cancelled' ? 'bg-destructive' : 'bg-amber-600 dark:bg-amber-400') }}"></span>
                    {{ $order->status_label }}
                </span>
            </div>
            <p class="text-xs text-muted-foreground mt-0.5">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }} WIB</p>
        </div>
    </div>

    {{-- Customer & Status Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        {{-- Card 1: Info Pelanggan --}}
        <div class="rounded-xl border border-border bg-card p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-semibold tracking-tight text-foreground uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="size-4 text-muted-foreground"></i>
                    Informasi Pelanggan
                </h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-muted-foreground block text-[11px]">Nama Pelanggan</span>
                        <p class="font-medium text-foreground text-sm mt-0.5">{{ $order->customer_name }}</p>
                    </div>

                    <div>
                        <span class="text-muted-foreground block text-[11px]">Nomor WhatsApp</span>
                        <a href="https://wa.me/{{ $order->customer_phone }}" target="_blank" class="font-medium text-primary hover:underline inline-flex items-center gap-1 mt-0.5">
                            <i data-lucide="phone" class="size-3"></i>
                            {{ $order->customer_phone }}
                        </a>
                    </div>

                    @if($order->customer_email)
                    <div>
                        <span class="text-muted-foreground block text-[11px]">Email</span>
                        <p class="font-medium text-foreground mt-0.5">{{ $order->customer_email }}</p>
                    </div>
                    @endif

                    <div>
                        <span class="text-muted-foreground block text-[11px]">Kota & Alamat Pengiriman</span>
                        <p class="font-medium text-foreground mt-0.5 leading-relaxed">{{ $order->city }} — {{ $order->shipping_address }}</p>
                    </div>

                    @if($order->notes)
                    <div class="p-2.5 rounded-lg bg-muted border border-border">
                        <span class="text-muted-foreground block text-[10px] font-semibold uppercase tracking-wider">Catatan Khusus</span>
                        <p class="text-foreground mt-0.5 italic">"{{ $order->notes }}"</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Card 2: Status & Kontrol --}}
        <div class="rounded-xl border border-border bg-card p-6 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-semibold tracking-tight text-foreground uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="sliders" class="size-4 text-muted-foreground"></i>
                    Perbarui Status Pesanan
                </h3>

                <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    
                    <div>
                        <label class="block text-xs font-semibold text-foreground mb-1.5">Pilih Status Baru</label>
                        <select name="status" class="h-9 w-full rounded-md border border-input bg-background px-3 text-xs text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                            @foreach(['pending'=>'Menunggu Pembayaran','paid'=>'Sudah Dibayar','processing'=>'Diproses','shipped'=>'Dikirim','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $v=>$l)
                            <option value="{{ $v }}" @selected($order->status===$v)>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="inline-flex h-9 w-full items-center justify-center gap-2 rounded-md bg-primary px-4 text-xs font-medium text-primary-foreground shadow-2xs hover:bg-primary/90 transition-all">
                        <i data-lucide="check" class="size-3.5"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </form>

                @if($order->transaction_id)
                <div class="mt-4 pt-4 border-t border-border">
                    <span class="text-muted-foreground text-[11px] block">ID Transaksi Payment Gateway:</span>
                    <code class="text-xs font-mono text-foreground mt-0.5 block">{{ $order->transaction_id }}</code>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Items Table Card --}}
    <div class="rounded-xl border border-border bg-card overflow-hidden shadow-xs">
        <div class="px-6 py-4 border-b border-border bg-muted/40">
            <h3 class="text-sm font-semibold text-foreground">Rincian Item yang Dipesan</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-border text-xs text-muted-foreground uppercase tracking-wider">
                        <th class="px-6 py-3.5 font-semibold">Nama Produk</th>
                        <th class="px-6 py-3.5 font-semibold text-right">Harga Satuan</th>
                        <th class="px-6 py-3.5 font-semibold text-center">Jumlah</th>
                        <th class="px-6 py-3.5 font-semibold text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-4 font-medium text-foreground">{{ $item->product_name }}</td>
                        <td class="px-6 py-4 text-right text-muted-foreground">{{ $item->formatted_price }}</td>
                        <td class="px-6 py-4 text-center font-medium text-foreground">{{ $item->quantity }}x</td>
                        <td class="px-6 py-4 text-right font-semibold text-foreground">{{ $item->formatted_subtotal }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-border bg-muted/20">
                        <td colspan="3" class="px-6 py-4 font-bold text-foreground text-end">Total Pembayaran:</td>
                        <td class="px-6 py-4 text-right text-base font-bold text-foreground">{{ $order->formatted_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
