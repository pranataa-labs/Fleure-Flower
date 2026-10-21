@extends('admin.layouts.app')
@section('title', 'Pengaturan Toko')

@section('content')
<div class="max-w-2xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-foreground">Pengaturan Toko</h1>
        <p class="text-sm text-muted-foreground">Konfigurasi profil bisnis, kontak WhatsApp pemesanan, dan lokasi fisik toko Fleure Flower.</p>
    </div>

    <div class="rounded-xl border border-border bg-card p-6 shadow-xs">
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5">Nama Toko</label>
                <input type="text" name="store_name" value="{{ old('store_name', $setting->store_name ?? '') }}" required 
                       class="h-9 w-full rounded-md border border-input bg-background px-3 text-xs text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5">Nomor WhatsApp Toko</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $setting->whatsapp ?? '') }}" required 
                       class="h-9 w-full rounded-md border border-input bg-background px-3 text-xs text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors" placeholder="628123456789">
                <p class="text-[11px] text-muted-foreground mt-1">Gunakan format 62 (kode negara). Nomor ini digunakan untuk konfirmasi otomatis pesanan pembeli.</p>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5">Username Instagram (Opsional)</label>
                <input type="text" name="instagram" value="{{ old('instagram', $setting->instagram ?? '') }}" 
                       class="h-9 w-full rounded-md border border-input bg-background px-3 text-xs text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors" placeholder="@fleureflower">
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5">Alamat Lengkap Toko</label>
                <textarea name="address" rows="3" required 
                          class="w-full rounded-md border border-input bg-background p-3 text-xs text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors resize-none">{{ old('address', $setting->address ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-foreground mb-1.5">Kode Embed Google Maps (Opsional)</label>
                <textarea name="maps_embed" rows="3" 
                          class="w-full rounded-md border border-input bg-background p-3 font-mono text-[11px] text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors resize-none" placeholder="<iframe src=&quot;https://www.google.com/maps/embed?...&quot; ...></iframe>">{{ old('maps_embed', $setting->maps_embed ?? '') }}</textarea>
                <p class="text-[11px] text-muted-foreground mt-1">Salin kode embed dari Google Maps → Bagikan → Sematkan peta.</p>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex h-9 w-full sm:w-auto items-center justify-center gap-2 rounded-md bg-primary px-5 text-xs font-medium text-primary-foreground shadow-2xs hover:bg-primary/90 transition-all">
                    <i data-lucide="save" class="size-3.5"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
