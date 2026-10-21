@extends('admin.layouts.app')
@section('title', 'Kelola Produk')

@section('content')
<div class="space-y-6">

    {{-- Title & Action Row --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-foreground">Kelola Produk</h1>
            <p class="text-sm text-muted-foreground">Atur katalog buket bunga, fresh flower, amplop kartu, serta kelola ketersediaan stok.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-3.5 py-2 text-xs font-medium text-primary-foreground shadow-2xs hover:bg-primary/90 transition-all">
                <i data-lucide="plus" class="size-3.5"></i>
                <span>Tambah Produk Baru</span>
            </a>
        </div>
    </div>

    {{-- Filter Toolbar --}}
    <div class="rounded-xl border border-border bg-card p-4 shadow-xs">
        <form class="flex flex-wrap items-center gap-2.5 w-full" method="GET">
            <div class="relative flex-1 min-w-[180px]">
                <i data-lucide="search" class="absolute left-2.5 top-2.5 size-4 text-muted-foreground"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk..." 
                       class="h-9 w-full rounded-md border border-input bg-background pl-8 pr-3 text-xs text-foreground placeholder:text-muted-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
            </div>

            <select name="category" class="h-9 rounded-md border border-input bg-background px-3 text-xs text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                <option value="">Semua Kategori</option>
                <option value="buket" @selected(request('category')=='buket')>Buket</option>
                <option value="fresh_flower" @selected(request('category')=='fresh_flower')>Fresh Flower</option>
                <option value="amplop" @selected(request('category')=='amplop')>Amplop / Kartu</option>
            </select>

            <select name="status" class="h-9 rounded-md border border-input bg-background px-3 text-xs text-foreground shadow-2xs focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring transition-colors">
                <option value="">Semua Status</option>
                <option value="available" @selected(request('status')=='available')>Tersedia</option>
                <option value="sold_out" @selected(request('status')=='sold_out')>Sold Out</option>
            </select>

            <button type="submit" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md bg-primary px-3.5 text-xs font-medium text-primary-foreground shadow-2xs hover:bg-primary/90 transition-all">
                <i data-lucide="filter" class="size-3.5"></i>
                <span>Filter</span>
            </button>

            @if(request('q') || request('category') || request('status'))
            <a href="{{ route('admin.products.index') }}" class="inline-flex h-9 items-center justify-center gap-1.5 rounded-md border border-input bg-background px-3 text-xs font-medium text-muted-foreground hover:bg-accent hover:text-foreground transition-all">
                <i data-lucide="x" class="size-3.5"></i>
                <span>Reset</span>
            </a>
            @endif
        </form>
    </div>

    {{-- Shadcn Data Table --}}
    <div class="rounded-xl border border-border bg-card overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-border bg-muted/40">
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider w-20">Foto</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold text-muted-foreground uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($products as $p)
                    <tr class="hover:bg-muted/40 transition-colors">
                        <td class="px-6 py-4">
                            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="size-12 rounded-lg object-cover border border-border shadow-2xs">
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-foreground text-sm">{{ $p->name }}</p>
                            @if($p->description)
                            <p class="text-xs text-muted-foreground line-clamp-1 mt-0.5 max-w-xs">{{ $p->description }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center rounded-md border border-border bg-muted px-2 py-0.5 text-xs font-medium text-muted-foreground">
                                {{ $p->category_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-foreground whitespace-nowrap">
                            {{ $p->formatted_price }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($p->status === 'available')
                            <span class="inline-flex items-center gap-1.5 rounded-md border border-teal-200 bg-teal-100/30 px-2 py-0.5 text-xs font-medium text-teal-800 dark:text-teal-200">
                                <span class="size-1.5 rounded-full bg-teal-600 dark:bg-teal-400"></span>
                                Tersedia
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 rounded-md border border-destructive/20 bg-destructive/10 px-2 py-0.5 text-xs font-medium text-destructive">
                                <span class="size-1.5 rounded-full bg-destructive"></span>
                                Sold Out
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.products.edit', $p) }}" class="inline-flex items-center gap-1.5 rounded-md border border-input bg-background px-2.5 py-1 text-xs font-medium text-foreground shadow-2xs hover:bg-accent hover:text-accent-foreground transition-all">
                                    <i data-lucide="pencil" class="size-3.5"></i>
                                    <span>Edit</span>
                                </a>

                                <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-md border border-destructive/20 bg-destructive/10 px-2.5 py-1 text-xs font-medium text-destructive hover:bg-destructive/20 transition-all">
                                        <i data-lucide="trash-2" class="size-3.5"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-muted-foreground">
                            <i data-lucide="package-search" class="size-8 mx-auto mb-2 stroke-1"></i>
                            <p class="text-sm font-medium">Tidak ada produk yang cocok dengan pencarian.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($products->hasPages())
    <div class="flex items-center justify-between">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
