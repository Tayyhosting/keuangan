<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    // Menampilkan halaman utama (Dashboard & Riwayat)
    public function index()
    {
        // Ambil semua transaksi, urutkan dari yang terbaru
        $transaksis = Transaksi::latest()->get();

        // Hitung total saldo dan total cash secara otomatis
        // Pemasukan nambah, Pengeluaran ngurang
        $totalSaldo = Transaksi::where('sumber_dana', 'saldo')
            ->selectRaw("SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE -nominal END) as total")
            ->value('total') ?? 0;

        $totalCash = Transaksi::where('sumber_dana', 'cash')
            ->selectRaw("SUM(CASE WHEN jenis = 'pemasukan' THEN nominal ELSE -nominal END) as total")
            ->value('total') ?? 0;

        return view('welcome', compact('transaksis', 'totalSaldo', 'totalCash'));
    }

    // Menyimpan data transaksi baru dari form
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'sumber_dana' => 'required|in:saldo,cash',
            'nominal' => 'required|numeric|min:500',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $data = $request->all();
        $data['tanggal'] = now(); // Otomatis tanggal dan jam saat ini

        Transaksi::create($data);

        return redirect()->back()->with('success', 'Transaksi berhasil dicatat!');
    }

    // Menghapus transaksi jika ada yang salah catat
    public function destroy($id)
    {
        Transaksi::destroy($id);
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
    }
}