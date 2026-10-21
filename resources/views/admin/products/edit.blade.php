@extends('admin.layouts.app')
@section('title', 'Edit Produk')

@section('content')
<div class="space-y-6 max-w-3xl">

    {{-- Breadcrumb & Title --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.products.index') }}" class="inline-flex size-9 items-center justify-center rounded-md border border-input bg-background text-muted-foreground hover:bg-accent hover:text-foreground transition-colors shadow-2xs" title="Kembali ke Katalog">
            <i data-lucide="arrow-left" class="size-4"></i>
        </a>
        <div>
            <div class="flex items-center gap-1.5 text-xs text-muted-foreground mb-1">
                <a href="{{ route('admin.products.index') }}" class="hover:text-foreground transition-colors">Katalog Produk</a>
                <i data-lucide="chevron-right" class="size-3 text-muted-foreground/60"></i>
                <span class="text-foreground font-medium">Edit Produk</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Edit Produk: {{ $product->name }}</h1>
        </div>
    </div>

    {{-- Form Card --}}
    <div class="rounded-xl border border-border bg-card p-6 sm:p-8 shadow-xs">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama Produk --}}
            <div class="space-y-2">
                <label for="name" class="text-xs font-medium text-foreground">
                    Nama Produk <span class="text-destructive">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                       class="h-9 w-full rounded-md border @error('name') border-destructive @else border-input @enderror bg-background px-3 text-sm text-foreground shadow-2xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                @error('name')
                    <p class="text-xs text-destructive flex items-center gap-1"><i data-lucide="alert-circle" class="size-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Kategori & Harga (Grid) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="category" class="text-xs font-medium text-foreground">
                        Kategori <span class="text-destructive">*</span>
                    </label>
                    <select id="category" name="category" required 
                            class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                        <option value="buket" @selected(old('category', $product->category)=='buket')>Buket Bunga</option>
                        <option value="fresh_flower" @selected(old('category', $product->category)=='fresh_flower')>Fresh Flower</option>
                        <option value="amplop" @selected(old('category', $product->category)=='amplop')>Amplop / Kartu Ucapan</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="price" class="text-xs font-medium text-foreground">
                        Harga (Rp) <span class="text-destructive">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-xs font-medium text-muted-foreground">Rp</span>
                        <input type="number" id="price" name="price" value="{{ old('price', intval($product->price)) }}" min="0" required
                               class="h-9 w-full rounded-md border @error('price') border-destructive @else border-input @enderror bg-background pl-9 pr-3 text-sm text-foreground shadow-2xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                    </div>
                    @error('price')
                        <p class="text-xs text-destructive flex items-center gap-1"><i data-lucide="alert-circle" class="size-3"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div class="space-y-2">
                <label for="description" class="text-xs font-medium text-foreground">
                    Deskripsi Produk <span class="text-destructive">*</span>
                </label>
                <textarea id="description" name="description" rows="4" required
                          class="w-full rounded-md border @error('description') border-destructive @else border-input @enderror bg-background p-3 text-sm text-foreground shadow-2xs placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors resize-none">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-destructive flex items-center gap-1"><i data-lucide="alert-circle" class="size-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Foto Produk (Current + Option to Replace) --}}
            <div class="space-y-3">
                <label class="text-xs font-medium text-foreground">Foto Produk</label>
                
                <div class="flex items-start gap-4 p-3 rounded-lg border border-border bg-muted/30">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-20 rounded-md object-cover border border-border shadow-2xs shrink-0">
                    <div class="text-xs text-muted-foreground space-y-1">
                        <p class="font-medium text-foreground">Foto Aktif Saat Ini</p>
                        <p>Jika ingin memperbarui foto produk, pilih file baru pada area di bawah. Jika tidak, biarkan kosong.</p>
                    </div>
                </div>

                <div class="relative rounded-lg border-2 border-dashed border-border p-6 text-center hover:bg-accent/40 hover:border-primary/40 transition-colors cursor-pointer group">
                    <input type="file" id="imageInput" name="image" accept="image/jpeg,image/png,image/webp" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewSelectedImage(event)">
                    <div id="uploadPlaceholder" class="space-y-2 pointer-events-none">
                        <div class="inline-flex size-9 items-center justify-center rounded-full bg-muted text-muted-foreground group-hover:text-primary group-hover:bg-primary/10 transition-colors">
                            <i data-lucide="upload-cloud" class="size-4"></i>
                        </div>
                        <p class="text-xs font-medium text-foreground">Klik untuk upload foto pengganti</p>
                        <p class="text-[11px] text-muted-foreground">JPG, PNG, atau WebP (Maksimal 3MB)</p>
                    </div>
                    <div id="imagePreviewContainer" class="hidden pointer-events-none space-y-2">
                        <img id="imagePreview" src="#" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-md border border-border shadow-2xs">
                        <p id="imagePreviewName" class="text-xs font-medium text-foreground"></p>
                        <p class="text-[11px] text-muted-foreground">Klik kembali jika ingin memilih foto lain</p>
                    </div>
                </div>
                @error('image')
                    <p class="text-xs text-destructive flex items-center gap-1"><i data-lucide="alert-circle" class="size-3"></i> {{ $message }}</p>
                @enderror
            </div>

            {{-- Status Ketersediaan --}}
            <div class="space-y-2">
                <label for="status" class="text-xs font-medium text-foreground">Status Ketersediaan</label>
                <select id="status" name="status" 
                        class="h-9 w-full rounded-md border border-input bg-background px-3 text-sm text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                    <option value="available" @selected(old('status', $product->status)=='available')>Tersedia (Tampil di Katalog Website)</option>
                    <option value="sold_out" @selected(old('status', $product->status)=='sold_out')>Sold Out (Disembunyikan dari Pemesanan)</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-border">
                <a href="{{ route('admin.products.index') }}" class="inline-flex h-9 items-center justify-center rounded-md border border-input bg-background px-4 text-xs font-medium text-foreground shadow-2xs hover:bg-accent hover:text-accent-foreground transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex h-9 items-center justify-center gap-2 rounded-md bg-primary px-4 text-xs font-medium text-primary-foreground shadow-2xs hover:bg-primary/90 transition-all">
                    <i data-lucide="check" class="size-3.5"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewSelectedImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreviewName').textContent = file.name;
                document.getElementById('uploadPlaceholder').classList.add('hidden');
                document.getElementById('imagePreviewContainer').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
