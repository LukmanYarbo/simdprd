<div class="modal fade animate-fade-in" id="modalHarta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-content-premium border-0 shadow-lg">
            <div class="modal-header modal-header-gradient border-bottom-0 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                        <i class="ti ti-wallet fs-3"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-extrabold text-body">Kelola Data Harta</h5>
                        <p class="text-secondary mb-0 small" id="modalHartaSubtitle">Memuat data anggota...</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Form Input Row (Top) -->
                    <div class="col-12 mb-4">
                        <div class="card premium-card border-0">
                            <div class="card-header premium-card-header border-0 d-flex align-items-center justify-content-between" id="formHeaderHarta">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-edit-circle fs-5" id="formHeaderIconHarta"></i>
                                    <h6 class="fw-bold mb-0 text-white" id="formTitleHarta">Form Tambah Harta</h6>
                                </div>
                                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-1 small" id="modeBadgeHarta" style="font-size: 0.75rem;">Tambah</span>
                            </div>
                            <div class="card-body p-4">
                                <form id="formHarta" class="needs-validation">
                                    @csrf
                                    <input type="hidden" id="id_harta" name="id">
                                    <input type="hidden" id="id_anggota_harta" name="id_anggota">
                                    
                                    <div class="row g-3">
                                        <!-- Jenis Harta & Nama Rincian -->
                                        <div class="col-md-6">
                                            <div class="form-floating-modern">
                                                <select class="form-select" id="jenis_harta" name="jenis_harta" required>
                                                    <option value="">Pilih Jenis Harta</option>
                                                    <option value="Tanah dan Bangunan">Tanah dan Bangunan</option>
                                                    <option value="Alat Transportasi dan Mesin">Alat Transportasi dan Mesin</option>
                                                    <option value="Harta Bergerak Lainnya">Harta Bergerak Lainnya</option>
                                                    <option value="Surat Berharga">Surat Berharga</option>
                                                    <option value="Kas dan Setara Kas">Kas dan Setara Kas</option>
                                                    <option value="Harta Lainnya">Harta Lainnya</option>
                                                </select>
                                                <label class="small text-secondary">Jenis Harta <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-category"></i></span>
                                                <div class="invalid-feedback">Jenis harta wajib dipilih.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="nama_harta" name="nama_harta" placeholder="Rincian Harta" required>
                                                <label class="small text-secondary">Nama / Rincian Harta <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-blockquote"></i></span>
                                                <div class="invalid-feedback">Nama/rincian harta wajib diisi.</div>
                                            </div>
                                        </div>

                                        <!-- Tahun Perolehan, Harga Perolehan, Keterangan -->
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <input type="number" class="form-control" id="tahun_perolehan" name="tahun_perolehan" placeholder="Ex: 2021" min="1900" max="2100" required>
                                                <label class="small text-secondary">Tahun Perolehan <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-calendar"></i></span>
                                                <div class="invalid-feedback">Tahun perolehan wajib diisi.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating-modern">
                                                <input type="number" class="form-control" id="harga_perolehan" name="harga_perolehan" placeholder="Harga" required>
                                                <label class="small text-secondary">Harga Perolehan (Rp) <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-coin"></i></span>
                                                <div class="invalid-feedback">Harga perolehan wajib diisi.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Keterangan">
                                                <label class="small text-secondary">Keterangan Tambahan</label>
                                                <span class="input-icon"><i class="ti ti-info-circle"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4 pt-3 border-top gap-2">
                                        <button type="button" class="btn btn-light rounded-pill px-3 transition-base" onclick="resetFormHarta()">
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

                    <!-- List Data Row (Bottom) -->
                    <div class="col-12 mt-2">
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent py-2 px-1 border-0 d-flex align-items-center justify-content-between mb-3 border-bottom border-white border-opacity-10 pb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-body">Daftar Aset & Harta Anggota</h6>
                                    <span class="text-secondary small">Kumpulan rincian aset terdaftar</span>
                                </div>
                            </div>
                            
                            <div class="card-body p-0">
                                <!-- Shimmer Skeleton Loader -->
                                <div class="shimmer-wrapper d-none" id="shimmerLoaderHarta">
                                    <div class="shimmer-card"></div>
                                    <div class="shimmer-card"></div>
                                </div>

                                <!-- Assets Grid Container -->
                                <div class="row g-3" id="assetsContainer">
                                    <!-- Populated dynamically via JS -->
                                </div>

                                <!-- Empty State -->
                                <div class="text-center p-5 d-none bg-body-tertiary rounded-4" id="emptyHarta">
                                    <i class="ti ti-wallet-off fs-1 text-secondary opacity-50 mb-3"></i>
                                    <p class="text-secondary mb-0 fw-medium">Belum ada data harta yang tercatat.</p>
                                    <span class="text-muted small">Silakan tambah data di form atas.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Specific styling for Asset Cards */
