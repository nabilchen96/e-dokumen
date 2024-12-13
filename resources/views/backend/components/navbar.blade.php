<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('dashboard') }}">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>

        </li>
        @if (Auth::user()->role == 'Admin')
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                    <i class="icon-layout menu-icon"></i>
                    <span class="menu-title">Master</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('user') }}">User</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('jenis-dokumen') }}">Jenis Dokumen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('district') }}">Daerah</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('skpd') }}">SKPD</a>
                        </li>
                    </ul>
                </div>
            </li>
        @else
        @endif
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#tahap1" aria-expanded="false" aria-controls="ui-basic">
                <i class="bi bi-file-earmark menu-icon"></i>
                <span class="menu-title">Dokumen</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="tahap1">
                <ul class="nav flex-column sub-menu">
                    @php
                        // Ambil data profil user
                        $profil = DB::table('users')
                            ->leftjoin('profils', 'profils.id_user', '=', 'users.id')
                            ->where('users.id', Auth::id())
                            ->first();

                        // Ambil jenis dokumen yang sesuai
                        $jenis_dokumen = DB::table('jenis_dokumens')
                            ->where('status', 'Aktif')
                            ->where(function ($query) use ($profil) {
                                if(Auth::user()->role == "Admin"){
                                    $query;
                                }
                                elseif ($profil->status_pegawai == 'PNS') {
                                    $query->where('jenis_pegawai', 'like', '%PNS%')
                                        ->orWhere('jenis_pegawai', 'Semua');
                                } elseif ($profil->status_pegawai == 'P3K') {
                                    $query->where('jenis_pegawai', 'like', '%P3K%')
                                        ->orWhere('jenis_pegawai', 'Semua');
                                } elseif ($profil->status_pegawai == 'Honorer') {
                                    $query->where('jenis_pegawai', 'like', '%Honorer%')
                                        ->orWhere('jenis_pegawai', 'Semua');
                                }
                            })
                            ->get();
                    @endphp

                    @if($profil->status_pegawai || Auth::user()->role == 'Admin')
                        @foreach ($jenis_dokumen as $i)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('file-dokumen') }}?jenis_dokumen={{ $i->id }}">
                                    {{ $i->jenis_dokumen }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>

            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('profil') }}">
                <i class="bi bi-person menu-icon"></i>
                <span class="menu-title">Profil Pegawai</span>
            </a>

        </li>
    </ul>
</nav>