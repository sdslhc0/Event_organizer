<x-frontend-layout>
    <x-slot:title>Riwayat Booking - MELODI</x-slot:title>

    @php
        $targetRateBooking = request('rate') ? $bookings->firstWhere('id', (int) request('rate')) : null;
        $autoOpenModal = $targetRateBooking && $targetRateBooking->paketBundling && !$targetRateBooking->kuisioner;
    @endphp

    <section class="pt-28 pb-24" x-data="{ 
        showModal: {{ $autoOpenModal ? 'true' : 'false' }}, 
        bookingId: {{ $autoOpenModal ? $targetRateBooking->id : 'null' }}, 
        paketId: {{ $autoOpenModal ? $targetRateBooking->paketBundling->id : 'null' }}, 
        paketNama: '{{ $autoOpenModal ? addslashes($targetRateBooking->paketBundling->nama) : '' }}',
        fasilitas: 'sangat_lengkap',
        harga: 'terjangkau',
        kualitas: 'sangat_bagus',
        komentar: ''
    }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-subtitle">Riwayat</div>
            <h1 class="text-3xl md:text-4xl font-serif text-brown-50 mb-2">Riwayat Booking Anda</h1>
            <p class="text-brown-400 mb-6">Lihat dan pantau status booking Anda.</p>

            {{-- Flash Alert --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-950/60 border border-green-700/50 text-green-300 flex items-center gap-3 shadow-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-950/60 border border-red-700/50 text-red-300 flex items-center gap-3 shadow-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            @endif

            @if($bookings->count() > 0)
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="bg-dark-100 border border-brown-800/30 rounded-xl overflow-hidden hover:border-gold-500/20 transition-all duration-300">
                            <div class="flex flex-col md:flex-row">
                                {{-- Left: Icon Placeholder --}}
                                <div class="md:w-48 md:flex-shrink-0">
                                    <div class="w-full h-48 md:h-full bg-dark-200 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-gold-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Details --}}
                                <div class="flex-1 p-6">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                        <div class="w-full">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="font-serif text-lg text-brown-100">{{ $booking->jenis_acara ?? 'Acara' }}</h3>
                                                @php
                                                    $status = $booking->detailBooking->status ?? 'pending';
                                                    $statusColors = [
                                                        'pending' => 'bg-yellow-900/30 border-yellow-700/30 text-yellow-400',
                                                        'di booking' => 'bg-blue-900/30 border-blue-700/30 text-blue-400',
                                                        'selesai' => 'bg-green-900/30 border-green-700/30 text-green-400',
                                                    ];
                                                @endphp
                                                <span class="px-2 py-0.5 text-[10px] uppercase tracking-widest border rounded-sm {{ $statusColors[$status] ?? $statusColors['pending'] }}">
                                                    {{ $status }}
                                                </span>
                                            </div>
                                            <p class="text-brown-500 text-xs uppercase tracking-widest mb-3">{{ $booking->nama_lengkap }} • {{ $booking->no_whatsapp }}</p>

                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                                                <div>
                                                    <span class="text-brown-500 text-xs block">Tanggal</span>
                                                    <span class="text-brown-200">{{ $booking->tanggal->format('d M Y') }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-brown-500 text-xs block">Waktu</span>
                                                    <span class="text-brown-200">{{ $booking->acara_mulai ?? '-' }} - {{ $booking->acara_selesai ?? '-' }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-brown-500 text-xs block">Jumlah Tamu</span>
                                                    <span class="text-brown-200">{{ $booking->jumlah_tamu ?? $booking->jumlah_porsi ?? '-' }} Paks</span>
                                                </div>
                                                <div>
                                                    <span class="text-brown-500 text-xs block">Total</span>
                                                    <span class="text-gold-500 font-semibold">{{ $booking->formatted_subtotal }}</span>
                                                </div>
                                                @if($booking->metode_pembayaran === 'dp_30')
                                                <div class="mt-2 sm:mt-0">
                                                    <span class="text-brown-500 text-xs block">DP (30%)</span>
                                                    <span class="text-gold-500 font-semibold">Rp {{ number_format($booking->subtotal * 0.3, 0, ',', '.') }}</span>
                                                </div>
                                                <div class="mt-2 sm:mt-0">
                                                    <span class="text-red-400/80 text-xs block italic">Kekurangan</span>
                                                    <span class="text-red-400/90 font-semibold">Rp {{ number_format($booking->subtotal * 0.7, 0, ',', '.') }}</span>
                                                </div>
                                                @endif
                                            </div>

                                            {{-- Layanan Terpilih --}}
                                            @if($booking->layanans && $booking->layanans->count() > 0)
                                                <div class="mt-4 pt-3 border-t border-brown-800/20">
                                                    <span class="text-brown-500 text-[10px] uppercase tracking-widest block mb-2">Layanan Tambahan</span>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($booking->layanans as $lay)
                                                            <span class="inline-block px-2 py-1 bg-dark-200 border border-brown-800/30 rounded text-[11px] text-brown-300">
                                                                {{ $lay->acara->nama ?? 'Layanan' }} 
                                                                @if($lay->qty > 1 || optional(optional($lay->acara)->kategori)->nama == 'Catering' || optional(optional($lay->acara)->kategori)->nama == 'Buku Undangan')
                                                                    <span class="text-brown-500">({{ $lay->qty }})</span>
                                                                @endif
                                                                — <span class="text-gold-500">Rp {{ number_format($lay->harga, 0, ',', '.') }}</span>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- Paket Bundling & Penilaian Kuisioner --}}
                                            @if($booking->paketBundling)
                                                <div class="mt-3 flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-brown-800/20">
                                                    <div>
                                                        <span class="text-brown-500 text-[10px] uppercase tracking-widest block mb-1">Bundling Exclusive</span>
                                                        <span class="inline-block px-2 py-1 bg-dark-200 border border-brown-800/30 rounded text-[11px] text-brown-300">
                                                            {{ $booking->paketBundling->nama }} — <span class="text-gold-500">Rp {{ number_format($booking->paketBundling->harga, 0, ',', '.') }}</span>
                                                        </span>
                                                    </div>

                                                    <div>
                                                        @if($booking->kuisioner)
                                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-950/60 border border-green-700/50 text-green-400 rounded-lg text-xs font-medium">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                                Sudah Dinilai
                                                            </span>
                                                        @else
                                                            <button type="button" 
                                                                @click="showModal = true; bookingId = {{ $booking->id }}; paketId = {{ $booking->paketBundling->id }}; paketNama = '{{ addslashes($booking->paketBundling->nama) }}'"
                                                                class="btn-gold text-xs py-1.5 px-4 flex items-center gap-1.5 shadow-md hover:shadow-gold-500/20">
                                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                                Beri Penilaian Paket
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            @if($booking->catatan)
                                                <p class="text-brown-400 text-sm mt-3 italic">"{{ $booking->catatan }}"</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-dark-100 border border-brown-800/20 rounded-xl">
                    <svg class="w-16 h-16 text-brown-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-brown-300 font-serif text-xl mb-2">Belum Ada Booking</h3>
                    <p class="text-brown-500 mb-6">Anda belum melakukan booking apapun.</p>
                    <a href="{{ route('home') }}#formulir-pemesanan" class="btn-gold">Buat Pesanan Sekarang</a>
                </div>
            @endif
        </div>

        {{-- MODAL KUISIONER PENILAIAN PAKET BUNDLING (REDESIGNED PREMIUM) --}}
        <div x-show="showModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                {{-- Backdrop --}}
                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click="showModal = false"
                    class="fixed inset-0 bg-black/85 backdrop-blur-md transition-opacity" 
                    aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal Panel --}}
                <div x-show="showModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-[#241a12] rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full border border-gold-500/30">
                    
                    <form action="{{ route('kuisioner.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_booking" :value="bookingId">
                        <input type="hidden" name="paket_id" :value="paketId">
                        <input type="hidden" name="fasilitas" :value="fasilitas">
                        <input type="hidden" name="harga" :value="harga">
                        <input type="hidden" name="kualitas" :value="kualitas">

                        {{-- Header Modal --}}
                        <div class="p-6 border-b border-brown-800/40 bg-[#1e150e]/90">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gold-500/15 border border-gold-500/30 flex items-center justify-center text-gold-400 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="text-gold-500 text-[11px] font-bold uppercase tracking-widest block">Kuisioner Kepuasan Pelanggan</span>
                                        <h3 class="font-serif text-2xl text-brown-50 leading-tight">Penilaian <span x-text="paketNama" class="text-gold-400"></span></h3>
                                    </div>
                                </div>
                                <button type="button" @click="showModal = false" class="w-8 h-8 rounded-lg bg-dark-200/50 hover:bg-dark-200 text-brown-400 hover:text-brown-100 flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <p class="text-brown-300 text-xs mt-3 leading-relaxed">
                                Pendapat Anda sangat berharga untuk menentukan akurasi rekomendasi paket MELODI bagi calon pelanggan lainnya.
                            </p>
                        </div>

                        {{-- Form Body --}}
                        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
                            
                            {{-- Pertanyaan 1: Fasilitas --}}
                            <div class="space-y-2.5">
                                <label class="flex items-center text-sm font-semibold text-brown-100">
                                    <span class="w-5 h-5 rounded-full bg-gold-500/20 text-gold-400 text-[11px] font-bold flex items-center justify-center mr-2 border border-gold-500/30">1</span>
                                    Bagaimana kelengkapan fasilitas yang disediakan?
                                </label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    {{-- Sangat Lengkap --}}
                                    <button type="button" 
                                        @click="fasilitas = 'sangat_lengkap'"
                                        :class="fasilitas === 'sangat_lengkap' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="fasilitas === 'sangat_lengkap'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">💎</span>
                                        <span class="text-xs font-semibold block">Sangat Lengkap</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Fasilitas prima</span>
                                    </button>

                                    {{-- Cukup Lengkap --}}
                                    <button type="button" 
                                        @click="fasilitas = 'cukup_lengkap'"
                                        :class="fasilitas === 'cukup_lengkap' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="fasilitas === 'cukup_lengkap'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">✨</span>
                                        <span class="text-xs font-semibold block">Cukup Lengkap</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Standar terpenuhi</span>
                                    </button>

                                    {{-- Kurang Lengkap --}}
                                    <button type="button" 
                                        @click="fasilitas = 'kurang_lengkap'"
                                        :class="fasilitas === 'kurang_lengkap' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="fasilitas === 'kurang_lengkap'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">⚪</span>
                                        <span class="text-xs font-semibold block">Kurang Lengkap</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Ada kekurangan</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Pertanyaan 2: Harga Sewa --}}
                            <div class="space-y-2.5 pt-2 border-t border-brown-800/30">
                                <label class="flex items-center text-sm font-semibold text-brown-100">
                                    <span class="w-5 h-5 rounded-full bg-gold-500/20 text-gold-400 text-[11px] font-bold flex items-center justify-center mr-2 border border-gold-500/30">2</span>
                                    Bagaimana kesesuaian harga sewa terhadap manfaat yang didapat?
                                </label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    {{-- Terjangkau --}}
                                    <button type="button" 
                                        @click="harga = 'terjangkau'"
                                        :class="harga === 'terjangkau' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="harga === 'terjangkau'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">🏷️</span>
                                        <span class="text-xs font-semibold block">Terjangkau</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Sangat sepadan</span>
                                    </button>

                                    {{-- Cukup Mahal --}}
                                    <button type="button" 
                                        @click="harga = 'cukup_mahal'"
                                        :class="harga === 'cukup_mahal' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="harga === 'cukup_mahal'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">⚖️</span>
                                        <span class="text-xs font-semibold block">Cukup Mahal</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Sesuai pasar</span>
                                    </button>

                                    {{-- Sangat Mahal --}}
                                    <button type="button" 
                                        @click="harga = 'sangat_mahal'"
                                        :class="harga === 'sangat_mahal' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="harga === 'sangat_mahal'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">💸</span>
                                        <span class="text-xs font-semibold block">Sangat Mahal</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Di atas budget</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Pertanyaan 3: Kualitas Layanan --}}
                            <div class="space-y-2.5 pt-2 border-t border-brown-800/30">
                                <label class="flex items-center text-sm font-semibold text-brown-100">
                                    <span class="w-5 h-5 rounded-full bg-gold-500/20 text-gold-400 text-[11px] font-bold flex items-center justify-center mr-2 border border-gold-500/30">3</span>
                                    Bagaimana kualitas keseluruhan layanan paket selama acara?
                                </label>
                                <div class="grid grid-cols-3 gap-2.5">
                                    {{-- Sangat Bagus --}}
                                    <button type="button" 
                                        @click="kualitas = 'sangat_bagus'"
                                        :class="kualitas === 'sangat_bagus' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="kualitas === 'sangat_bagus'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">🏆</span>
                                        <span class="text-xs font-semibold block">Sangat Bagus</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Sangat puas</span>
                                    </button>

                                    {{-- Cukup Bagus --}}
                                    <button type="button" 
                                        @click="kualitas = 'cukup_bagus'"
                                        :class="kualitas === 'cukup_bagus' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="kualitas === 'cukup_bagus'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">👍</span>
                                        <span class="text-xs font-semibold block">Cukup Bagus</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Cukup puas</span>
                                    </button>

                                    {{-- Kurang Bagus --}}
                                    <button type="button" 
                                        @click="kualitas = 'kurang_bagus'"
                                        :class="kualitas === 'kurang_bagus' ? 'bg-gold-500/20 border-gold-500 text-gold-300 shadow-[0_0_15px_rgba(212,168,67,0.2)] ring-1 ring-gold-500/50' : 'bg-[#1b130c] border-brown-800/80 text-brown-400 hover:border-gold-500/40 hover:text-brown-200'"
                                        class="relative flex flex-col items-center justify-center p-3 text-center rounded-xl border transition-all duration-200 cursor-pointer">
                                        <span x-show="kualitas === 'kurang_bagus'" class="absolute -top-1.5 -right-1.5 w-4 h-4 rounded-full bg-gold-500 text-black flex items-center justify-center text-[10px] font-bold shadow-md">✓</span>
                                        <span class="text-base mb-1">⚠️</span>
                                        <span class="text-xs font-semibold block">Kurang Bagus</span>
                                        <span class="text-[10px] text-brown-400 opacity-80 mt-0.5">Perlu evaluasi</span>
                                    </button>
                                </div>
                            </div>

                            {{-- Masukan Tambahan --}}
                            <div class="space-y-2 pt-2 border-t border-brown-800/30">
                                <label class="block text-xs font-semibold text-brown-300 uppercase tracking-wider">
                                    Catatan & Kesan Pribadi (Opsional)
                                </label>
                                <textarea name="komentar" rows="3" 
                                    style="background-color: #1a120b !important; color: #f5ebe4 !important;"
                                    class="w-full border border-brown-800/80 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold-500 focus:ring-1 focus:ring-gold-500/30 transition-all placeholder-brown-600 shadow-inner" 
                                    placeholder="Ceritakan pengalaman Anda menggunakan paket ini..."></textarea>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="px-6 py-4 bg-[#1e150e]/95 border-t border-brown-800/40 flex items-center justify-end gap-3">
                            <button type="button" @click="showModal = false" class="px-4 py-2.5 text-xs uppercase tracking-wider text-brown-300 hover:text-brown-100 font-semibold transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="btn-gold text-xs py-2.5 px-6 flex items-center gap-2 shadow-lg shadow-gold-500/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                                <span>Kirim Penilaian</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-frontend-layout>