.asset-card {
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 0.85rem;
    padding: 1.25rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    height: 100%;
}

[data-bs-theme="dark"] .asset-card {
    background: rgba(30, 41, 59, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.asset-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
    background: var(--bs-body-bg);
}

.asset-icon-badge {
    width: 3rem;
    height: 3rem;
    border-radius: 0.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform var(--transition-fast) ease;
}

.asset-card:hover .asset-icon-badge {
    transform: scale(1.08);
}

.asset-price {
    font-size: 1.1rem;
    font-weight: 700;
}

.asset-actions {
    opacity: 0.3;
    transition: opacity var(--transition-fast) ease;
}

.asset-card:hover .asset-actions {
    opacity: 1;
}
</style>
@endpush

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
            $('#assetsContainer').html('');
            $('#emptyHarta').addClass('d-none');
            $('#shimmerLoaderHarta').removeClass('d-none');
            
            $.get("{{ url('admin/anggota') }}/" + id + "/harta", function(data) {
                $('#modalHartaSubtitle').text('Anggota: ' + data.anggota.nama_anggota);
                renderHartaCards(data.harta);
                modalHarta.show();
            }).fail(function(xhr) {
                Swal.fire('Error', 'Gagal memuat data harta.', 'error');
            }).always(function() {
                $('#shimmerLoaderHarta').addClass('d-none');
            });
        }

        // Helper to assign colors/icons based on asset type
        function getAssetTypeStyle(type) {
            switch(type) {
                case 'Tanah dan Bangunan':
                    return {
                        icon: 'ti-home-2',
                        color: '#6366f1', // Indigo
                        bg: 'rgba(99, 102, 241, 0.12)',
                        border: 'border-primary'
                    };
                case 'Alat Transportasi dan Mesin':
                    return {
                        icon: 'ti-car',
                        color: '#0d9488', // Teal
                        bg: 'rgba(13, 148, 136, 0.12)',
                        border: 'border-teal'
                    };
                case 'Harta Bergerak Lainnya':
                    return {
                        icon: 'ti-package',
                        color: '#0ea5e9', // Sky
                        bg: 'rgba(14, 165, 233, 0.12)',
                        border: 'border-info'
                    };
                case 'Surat Berharga':
                    return {
                        icon: 'ti-file-invoice',
                        color: '#8b5cf6', // Violet
                        bg: 'rgba(139, 92, 246, 0.12)',
                        border: 'border-purple'
                    };
                case 'Kas dan Setara Kas':
                    return {
                        icon: 'ti-wallet',
                        color: '#10b981', // Emerald
                        bg: 'rgba(16, 185, 129, 0.12)',
                        border: 'border-success'
                    };
                default:
                    return {
                        icon: 'ti-box',
                        color: '#f59e0b', // Amber
                        bg: 'rgba(245, 158, 11, 0.12)',
                        border: 'border-warning'
                    };
            }
        }

        function renderHartaCards(data) {
            var html = '';
            if(data.length === 0) {
                $('#emptyHarta').removeClass('d-none');
            } else {
                $('#emptyHarta').addClass('d-none');
                $.each(data, function(index, item) {
                    var style = getAssetTypeStyle(item.jenis_harta);
                    var hargaFormat = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(item.harga_perolehan);
                    
                    var delay = index * 0.08;

                    html += '<div class="col-md-6 col-12 timeline-item" style="animation-delay: ' + delay + 's">';
                    html += '  <div class="card asset-card border-0">';
                    html += '    <div class="d-flex align-items-start gap-3">';
                    html += '      <div class="asset-icon-badge" style="background: ' + style.bg + '; color: ' + style.color + '">';
                    html += '        <i class="ti ' + style.icon + ' fs-3"></i>';
                    html += '      </div>';
                    html += '      <div class="flex-grow-1 overflow-hidden">';
                    html += '        <div class="d-flex align-items-center justify-content-between mb-1">';
                    html += '          <span class="badge bg-body-secondary text-secondary small" style="font-size: 0.7rem;">' + item.jenis_harta + '</span>';
                    html += '          <span class="text-secondary small fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;"><i class="ti ti-calendar"></i>' + item.tahun_perolehan + '</span>';
                    html += '        </div>';
                    html += '        <h6 class="fw-bold mb-1 text-body text-truncate" title="' + item.nama_harta + '">' + item.nama_harta + '</h6>';
                    html += '        <p class="text-secondary small mb-2 text-truncate" style="font-size: 0.78rem;">' + (item.keterangan ? item.keterangan : '<span class="text-muted italic">Tidak ada keterangan</span>') + '</p>';
                    
                    html += '        <div class="d-flex align-items-center justify-content-between pt-2 border-top border-white border-opacity-10">';
                    html += '          <div class="asset-price text-primary">' + hargaFormat + '</div>';
                    html += '          <div class="asset-actions d-flex gap-1">';
                    html += '            <button type="button" class="btn-icon-modern text-primary btn-edit-harta" data-id="'+item.id+'" title="Edit"><i class="ti ti-edit"></i></button>';
                    html += '            <button type="button" class="btn-icon-modern text-danger btn-delete-harta" data-id="'+item.id+'" title="Hapus"><i class="ti ti-trash"></i></button>';
                    html += '          </div>';
                    html += '        </div>';
                    html += '      </div>';
                    html += '    </div>';
                    html += '  </div>';
                    html += '</div>';
                });
                $('#assetsContainer').html(html);
            }
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

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                data: formData,
                url: url,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(response) {
                    loadHarta(currentAnggotaIdHarta);
                    resetFormHarta();
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: response.success || 'Data harta berhasil disimpan'
                    });
                },
                error: function(xhr) {
                    var msg = 'Terjadi kesalahan saat menyimpan data.';
                    if(xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            var $input = $('[name="'+key+'"]');
                            $input.addClass('is-invalid');
                            $input.siblings('.invalid-feedback').text(value[0]);
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
            
            $('.premium-card').css('transform', 'scale(0.98)').delay(100).queue(function(next){
                $(this).css('transform', 'none');
                next();
            });

            $.get("{{ url('admin/harta') }}/" + id + "/edit", function(data) {
                // Change UI Header styling to Edit mode
                $('#formHeaderHarta').addClass('edit-mode');
                $('#formHeaderIconHarta').attr('class', 'ti ti-edit fs-5');
                $('#formTitleHarta').text('Edit Data Harta');
                $('#modeBadgeHarta').text('Edit').removeClass('bg-opacity-20').addClass('bg-warning');

                $('#id_harta').val(data.id);
                $('#id_anggota_harta').val(data.id_anggota);
                $('#jenis_harta').val(data.jenis_harta);
                $('#nama_harta').val(data.nama_harta);
                $('#tahun_perolehan').val(data.tahun_perolehan);
                $('#harga_perolehan').val(data.harga_perolehan);
                $('#keterangan').val(data.keterangan);
                
                $('#btnSaveHarta').html('<i class="ti ti-check"></i> Perbarui Data').removeClass('btn-primary').addClass('btn-warning');
                $('.is-invalid').removeClass('is-invalid');
                
                // Scroll form container into view
                document.getElementById('formHeaderHarta').scrollIntoView({ behavior: 'smooth' });
            });
        });

        $(document).on('click', '.btn-delete-harta', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Harta?',
                text: "Data aset/harta ini akan dihapus secara permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/harta') }}/" + id,
                        type: 'DELETE',
                        data: { "_token": "{{ csrf_token() }}" },
                        success: function(response) {
                            loadHarta(currentAnggotaIdHarta);
                            
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            Toast.fire({
                                icon: 'success',
                                title: response.success || 'Data berhasil dihapus'
                            });
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                        }
                    });
                }
            });
        });

        window.resetFormHarta = function() {
            $('#formHarta')[0].reset();
            $('#id_harta').val('');
            $('#id_anggota_harta').val(currentAnggotaIdHarta);
            
            // Revert Header UI back to Add mode
            $('#formHeaderHarta').removeClass('edit-mode');
            $('#formHeaderIconHarta').attr('class', 'ti ti-edit-circle fs-5');
            $('#formTitleHarta').text('Form Tambah Harta');
            $('#modeBadgeHarta').text('Tambah').addClass('bg-opacity-20').removeClass('bg-warning');
            
            $('#btnSaveHarta').html('<i class="ti ti-device-floppy"></i> Simpan Data').removeClass('btn-warning').addClass('btn-primary');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }
    });
</script>
@endpush
