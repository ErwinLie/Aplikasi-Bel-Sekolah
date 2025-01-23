<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('dashboard') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Bell</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ url('bell') }}">Bell</a></div>
            </div>
        </div>

        <div class="col-lg-10 col-md-12 col-12 col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Bell</h4>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#tambahBellModal">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                    <div class="card-header-action">
                        <form class="form-inline">
                            <input id="bellSearchInput" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        </form>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Bell</th>
                                    <th scope="col">File Bell</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bellTableBody">
                                @php $no = 1; @endphp
                                @foreach ($bells as $bell)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $bell->nama_bell }}</td>
                                        <td>
    <audio controls>
        <source src="{{ asset($bell->nama_bell) }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
</td>
<td>
<a href="{{ route('e_bell', $bell->id_bell) }}" 
   class="btn btn-primary btn-action mr-1" 
   data-toggle="tooltip" 
   title="Edit">
   <i class="fas fa-pencil-alt"></i>
</a>
</td>
<td>
                                            <a href="{{ route('hapus_bell', $bell->id_bell) }}" 
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

<!-- Modal Tambah Bell -->
<div class="modal fade" id="tambahBellModal" tabindex="-1" role="dialog" aria-labelledby="tambahBellModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBellModalLabel">Tambah Bell</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="tambahBellForm" action="{{ route('aksi_t_bell') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tambahNamaBell">Nama Bell</label>
                        <input type="text" class="form-control" id="tambahNamaBell" name="nama_bell" required>
                    </div>
                    <div class="form-group">
                        <label for="tambahFileBell">File Bell</label>
                        <input type="file" class="form-control" id="tambahFileBell" name="file_bell" required>
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

<!-- Modal Edit Bell -->
<div class="modal fade" id="editBellModal" tabindex="-1" role="dialog" aria-labelledby="editBellModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBellModalLabel">Edit Bell</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editBellForm" action="{{ route('aksi_e_bell') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <div class="modal-body">
                    <input type="hidden" name="id_bell" id="editIdBell">
                    <div class="form-group">
                        <label for="editNamaBell">Nama Bell</label>
                        <input type="text" class="form-control" id="editNamaBell" name="nama_bell" required>
                    </div>
                    <div class="form-group">
                        <label for="editFileBell">File Bell</label>
                        <input type="file" class="form-control" id="editFileBell" name="file_bell">
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
        const bellSearchInput = document.getElementById('bellSearchInput');

        // Filter table function
        bellSearchInput.addEventListener('keyup', function() {
            filterBellTable();
        });

        function filterBellTable() {
            const filter = bellSearchInput.value.toUpperCase();
            const rows = document.querySelectorAll('#bellTableBody tr');

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
            var id_bell = $(this).data('id_bell');
            var nama_bell = $(this).data('nama_bell');
            var file_bell = $(this).data('file_bell');

            $('#editIdBell').val(id_bell);
            $('#editNamaBell').val(nama_bell);
            $('#editFileBell').val(file_bell);

            $('#editBellModal').modal('show');
        });
    });
</script>
