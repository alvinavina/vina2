@extends('front.master')
@section('content')
    <body class="font-[Poppins]">
       <x-navbar/>  
{{-- SCRIPT UNTUK MENGAKTIFKAN MENU MOBILE --}}
@push('after-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuButton = document.getElementById('mobile-menu-button');
        const navButtons = document.getElementById('nav-buttons');

        if (menuButton && navButtons) {
            menuButton.addEventListener('click', function () {
                // Toggle kelas 'hidden' untuk menampilkan/menyembunyikan menu
                navButtons.classList.toggle('hidden');
                
                // Pastikan menu selalu berbentuk kolom di mobile saat terlihat
                if (!navButtons.classList.contains('hidden')) {
                    navButtons.classList.add('flex');
                    navButtons.classList.remove('flex-row'); // Jaga agar tetap kolom
                    navButtons.classList.add('flex-col'); // Jaga agar tetap kolom
                } else {
                    navButtons.classList.remove('flex');
                }
            });
        }
    });
</script>
@endpush
        
        <!-- Navigasi Kategori (Dibuat Responsif) -->
        <nav id="Category" class="max-w-[1130px] mx-auto flex flex-wrap justify-center items-center gap-4 mt-[30px] px-4">
            @foreach ($categories as $category)
            <a href="{{ route('front.category', $category->slug) }}" class="rounded-full p-[10px_18px] flex gap-[8px] font-semibold text-sm transition-all duration-300 border border-[#FF69B4] hover:ring-2 hover:ring-[#FF69B4] shrink-0">
                <div class="flex w-5 h-5 shrink-0">
                    <img src="{{ Storage::url($category->icon) }}" alt="icon" />
                </div>
                <span class="hidden sm:inline">{{ $category->name }}</span>
            </a>
            @endforeach
        </nav>
        
        <!-- Bagian Heading dan Form Pencarian (Dibuat Responsif) -->
        <section id="heading" class="max-w-[1130px] mx-auto flex items-center flex-col gap-[30px] mt-[70px] px-4">
            <h1 class="text-3xl sm:text-4xl leading-tight sm:leading-[45px] font-bold text-center">
                Explore Hot Trending <br class="hidden sm:inline" />
                Good News Today
            </h1>
            <form action="{{ route('front.search') }}" method="GET" class="w-full max-w-[500px]">
                <label for="search-bar" class="w-full flex p-[12px_20px] transition-all duration-300 gap-[10px] ring-1 ring-[#FF69B4] focus-within:ring-2 focus-within:ring-[#FF69B4] rounded-[50px] group">
                    <div class="flex w-5 h-5 shrink-0">
                        <img src="{{ asset('assets/images/icons/search-normal.svg') }}" alt="icon" />
                    </div>
                    <input
                        autocomplete="off"
                        type="text"
                        id="search-bar"
                        name="keyword"
                        placeholder="Search hot trendy news today..."
                        class="appearance-none font-semibold placeholder:font-normal placeholder:text-[#FF69B4] outline-none focus:ring-0 w-full"
                    />
                </label>
            </form>
        </section>

        <!-- Bagian Rekomendasi Artikel Trending (Updated Grid for responsiveness) -->
        <!-- Bagian ini akan tampil jika hasil pencarian kosong (articles->isEmpty()) -->
        @if ($articles->isEmpty())
        <section id="trending-articles" class="max-w-[1130px] mx-auto flex items-start flex-col gap-[30px] mt-[70px] px-4">
            <h2 class="text-[26px] leading-[39px] font-bold text-[#FF69B4]">Artikel Trending Hari Ini</h2>
            
            <p class="text-gray-500 -mt-5">Tidak ada artikel yang cocok dengan kata kunci "{{ ucfirst($keyword) }}". Berikut adalah rekomendasi artikel terpopuler:</p>

            {{-- Grid responsif: 1 kolom di mobile, 2 di tablet, 3 di desktop --}}
            <div id="search-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[30px] w-full">
                {{-- Asumsi Anda memiliki variabel $trendingArticles yang berisi artikel trending --}}
                @forelse ($trendingArticles as $article) 
                <a href="{{ route('front.details', $article->slug) }}" class="card">
                    <div
                        class="flex flex-col gap-4 p-[26px_20px] transition-all duration-300 ring-1 ring-[#FF69B4] hover:ring-2 hover:ring-[#FF69B4] rounded-[20px] overflow-hidden bg-white">
                        <div class="thumbnail-container h-[200px] relative rounded-[20px] overflow-hidden">
                            <div
                                class="badge absolute left-5 top-5 bottom-auto right-auto flex p-[8px_18px] bg-white rounded-[50px]">
                                <p class="text-xs leading-[18px] font-bold uppercase">{{ $article->category->name }}</p>
                            </div>
                            <img src="{{ Storage::url($article->thumbnail) }}" alt="thumbnail photo"
                                class="object-cover w-full h-full" />
                        </div>
                        <div class="flex flex-col gap-[6px]">
                            <h3 class="text-lg leading-[27px] font-bold">
                                {{ substr($article->name, 0, 55) }}{{ strlen($article->name) > 55 ? '...':''}}
                            </h3>
                            <p class="text-sm leading-[21px] text-[#A3A6AE]">
                                {{ $article->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </a>
                @empty
                    <p class="col-span-full text-lg text-red-500">Tidak ada artikel trending yang tersedia saat ini.</p>
                @endforelse
            </div>
        </section>
        <!-- Garis pemisah hanya ditampilkan jika ada hasil pencarian -->
        @else
        <div class="max-w-[1130px] mx-auto border-t border-[#FF69B4] mt-[50px] px-4"></div>
        @endif

        <!-- Hasil Pencarian (Updated Grid for responsiveness) -->
        <section id="search-result" class="max-w-[1130px] mx-auto flex items-start flex-col gap-[30px] mt-[50px] mb-[100px] px-4 {{ $articles->isEmpty() ? 'hidden' : '' }}">
            <h2 class="text-[26px] leading-[39px] font-bold">Search Result: <span>{{ ucfirst($keyword) }}</span></h2>
            {{-- Grid responsif: 1 kolom di mobile, 2 di tablet, 3 di desktop --}}
            <div id="search-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-[30px] w-full">
                @forelse ($articles as $article)
                <a href="{{ route('front.details', $article->slug) }}" class="card">
                    <div
                        class="flex flex-col gap-4 p-[26px_20px] transition-all duration-300 ring-1 ring-[#FF69B4] hover:ring-2 hover:ring-[#FF69B4] rounded-[20px] overflow-hidden bg-white">
                        <div class="thumbnail-container h-[200px] relative rounded-[20px] overflow-hidden">
                            <div
                                class="badge absolute left-5 top-5 bottom-auto right-auto flex p-[8px_18px] bg-white rounded-[50px]">
                                <p class="text-xs leading-[18px] font-bold uppercase">{{ $article->category->name }}</p>
                            </div>
                            <img src="{{ Storage::url($article->thumbnail) }}" alt="thumbnail photo"
                                class="object-cover w-full h-full" />
                        </div>
                        <div class="flex flex-col gap-[6px]">
                            <h3 class="text-lg leading-[27px] font-bold">
                                {{ substr($article->name, 0, 55) }}{{ strlen($article->name) > 55 ? '...':''}}
                            </h3>
                            <p class="text-sm leading-[21px] text-[#A3A6AE]">
                                {{ $article->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </a>
                @empty
                <p class="col-span-full text-lg text-red-500">belum ada artikel dengan keyword tersebut</p>
                @endforelse
            </div>
        </section>
    </body>
@endsection