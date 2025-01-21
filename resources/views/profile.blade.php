<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profile</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                <div class="breadcrumb-item">Profile</div>
            </div>
        </div>
        <div class="section-body">
            <h2 class="section-title">Hi, {{ $darren->username }}</h2>
            <p class="section-lead">
                Change information about yourself on this page.
            </p>

            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-5">
                    <div class="card profile-widget">
                        <div class="profile-widget-header text-center">
                            <!-- Profile Image -->
                            <?php
                                $foto_profil = ($darren->foto) ? asset ('img/avatar/' . $darren->foto) : asset ('img/avatar/user.png');
                            ?>
                            <img src="<?= $foto_profil ?>" alt="profile-image" class="rounded-circle profile-widget-picture mb-3" style="width: 150px; height: 150px;">

                            <!-- Form for Changing Profile Picture -->
                            <form action="{{ route('editfoto') }}" method="post" enctype="multipart/form-data">
                                <div>
                                    <input class="file-input" type="file" id="foto" name="foto" accept="image/*" style="display: none;">
                                    <label for="foto" class="btn btn-primary px-3 mt-2">Ganti Foto Profil</label>
                                </div>
                                <span id="file-name" class="d-block mt-2"></span>
                                <button id="saveButton" class="btn btn-primary mt-2" style="display: none;">Save</button>
                            </form>
                        </div>

                        <div class="profile-widget-description">
                            <div class="profile-widget-name">
                                {{ $darren->username }} 
                                <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div>{{ $darren->nis }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-lg-7">
                    <div class="card">
                        <form action="{{ route ('aksi_e_profile') }}" method="POST">
                            
                            <div class="card-header">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">                               
                                    <!-- <div class="form-group col-md-6 col-12">
                                        <label>Nis</label>
                                        <input type="text" class="form-control" value="{{$darren->nis}} readonly="">
                                        <div class="invalid-feedback">
                                            Please fill in the Nis
                                        </div>
                                    </div> -->
                                    <div class="form-group col-md-6 col-12">
                                        <label>Status</label>
                                        <input type="text" class="form-control" value="<?php
                                            if ($darren->id_level == 1) {
                                                echo "Admin";
                                            } else if ($darren->id_level == 2) {
                                                echo "Wakil";
                                            } else if ($darren->id_level == 3) {
                                                echo "Guru";
                                            } else if ($darren->id_level == 4) {
                                                echo "Siswa";
                                            } else {
                                                echo "Siswa";
                                            }
                                        ?>" readonly>
                                        <div class="invalid-feedback">
                                            Please fill in the status
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                               
                                    <div class="form-group col-md-6 col-12">
                                        <label>Username</label>
                                        <input type="text" class="form-control" name="username" value="{{ $darren->username }}" required="">
                                        <div class="invalid-feedback">
                                            Please fill in the username
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ $darren->email }}" required="">
                                        <div class="invalid-feedback">
                                            Please fill in the email
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="card-footer text-right">
                            <input type="hidden" name="id" value="<?= $darren->id_user ?>">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <!-- Button to trigger the modal -->
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#changePasswordModal">
                                    Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Change Password Modal (Bootstrap 4) -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Session messages -->
                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success" role="alert">
                            <?= session('success') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="{{ route ('aksi_changepass') }}">
                        <div class="form-group">
                            <label for="inputOldPassword">Old Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="inputOldPassword" name="old">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleOldPassword">
                                        <i class="fas fa-eye-slash" id="iconOldPassword"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputNewPassword">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="inputNewPassword" name="new">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleNewPassword">
                                        <i class="fas fa-eye-slash" id="iconNewPassword"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for File Input Handling and Modal Toggle -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    
    <script>
        document.getElementById('foto').addEventListener('change', function() {
            var fileInput = document.getElementById('foto');
            var saveButton = document.getElementById('saveButton');
            var fileNameDisplay = document.getElementById('file-name');
            
            // Show file name and Save button when a file is selected
            if (fileInput.files.length > 0) {
                fileNameDisplay.textContent = fileInput.files[0].name;
                saveButton.style.display = 'inline-block';
            } else {
                fileNameDisplay.textContent = '';
                saveButton.style.display = 'none';
            }
        });

        // Toggle password visibility
        function togglePasswordVisibility(inputId, iconId) {
            var passwordField = document.getElementById(inputId);
            var icon = document.getElementById(iconId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        document.getElementById('toggleOldPassword').addEventListener('click', function() {
            togglePasswordVisibility('inputOldPassword', 'iconOldPassword');
        });

        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            togglePasswordVisibility('inputNewPassword', 'iconNewPassword');
        });

    </script>
</div>
