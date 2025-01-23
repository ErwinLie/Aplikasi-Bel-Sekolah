<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Jadwal</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ url('jadwal') }}">Jadwal</a></div>
            </div>
        </div>

        <div class="col-lg-10 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Jadwal</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahJadwalModal">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    <div class="card-header-action">
                        <form class="form-inline">
                            <input id="jadwalSearchInput" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Event</th>
                                    <th scope="col">Sesi</th>
                                    <th scope="col">Jam Mulai</th>
                                    <th scope="col">Jam Selesai</th>
                                    <th scope="col">Bell</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="jadwalTableBody">
                                @php $no = 1; @endphp
                                @foreach ($jadwals as $jadwal)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $jadwal->nama_event }}</td>
                                        <td>{{ $jadwal->sesi }}</td>
                                        <td>{{ $jadwal->jam_mulai }}</td>
                                        <td>{{ $jadwal->jam_selesai }}</td>
                                        <td>
    <audio controls>
        <source src="{{ asset($jadwal->nama_bell) }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
</td>
                                        <td>
                                            <button class="btn btn-primary btn-action-edit" 
                                                    data-id_jadwal="{{ $jadwal->id_jadwal }}" 
                                                    data-id_event="{{ $jadwal->id_event }}" 
                                                    data-sesi="{{ $jadwal->sesi }}" 
                                                    data-jam_mulai="{{ $jadwal->jam_mulai }}" 
                                                    data-jam_selesai="{{ $jadwal->jam_selesai }}"
                                                    data-id_bell="{{ $jadwal->id_bell }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="{{ route('hapus_jadwal', $jadwal->id_jadwal) }}" 
                                               class="btn btn-danger btn-action" 
                                               data-toggle="tooltip" 
                                               title="Delete">
                                               <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="tambahJadwalModal" tabindex="-1" role="dialog" aria-labelledby="tambahJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahJadwalModalLabel">Tambah Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="tambahJadwalForm" action="{{ route('aksi_t_jadwal') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tambahIdEvent">Event</label>
                        <select class="form-control" id="tambahIdEvent" name="id_event" required>
                            <option value="">Pilih Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id_event }}">{{ $event->nama_event }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tambahSesi">Sesi</label>
                        <input type="text" class="form-control" id="tambahSesi" name="sesi" required>
                    </div>
                    <div class="form-group">
                        <label for="tambahJamMulai">Jam Mulai</label>
                        <input type="time" class="form-control" id="tambahJamMulai" name="jam_mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="tambahJamSelesai">Jam Selesai</label>
                        <input type="time" class="form-control" id="tambahJamSelesai" name="jam_selesai" required>
                    </div>
                    <div class="form-group">
                        <label for="tambahIdBell">Bell</label>
                        <select class="form-control" id="tambahIdBell" name="id_bell" required>
                            <option value="">Pilih Event</option>
                            @foreach ($bells as $bell)
                                <option value="{{ $bell->id_bell }}">{{ $bell->nama_bell }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Jadwal -->
<div class="modal fade" id="editJadwalModal" tabindex="-1" role="dialog" aria-labelledby="editJadwalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editJadwalModalLabel">Edit Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editJadwalForm" action="{{ route('aksi_e_jadwal') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_jadwal" id="editIdJadwal">
                    <div class="form-group">
                        <label for="editIdEvent">Event</label>
                        <select class="form-control" id="editIdEvent" name="id_event" required>
                            <option value="">Pilih Event</option>
                            @foreach ($events as $event)
                                <option value="{{ $event->id_event }}">{{ $event->nama_event }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSesi">Sesi</label>
                        <input type="text" class="form-control" id="editSesi" name="sesi" required>
                    </div>
                    <div class="form-group">
                        <label for="editJamMulai">Jam Mulai</label>
                        <input type="time" class="form-control" id="editJamMulai" name="jam_mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="editJamSelesai">Jam Selesai</label>
                        <input type="time" class="form-control" id="editJamSelesai" name="jam_selesai" required>
                    </div>
                    <!-- <div class="form-group">
                        <label for="editBell">Bell</label>
                        <input type="time" class="form-control" id="editBell" name="bell" required>
                    </div> -->
                    <div class="form-group">
                        <label for="editIdBell">Bell</label>
                        <select class="form-control" id="editIdBell" name="id_bell" required>
                            <option value="">Pilih Bell</option>
                            @foreach ($bells as $bell)
                                <option value="{{ $bell->id_bell }}">{{ $bell->nama_bell }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Event untuk tombol edit
        $('.btn-action-edit').on('click', function () {
            var id_jadwal = $(this).data('id_jadwal');
            var id_event = $(this).data('id_event');
            var sesi = $(this).data('sesi');
            var jam_mulai = $(this).data('jam_mulai');
            var jam_selesai = $(this).data('jam_selesai');
            var id_bell = $(this).data('id_bell');

            // Isi form modal dengan data tersebut
            $('#editIdJadwal').val(id_jadwal);
            $('#editIdEvent').val(id_event);
            $('#editSesi').val(sesi);
            $('#editJamMulai').val(jam_mulai);
            $('#editJamSelesai').val(jam_selesai);
            $('#editIdBell').val(id_bell);

            // Tampilkan modal
            $('#editJadwalModal').modal('show');
        });
    });
</script>
