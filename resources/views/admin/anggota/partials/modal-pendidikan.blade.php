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

@push('scripts')
<script>
    $(function() {
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
