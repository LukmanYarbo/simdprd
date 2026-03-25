<div class="modal fade" id="modalHarta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold">Data Harta Anggota</h5>
                    <p class="text-body-secondary mb-0 small" id="modalHartaSubtitle">Anggota</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Form Input -->
                    <div class="col-12 mb-4">
                        <div class="card bg-body-tertiary border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">Form Harta</h6>
                                <form id="formHarta">
                                    @csrf
                                    <input type="hidden" id="id_harta" name="id">
                                    <input type="hidden" id="id_anggota_harta" name="id_anggota">
                                    
                                    <div class="row g-3">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label small">Jenis Harta <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="jenis_harta" name="jenis_harta" required>
                                                <option value="">Pilih Jenis Harta</option>
                                                <option value="Tanah dan Bangunan">Tanah dan Bangunan</option>
                                                <option value="Alat Transportasi dan Mesin">Alat Transportasi dan Mesin</option>
                                                <option value="Harta Bergerak Lainnya">Harta Bergerak Lainnya</option>
                                                <option value="Surat Berharga">Surat Berharga</option>
                                                <option value="Kas dan Setara Kas">Kas dan Setara Kas</option>
                                                <option value="Harta Lainnya">Harta Lainnya</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label small">Nama / Rincian Harta <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm" id="nama_harta" name="nama_harta" placeholder="Ex: Mobil Toyota Avanza Tahun 2021" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-label small">Tahun Perolehan <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-sm" id="tahun_perolehan" name="tahun_perolehan" placeholder="Ex: 2021" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label small">Harga Perolehan (Rp) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control form-control-sm" id="harga_perolehan" name="harga_perolehan" required>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-5 mb-2">
                                            <label class="form-label small">Keterangan Tambahan</label>
                                            <input type="text" class="form-control form-control-sm" id="keterangan" name="keterangan" placeholder="Informasi tambahan">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4 pt-3 border-top gap-2">
                                        <button type="button" class="btn btn-light rounded-pill px-4 transition-base" onclick="resetFormHarta()">
                                            <i class="ti ti-rotate-clockwise-2 text-secondary"></i> Batal / Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm transition-base" id="btnSaveHarta">
                                            <i class="ti ti-device-floppy"></i> Simpan Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- List Data -->
                    <div class="col-12 mt-2">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-transparent py-3">
                                <h6 class="fw-bold mb-0">Daftar Harta Anggota</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0" id="tableHartaList">
                                        <thead class="bg-body-tertiary">
                                            <tr>
                                                <th class="ps-4">Jenis Harta</th>
                                                <th>Nama / Rincian</th>
                                                <th>Tahun</th>
                                                <th class="text-end">Harga (Rp)</th>
                                                <th class="text-end pe-4">Aksi</th>
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
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        const modalHarta = new bootstrap.Modal(document.getElementById('modalHarta'));
        let currentAnggotaIdHarta = null;

        $(document).on('click', '.btn-harta', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            currentAnggotaIdHarta = id;
            loadHarta(id);
        });

        function loadHarta(id) {
            $('#id_anggota_harta').val(id);
            $('#tableHartaList tbody').html('<tr><td colspan="5" class="text-center">Memuat data...</td></tr>');
            
            $.get("{{ url('admin/anggota') }}/" + id + "/harta", function(data) {
                $('#modalHartaSubtitle').text('Anggota: ' + data.anggota.nama_anggota);
                renderHartaTable(data.harta);
                modalHarta.show();
            }).fail(function(xhr) {
                Swal.fire('Error', 'Gagal memuat data harta.', 'error');
            });
        }

        function renderHartaTable(data) {
            var html = '';
            if(data.length === 0) {
                html = '<tr><td colspan="5" class="text-center text-muted">Belum ada data harta yang tercatat.</td></tr>';
            } else {
                $.each(data, function(index, item) {
                    var hargaFormat = new Intl.NumberFormat('id-ID').format(item.harga_perolehan);
                    html += '<tr>';
                    html += '<td class="ps-4"><span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">' + item.jenis_harta + '</span></td>';
                    html += '<td><div class="fw-semibold">' + item.nama_harta + '</div><div class="small text-muted">' + (item.keterangan ? item.keterangan : '-') + '</div></td>';
                    html += '<td>' + item.tahun_perolehan + '</td>';
                    html += '<td class="text-end fw-semibold">' + hargaFormat + '</td>';
                    html += '<td class="text-end pe-4">';
                    html += '<div class="d-flex justify-content-end gap-2">';
                    html += '<button type="button" class="btn-icon-modern text-primary btn-edit-harta" data-id="'+item.id+'" title="Edit"><i class="ti ti-edit"></i></button>';
                    html += '<button type="button" class="btn-icon-modern text-danger btn-delete-harta" data-id="'+item.id+'" title="Hapus"><i class="ti ti-trash"></i></button>';
                    html += '</div></td>';
                    html += '</tr>';
                });
            }
            $('#tableHartaList tbody').html(html);
        }

        $('#formHarta').submit(function(e) {
            e.preventDefault();
            
            var id = $('#id_harta').val();
            var url = id ? "{{ url('admin/harta') }}/" + id : "{{ url('admin/anggota') }}/" + currentAnggotaIdHarta + "/harta";
            var formData = new FormData(this);
            
            if (id) {
                formData.append('_method', 'PUT');
            }

            var btn = $('#btnSaveHarta');
            var originalBtnHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

            $.ajax({
                data: formData,
                url: url,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(response) {
                    loadHarta(currentAnggotaIdHarta);
                    resetFormHarta();
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
                        $('.is-invalid').removeClass('is-invalid');
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

        $(document).on('click', '.btn-edit-harta', function() {
            var id = $(this).data('id');
            $.get("{{ url('admin/harta') }}/" + id + "/edit", function(data) {
                $('#id_harta').val(data.id);
                $('#id_anggota_harta').val(data.id_anggota);
                $('#jenis_harta').val(data.jenis_harta);
                $('#nama_harta').val(data.nama_harta);
                $('#tahun_perolehan').val(data.tahun_perolehan);
                $('#harga_perolehan').val(data.harga_perolehan);
                $('#keterangan').val(data.keterangan);
                
                $('#btnSaveHarta').html('<i class="ti ti-check"></i> Perbarui Data');
                $('.is-invalid').removeClass('is-invalid');
            });
        });

        $(document).on('click', '.btn-delete-harta', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Harta?',
                text: "Data ini akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/harta') }}/" + id,
                        type: 'DELETE',
                        data: { "_token": "{{ csrf_token() }}" },
                        success: function(response) {
                            loadHarta(currentAnggotaIdHarta);
                            Swal.fire('Terhapus!', response.success, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                        }
                    });
                }
            });
        });

        window.resetFormHarta = function() {
            $('#formHarta')[0].reset();
            $('#id_harta').val('');
            $('#id_anggota_harta').val(currentAnggotaIdHarta);
            $('#btnSaveHarta').html('<i class="ti ti-device-floppy"></i> Simpan Data');
            $('.is-invalid').removeClass('is-invalid');
        }
    });
</script>
@endpush
