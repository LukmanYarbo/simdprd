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
        <div class="card-body">
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
        </div>
    </div>

    {{-- PDF Preview Panel --}}
    <div class="card border-0 shadow-lg mt-4" id="pdfPreviewCard" style="display:none;">
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

<!-- Modal -->
<div class="modal fade" id="modalSuratKeputusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-bottom-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold text-primary" id="modalTitle">Tambah Surat Keputusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formSuratKeputusan" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label for="no_sk" class="form-label">No. SK <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="no_sk" name="no_sk" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_sk" class="form-label">Tanggal SK <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="tgl_sk" name="tgl_sk" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="id_alat_kelengkapan" class="form-label">Alat Kelengkapan <span class="text-danger">*</span></label>
                        <select class="form-select" id="id_alat_kelengkapan" name="id_alat_kelengkapan" required>
                            <option value="">Pilih Alat Kelengkapan</option>
                            @foreach($alatKelengkapans as $ak)
                                <option value="{{ $ak->id }}">{{ $ak->nama }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="A">Aktif</option>
                            <option value="T">Tidak Aktif</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="ket_sk" class="form-label">Keterangan SK</label>
                        <textarea class="form-control" id="ket_sk" name="ket_sk" rows="3"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-4">
                        <label for="file_sk" class="form-label">File SK (PDF/DOC/IMG)</label>
                        <input class="form-control" type="file" id="file_sk" name="file_sk">
                        <small class="text-muted d-none" id="fileHelp">Biarkan kosong jika tidak ingin mengubah file.</small>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="modal-footer border-top-0 pb-4 px-4 pt-0">
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
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
                        <h6 class="fw-bold mb-3">Tambah Anggota</h6>
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
                <h6 class="fw-bold mb-3">Daftar Anggota</h6>
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
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/select2-bootstrap-5-theme.min.css') }}" />
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
        $('#btnSave').text('Simpan');
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
            $('#btnSave').text('Simpan Perubahan');
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

            // Populate Jabatan Select
            var jabatanSelect = $('#id_jabatan_alat_kelengkapan');
            jabatanSelect.empty().append('<option value="">Pilih Jabatan</option>');
            $.each(data.jabatan_options, function(key, val) {
                jabatanSelect.append('<option value="'+val.id+'">'+val.nama+'</option>');
            });

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

    $('#formAnggota').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true);

        $.ajax({
            url: "{{ route('admin.surat-keputusan.store-anggota') }}",
            type: "POST",
            data: formData,
            success: function(response) {
                loadAnggota($('#id_surat_keputusan_anggota').val());
                $('#formAnggota')[0].reset();
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
                var msg = 'Terjadi kesalahan.';
                $('#nama_komisi_error').text('');
                if(xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
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

});
</script>
@endpush
