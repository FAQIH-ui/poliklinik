<x-layouts.app title="Data Obat">

    {{-- Alert Notifikasi --}}
    @if(session('message'))
        <div class="mb-4 px-4 py-3 rounded-xl text-sm font-semibold
            {{ session('type') == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
            {{ session('message') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Data Obat</h2>
        <a href="{{ route('obat.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 
                  bg-primary hover:bg-primary/90 
                  text-white text-sm font-semibold 
                  rounded-xl transition">
            <i class="fas fa-plus text-xs"></i>
            Tambah Obat
        </a>
    </div>

    {{-- Card --}}
    <div class="card bg-base-100 shadow-md rounded-2 border">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table w-full">

                    {{-- Table Head --}}
                    <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Nama Obat</th>
                            <th class="px-6 py-4">Kemasan</th>
                            <th class="px-6 py-4">Harga</th>
                            <th class="px-6 py-4">Stok</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>

                    {{-- Table Body --}}
                    <tbody class="text-sm text-slate-700">
                        @forelse($obats as $obat)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                {{ $obat->nama_obat }}
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 text-xs font-semibold 
                                             rounded-full bg-green-100 text-green-600">
                                    {{ $obat->kemasan ?? '-' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 font-semibold text-slate-800">
                                Rp {{ number_format($obat->harga, 0, ',', '.') }}
                            </td>

                            {{-- Kolom Stok dengan indikator warna --}}
                            <td class="px-6 py-4">
                                @if($obat->isStokHabis())
                                    <span class="inline-block px-3 py-1 text-xs font-semibold 
                                                 rounded-full bg-red-100 text-red-600">
                                        Habis (0)
                                    </span>
                                @elseif($obat->isStokMenipis())
                                    <span class="inline-block px-3 py-1 text-xs font-semibold 
                                                 rounded-full bg-amber-100 text-amber-600">
                                        Menipis ({{ $obat->stok }})
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 text-xs font-semibold 
                                                 rounded-full bg-green-100 text-green-600">
                                        {{ $obat->stok }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2 flex-wrap">

                                    {{-- Tambah Stok --}}
                                    <button onclick="document.getElementById('modal-tambah-{{ $obat->id }}').showModal()"
                                        class="inline-flex items-center gap-1 px-3 py-2 
                                               bg-green-500 hover:bg-green-600 
                                               text-white text-xs font-semibold 
                                               rounded-lg transition">
                                        <i class="fas fa-plus text-xs"></i>
                                        Stok
                                    </button>

                                    {{-- Kurangi Stok --}}
                                    <button onclick="document.getElementById('modal-kurangi-{{ $obat->id }}').showModal()"
                                        class="inline-flex items-center gap-1 px-3 py-2 
                                               bg-blue-500 hover:bg-blue-600 
                                               text-white text-xs font-semibold 
                                               rounded-lg transition">
                                        <i class="fas fa-minus text-xs"></i>
                                        Stok
                                    </button>

                                    {{-- Edit --}}
                                    <a href="{{ route('obat.edit', $obat->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-2 
                                               bg-amber-500 hover:bg-amber-600 
                                               text-white text-xs font-semibold 
                                               rounded-lg transition">
                                        <i class="fas fa-pen text-xs"></i>
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('obat.destroy', $obat->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            onclick="return confirm('Yakin ingin menghapus obat ini?')"
                                            class="inline-flex items-center gap-1 px-3 py-2 
                                                   bg-red-500 hover:bg-red-600 
                                                   text-white text-xs font-semibold 
                                                   rounded-lg transition">
                                            <i class="fas fa-trash text-xs"></i>
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                        {{-- Modal Tambah Stok --}}
                        <dialog id="modal-tambah-{{ $obat->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg mb-4">Tambah Stok - {{ $obat->nama_obat }}</h3>
                                <form action="{{ route('obat.tambahStok', $obat->id) }}" method="POST">
                                    @csrf
                                    <div class="form-control mb-4">
                                        <label class="label">
                                            <span class="label-text">Jumlah Tambah Stok</span>
                                        </label>
                                        <input type="number" name="jumlah" min="1" 
                                               class="input input-bordered w-full" 
                                               placeholder="Masukkan jumlah" required>
                                    </div>
                                    <div class="modal-action">
                                        <button type="button"
                                            onclick="document.getElementById('modal-tambah-{{ $obat->id }}').close()"
                                            class="btn btn-ghost">Batal</button>
                                        <button type="submit" class="btn btn-success text-green-500">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </dialog>

                        {{-- Modal Kurangi Stok --}}
                        <dialog id="modal-kurangi-{{ $obat->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="font-bold text-lg mb-4">Kurangi Stok - {{ $obat->nama_obat }}</h3>
                                <p class="text-sm text-slate-500 mb-3">Stok saat ini: <strong>{{ $obat->stok }}</strong></p>
                                <form action="{{ route('obat.kurangiStok', $obat->id) }}" method="POST">
                                    @csrf
                                    <div class="form-control mb-4">
                                        <label class="label">
                                            <span class="label-text">Jumlah Kurangi Stok</span>
                                        </label>
                                        <input type="number" name="jumlah" min="1" max="{{ $obat->stok }}"
                                               class="input input-bordered w-full" 
                                               placeholder="Masukkan jumlah" required>
                                    </div>
                                    <div class="modal-action">
                                        <button type="button"
                                            onclick="document.getElementById('modal-kurangi-{{ $obat->id }}').close()"
                                            class="btn btn-ghost">Batal</button>
                                        <button type="submit" class="btn btn-error text-blue-500">Kurangi</button>
                                    </div>
                                </form>
                            </div>
                        </dialog>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-slate-400">
                                <i class="fas fa-inbox text-3xl mb-3 block"></i>
                                Belum ada data obat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-layouts.app>