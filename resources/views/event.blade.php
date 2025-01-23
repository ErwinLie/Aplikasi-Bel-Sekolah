<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Event</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ url('event') }}">Event</a></div>
            </div>
        </div>

        <div class="col-lg-10 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Event</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahEventModal">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    <div class="card-header-action">
                        <form class="form-inline">
                            <input id="eventSearchInput" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
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
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="eventTableBody">
                                @php $no = 1; @endphp
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $event->nama_event }}</td>
                                        <td>
    <button class="btn btn-primary btn-action-edit" 
            data-id_event="{{ $event->id_event }}" 
            data-nama_event="{{ $event->nama_event }}">
        <i class="fas fa-edit"></i> Edit
    </button>
    <a href="{{ route('hapus_event', $event->id_event) }}" 
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

<!-- Modal Tambah Event -->
<div class="modal fade" id="tambahEventModal" tabindex="-1" role="dialog" aria-labelledby="tambahEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahEventModalLabel">Tambah Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="tambahEventForm" action="{{ route ('aksi_t_event') }}" method="POST">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tambahNamaEvent">Nama Event</label>
                        <input type="text" class="form-control" id="tambahNamaEvent" name="nama_event" required>
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

<!-- Modal Edit Event -->
<div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editEventForm" action="{{ route ('aksi_e_event') }}" method="POST">
            @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_event" id="editIdEvent">
                    <div class="form-group">
                        <label for="editNamaEvent">Nama Event</label>
                        <input type="text" class="form-control" id="editNamaEvent" name="nama_event" required>
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
    document.addEventListener('DOMContentLoaded', function() {
        const eventSearchInput = document.getElementById('eventSearchInput');

        // Filter table function
        eventSearchInput.addEventListener('keyup', function() {
            filterEventTable();
        });

        function filterEventTable() {
            const filter = eventSearchInput.value.toUpperCase();
            const rows = document.querySelectorAll('#eventTableBody tr');

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let isVisible = false;

                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toUpperCase().includes(filter)) {
                        isVisible = true;
                        break;
                    }
                }

                row.style.display = isVisible ? '' : 'none';
            });
        }
    });

    $(document).ready(function() {
        // Event untuk tombol edit
        $('.btn-action-edit').on('click', function() {
            // Ambil data dari atribut data-* di baris tabel
            var id_event = $(this).data('id_event');
            var nama_event = $(this).data('nama_event');

            // Isi form modal dengan data tersebut
            $('#editIdEvent').val(id_event);
            $('#editNamaEvent').val(nama_event);

            // Tampilkan modal
            $('#editEventModal').modal('show');
        });
    });
</script>
