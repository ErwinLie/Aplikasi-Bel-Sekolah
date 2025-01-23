<div class="main-content">
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('home/barang') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Edit Barang</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="{{ url('home/dashboard') }}">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="{{ url('home/bell') }}">Data Bell</a></div>
                <div class="breadcrumb-item">Edit Bell</div>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Form Edit Bell</h4>
                </div>
                <div class="card-body">
                    <form action="{{ url('home/aksi_e_bell') }}" method="post">
                        <input type="hidden" name="id_barang" value="<?= $bells->id_bell ?>">
                        <div class="form-group">
                            <label for="nama_bell">Nama Bell</label>
                            <input type="text" class="form-control" id="nama_bell" name="nama_bell" value="<?= $bells->nama_bell ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="file_bell">File Bell</label>
                            <input type="file" class="form-control" id="file_bell" name="file_bell" value="<?= $bells->file_bell ?>" required>
                        </div>
                        <div class="form-footer text-right">
                            <a href="{{ url('home/bell') }}" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
