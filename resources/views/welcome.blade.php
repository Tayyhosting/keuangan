<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi KeuanganKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans pb-12">

    <div class="max-w-md mx-auto p-4 mt-4">
        <!-- Header -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-indigo-600">💰 Dompet Keuangan Riann</h1>
            <p class="text-xs text-slate-500">Pecahan fleksibel & waktu otomatis</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-100 border border-emerald-400 text-emerald-700 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Saldo & Cash -->
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-gradient-to-br from-indigo-500 to-blue-600 text-white p-4 rounded-2xl shadow-md">
                <p class="text-xs opacity-80 uppercase tracking-wider font-semibold">Sisa Saldo</p>
                <h2 class="text-xl font-bold mt-1">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</h2>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-4 rounded-2xl shadow-md">
                <p class="text-xs opacity-80 uppercase tracking-wider font-semibold">Sisa Cash</p>
                <h2 class="text-xl font-bold mt-1">Rp {{ number_format($totalCash, 0, ',', '.') }}</h2>
            </div>
        </div>

        <!-- Form Tambah Transaksi -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-6">
            <h2 class="font-bold text-base mb-4 text-slate-700">➕ Tambah Catatan Baru</h2>
            
            <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Jenis Transaksi -->
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Jenis</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="border rounded-xl p-2 text-center text-sm cursor-pointer hover:bg-slate-50 flex items-center justify-center gap-1 font-medium">
                            <input type="radio" name="jenis" value="pemasukan" required class="accent-indigo-600"> Pemasukan
                        </label>
                        <label class="border rounded-xl p-2 text-center text-sm cursor-pointer hover:bg-slate-50 flex items-center justify-center gap-1 font-medium">
                            <input type="radio" name="jenis" value="pengeluaran" required class="accent-indigo-600"> Pengeluaran
                        </label>
                    </div>
                </div>

                <!-- Sumber Dana -->
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Sumber Dana</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="border rounded-xl p-2 text-center text-sm cursor-pointer hover:bg-slate-50 flex items-center justify-center gap-1 font-medium">
                            <input type="radio" name="sumber_dana" value="saldo" id="sumberSaldo" class="accent-indigo-600" onclick="togglePecahan(false)"> Saldo (ATM)
                        </label>
                        <label class="border rounded-xl p-2 text-center text-sm cursor-pointer hover:bg-slate-50 flex items-center justify-center gap-1 font-medium">
                            <input type="radio" name="sumber_dana" value="cash" id="sumberCash" class="accent-indigo-600" onclick="togglePecahan(true)"> Uang Cash
                        </label>
                    </div>
                </div>

                <!-- Pilihan Pecahan Rupiah Cepat (Muncul khusus saat pilih Cash) -->
                <div id="boxPecahan" class="hidden bg-slate-50 p-3 rounded-xl border border-dashed border-slate-300">
                    <label class="block text-[11px] font-bold uppercase text-slate-400 mb-2">Pilih Pecahan Cepat / Ketik Manual</label>
                    <div class="grid grid-cols-4 gap-1.5">
                        <button type="button" onclick="setPecahan(1000)" class="bg-white border text-xs py-1.5 rounded-lg font-semibold hover:bg-indigo-50 hover:border-indigo-500">1rb</button>
                        <button type="button" onclick="setPecahan(2000)" class="bg-white border text-xs py-1.5 rounded-lg font-semibold hover:bg-indigo-50 hover:border-indigo-500">2rb</button>
                        <button type="button" onclick="setPecahan(5000)" class="bg-white border text-xs py-1.5 rounded-lg font-semibold hover:bg-indigo-50 hover:border-indigo-500">5rb</button>
                        <button type="button" onclick="setPecahan(10000)" class="bg-white border text-xs py-1.5 rounded-lg font-semibold hover:bg-indigo-50 hover:border-indigo-500">10rb</button>
                        <button type="button" onclick="setPecahan(20000)" class="bg-white border text-xs py-1.5 rounded-lg font-semibold hover:bg-indigo-50 hover:border-indigo-500">20rb</button>
                        <button type="button" onclick="setPecahan(50000)" class="bg-white border text-xs py-1.5 rounded-lg font-semibold hover:bg-indigo-50 hover:border-indigo-500">50rb</button>
                        <button type="button" onclick="setPecahan(100000)" class="bg-white border text-xs py-1.5 rounded-lg font-semibold hover:bg-indigo-50 hover:border-indigo-500">100rb</button>
                    </div>
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" id="inputNominal" placeholder="Bisa ketik bebas, misal: 3500" required
                        class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-xs font-semibold uppercase text-slate-500 mb-1">Keterangan / Beli Apa</label>
                    <input type="text" name="keterangan" placeholder="Contoh: Beli parkir / Es teh"
                        class="w-full border border-slate-300 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-sm transition shadow-md">
                    Simpan Transaksi
                </button>
            </form>
        </div>

        <!-- Riwayat Transaksi -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <h2 class="font-bold text-base mb-4 text-slate-700">📜 Riwayat Transaksi</h2>

            <div class="space-y-3">
                @forelse($transaksis as $item)
                    <div class="flex items-center justify-between border-b pb-3 last:border-b-0 last:pb-0">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $item->jenis == 'pemasukan' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                    {{ ucfirst($item->jenis) }}
                                </span>
                                <span class="text-xs text-slate-400 uppercase font-medium">({{ $item->sumber_dana }})</span>
                            </div>
                            <p class="text-sm font-medium mt-1 text-slate-700">{{ $item->keterangan ?: 'Tanpa keterangan' }}</p>
                            <span class="text-[10px] text-slate-400">
                                {{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->translatedFormat('d M Y, H:i') }}
                            </span>
                        </div>
                        <div class="text-right flex items-center gap-3">
                            <div>
                                <p class="text-sm font-bold {{ $item->jenis == 'pemasukan' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $item->jenis == 'pemasukan' ? '+' : '-' }} Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </p>
                            </div>
                            <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-rose-500 text-xs">✕</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-400 text-sm py-4">Belum ada transaksi tercatat.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Script buat atur tombol pecahan cepat -->
    <script>
        function togglePecahan(show) {
            const box = document.getElementById('boxPecahan');
            if (show) {
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        }

        function setPecahan(nominal) {
            document.getElementById('inputNominal').value = nominal;
        }
    </script>
</body>
</html>