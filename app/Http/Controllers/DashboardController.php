<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class DashboardController extends Controller
{
    public function index()
    {

        $id_user = Auth::id();

        // Ambil daftar jenis dokumen yang aktif
        $jenisDokumenAktif = DB::table('jenis_dokumens')
            ->where('status', 'Aktif')
            ->get(['id', 'jenis_dokumen']); // Ambil ID dan nama jenis dokumen yang aktif

        // Ambil dokumen yang sudah diupload oleh pengguna
        $dokumenUploaded = DB::table('dokumens')
            ->where('id_user', $id_user)
            ->pluck('id_dokumen'); // Ambil ID jenis dokumen yang sudah diupload

        // Menyaring jenis dokumen yang belum diupload
        $belumDiupload = $jenisDokumenAktif->filter(function ($item) use ($dokumenUploaded) {
            return !in_array($item->id, $dokumenUploaded->toArray());
        });

        // Jika tidak ada dokumen yang belum diupload
        if ($belumDiupload->isEmpty()) {
            // return response()->json(['message' => 'Semua dokumen telah diupload.'], 200);
            return view('backend.dashboard');
        } else {
            // Ambil nama-nama dokumen yang belum diupload
            $dokumenBelumDiupload = $belumDiupload->pluck('jenis_dokumen')->implode(', ');
            // dd($dokumenBelumDiupload);

            if (Auth::user()->role == 'Admin') {

                //total pegawai
                $total_pegawai = DB::table('users')->where('role', 'Pegawai')->count();

                //jenis dokumen
                $total_jenis_dokumen = DB::table('jenis_dokumens')->where('status', 'Aktif')->count();

                //total dokumen
                $total_dokumen = DB::table('dokumens')->count();

                //sebaran pegawai
                $total_asal_pegawai = DB::table('profils')
                    ->distinct('district_id') // Pastikan hanya menghitung `district_id` yang unik
                    ->count('district_id'); // Hitung jumlah `district_id` yang unik
            }

            return view('backend.dashboard', [
                'dokumenBelumDiupload' => $dokumenBelumDiupload ?? 0,
                'total_pegawai' => $total_pegawai ?? 0,
                'total_jenis_dokumen' => $total_jenis_dokumen ?? 0,
                'total_dokumen' => $total_dokumen ?? 0,
                'total_asal_pegawai' => $total_asal_pegawai ?? 0
            ]);
        }
    }

    public function dataPeta()
    {

        $districts = DB::table('districts')
            ->join('profils', 'districts.id', '=', 'profils.district_id')
            ->select(
                'districts.id',
                'districts.name',
                'districts.latitude',
                'districts.longitude',
                DB::raw('COUNT(profils.id) as total_employees')
            )
            ->groupBy('districts.id', 'districts.name', 'districts.latitude', 'districts.longitude')
            ->get();


        return response()->json($districts);

    }
}