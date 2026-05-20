<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Modul: Admin - Manajemen Reward
 * Fitur: CRUD reward, soft delete/restore, dan monitoring reward pelanggan.
 */
class RewardController extends Controller
{
    /**
     * Bagian: Listing reward admin beserta statistik.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');

        $query = Reward::query();

        if ($status === 'terhapus') {
            $query->where('is_delete', true);
        } else {
            $query->where('is_delete', false);
        }

        if ($search) {
            $query->where('nama_reward', 'like', "%{$search}%");
        }

        $rewards = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total' => Reward::count(),
            'aktif' => Reward::where('is_delete', false)->count(),
            'dihapus' => Reward::where('is_delete', true)->count(),
        ];

        return view('admin.reward.index', compact('rewards', 'stats'));
    }

    /**
     * Bagian: Simpan reward baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_reward' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'diskon' => 'required|numeric|min:0',
            'minimal_pembelian' => 'required|numeric|min:0|gte:diskon',
            'poin_diperlukan' => 'required|integer|min:0',
            'durasi_reward' => 'required|integer|min:1',
        ], [
            'minimal_pembelian.gte' => 'Minimal transaksi tidak boleh lebih kecil dari diskon.',
        ]);

        Reward::create($request->all());

        return back()->with('success', 'Reward berhasil ditambahkan.');
    }

    /**
     * Bagian: Update reward atau restore reward terhapus.
     */
    public function update(Request $request, $id)
    {
        $reward = Reward::findOrFail($id);

        if ($request->has('restore')) {
            $reward->update(['is_delete' => false]);

            return back()->with('success', 'Reward berhasil dipulihkan.');
        }

        $request->validate([
            'nama_reward' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'diskon' => 'required|numeric|min:0',
            'minimal_pembelian' => 'required|numeric|min:0|gte:diskon',
            'poin_diperlukan' => 'required|integer|min:0',
            'durasi_reward' => 'required|integer|min:1',
        ], [
            'minimal_pembelian.gte' => 'Minimal transaksi tidak boleh lebih kecil dari diskon.',
        ]);

        $reward->update($request->all());

        return back()->with('success', 'Reward berhasil diperbarui.');
    }

    /**
     * Bagian: Soft delete reward.
     */
    public function destroy($id)
    {
        $reward = Reward::findOrFail($id);
        $reward->update(['is_delete' => true]);

        return back()->with('success', 'Reward berhasil dihapus.');
    }

    /**
     * Bagian: Listing pelanggan untuk modul reward.
     */
    public function customers(Request $request)
    {
        $search = $request->get('search');
        $query = User::where('role', 'pelanggan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.reward.customers.index', compact('customers'));
    }

    /**
     * Bagian: Detail reward milik pelanggan (aktif, digunakan, kedaluwarsa).
     */
    public function customerShow($id)
    {
        $customer = User::with(['penukaranRewards.reward', 'riwayatPoins'])->findOrFail($id);

        $rewards = $customer->penukaranRewards;

        // Auto-expire rewards
        foreach ($rewards as $penukaran) {
            if ($penukaran->status_reward === 'Tersedia' && $penukaran->batas_berlaku < now()) {
                $penukaran->update(['status_reward' => 'Kedaluwarsa']);
            }
        }

        // Refresh after auto-expire
        $customer->load('penukaranRewards.reward');
        $rewards = $customer->penukaranRewards;

        $activeRewards = $rewards->where('status_reward', 'Tersedia');
        $usedRewards = $rewards->where('status_reward', 'Digunakan');
        $expiredRewards = $rewards->where('status_reward', 'Kedaluwarsa');

        return view('admin.reward.customers.show', compact('customer', 'activeRewards', 'usedRewards', 'expiredRewards'));
    }
}
