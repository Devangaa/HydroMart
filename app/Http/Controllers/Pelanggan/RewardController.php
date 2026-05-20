<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use App\Models\PenukaranReward;
use App\Models\Reward;
use App\Models\RiwayatPoin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Modul: Pelanggan - Reward
 * Fitur: Lihat reward, detail reward, klaim reward, dan riwayat reward milik user.
 */
class RewardController extends Controller
{
    /**
     * Bagian: Listing reward yang tersedia.
     */
    public function index()
    {
        $rewards = Reward::where('is_delete', 0)->get();

        return view('pelanggan.reward.index', compact('rewards'));
    }

    /**
     * Bagian: Detail reward.
     */
    public function show($id)
    {
        $reward = Reward::where('is_delete', 0)->findOrFail($id);

        return view('pelanggan.reward.show', compact('reward'));
    }

    /**
     * Bagian: Riwayat reward milik pelanggan.
     * Alur: sinkronisasi kedaluwarsa -> kelompokkan status reward.
     */
    public function myRewards()
    {
        $user = Auth::user();

        // Pengecekan reward kadaluarsa (sama seperti sistem pembayaran)
        PenukaranReward::where('id_akun', $user->id)
            ->where('status_reward', 'Tersedia')
            ->where('batas_berlaku', '<', now())
            ->update(['status_reward' => 'Kedaluwarsa']);

        $myRewards = PenukaranReward::with('reward')
            ->where('id_akun', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeRewards = $myRewards->filter(fn ($r) => $r->status_reward === 'Tersedia');
        $usedRewards = $myRewards->filter(fn ($r) => $r->status_reward === 'Digunakan');
        $expiredRewards = $myRewards->filter(fn ($r) => $r->status_reward === 'Kedaluwarsa');

        return view('pelanggan.reward.my-rewards', compact('activeRewards', 'usedRewards', 'expiredRewards'));
    }

    /**
     * Bagian: Klaim reward oleh pelanggan.
     * Alur: validasi poin -> transaksi potong poin -> simpan penukaran.
     */
    public function claim($id)
    {
        $reward = Reward::where('is_delete', 0)->findOrFail($id);
        $user = Auth::user();

        if ($user->poin_reward < $reward->poin_diperlukan) {
            return back()->with('error', 'Poin Anda tidak mencukupi untuk mengklaim reward ini.');
        }

        DB::beginTransaction();
        try {
            // Potong poin user
            $user->poin_reward -= $reward->poin_diperlukan;
            $user->save();

            // Catat di riwayat poin
            RiwayatPoin::create([
                'id_akun' => $user->id,
                'jumlah_poin' => -$reward->poin_diperlukan,
                'keterangan' => 'Penukaran Reward: '.$reward->nama_reward,
            ]);

            // Buat penukaran reward
            PenukaranReward::create([
                'id_akun' => $user->id,
                'id_reward' => $reward->id,
                'status_reward' => 'Tersedia',
                'tanggal_klaim' => now(),
                'batas_berlaku' => now()->addDays($reward->durasi_reward),
            ]);

            DB::commit();

            return redirect()->route('reward.my-rewards')->with('success', 'Reward berhasil diklaim!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan saat mengklaim reward. Silakan coba lagi.');
        }
    }
}
