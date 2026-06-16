<x-frontend-layout>
    <x-slot name="title">Rekomendasi Paket Bundling - MELODI</x-slot>

    <!-- Header Section -->
    <section class="pb-16 relative overflow-hidden" style="padding-top: 160px;">
        <div class="absolute inset-0 bg-[url('/public/images/pattern.png')] opacity-5"></div>
        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-gold-500/50 to-transparent"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12 relative z-10 text-center">
            <h1 class="text-3xl md:text-4xl font-serif text-gold-500 mb-4 tracking-wide">
                <span class="block text-brown-200 text-xs md:text-sm uppercase tracking-[0.25em] font-sans mb-3">Pilihan Terbaik Untuk Anda</span>
                Rekomendasi Paket
            </h1>
            <p class="text-brown-300 max-w-2xl mx-auto text-sm leading-relaxed mb-10">
                Sistem kami telah memilihkan paket bundling terbaik berdasarkan kriteria harga, kualitas, dan fasilitas.
            </p>
        </div>
    </section>

    <!-- Recommendation List Section -->
    <section class="pb-24 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-12">
            
            @if($pakets->isEmpty())
                <div class="text-center py-16 bg-dark-100/30 rounded-2xl border border-brown-800/30">
                    <svg class="w-16 h-16 mx-auto text-brown-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3 class="text-xl font-serif text-brown-200 mb-2">Belum Ada Rekomendasi</h3>
                    <p class="text-brown-400">Silakan sesuaikan filter atau hubungi admin kami.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($pakets as $paket)
                        <div class="group bg-dark-100 rounded-2xl border border-brown-800/30 overflow-hidden hover:border-gold-500/30 transition-all duration-500 flex flex-col relative">

                            <div class="relative h-48 overflow-hidden">
                                @if($paket->gambar)
                                    <img src="{{ asset('storage/' . $paket->gambar) }}" alt="{{ $paket->nama }}" 
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-dark-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-brown-600/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-dark-100 via-transparent to-transparent"></div>
                                
                                @php
                                    $rank = $paket->penilaian->ranking;

                                    if($rank == 1) {
                                        $bgStyle = 'background: linear-gradient(135deg, #FDE68A 0%, #D97706 100%); color: #1e1b18;';
                                    } elseif($rank == 2) {
                                        $bgStyle = 'background: linear-gradient(135deg, #F3F4F6 0%, #9CA3AF 100%); color: #1e1b18;';
                                    } elseif($rank == 3) {
                                        $bgStyle = 'background: linear-gradient(135deg, #FDBA74 0%, #B45309 100%); color: #ffffff;';
                                    } else {
                                        $bgStyle = 'background: linear-gradient(135deg, #4B5563 0%, #1F2937 100%); color: #ffffff;';
                                    }
                                @endphp
                                <div style="position: absolute; top: 20px; left: 0; z-index: 20;">
                                    <div style="{{ $bgStyle }} padding: 6px 16px 6px 12px; border-radius: 0 16px 16px 0; border-top: 1px solid rgba(255,255,255,0.3); border-right: 1px solid rgba(255,255,255,0.3); border-bottom: 1px solid rgba(255,255,255,0.3); box-shadow: 5px 5px 15px rgba(0,0,0,0.5); display: flex; align-items: flex-start; gap: 4px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        <span style="font-family: ui-sans-serif, system-ui, sans-serif; font-weight: 700; font-size: 16px; opacity: 0.8; margin-top: 2px;">#</span>
                                        <span style="font-family: ui-sans-serif, system-ui, sans-serif; font-weight: 900; font-size: 38px; font-style: italic; line-height: 0.9; letter-spacing: -1px;">{{ $rank }}</span>
                                    </div>
                                </div>

                                <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                                    <h3 class="text-xl font-serif text-brown-100 group-hover:text-gold-400 transition-colors">{{ $paket->nama }}</h3>
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <div class="text-2xl font-bold text-brown-100 mb-6">
                                    {{ $paket->formatted_harga }}
                                </div>

                                <div class="space-y-4 mb-6 flex-1">
                                    <!-- Score Progress -->
                                    <div>
                                        <div class="flex justify-between text-xs mb-1.5">
                                            <span class="text-brown-300">Skor Rekomendasi</span>
                                            <span class="text-gold-500 font-bold">{{ number_format($paket->penilaian->skor_akhir * 100, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-dark-200 rounded-full h-2 overflow-hidden border border-brown-800/30">
                                            <div class="bg-gradient-to-r from-gold-600 to-gold-400 h-2 rounded-full transition-all duration-1000" 
                                                 style="width: {{ $paket->penilaian->skor_akhir * 100 }}%"></div>
                                        </div>
                                    </div>

                                    <div class="border-t border-brown-800/30 pt-4 mt-4">
                                        @if($paket->acaras->count() > 0)
                                            <ul class="space-y-1 mb-3">
                                                @foreach($paket->acaras as $acara)
                                                <li class="flex items-center gap-2 text-brown-300 text-[11px] uppercase tracking-wider">
                                                    <svg class="w-3 h-3 text-gold-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $acara->nama }}
                                                </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <p class="text-xs text-brown-400 italic line-clamp-2">
                                            "{{ $paket->deskripsi }}"
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-auto pt-4 border-t border-brown-800/30">
                                    <a href="{{ url('/?paket_bundling=' . $paket->id . '#formulir-pemesanan') }}" 
                                       onclick="confirmBooking(event, this.href, '{{ addslashes($paket->nama) }}')" 
                                       class="block w-full py-3 px-4 bg-dark-200 hover:bg-gold-500 border border-brown-800/50 hover:border-gold-500 text-brown-200 hover:text-dark text-center text-sm uppercase tracking-wider font-bold transition-all duration-300 rounded-lg group-hover:shadow-[0_0_20px_rgba(201,168,76,0.2)]">
                                        Pesan Sekarang
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>

    <script>
        function confirmBooking(event, url, packageName) {
            event.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Booking',
                html: `<p class="text-[#a89880] font-sans text-sm mt-2">Apakah Anda ingin melanjutkan pemesanan untuk<br><b class="text-[#c9a84c] text-[15px] block mt-1">${packageName}</b>?</p>`,
                icon: 'question',
                showCancelButton: true,
                background: '#1a140e',
                color: '#f0e8d8',
                confirmButtonColor: '#c9a84c',
                cancelButtonColor: '#2e261f',
                confirmButtonText: 'Ya, Booking Sekarang',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'border-2 border-[#c9a84c]/30 rounded-2xl shadow-[0_0_50px_rgba(201,168,76,0.1)] bg-gradient-to-b from-[#1f1812] to-[#120d0a]',
                    title: 'font-serif text-[#c9a84c] text-2xl tracking-widest uppercase mt-4 block',
                    confirmButton: 'font-bold tracking-widest text-[#1a140e] px-6 py-3 rounded-md text-[11px] uppercase transition-all duration-300 shadow-md',
                    cancelButton: 'font-bold tracking-widest text-[#f0e8d8] px-6 py-3 rounded-md text-[11px] uppercase border border-[#5d5248] hover:bg-[#3a3028] transition-colors',
                    actions: 'gap-3 mt-6 flex flex-row-reverse',
                    icon: 'border-[#c9a84c] text-[#c9a84c]'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
</x-frontend-layout>
