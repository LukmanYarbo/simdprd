@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[['label' => 'Anggota', 'icon' => 'bi-people']]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Manajemen Anggota</h2>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.anggota.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-person-plus-fill me-2"></i>Tambah Anggota
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-lg h-100 bg-primary text-white overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-people-fill fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-white text-opacity-75 small fw-bold text-uppercase">Total Anggota</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-lg h-100 bg-success text-white overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-white bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="bi bi-person-check-fill fs-3"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-white text-opacity-75 small fw-bold text-uppercase">Anggota Aktif</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['aktif'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 bg-warning text-dark overflow-hidden">
                <div class="card-body position-relative">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-dark bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-person-x-fill fs-3 text-dark"></i>
                        </div>
                        <div>
                            <p class="mb-0 text-dark text-opacity-75 small fw-bold text-uppercase">Non-Aktif / Pensiun</p>
                            <h3 class="mb-0 fw-bold">{{ $stats['nonAktif'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 border-0">
            <h5 class="mb-0 fw-bold text-muted">Daftar Anggota</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="anggota-table">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="border-0">No</th>
                            <th class="border-0">Nama / NIK</th>
                            <th class="border-0">Jabatan</th>
                          
                            <th class="border-0">Status</th>
                            <th class="border-0">Kontak</th>
                            <th class="border-0 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal Keluarga -->
<div class="modal fade" id="modalKeluarga" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">Data Keluarga</h5>
                    <p class="text-muted mb-0 small" id="modalKeluargaSubtitle">Anggota</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Form Input -->
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Form Keluarga</h6>
                                <form id="formKeluarga">
                                    @csrf
                                    <input type="hidden" id="id_keluarga" name="id">
                                    <input type="hidden" id="id_anggota_keluarga" name="id_anggota">
                                    
                                    <div class="mb-2">
                                        <label class="form-label small">Hubungan</label>
                                        <select class="form-select form-select-sm" id="id_ikatan_keluarga" name="id_ikatan_keluarga" required></select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">NIK</label>
                                        <input type="text" class="form-control form-control-sm" id="nik_keluarga" name="nik" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Nama Lengkap</label>
                                        <input type="text" class="form-control form-control-sm" id="nama_keluarga" name="nama" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="form-label small">Tempat Lahir</label>
                                            <input type="text" class="form-control form-control-sm" id="tempat_lahir_keluarga" name="tempat_lahir" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">Tgl Lahir</label>
                                            <input type="date" class="form-control form-control-sm" id="tgl_lahir_keluarga" name="tgl_lahir" required>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Umur</label>
                                        <input type="text" class="form-control form-control-sm bg-light" id="umur_keluarga" readonly>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Jenis Kelamin</label>
                                        <select class="form-select form-select-sm" id="jk_keluarga" name="jk" required>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Status Kawin</label>
                                        <select class="form-select form-select-sm" id="id_status_kawin_keluarga" name="id_status_kawin" required></select>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Pekerjaan</label>
                                        <input type="text" class="form-control form-control-sm" id="pekerjaan_keluarga" name="pekerjaan" required>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6" id="div_status_anak">
                                            <label class="form-label small">Status Anak</label>
                                            <select class="form-select form-select-sm" id="status_anak" name="status_anak" required>
                                                <option value="AK">Anak Kandung</option>
                                                <option value="AA">Anak Angkat</option>
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">Tunjangan</label>
                                            <select class="form-select form-select-sm" id="status_tunjangan" name="status_tunjangan" required>
                                                <option value="Y">Ditunjang</option>
                                                <option value="T">Tidak Ditunjang</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3" id="div_sk_pengadilan">
                                        <label class="form-label small">No. SK Pengadilan (Opsional)</label>
                                        <input type="text" class="form-control form-control-sm" id="no_sk_pengadilan" name="no_sk_pengadilan">
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-sm btn-light border" onclick="resetFormKeluarga()">Reset</button>
                                        <button type="submit" class="btn btn-sm btn-primary" id="btnSaveKeluarga"><i class="bi bi-plus-lg"></i> Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- List Data -->
                    <div class="col-lg-8">
                        <h6 class="fw-bold mb-3">Ryawat Keluarga</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="tableKeluargaList">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Nama / NIK</th>
                                        <th>Hubungan</th>
                                        <th>JK</th>
                                        <th>Tunjangan</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populated via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pendidikan -->
<div class="modal fade" id="modalPendidikan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">Data Pendidikan</h5>
                    <p class="text-muted mb-0 small" id="modalPendidikanSubtitle">Anggota</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Form Input -->
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Form Pendidikan</h6>
                                <form id="formPendidikan" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="id_pendidikan" name="id">
                                    <input type="hidden" id="id_anggota_pendidikan" name="id_anggota">
                                    
                                    <div class="mb-2">
                                        <label class="form-label small">Tingkat Pendidikan <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="id_jenis_pendidikan" name="id_jenis_pendidikan" required></select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Nama Institusi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm" id="tempat_pendidikan" name="tempat_pendidikan" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="form-label small">Thn Masuk</label>
                                            <input type="number" class="form-control form-control-sm" id="tahun_masuk" name="tahun_masuk" placeholder="Ex: 2010">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">Thn Lulus</label>
                                            <input type="number" class="form-control form-control-sm" id="tahun_lulus" name="tahun_lulus" placeholder="Ex: 2014">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">No. Induk / NIM</label>
                                        <input type="text" class="form-control form-control-sm" id="no_induk" name="no_induk">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Jurusan</label>
                                        <input type="text" class="form-control form-control-sm" id="jurusan" name="jurusan">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Program Studi</label>
                                        <input type="text" class="form-control form-control-sm" id="program_studi" name="program_studi">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">Fakultas</label>
                                        <input type="text" class="form-control form-control-sm" id="fakultas" name="fakultas">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">No. Ijazah</label>
                                        <input type="text" class="form-control form-control-sm" id="no_ijazah" name="no_ijazah">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small">File Ijazah (PDF/JPG)</label>
                                        <input type="file" class="form-control form-control-sm" id="file_ijazah" name="file_ijazah">
                                        <div class="form-text small" id="current_file_ijazah"></div>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-sm btn-light border" onclick="resetFormPendidikan()">Reset</button>
                                        <button type="submit" class="btn btn-sm btn-primary" id="btnSavePendidikan"><i class="bi bi-plus-lg"></i> Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- List Data -->
                    <div class="col-lg-8">
                        <h6 class="fw-bold mb-3">Ryawat Pendidikan</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" id="tablePendidikanList">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Tingkat</th>
                                        <th>Institusi</th>
                                        <th>Thn Lulus</th>
                                        <th>Jurusan</th>
                                        <th>Ijazah</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populated via JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0;
        margin-left: 0;
    }
    .dataTables_wrapper .dataTables_filter input {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.4rem 1rem;
        background-color: #f8f9fa;
        margin-left: 10px;
    }
    .dataTables_wrapper .dataTables_length select {
        border-radius: 0.5rem;
        border: 1px solid #dee2e6;
        padding: 0.4rem 2rem 0.4rem 1rem;
        background-color: #f8f9fa;
    }
    #anggota-table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .stats-icon i {
        line-height: 1;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(function() {
    var table = $('#anggota-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('admin.anggota.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'nama_nik', name: 'nama_anggota'},
            {data: 'jabatan.nama', name: 'jabatan.nama'},
          
            {data: 'status', name: 'statusKeanggotaan.nama'},
            {data: 'kontak', name: 'email'},
            {data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end pe-4', render: function(data, type, row) {
                var btn = '<div class="btn-group">';
                btn += '<a href="#" data-id="' + row.id + '" class="btn btn-sm btn-info text-white btn-pendidikan" title="Kelola Pendidikan"><i class="bi bi-mortarboard-fill"></i></a>';
                btn += data; // Append existing actions
                btn += '</div>';
                return btn;
            }},
        ],
        order: [], // Disable initial sort to respect server-side ordering
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
            search: "_INPUT_",
            searchPlaceholder: "Cari data..."
        },
        drawCallback: function() {
            $('.dataTables_paginate > .pagination').addClass('pagination-sm justify-content-end');
        }
    });

    window.deleteAnggota = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data anggota ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.anggota.index') }}/" + id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw();
                        Swal.fire(
                            'Terhapus!',
                            response.message || 'Data anggota berhasil dihapus.',
                            'success'
                        );
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menghapus data.';
                        if (xhr.status === 403) {
                            errorMessage = 'Anda tidak memiliki hak akses untuk menghapus data ini.';
                        }
                        
                        Swal.fire(
                            'Gagal!',
                            errorMessage,
                            'error'
                        );
                    }
                });
            }
        });
    }

    // --- Keluarga Management Logic ---
    const modalKeluarga = new bootstrap.Modal(document.getElementById('modalKeluarga'));
    let currentAnggotaId = null;
    let currentKeluargaData = []; // Store current family data for validation

    $(document).on('click', '.btn-keluarga', function() {
        var id = $(this).data('id');
        currentAnggotaId = id;
        loadKeluarga(id);
    });

    function loadKeluarga(id) {
        $('#id_anggota_keluarga').val(id);
        $('#tableKeluargaList tbody').html('<tr><td colspan="5" class="text-center">Memuat data...</td></tr>');
        
        $.get("{{ url('admin/anggota') }}/" + id + "/keluarga", function(data) {
            $('#modalKeluargaSubtitle').text('Anggota: ' + data.anggota.nama_anggota);
            currentKeluargaData = data.keluarga; // Store for validation
            
            // Populate Selects
            populateSelect('#id_ikatan_keluarga', data.ikatan_keluarga, 'id', 'nama', 'Pilih Hubungan');
            populateSelect('#id_status_kawin_keluarga', data.status_kawin, 'id', 'nama', 'Pilih Status Kawin');

            // Filter Relationship based on Anggota Gender
            filterRelationship(data.anggota.jk);
            
            toggleChildFields(); // Initialize visibility

            renderKeluargaTable(data.keluarga);
            modalKeluarga.show();
        }).fail(function(xhr) {
            Swal.fire('Error', 'Gagal memuat data keluarga.', 'error');
        });
    }

    function filterRelationship(genderAnggota) {
        var options = $('#id_ikatan_keluarga option');
        options.show(); // Reset visibility
        
        options.each(function() {
            var text = $(this).text();
            if (genderAnggota === 'L' && text === 'Suami') {
                $(this).hide();
            } else if (genderAnggota === 'P' && text === 'Istri') {
                $(this).hide();
            }
        });
    }

    // Auto-fill Gender based on Relationship & Toggle Fields
    $('#id_ikatan_keluarga').change(function() {
        var text = $("#id_ikatan_keluarga option:selected").text();
        if (text === 'Suami') {
            $('#jk_keluarga').val('L');
        } else if (text === 'Istri') {
            $('#jk_keluarga').val('P');
        }
        toggleChildFields();
    });

    $('#status_anak').change(function() {
        toggleChildFields();
    });

    function toggleChildFields() {
        var relationText = $("#id_ikatan_keluarga option:selected").text();
        var isChild = relationText.toLowerCase().includes('anak');
        
        if (isChild) {
            $('#div_status_anak').show();
            $('#status_anak').prop('required', true);
            
            var statusAnak = $('#status_anak').val();
            if (statusAnak === 'AA') { // Anak Angkat
                $('#div_sk_pengadilan').show();
            } else {
                $('#div_sk_pengadilan').hide();
                $('#no_sk_pengadilan').val(''); // Clear value
            }
        } else {
            $('#div_status_anak').hide();
            $('#status_anak').prop('required', false);
            $('#status_anak').val(''); // Clear value

            $('#div_sk_pengadilan').hide();
            $('#no_sk_pengadilan').val('');
        }
    }

    // Calculate Age
    $('#tgl_lahir_keluarga').on('change', function() {
        var dob = new Date($(this).val());
        var today = new Date();
        if (isNaN(dob.getTime())) {
            $('#umur_keluarga').val('');
            return;
        }
        var age = today.getFullYear() - dob.getFullYear();
        var m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        $('#umur_keluarga').val(age + ' Tahun');
    });

    // Validate before Submit
    function validateAndSubmit(form) {
        try {
            var status = $('#status_tunjangan').val();
            
            // Rule: Max 2 children supported
            if (status === 'Y') {
                var currentId = $('#id_keluarga').val();
                var supportedChildren = currentKeluargaData.filter(function(item) {
                    // Safety check for item properties
                    var isTunjanganRef = item.status_tunjangan === 'Y';
                    var isNotCurrent = item.id != currentId;
                    var relationName = item.ikatan_keluarga ? item.ikatan_keluarga.nama : '';
                    var isChildRef = relationName.includes('Anak') || item.status_anak === 'AK' || item.status_anak === 'AA';
                    
                    return isTunjanganRef && isNotCurrent && isChildRef;
                }).length;

                var relationText = $("#id_ikatan_keluarga option:selected").text();
                var statusAnak = $("#status_anak").val();
                var isChild = relationText.includes('Anak') || statusAnak === 'AK' || statusAnak === 'AA';

                if (isChild && supportedChildren >= 2) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Batas Tunjangan',
                        text: 'Maksimal hanya 2 anak yang bisa mendapatkan tunjangan. Saat ini sudah ada ' + supportedChildren + ' anak ditunjang.',
                    });
                    return; // Stop submission
                }
            }

            // Rule: Max 21 years logic
            if (status === 'Y') {
                var ageStr = $('#umur_keluarga').val();
                var age = parseInt(ageStr);
                if (!isNaN(age)) {
                    var relationText = $("#id_ikatan_keluarga option:selected").text();
                    var isChild = relationText.toLowerCase().includes('anak');

                    if (isChild && age >= 21) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan Umur 21+ Tahun',
                            text: 'Anak berusia 21 tahun ke atas hanya boleh mendapatkan tunjangan jika TIDAK BEKERJA (Masih Sekolah/Kuliah). Apakah Anda yakin melanjutkan?',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, Lanjutkan',
                            cancelButtonText: 'Batalkan',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                performAjaxSubmit(form);
                            }
                        });
                        return; // Stop here, wait for user confirmation
                    }
                }
            }

            // If no validation errors or warnings blocking, proceed
            performAjaxSubmit(form);
        } catch (error) {
            console.error(error);
            Swal.fire('Error', 'Terjadi kesalahan validasi: ' + error.message, 'error');
        }
    }

    function performAjaxSubmit(formElement) {
        var id = $('#id_keluarga').val();
        var url = id ? "{{ url('admin/keluarga') }}/" + id : "{{ route('admin.keluarga.store') }}";
        var formData = new FormData(formElement);
        
        if (id) {
            formData.append('_method', 'PUT');
        }

        // Disable button to prevent double submit
        var btn = $('#btnSaveKeluarga');
        var originalBtnHtml = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

        $.ajax({
            data: formData,
            url: url,
            type: "POST",
            contentType: false,
            processData: false,
            success: function(response) {
                loadKeluarga(currentAnggotaId);
                resetFormKeluarga();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.success,
                    timer: 1000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                var msg = 'Terjadi kesalahan.';
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('[name="'+key+'"]').addClass('is-invalid');
                        $('[name="'+key+'"]').next('.invalid-feedback').text(value[0]);
                    });
                    msg = 'Silakan periksa kembali inputan Anda.';
                }
                Swal.fire('Gagal!', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    }

    function populateSelect(selector, data, valueField, textField, placeholder) {
        var select = $(selector);
        select.empty().append('<option value="">'+placeholder+'</option>');
        $.each(data, function(key, val) {
            select.append('<option value="'+val[valueField]+'">'+val[textField]+'</option>');
        });
    }

    function renderKeluargaTable(data) {
        var html = '';
        if(data.length === 0) {
            html = '<tr><td colspan="5" class="text-center text-muted">Belum ada data keluarga.</td></tr>';
        } else {
            $.each(data, function(index, item) {
                html += '<tr>';
                html += '<td>' + item.nama + '<br><small class="text-muted">' + item.nik + '</small></td>';
                html += '<td>' + (item.ikatan_keluarga ? item.ikatan_keluarga.nama : '-') + '</td>';
                html += '<td>' + (item.jk == 'L' ? 'Laki-laki' : 'Perempuan') + '</td>';
                html += '<td><span class="badge ' + (item.status_tunjangan == 'Y' ? 'bg-success' : 'bg-secondary') + '">' + (item.status_tunjangan == 'Y' ? 'Ditunjang' : 'Tidak') + '</span></td>';
                html += '<td class="text-end">';
                html += '<button type="button" class="btn btn-sm btn-light border-end btn-edit-keluarga" data-id="'+item.id+'"><i class="bi bi-pencil-square text-warning"></i></button>';
                html += '<button type="button" class="btn btn-sm btn-light btn-delete-keluarga" data-id="'+item.id+'"><i class="bi bi-trash3-fill text-danger"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tableKeluargaList tbody').html(html);
    }

    // Remove old change listener if any (it was removed in previous step, but ensuring code cleanliness)
    $('#status_tunjangan').off('change'); 

    $('#formKeluarga').submit(function(e) {
        e.preventDefault();
        validateAndSubmit(this);
    });

    $(document).on('click', '.btn-edit-keluarga', function() {
        var id = $(this).data('id');
        $.get("{{ url('admin/keluarga') }}/" + id + "/edit", function(data) {
            $('#id_keluarga').val(data.id);
            $('#id_anggota_keluarga').val(data.id_anggota);
            $('#nik_keluarga').val(data.nik);
            $('#nama_keluarga').val(data.nama);
            $('#tempat_lahir_keluarga').val(data.tempat_lahir);
            $('#tgl_lahir_keluarga').val(data.tgl_lahir.substring(0, 10)).trigger('change');
            $('#jk_keluarga').val(data.jk);
            $('#id_ikatan_keluarga').val(data.id_ikatan_keluarga);
            $('#id_status_kawin_keluarga').val(data.id_status_kawin);
            $('#pekerjaan_keluarga').val(data.pekerjaan);
            $('#status_anak').val(data.status_anak);
            $('#status_tunjangan').val(data.status_tunjangan);
            $('#no_sk_pengadilan').val(data.no_sk_pengadilan);
            
            $('#no_sk_pengadilan').val(data.no_sk_pengadilan);
            
            toggleChildFields(); // Update visibility based on loaded data

            $('#btnSaveKeluarga').html('<i class="bi bi-check-lg"></i> Update');
            $('.is-invalid').removeClass('is-invalid');
        });
    });

    $(document).on('click', '.btn-delete-keluarga', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Keluarga?',
            text: "Data ini akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/keluarga') }}/" + id,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(response) {
                        loadKeluarga(currentAnggotaId);
                        Swal.fire('Terhapus!', response.success, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
    });

    window.resetFormKeluarga = function() {
        $('#formKeluarga')[0].reset();
        $('#id_keluarga').val('');
        $('#id_anggota_keluarga').val(currentAnggotaId);
        $('#btnSaveKeluarga').html('<i class="bi bi-plus-lg"></i> Tambah');
        $('.is-invalid').removeClass('is-invalid');
    }

    // --- Pendidikan Management Logic ---
    const modalPendidikan = new bootstrap.Modal(document.getElementById('modalPendidikan'));
    let currentAnggotaIdPendidikan = null;

    $(document).on('click', '.btn-pendidikan', function(e) {

        e.preventDefault();
        var id = $(this).data('id');
        currentAnggotaIdPendidikan = id;
        loadPendidikan(id);
    });

    function loadPendidikan(id) {
        $('#id_anggota_pendidikan').val(id);
        $('#tablePendidikanList tbody').html('<tr><td colspan="6" class="text-center">Memuat data...</td></tr>');
        
        $.get("{{ url('admin/anggota') }}/" + id + "/pendidikan", function(data) {
            $('#modalPendidikanSubtitle').text('Anggota: ' + data.anggota.nama_anggota);
            
            // Populate Select
            var select = $('#id_jenis_pendidikan');
            select.empty().append('<option value="">Pilih Tingkat</option>');
            $.each(data.jenis_pendidikan, function(key, val) {
                select.append('<option value="'+val.id+'">'+val.nama+'</option>');
            });

            renderPendidikanTable(data.pendidikan);
            modalPendidikan.show();
        }).fail(function(xhr) {
            Swal.fire('Error', 'Gagal memuat data pendidikan.', 'error');
        });
    }

    function renderPendidikanTable(data) {
        var html = '';
        if(data.length === 0) {
            html = '<tr><td colspan="6" class="text-center text-muted">Belum ada data pendidikan.</td></tr>';
        } else {
            $.each(data, function(index, item) {
                var fileLink = item.file_ijazah ? '<a href="/storage/'+item.file_ijazah+'" target="_blank" class="btn btn-xs btn-outline-primary"><i class="bi bi-file-earmark-pdf"></i></a>' : '-';
                
                html += '<tr>';
                html += '<td>' + (item.jenis_pendidikan ? item.jenis_pendidikan.nama : '-') + '</td>';
                html += '<td>' + item.tempat_pendidikan + '</td>';
                html += '<td>' + (item.tahun_lulus ? item.tahun_lulus : '-') + '</td>';
                html += '<td>' + (item.jurusan ? item.jurusan : '-') + '</td>';
                html += '<td>' + fileLink + '</td>';
                html += '<td class="text-end">';
                html += '<button type="button" class="btn btn-sm btn-light border-end btn-edit-pendidikan" data-id="'+item.id+'"><i class="bi bi-pencil-square text-warning"></i></button>';
                html += '<button type="button" class="btn btn-sm btn-light btn-delete-pendidikan" data-id="'+item.id+'"><i class="bi bi-trash3-fill text-danger"></i></button>';
                html += '</td>';
                html += '</tr>';
            });
        }
        $('#tablePendidikanList tbody').html(html);
    }

    $('#formPendidikan').submit(function(e) {
        e.preventDefault();
        
        var id = $('#id_pendidikan').val();
        var url = id ? "{{ url('admin/pendidikan') }}/" + id : "{{ url('admin/anggota') }}/" + currentAnggotaIdPendidikan + "/pendidikan";
        var formData = new FormData(this);
        
        if (id) {
            formData.append('_method', 'PUT');
        }

        var btn = $('#btnSavePendidikan');
        var originalBtnHtml = btn.html();
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

        $.ajax({
            data: formData,
            url: url,
            type: "POST",
            contentType: false,
            processData: false,
            success: function(response) {
                loadPendidikan(currentAnggotaIdPendidikan);
                resetFormPendidikan();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.success,
                    timer: 1000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                var msg = 'Terjadi kesalahan.';
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('[name="'+key+'"]').addClass('is-invalid');
                        $('[name="'+key+'"]').next('.invalid-feedback').text(value[0]);
                    });
                    msg = 'Silakan periksa kembali inputan Anda.';
                }
                Swal.fire('Gagal!', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false).html(originalBtnHtml);
            }
        });
    });

    $(document).on('click', '.btn-edit-pendidikan', function() {
        var id = $(this).data('id');
        $.get("{{ url('admin/pendidikan') }}/" + id + "/edit", function(data) {
            $('#id_pendidikan').val(data.id);
            $('#id_anggota_pendidikan').val(data.id_anggota);
            $('#id_jenis_pendidikan').val(data.id_jenis_pendidikan);
            $('#tempat_pendidikan').val(data.tempat_pendidikan);
            $('#tahun_masuk').val(data.tahun_masuk);
            $('#tahun_lulus').val(data.tahun_lulus);
            $('#no_induk').val(data.no_induk);
            $('#jurusan').val(data.jurusan);
            $('#program_studi').val(data.program_studi);
            $('#fakultas').val(data.fakultas);
            $('#no_ijazah').val(data.no_ijazah);
            
            if(data.file_ijazah) {
                $('#current_file_ijazah').html('<a href="/storage/'+data.file_ijazah+'" target="_blank">Lihat File Saat Ini</a>');
            } else {
                $('#current_file_ijazah').html('');
            }

            $('#btnSavePendidikan').html('<i class="bi bi-check-lg"></i> Update');
            $('.is-invalid').removeClass('is-invalid');
        });
    });

    $(document).on('click', '.btn-delete-pendidikan', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Pendidikan?',
            text: "Data ini akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/pendidikan') }}/" + id,
                    type: 'DELETE',
                    data: { "_token": "{{ csrf_token() }}" },
                    success: function(response) {
                        loadPendidikan(currentAnggotaIdPendidikan);
                        Swal.fire('Terhapus!', response.success, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
    });

    window.resetFormPendidikan = function() {
        $('#formPendidikan')[0].reset();
        $('#id_pendidikan').val('');
        $('#id_anggota_pendidikan').val(currentAnggotaIdPendidikan);
        $('#current_file_ijazah').html('');
        $('#btnSavePendidikan').html('<i class="bi bi-plus-lg"></i> Tambah');
        $('.is-invalid').removeClass('is-invalid');
    }
});
</script>
@endpush



