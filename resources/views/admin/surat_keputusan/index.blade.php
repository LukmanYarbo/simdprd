@extends('layouts.admin')

@section('breadcrumbs')
<x-breadcrumbs :items="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
    ['label' => 'Surat Keputusan', 'icon' => 'ti ti-award']
]" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h2 class="h4 mb-0 fw-bold">Surat Keputusan</h2>
            <p class="text-muted small mb-0">Kelola SK dan struktur organisasi setiap Alat Kelengkapan DPRD</p>
        </div>
        <div class="col-auto">
            @can('create surat_keputusan')
            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm transition-base btn-add">
                <i class="ti ti-plus me-1"></i>Tambah SK
            </button>
            @endcan
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-2 px-3">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold" id="tab-data-btn" data-bs-toggle="tab" data-bs-target="#tabData" type="button" role="tab">
                        <i class="ti ti-list-details me-1"></i>Data Surat Keputusan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold" id="tab-struktur-btn" data-bs-toggle="tab" data-bs-target="#tabStruktur" type="button" role="tab">
                        <i class="ti ti-sitemap me-1"></i>Struktur Organisasi
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-3 p-md-4">
            <div class="tab-content">
                {{-- ================= TAB DATA ================= --}}
                <div class="tab-pane fade show active" id="tabData" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100" id="surat-keputusan-table">
                            <thead class="bg-body-tertiary text-muted">
                                <tr>
                                    <th class="border-0" width="5%">No</th>
                                    <th class="border-0">No. SK</th>
                                    <th class="border-0">Tanggal SK</th>
                                    <th class="border-0">Nama Alat Kelengkapan</th>
                                    <th class="border-0">Jumlah Anggota</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">File</th>
                                    <th class="border-0 text-end pe-4" width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                    {{-- PDF Preview Panel --}}
                    <div class="card border shadow-lg mt-4" id="pdfPreviewCard" style="display:none;">
                        <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-danger"><i class="ti ti-file-earmark-pdf me-2"></i>Preview SK: <span id="pdfTitle"></span></h6>
                            <button class="btn-icon-modern" onclick="closePdfPreview()">
                                <i class="ti ti-x"></i>
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <iframe id="pdfFrame" src="" style="width:100%; height:80vh; border:none;"></iframe>
                        </div>
                    </div>
                </div>

                {{-- ================= TAB STRUKTUR ORGANISASI ================= --}}
                <div class="tab-pane fade" id="tabStruktur" role="tabpanel">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <p class="text-muted small mb-0">
                            <i class="ti ti-info-circle me-1"></i>Struktur organisasi berdasarkan <strong>SK Aktif</strong> masing-masing Alat Kelengkapan DPRD.
                        </p>
                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-3" id="btnReloadStruktur">
                            <i class="ti ti-refresh me-1"></i>Muat Ulang
                        </button>
                    </div>
                    <div id="strukturContainer">
                        <div class="text-center text-muted py-5">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Memuat struktur organisasi...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form SK -->
