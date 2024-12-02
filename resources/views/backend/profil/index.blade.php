@extends('backend.app')
@push('style')
    <style>
        #myTable_filter input {
            height: 29.67px !important;
        }

        #myTable_length select {
            height: 29.67px !important;
        }

        .btn {
            border-radius: 50px !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #9e9e9e21 !important;
        }

        td,
        th {
            font-size: 13.5px !important;
        }

        #map {
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

@endpush
@section('content')
<div class="row" style="margin-top: -200px;">
    <div class="col-md-12">
        <div class="row">
            <div class="col-12 col-xl-8 mb-xl-0">
                <h3 class="font-weight-bold">Data Profil</h3>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 mt-4">
        <div class="card w-100">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="myTable" class="table table-striped" style="width: 100%;">
                        <thead class="bg-info text-white">
                            <tr>
                                <th width="5%">No</th>
                                <th>Name</th>
                                <th>NIP/EMAIL/WA</th>
                                <th>Jenis Kelamin/Tempat, Tgl Lahir</th>
                                <th>No. WA</th>
                                <th>Peta</th>
                                <th width="5%"></th>
                                <th width="5%"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form">
                <div class="modal-header p-3">
                    <h5 class="modal-title m-2" id="exampleModalLabel">User Form</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input name="email" id="email" type="email" placeholder="email"
                            class="form-control form-control-sm" aria-describedby="emailHelp" required>
                        <span class="text-danger error" style="font-size: 12px;" id="email_alert"></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nama Lengkap</label>
                        <input name="name" id="name" type="text" placeholder="Nama Lengkap"
                            class="form-control form-control-sm" aria-describedby="emailHelp" required>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input name="password" id="password" type="password" placeholder="Password"
                            class="form-control form-control-sm" required>
                        <span class="text-danger error" style="font-size: 12px;" id="password_alert"></span>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Role</label>
                        <select name="role" class="form-control" id="role" required>
                            <option value="Admin">Admin</option>
                            <option value="Reviewer">Pegawai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="no_wa">No Whatsapp</label>
                        <input name="no_wa" id="no_wa" type="text" placeholder="082777120"
                            class="form-control form-control-sm" aria-describedby="emailHelp" required>
                    </div>

                </div>
                <div class="modal-footer p-3">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                    <button id="tombol_kirim" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalpeta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Peta Lokasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" style="aspect-ratio: 2.5/1 !important; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            getData()
        })

        function getData() {
            $("#myTable").DataTable({
                "ordering": false,
                ajax: '/data-profil',
                processing: true,
                'language': {
                    'loadingRecords': '&nbsp;',
                    'processing': 'Loading...'
                },
                columns: [{
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        return `<b>Name</b>: ${row.name} <br> 
                                        <b>Role</b>: ${row.role} <br>`;
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        return `<b>NIP</b>: ${row.nip} <br> 
                                        <b>Email</b>: ${row.email} <br> 
                                        <b>Whatsapp</b>: ${row.no_wa}`;
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        return `<b>Jenis Kelamin</b>: ${row.jenis_kelamin} <br> 
                                        <b>Tempat lahir</b>: ${row.tempat_lahir} <br> 
                                        <b>Tanggal Lahir</b>: ${row.tanggal_lahir}`;
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        return `<b>Alamat</b>: ${row.alamat} <br> 
                                        <b>Daerah</b>: ${row.district} <br>`;
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        return `<a data-toggle="modal" data-target="#modalpeta"
                                                data-lat="${row.latitude}" 
                                                data-lng="${row.longitude}" 
                                                href="javascript:void(0)">
                                                <i style="font-size: 1.5rem;" class="text-info bi bi-geo-alt"></i>
                                        </a>`;
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        return `<a data-toggle="modal" data-target="#modal"
                                            data-bs-id=` + (row.id) + ` href="javascript:void(0)">
                                            <i style="font-size: 1.5rem;" class="text-success bi bi-grid"></i>
                                        </a>`
                    }
                },
                {
                    render: function (data, type, row, meta) {
                        return `<a href="javascript:void(0)" onclick="hapusData(` + (row
                            .id) + `)">
                                    <i style="font-size: 1.5rem;" class="text-danger bi bi-trash"></i>
                                </a>`
                    }
                },
                ]
            })
        }

        $('#modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var recipient = button.data('bs-id') // Extract info from data-* attributes
            var cok = $("#myTable").DataTable().rows().data().toArray()

            let cokData = cok.filter((dt) => {
                return dt.id == recipient;
            })

            document.getElementById("form").reset();
            document.getElementById('id').value = ''
            $('.error').empty();

            if (recipient) {
                var modal = $(this)
                modal.find('#id').val(cokData[0].id)
                modal.find('#email').val(cokData[0].email)
                modal.find('#name').val(cokData[0].name)
                modal.find('#role').val(cokData[0].role)
                modal.find('#no_wa').val(cokData[0].no_wa)
            }
        })

        $('#modalpeta').on('shown.bs.modal', function (event) {
            const button = $(event.relatedTarget); // Tombol yang men-trigger modal
            const latitude = button.data('lat');  // Ambil latitude dari atribut data
            const longitude = button.data('lng'); // Ambil longitude dari atribut data

            // Bersihkan elemen peta jika sebelumnya sudah ada
            $('#map').html('');

            // Inisialisasi peta Leaflet
            const map = L.map('map').setView([latitude, longitude], 13);

            // Tambahkan layer peta dasar (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Tambahkan marker pada peta
            L.marker([latitude, longitude]).addTo(map)
                .bindPopup(`<b>Lokasi:</b><br>Lat: ${latitude}, Lng: ${longitude}`)
                .openPopup();

            // Pastikan dimensi peta diperbarui
            setTimeout(() => {
                map.invalidateSize();
            }, 100); // Tunggu sejenak agar modal selesai dibuka
        });

        form.onsubmit = (e) => {

            let formData = new FormData(form);

            e.preventDefault();

            document.getElementById("tombol_kirim").disabled = true;

            axios({
                method: 'post',
                url: formData.get('id') == '' ? '/store-user' : '/update-user',
                data: formData,
            })
                .then(function (res) {
                    //handle success         
                    if (res.data.responCode == 1) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: res.data.respon,
                            timer: 3000,
                            showConfirmButton: false
                        })

                        $("#modal").modal("hide");
                        $('#myTable').DataTable().clear().destroy();
                        getData()

                    } else {
                        //error validation
                        document.getElementById('password_alert').innerHTML = res.data.respon.password ?? ''
                        document.getElementById('email_alert').innerHTML = res.data.respon.email ?? ''
                        document.getElementById('no_wa_alert').innerHTML = res.data.respon.no_wa ?? ''
                    }

                    document.getElementById("tombol_kirim").disabled = false;
                })
                .catch(function (res) {
                    document.getElementById("tombol_kirim").disabled = false;
                    //handle error
                    console.log(res);
                });
        }

        hapusData = (id) => {
            Swal.fire({
                title: "Yakin hapus data?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonColor: '#3085d6',
                cancelButtonText: "Batal"

            }).then((result) => {

                if (result.value) {
                    axios.post('/delete-user', {
                        id
                    })
                        .then((response) => {
                            if (response.data.responCode == 1) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    timer: 2000,
                                    showConfirmButton: false
                                })

                                $('#myTable').DataTable().clear().destroy();
                                getData();

                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Gagal...',
                                    text: response.data.respon,
                                })
                            }
                        }, (error) => {
                            console.log(error);
                        });
                }

            });
        }
    </script>
@endpush