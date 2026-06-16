<x-backend-layout>
    <x-slot name="title">Penilaian Paket Jasa - Admin MELODI</x-slot>
    <x-slot name="header">Penilaian Paket Jasa</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Kiri -->
        <div class="lg:col-span-1 bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden flex flex-col h-fit">
            <div class="p-6 border-b border-brown-800/30">
                <h3 class="font-serif text-lg text-gold-500">Form Penilaian</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('backend.spk.penilaian.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="label-melodi">Paket Jasa</label>
                        <select name="paket_id" id="paket_id" required class="select-melodi">
                            <option value="">-- Pilih Paket Jasa --</option>
                            @foreach($pakets as $paket)
                                <option value="{{ $paket->id }}" {{ (isset($editPenilaian) && $editPenilaian->paket_id == $paket->id) ? 'selected' : '' }}>
                                    {{ $paket->id }} - {{ $paket->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="label-melodi">Fasilitas</label>
                        <select name="nilai_fasilitas" required class="select-melodi">
                            <option value="">-- Pilih Fasilitas --</option>
                            <option value="0.61" {{ (isset($editPenilaian) && $editPenilaian->nilai_fasilitas == 0.61) ? 'selected' : '' }}>0.61 (Sangat Lengkap)</option>
                            <option value="0.30" {{ (isset($editPenilaian) && $editPenilaian->nilai_fasilitas == 0.30) ? 'selected' : '' }}>0.30 (Cukup Lengkap)</option>
                            <option value="0.09" {{ (isset($editPenilaian) && $editPenilaian->nilai_fasilitas == 0.09) ? 'selected' : '' }}>0.09 (Kurang Lengkap)</option>
                        </select>
                    </div>

                    <div>
                        <label class="label-melodi">Harga Sewa</label>
                        <select name="nilai_harga" required class="select-melodi">
                            <option value="">-- Pilih Harga Sewa --</option>
                            <option value="0.56" {{ (isset($editPenilaian) && $editPenilaian->nilai_harga == 0.56) ? 'selected' : '' }}>0.56 (Terjangkau)</option>
                            <option value="0.36" {{ (isset($editPenilaian) && $editPenilaian->nilai_harga == 0.36) ? 'selected' : '' }}>0.36 (Cukup Mahal)</option>
                            <option value="0.08" {{ (isset($editPenilaian) && $editPenilaian->nilai_harga == 0.08) ? 'selected' : '' }}>0.08 (Sangat Mahal)</option>
                        </select>
                    </div>

                    <div>
                        <label class="label-melodi">Kualitas</label>
                        <select name="nilai_kualitas" required class="select-melodi">
                            <option value="">-- Pilih Kualitas --</option>
                            <option value="0.71" {{ (isset($editPenilaian) && $editPenilaian->nilai_kualitas == 0.71) ? 'selected' : '' }}>0.71 (Sangat Bagus)</option>
                            <option value="0.23" {{ (isset($editPenilaian) && $editPenilaian->nilai_kualitas == 0.23) ? 'selected' : '' }}>0.23 (Cukup Bagus)</option>
                            <option value="0.06" {{ (isset($editPenilaian) && $editPenilaian->nilai_kualitas == 0.06) ? 'selected' : '' }}>0.06 (Kurang Bagus)</option>
                        </select>
                    </div>

                    <div class="pt-4 flex items-center gap-3">
                        <button type="submit" class="btn-gold flex-1 h-11 flex items-center justify-center text-center">SIMPAN</button>
                        <a href="{{ route('backend.spk.penilaian') }}" class="btn-outline flex-1 h-11 flex items-center justify-center text-center uppercase">Batal</a>
                    </div>
                </form>
                @if(isset($editPenilaian))
                    <form action="{{ route('backend.spk.penilaian.destroy', $editPenilaian->paket_id) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus data penilaian ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger w-full text-center py-2">Hapus</button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Tabel Kanan -->
        <div class="lg:col-span-2 bg-dark-100 rounded-xl border border-brown-800/30 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-brown-800/30 bg-dark-100/50">
                <h3 class="font-serif text-lg text-brown-100">Data Penilaian Paket</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-dark-200/50 text-brown-300 font-serif border-b border-brown-800/30">
                        <tr>
                            <th class="px-4 py-3 font-medium">ID Paket</th>
                            <th class="px-4 py-3 font-medium">Nama Paket</th>
                            <th class="px-4 py-3 font-medium">Fasilitas</th>
                            <th class="px-4 py-3 font-medium">Harga Sewa</th>
                            <th class="px-4 py-3 font-medium">Kualitas</th>
                            <th class="px-4 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brown-800/30">
                        @forelse($penilaians as $p)
                            <tr class="hover:bg-dark-200/30 transition-colors {{ isset($editPenilaian) && $editPenilaian->paket_id == $p->paket_id ? 'bg-gold-500/10' : '' }}">
                                <td class="px-4 py-3 text-brown-300">{{ $p->paket_id }}</td>
                                <td class="px-4 py-3 text-brown-100">{{ $p->paketBundling->nama }}</td>
                                <td class="px-4 py-3 text-brown-300">{{ $p->nilai_fasilitas }}</td>
                                <td class="px-4 py-3 text-brown-300">{{ $p->nilai_harga }}</td>
                                <td class="px-4 py-3 text-brown-300">{{ $p->nilai_kualitas }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('backend.spk.penilaian', ['edit' => $p->paket_id]) }}" class="text-gold-500 hover:text-gold-400 text-sm font-medium">Ubah</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-brown-400 italic">Belum ada data penilaian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-backend-layout>