<div class="modal fade" id="modalSuratKeputusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white pt-4 px-4 pb-3 rounded-top">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width:38px;height:38px;">
                        <i class="ti ti-file-text fs-5"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalTitle">Tambah Surat Keputusan</h5>
                        <small class="opacity-75" id="modalSubtitle">Lengkapi data surat keputusan di bawah ini</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formSuratKeputusan" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id">

                    <h6 class="text-uppercase text-muted fw-bold small mb-3"><i class="ti ti-id-badge me-1"></i>Informasi SK</h6>
                    <div class="row g-3 mb-1">
                        <div class="col-md-7">
                            <label for="no_sk" class="form-label fw-semibold">No. SK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="no_sk" name="no_sk" placeholder="Contoh: 001/SK/DPRD/2026" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-5">
                            <label for="tgl_sk" class="form-label fw-semibold">Tanggal SK <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tgl_sk" name="tgl_sk" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-7">
                            <label for="id_alat_kelengkapan" class="form-label fw-semibold">Alat Kelengkapan <span class="text-danger">*</span></label>
                            <select class="form-select" id="id_alat_kelengkapan" name="id_alat_kelengkapan" required>
                                <option value="">Pilih Alat Kelengkapan</option>
                                @foreach($alatKelengkapans as $ak)
                                    <option value="{{ $ak->id }}">{{ strtoupper($ak->nama) }}{{ $ak->ket ? ' - '.$ak->ket : '' }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-5">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="A">Aktif</option>
                                <option value="T">Tidak Aktif</option>
                            </select>
                            <small class="text-muted d-block mt-1"><i class="ti ti-info-circle me-1"></i>Hanya boleh ada satu SK aktif per Alat Kelengkapan.</small>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3"><i class="ti ti-file-upload me-1"></i>Dokumen &amp; Keterangan</h6>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="ket_sk" class="form-label fw-semibold">Keterangan SK</label>
                            <textarea class="form-control" id="ket_sk" name="ket_sk" rows="3" placeholder="Tentang / dasar penetapan SK..."></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label for="file_sk" class="form-label fw-semibold">File SK (PDF/DOC/IMG)</label>
                            <input class="form-control" type="file" id="file_sk" name="file_sk">
                            <small class="text-muted d-none" id="fileHelp">Biarkan kosong jika tidak ingin mengubah file.</small>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <div class="modal-footer border-top-0 pb-0 px-0 pt-4">
                        <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm transition-base" id="btnSave">
                            <i class="ti ti-device-floppy me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Anggota -->
<div class="modal fade" id="modalAnggota" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom-0 pt-4 px-4 pb-2">
                <div>
                    <h5 class="modal-title fw-bold text-primary" id="modalAnggotaTitle">Kelola Anggota</h5>
                    <p class="text-muted mb-0 small" id="modalAnggotaSubtitle">Surat Keputusan</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Form Tambah Anggota -->
                <div class="card bg-body-tertiary border-0 mb-4" id="formAnggotaCard">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="ti ti-user-plus me-1"></i>Tambah Anggota</h6>
                        <form id="formAnggota" class="row g-2">
                            @csrf
                            <input type="hidden" id="id_surat_keputusan_anggota" name="id_surat_keputusan">
                            <!-- Nama Komisi row (only visible for Komisi-type SK) -->
                            <div class="col-12 d-none" id="nama_komisi_row">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ti ti-diagram-3"></i></span>
                                    <input type="text" class="form-control" id="nama_komisi" name="nama_komisi"
                                        list="namaKomisiList" autocomplete="off"
                                        placeholder="Nama Komisi, contoh: Komisi A">
                                    <button type="button" class="btn btn-link link-secondary p-0" id="btnClearKomisi" title="Hapus isian Nama Komisi">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                                <datalist id="namaKomisiList"></datalist>
                                <div class="invalid-feedback d-block" id="nama_komisi_error"></div>
                            </div>
                            <div class="col-md-5">
                                <select class="form-select" id="id_anggota" name="id_anggota" required>
                                    <option value="">Pilih Anggota</option>
                                    <!-- Options populated via JS -->
                                </select>
                            </div>
                            <div class="col-md-5">
                                <select class="form-select" id="id_jabatan_alat_kelengkapan" name="id_jabatan_alat_kelengkapan" required>
                                    <option value="">Pilih Jabatan</option>
                                    <!-- Options populated via JS -->
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100 shadow-sm transition-base py-2">
                                    <i class="ti ti-plus me-1"></i> Tambah
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Daftar Anggota -->
                <div class="card bg-body-tertiary border-0 mb-4 d-none" id="copySkCard">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="ti ti-copy me-1"></i>Copy dari SK Sebelumnya</h6>
                        <div class="row g-2 align-items-end">
                            <div class="col-md-8">
                                <select class="form-select" id="skCopySelect">
                                    <option value="">Pilih SK tidak aktif...</option>
                                    <!-- Options populated via JS -->
                                </select>
                                <small class="text-muted d-none mt-1 d-block" id="skCopyInfo"></small>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill w-100 py-2" id="btnCopyAnggota" disabled>
                                    <i class="ti ti-users-group me-1"></i>Salin Anggota
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3"><i class="ti ti-users me-1"></i>Daftar Anggota</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tableAnggotaList">
                        <thead class="bg-body-tertiary">
                            <tr>
                                <th>Nama Anggota</th>
                                <th class="d-none komisi-col">Nama Komisi</th>
                                <th>Jabatan</th>
                                <th class="text-end action-col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Struktur Organisasi (per SK) -->
<div class="modal fade" id="modalStruktur" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white pt-4 px-4 pb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width:38px;height:38px;">
                        <i class="ti ti-sitemap fs-5"></i>
                    </span>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalStrukturTitle">Struktur Organisasi</h5>
                        <p class="mb-0 small opacity-75" id="modalStrukturSubtitle"></p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="modalStrukturBody"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}" />
    <style>
        /* ===== Struktur Organisasi ===== */
        .struktur-scroll { overflow-x: auto; }
        .org-tree { display: flex; flex-direction: column; align-items: center; padding: .5rem 0 1rem; }
        .org-row { display: flex; justify-content: center; flex-wrap: wrap; gap: 1rem; }
        .org-node {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: .85rem;
            padding: .75rem 1rem;
            text-align: center;
            min-width: 150px;
            max-width: 200px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            position: relative;
        }
        .org-avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: .5rem;
            font-weight: 700; font-size: .875rem; color: #fff;
            overflow: hidden;
        }
        .org-avatar-img {
            width: 100%; height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        .org-name { font-weight: 600; font-size: .85rem; line-height: 1.25; word-break: break-word; }
        .org-role {
            font-size: .68rem; font-weight: 700; letter-spacing: .04em;
            text-transform: uppercase; color: #6c757d; margin-top: .15rem;
        }
        .org-connector { width: 2px; height: 26px; background: #ced4da; }
        .org-members-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: .75rem; max-width: 900px; }
        .org-members-grid .org-node { min-width: 130px; max-width: 160px; padding: .6rem .75rem; }
        .org-members-grid .org-avatar { width: 36px; height: 36px; font-size: .75rem; }
        .avatar-ketua { background: linear-gradient(135deg,#2563eb,#3b82f6); }
        .avatar-wakil { background: linear-gradient(135deg,#059669,#10b981); }
        .avatar-sekretaris { background: linear-gradient(135deg,#d97706,#f59e0b); }
        .avatar-anggota { background: linear-gradient(135deg,#475569,#64748b); }
        .org-komisi-section { background: #f8f9fa; border: 1px dashed #ced4da; border-radius: 1rem; padding: 1rem 1.25rem 1.5rem; margin-bottom: 1.5rem; }
        .org-komisi-title {
            display: inline-block; font-weight: 700; font-size: .8rem;
            background: #eef2ff; color: #4338ca;
            padding: .25rem .9rem; border-radius: 999px; margin-bottom: .5rem;
        }
        .ak-struktur-card { border-left: 4px solid #2563eb; }
        .sk-empty { font-style: italic; color: #adb5bd; }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

<script>
$(function() {
    var table = $('#surat-keputusan-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('admin.surat-keputusan.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'no_sk', name: 'no_sk'},
            {data: 'tgl_sk', name: 'tgl_sk'},
            {data: 'alat_kelengkapan.nama', name: 'alatKelengkapan.nama'},
            {data: 'jumlah_anggota', name: 'jumlah_anggota', searchable: false},
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    if (data === 'A') {
                        return '<span class="badge bg-success">Aktif</span>';
                    } else {
                        return '<span class="badge bg-secondary">Tidak Aktif</span>';
                    }
                }
            },

            {data: 'file_download', name: 'file_download', orderable: false, searchable: false},
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-end pe-4'
            },
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
        }
    });

    window.previewPdf = function(url, title) {
        var card = document.getElementById('pdfPreviewCard');
        document.getElementById('pdfTitle').textContent = title;
        document.getElementById('pdfFrame').src = url;
        card.style.display = 'block';
        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    window.closePdfPreview = function() {
        document.getElementById('pdfPreviewCard').style.display = 'none';
        document.getElementById('pdfFrame').src = '';
    };

    const modal = new bootstrap.Modal(document.getElementById('modalSuratKeputusan'));

    function openModal() {
        $('#formSuratKeputusan')[0].reset();
        $('#id').val('');
        $('#modalTitle').text('Tambah Surat Keputusan');
        $('#modalSubtitle').text('Lengkapi data surat keputusan di bawah ini');
        $('#btnSave').html('<i class="ti ti-device-floppy me-1"></i>Simpan');
        $('#fileHelp').addClass('d-none');
        $('.is-invalid').removeClass('is-invalid');
        modal.show();
    }

    function editItem(id) {
        $.get("{{ route('admin.surat-keputusan.index') }}" + '/' + id + '/edit', function(data) {
            $('#id').val(data.id);
            $('#no_sk').val(data.no_sk);
            $('#tgl_sk').val(data.tgl_sk.substring(0, 10)); // Format YYYY-MM-DD
            $('#id_alat_kelengkapan').val(data.id_alat_kelengkapan);
            $('#status').val(data.status);
            $('#ket_sk').val(data.ket_sk);
            $('#modalTitle').text('Edit Surat Keputusan');
            $('#modalSubtitle').text(data.no_sk + ' - ' + (data.ket_sk || 'Tanpa keterangan'));
            $('#btnSave').html('<i class="ti ti-device-floppy me-1"></i>Simpan Perubahan');
            $('#fileHelp').removeClass('d-none');
            $('.is-invalid').removeClass('is-invalid');
            modal.show();
        });
    }

    // Event Delegation
    $(document).on('click', '.btn-add', function() {
        openModal();
    });

    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        editItem(id);
    });

    $('#formSuratKeputusan').submit(function(e) {
        e.preventDefault();
        var id = $('#id').val();
        var url = id ? "{{ route('admin.surat-keputusan.index') }}" + '/' + id : "{{ route('admin.surat-keputusan.store') }}";
        var formData = new FormData(this);

        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            data: formData,
            url: url,
            type: "POST",
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                modal.hide();
                table.draw();
                loadStruktur(true);
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.success,
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function(data) {
                if(data.status === 422) {
                    var errors = data.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $('#'+key).addClass('is-invalid');
                        $('#'+key).next('.invalid-feedback').text(value[0]);
                    });
                } else {
                    Swal.fire('Gagal!', 'Terjadi kesalahan pada server.', 'error');
                }
            }
        });
    });

    window.deleteItem = function(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('admin.surat-keputusan.index') }}/" + id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        table.draw();
                        loadStruktur(true);
                        Swal.fire('Terhapus!', response.success, 'success');
                    },
                    error: function(xhr) {
                        var msg = 'Terjadi kesalahan saat menghapus data.';
                        if(xhr.status === 422 && xhr.responseJSON.error) {
                            msg = xhr.responseJSON.error;
                        }
                        Swal.fire('Gagal!', msg, 'error');
                    }
                });
            }
        });
    }

    // --- Member Management Logic ---
    const modalAnggota = new bootstrap.Modal(document.getElementById('modalAnggota'));

    $(document).on('click', '.btn-members', function() {
        var id = $(this).data('id');
        loadAnggota(id);
    });

    // Initialize Select2
    $('#id_anggota').select2({
        theme: 'bootstrap-5',
        dropdownParent: $('#modalAnggota'),
        placeholder: 'Pilih Anggota',
        allowClear: true
    });

    function loadAnggota(id) {
        $('#id_surat_keputusan_anggota').val(id);
        $('#tableAnggotaList tbody').html('<tr><td colspan="4" class="text-center">Memuat data...</td></tr>');

        $.get("{{ url('admin/surat-keputusan') }}/" + id + "/anggota", function(data) {
            $('#modalAnggotaTitle').html('Kelola Anggota ' + (data.surat_keputusan.alat_kelengkapan.ket || ''));
            $('#modalAnggotaSubtitle').text('No. SK: ' + data.surat_keputusan.no_sk + ' (' + data.surat_keputusan.alat_kelengkapan.nama + ')');

            // Always show the Tambah form (member management allowed regardless of status)
            $('#formAnggotaCard').show();

            // Toggle Nama Komisi field
            var isKomisi = data.is_komisi || false;
            if (isKomisi) {
                $('#nama_komisi_row').removeClass('d-none');
                $('#nama_komisi').attr('required', true);
                $('.komisi-col').removeClass('d-none');
            } else {
                $('#nama_komisi_row').addClass('d-none');
                $('#nama_komisi').removeAttr('required').val('');
                $('.komisi-col').addClass('d-none');
            }

            // Populate Nama Komisi datalist
            var datalist = $('#namaKomisiList').empty();
            if (data.nama_komisi_list && data.nama_komisi_list.length) {
                $.each(data.nama_komisi_list, function(i, val) {
                    datalist.append('<option value="' + val + '">');
                });
            }

            // Populate Anggota Select
            var anggotaSelect = $('#id_anggota');
            anggotaSelect.empty().append('<option value="">Pilih Anggota</option>');
            $.each(data.all_anggota, function(key, val) {
                anggotaSelect.append('<option value="'+val.id+'">'+val.nama_anggota+'</option>');
            });
            anggotaSelect.trigger('change');

            // Populate Jabatan Select (hindari pilihan berulang)
            var jabatanSelect = $('#id_jabatan_alat_kelengkapan');
            jabatanSelect.empty().append('<option value="">Pilih Jabatan</option>');
            var seenJabatan = {};
            $.each(data.jabatan_options, function(key, val) {
                if (seenJabatan[val.nama]) return;
                seenJabatan[val.nama] = true;
                jabatanSelect.append('<option value="'+val.id+'">'+val.nama+'</option>');
            });

            // Muat daftar SK tidak aktif untuk fitur "Copy dari" (Alat Kelengkapan sama)
            var currentSkId = String(data.surat_keputusan.id);
            $.get("{{ route('admin.surat-keputusan.inactive', ':idAlatKelengkapan') }}".replace(':idAlatKelengkapan', data.surat_keputusan.id_alat_kelengkapan), function(list) {
                var $card = $('#copySkCard'), $select = $('#skCopySelect');
                var options = (list || []).filter(function(s) { return String(s.id) !== currentSkId; });
                $select.empty().append('<option value="">Pilih SK tidak aktif...</option>');
                if (options.length) {
                    $.each(options, function(i, s) {
                        $select.append('<option value="' + s.id + '">' + escapeHtml(s.no_sk) + ' — ' + s.jumlah_anggota + ' anggota</option>');
                    });
                    $select.off('change').on('change', function() {
                        var val = $(this).val(), sel = null;
                        $.each(options, function(i, s) { if (String(s.id) === String(val)) sel = s; });
                        if (sel) {
                            $('#skCopyInfo').removeClass('d-none').text(sel.no_sk + ' • ' + sel.tgl_sk + ' • ' + sel.jumlah_anggota + ' anggota siap disalin');
                            $('#btnCopyAnggota').prop('disabled', false);
                        } else {
                            $('#skCopyInfo').addClass('d-none').text('');
                            $('#btnCopyAnggota').prop('disabled', true);
                        }
                    });
                    $('#skCopyInfo').addClass('d-none').text('');
                    $('#btnCopyAnggota').prop('disabled', true);
                    $card.removeClass('d-none');
                } else {
                    $card.addClass('d-none');
                }
            }).fail(function() { $('#copySkCard').addClass('d-none'); });

            renderAnggotaTable(data.existing_anggota, isKomisi);
            modalAnggota.show();
        }).fail(function(xhr) {
            console.error(xhr);
            Swal.fire('Error', 'Gagal memuat data anggota. Cek log console.', 'error');
        });
    }

    function renderAnggotaTable(data, isKomisi) {
        var html = '';
        var colSpan = isKomisi ? 4 : 3;
        if(data.length === 0) {
            html = '<tr><td colspan="' + colSpan + '" class="text-center text-muted">Belum ada anggota.</td></tr>';
        } else {
            $.each(data, function(index, item) {
                html += '<tr>';
                html += '<td>' + item.anggota.nama_anggota + '</td>';
                if(isKomisi) {
                    html += '<td>' + (item.nama_komisi || '-') + '</td>';
                }
                html += '<td>' + item.jabatan_alat_kelengkapan.nama + '</td>';
                html += '<td class="text-end"><button type="button" class="btn-icon-modern text-danger btn-delete-member" data-id="'+item.id+'"><i class="ti ti-trash"></i></button></td>';
                html += '</tr>';
            });
        }
        $('#tableAnggotaList tbody').html(html);
        $('.action-col').show();
    }

    // Clear Nama Komisi button
    $(document).on('click', '#btnClearKomisi', function() {
        $('#nama_komisi').val('').focus();
    });

    function submitFormAnggota(force) {
        if (!$('#id_surat_keputusan_anggota').val()) {
            Swal.fire('Gagal!', 'Surat Keputusan belum dipilih.', 'error');
            return;
        }
        var formData = $('#formAnggota').serialize();
        if (force) {
            formData += '&force=1';
        }
        var btn = $('#formAnggota').find('button[type="submit"]');
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('admin.surat-keputusan.store-anggota') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                // Reset form & select2 agar siap untuk input berikutnya
                $('#formAnggota')[0].reset();
                $('#id_anggota').val(null).trigger('change');
                $('#nama_komisi_error').text('');
                loadAnggota($('#id_surat_keputusan_anggota').val());
                loadStruktur(true);
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.success,
                    timer: 1000,
                    showConfirmButton: false
                });
                table.ajax.reload(null, false); // Reload main table to update count
            },
            error: function(xhr) {
                var rj = xhr.responseJSON || {};

                // Batas jabatan terlampaui -> tanyakan konfirmasi ke pengguna
                if (xhr.status === 422 && rj.requires_confirmation) {
                    Swal.fire({
                        title: 'Konfirmasi Jabatan',
                        text: rj.message,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Tetap Tambahkan',
                        cancelButtonText: 'Batal'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            submitFormAnggota(true);
                        }
                    });
                    return;
                }

                var msg = 'Terjadi kesalahan.';
                $('#nama_komisi_error').text('');
                if(xhr.status === 422) {
                    var errors = rj.errors;
                    if (errors.nama_komisi) {
                        $('#nama_komisi_error').text(errors.nama_komisi[0]);
                        msg = errors.nama_komisi[0];
                    } else if (errors.id_anggota) {
                         msg = errors.id_anggota[0];
                    } else if (errors.id_jabatan_alat_kelengkapan) {
                         msg = errors.id_jabatan_alat_kelengkapan[0];
                    } else {
                        msg = 'Cek kembali inputan anda.';
                    }
                }
                Swal.fire('Gagal!', msg, 'error');
            },
            complete: function() {
                btn.prop('disabled', false);
            }
        });
    }

    $('#formAnggota').submit(function(e) {
        e.preventDefault();
        submitFormAnggota(false);
    });

    $(document).on('click', '.btn-delete-member', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus Anggota?',
            text: "Anggota akan dihapus dari SK ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/surat-keputusan/anggota') }}/" + id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        loadAnggota($('#id_surat_keputusan_anggota').val());
                        loadStruktur(true);
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: response.success,
                            timer: 1000,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false); // Reload main table to update count
                    },
                    error: function(xhr) {
                        var msg = 'Terjadi kesalahan saat menghapus data.';
                        if(xhr.status === 422 && xhr.responseJSON.error) {
                            msg = xhr.responseJSON.error;
                        }
                        Swal.fire('Gagal!', msg, 'error');
                    }
                });
            }
        });
    });

    // Salin anggota dari SK tidak aktif yang dipilih
    $(document).on('click', '#btnCopyAnggota', function() {
        var target = $('#id_surat_keputusan_anggota').val();
        var source = $('#skCopySelect').val();
        if (!target || !source) return;

        Swal.fire({
            title: 'Salin Anggota?',
            text: 'Seluruh anggota dari SK terpilih akan ditambahkan ke SK ini. Anggota yang sudah terdaftar akan dilewati.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Salin',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (!result.isConfirmed) return;
            var btn = $('#btnCopyAnggota');
            btn.prop('disabled', true);
            $.post("{{ route('admin.surat-keputusan.copy-anggota') }}", {
                _token: "{{ csrf_token() }}",
                id_surat_keputusan: target,
                id_copy_from: source
            }).done(function(resp) {
                loadAnggota(target);
                loadStruktur(true);
                table.ajax.reload(null, false);
                Swal.fire({ icon: 'success', title: 'Berhasil', text: resp.success, timer: 2000, showConfirmButton: false });
            }).fail(function(xhr) {
                var rj = xhr.responseJSON || {};
                var msg = rj.error || (rj.errors ? Object.values(rj.errors)[0][0] : 'Gagal menyalin anggota.');
                Swal.fire('Gagal!', msg, 'error');
            }).always(function() {
                btn.prop('disabled', false);
            });
        });
    });

    // ==========================================
    // ======== STRUKTUR ORGANISASI =============
    // ==========================================
    const modalStruktur = new bootstrap.Modal(document.getElementById('modalStruktur'));
    var strukturLoaded = false;

    function escapeHtml(str) {
        return $('<div/>').text(str == null ? '' : String(str)).html();
    }

    function initialsOf(name) {
        return (name || '?').trim().split(/\s+/).slice(0, 2).map(function(w) {
            return w.charAt(0);
        }).join('').toUpperCase();
    }

    function avatarClass(role) {
        switch ((role || '').toLowerCase()) {
            case 'ketua': return 'avatar-ketua';
            case 'wakil': return 'avatar-wakil';
            case 'sekretaris': return 'avatar-sekretaris';
            default: return 'avatar-anggota';
        }
    }

    function orgNode(nama, jabatan, komisi, fotoUrl) {
        var avatarContent = fotoUrl
            ? '<img src="' + escapeHtml(fotoUrl) + '" alt="' + escapeHtml(nama) + '" class="org-avatar-img">'
            : escapeHtml(initialsOf(nama));
        var html = '<div class="org-node">';
        html += '<div class="org-avatar ' + avatarClass(jabatan) + '">' + avatarContent + '</div>';
        html += '<div class="org-name">' + escapeHtml(nama) + '</div>';
        if (komisi) {
            html += '<div class="org-komisi-title mt-1">' + escapeHtml(komisi) + '</div>';
        }
        html += '<div class="org-role">' + escapeHtml(jabatan) + '</div>';
        html += '</div>';
        return html;
    }

    function orgLevel(items) {
        if (!items.length) return '';
        return '<div class="org-row">' + items.map(function(m) {
            return orgNode(m.nama_anggota, m.jabatan, m.nama_komisi, m.foto_url);
        }).join('') + '</div><div class="org-connector"></div>';
    }

    /**
     * Render bagan struktur dari daftar anggota (sudah terurut).
     * item: { nama_anggota, jabatan, nama_komisi }
     */
    function renderOrgChart(anggota, isKomisi) {
        anggota = anggota || [];
        if (!anggota.length) {
            return '<div class="text-center text-muted py-4 sk-empty"><i class="ti ti-users-off me-1"></i>Belum ada anggota pada SK aktif.</div>';
        }

        var sections = [];

        if (isKomisi) {
            // Kelompokkan per nama_komisi
            var groups = {};
            $.each(anggota, function(i, m) {
                var key = m.nama_komisi || 'Tanpa Komisi';
                if (!groups[key]) groups[key] = [];
                groups[key].push(m);
            });
            Object.keys(groups).sort().forEach(function(komisi) {
                var members = groups[komisi];
                var ketua = members.filter(function(m){ return (m.jabatan||'').toLowerCase() === 'ketua'; });
                var wakil = members.filter(function(m){ return (m.jabatan||'').toLowerCase() === 'wakil'; });
                var sekre = members.filter(function(m){ return (m.jabatan||'').toLowerCase() === 'sekretaris'; });
                var reguler = members.filter(function(m){
                    return ['ketua','wakil','sekretaris'].indexOf((m.jabatan||'').toLowerCase()) === -1;
                });
                var chart = '<div class="org-tree">'
                    + orgLevel(ketua)
                    + orgLevel(wakil)
                    + orgLevel(sekre)
                    + (reguler.length
                        ? '<div class="org-members-grid">' + reguler.map(function(m){ return orgNode(m.nama_anggota, m.jabatan, null, m.foto_url); }).join('') + '</div>'
                        : '')
                    + '</div>';
                sections.push(
                    '<div class="org-komisi-section">' +
                        '<div class="text-center"><span class="org-komisi-title"><i class="ti ti-diagram-3 me-1"></i>' + escapeHtml(komisi) + '</span></div>' +
                        chart +
                    '</div>'
                );
            });
        } else {
            var ketua = anggota.filter(function(m){ return (m.jabatan||'').toLowerCase() === 'ketua'; });
            var wakil = anggota.filter(function(m){ return (m.jabatan||'').toLowerCase() === 'wakil'; });
            var sekre = anggota.filter(function(m){ return (m.jabatan||'').toLowerCase() === 'sekretaris'; });
            var reguler = anggota.filter(function(m){
                return ['ketua','wakil','sekretaris'].indexOf((m.jabatan||'').toLowerCase()) === -1;
            });
            sections.push('<div class="org-tree">'
                + orgLevel(ketua)
                + orgLevel(wakil)
                + orgLevel(sekre)
                + (reguler.length
                    ? '<div class="org-members-grid">' + reguler.map(function(m){ return orgNode(m.nama_anggota, m.jabatan, null, m.foto_url); }).join('') + '</div>'
                    : '')
                + '</div>');
        }

        return sections.join('');
    }

    /**
     * Render kartu struktur untuk satu alat kelengkapan.
     * data: { nama, ket, is_komisi, surat_keputusan: {no_sk, tgl_sk, jumlah_anggota, anggota:[...] } | null }
     */
    function renderAkStrukturCard(data) {
        var sk = data.surat_keputusan;
        var header =
            '<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">' +
                '<div>' +
                    '<h5 class="fw-bold mb-0 text-uppercase"><i class="ti ti-building-community me-2 text-primary"></i>' + escapeHtml(data.nama) + (data.ket ? ' <span class="fw-normal text-muted small">— ' + escapeHtml(data.ket) + '</span>' : '') + '</h5>' +
                    (sk
                        ? '<small class="text-muted"><i class="ti ti-file-certificate me-1"></i>No. SK: ' + escapeHtml(sk.no_sk) + ' • Tanggal: ' + escapeHtml(sk.tgl_sk) + ' • <span class="badge bg-success ms-1">SK Aktif</span></small>'
                        : '<small class="sk-empty"><i class="ti ti-file-x me-1"></i>Tidak ada SK aktif</small>') +
                '</div>' +
                (sk && sk.jumlah_anggota ? '<span class="badge bg-primary-subtle text-primary-emphasis">' + sk.jumlah_anggota + ' Anggota</span>' : '') +
            '</div>';

        var body = sk ? renderOrgChart(sk.anggota, data.is_komisi)
                      : '<div class="alert alert-light border text-center text-muted mb-0 sk-empty">Belum ada SK aktif untuk alat kelengkapan ini.</div>';

        return '<div class="card ak-struktur-card border shadow-sm mb-4"><div class="card-body">' + header + body + '</div></div>';
    }

    function loadStruktur(force) {
        if (strukturLoaded && !force) return;
        $('#strukturContainer').html('<div class="text-center text-muted py-5"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Memuat struktur organisasi...</div>');

        $.get("{{ route('admin.surat-keputusan.struktur-all') }}", function(data) {
            var html = '';
            if (!data.length) {
                html = '<div class="alert alert-light border text-center">Belum ada data Alat Kelengkapan.</div>';
            } else {
                $.each(data, function(i, ak) {
                    html += renderAkStrukturCard(ak);
                });
            }
            $('#strukturContainer').html(html);
            strukturLoaded = true;
        }).fail(function() {
            $('#strukturContainer').html('<div class="alert alert-danger text-center mb-0">Gagal memuat struktur organisasi.</div>');
        });
    }

    // Muat struktur saat tab dibuka pertama kali
    $(document).on('shown.bs.tab', '#tab-struktur-btn', function() {
        loadStruktur(false);
    });

    $(document).on('click', '#btnReloadStruktur', function() {
        loadStruktur(true);
    });

    // Tombol Struktur per baris tabel (per SK)
    $(document).on('click', '.btn-struktur', function() {
        var id = $(this).data('id');
        $.get("{{ url('admin/surat-keputusan') }}/" + id + "/anggota", function(data) {
            var sk = data.surat_keputusan;
            $('#modalStrukturTitle').text('Struktur Organisasi ' + (sk.alat_kelengkapan.ket || sk.alat_kelengkapan.nama));
            $('#modalStrukturSubtitle').text('No. SK: ' + sk.no_sk + ' • Status: ' + (sk.status === 'A' ? 'Aktif' : 'Tidak Aktif'));
            var anggota = (data.existing_anggota || []).map(function(item) {
                return {
                    nama_anggota: item.anggota ? item.anggota.nama_anggota : '-',
                    jabatan: item.jabatan_alat_kelengkapan ? item.jabatan_alat_kelengkapan.nama : '-',
                    nama_komisi: item.nama_komisi,
                    foto_url: (item.anggota && item.anggota.foto_anggota) ? "{{ asset('storage') }}/" + item.anggota.foto_anggota : null
                };
            });
            $('#modalStrukturBody').html(renderOrgChart(anggota, data.is_komisi));
            modalStruktur.show();
        }).fail(function() {
            Swal.fire('Error', 'Gagal memuat struktur organisasi.', 'error');
        });
    });

});
</script>
@endpush
