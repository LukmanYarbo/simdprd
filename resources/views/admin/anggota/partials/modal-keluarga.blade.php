<div class="modal fade animate-fade-in" id="modalKeluarga" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-content-premium border-0 shadow-lg">
            <div class="modal-header modal-header-gradient border-bottom-0 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                        <i class="ti ti-users fs-3"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-extrabold text-body">Kelola Anggota Keluarga</h5>
                        <p class="text-secondary mb-0 small" id="modalKeluargaSubtitle">Memuat data anggota...</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Form Input Row (Top) -->
                    <div class="col-12 mb-4">
                        <div class="card premium-card border-0">
                            <div class="card-header premium-card-header border-0 d-flex align-items-center justify-content-between" id="formHeaderKeluarga">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-edit-circle fs-5" id="formHeaderIconKeluarga"></i>
                                    <h6 class="fw-bold mb-0 text-white" id="formTitleKeluarga">Form Tambah Keluarga</h6>
                                </div>
                                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-1 small" id="modeBadgeKeluarga" style="font-size: 0.75rem;">Tambah</span>
                            </div>
                            <div class="card-body p-4">
                                <form id="formKeluarga" enctype="multipart/form-data" class="needs-validation">
                                    @csrf
                                    <input type="hidden" id="id_keluarga" name="id">
                                    <input type="hidden" id="id_anggota_keluarga" name="id_anggota">
                                    
                                    <div class="row g-3">
                                        <!-- Hubungan, NIK & Nama Lengkap -->
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <select class="form-select" id="id_ikatan_keluarga" name="id_ikatan_keluarga" required>
                                                    <!-- Loaded via JS -->
                                                </select>
                                                <label class="small text-secondary">Hubungan Keluarga <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-git-merge"></i></span>
                                                <div class="invalid-feedback">Hubungan wajib dipilih.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="nik_keluarga" name="nik" placeholder="NIK" required>
                                                <label class="small text-secondary">NIK <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-id-badge"></i></span>
                                                <div class="invalid-feedback">NIK wajib diisi.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="nama_keluarga" name="nama" placeholder="Nama Lengkap" required>
                                                <label class="small text-secondary">Nama Lengkap <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-user"></i></span>
                                                <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                                            </div>
                                        </div>

                                        <!-- Tempat Lahir, Tgl Lahir, Umur, Jenis Kelamin -->
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="tempat_lahir_keluarga" name="tempat_lahir" placeholder="Tempat Lahir" required>
                                                <label class="small text-secondary">Tempat Lahir <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-map-pin"></i></span>
                                                <div class="invalid-feedback">Tempat lahir wajib diisi.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <input type="date" class="form-control" id="tgl_lahir_keluarga" name="tgl_lahir" required>
                                                <label class="small text-secondary">Tanggal Lahir <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-calendar"></i></span>
                                                <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control bg-body-secondary" id="umur_keluarga" placeholder="Umur" readonly style="opacity: 0.85;">
                                                <label class="small text-secondary">Umur</label>
                                                <span class="input-icon"><i class="ti ti-hourglass-high"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <select class="form-select" id="jk_keluarga" name="jk" required>
                                                    <option value="L">Laki-laki</option>
                                                    <option value="P">Perempuan</option>
                                                </select>
                                                <label class="small text-secondary">Jenis Kelamin <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-gender-transgender"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <!-- Status Kawin, Pekerjaan, Status Anak, Tunjangan -->
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <select class="form-select" id="id_status_kawin_keluarga" name="id_status_kawin" required>
                                                    <!-- Loaded via JS -->
                                                </select>
                                                <label class="small text-secondary">Status Kawin <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-heart"></i></span>
                                                <div class="invalid-feedback">Status kawin wajib dipilih.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="pekerjaan_keluarga" name="pekerjaan" placeholder="Pekerjaan" required>
                                                <label class="small text-secondary">Pekerjaan <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-briefcase"></i></span>
                                                <div class="invalid-feedback">Pekerjaan wajib diisi.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3" id="div_status_anak" style="display:none;">
                                            <div class="form-floating-modern">
                                                <select class="form-select" id="status_anak" name="status_anak">
                                                    <option value="AK">Anak Kandung</option>
                                                    <option value="AA">Anak Angkat</option>
                                                </select>
                                                <label class="small text-secondary">Status Anak <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-hierarchy-3"></i></span>
                                                <div class="invalid-feedback">Status anak wajib dipilih.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-floating-modern">
                                                <select class="form-select" id="status_tunjangan" name="status_tunjangan" required>
                                                    <option value="Y">Ditunjang</option>
                                                    <option value="T">Tidak Ditunjang</option>
                                                </select>
                                                <label class="small text-secondary">Tunjangan Gaji <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-cash-register"></i></span>
                                                <div class="invalid-feedback">Tunjangan wajib dipilih.</div>
                                            </div>
                                        </div>

                                        <!-- SK Pengadilan & Dropzone Surat Keterangan -->
                                        <div class="col-md-6" id="div_sk_pengadilan" style="display:none;">
                                            <div class="form-floating-modern h-100">
                                                <input type="text" class="form-control" id="no_sk_pengadilan" name="no_sk_pengadilan" placeholder="No. SK Pengadilan">
                                                <label class="small text-secondary">No. SK Pengadilan Anak Angkat</label>
                                                <span class="input-icon"><i class="ti ti-scale-outline"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6" id="div_file_surat_ket" style="display:none;">
                                            <div class="upload-zone" id="uploadZoneKeluarga">
                                                <input type="file" class="d-none" id="file_surat_ket" name="file_surat_ket" accept=".pdf,image/*">
                                                <div class="upload-zone-content py-2 text-center" id="uploadContentKeluarga">
                                                    <i class="ti ti-cloud-upload text-primary fs-3 mb-1"></i>
                                                    <p class="mb-0 fw-semibold small text-body">Pilih / seret Surat Keterangan (>21 Thn & Tidak Bekerja)</p>
                                                    <span class="text-secondary small" style="font-size: 0.72rem;">Maksimal file 2MB</span>
                                                </div>
                                                <div class="upload-zone-preview d-none p-2 align-items-center justify-content-between bg-body-tertiary rounded-3 mt-1" id="uploadPreviewKeluarga">
                                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                        <i class="ti ti-file-certificate text-danger fs-3" id="previewFileIconKeluarga"></i>
                                                        <div class="overflow-hidden">
                                                            <div class="fw-semibold text-truncate small" id="previewFileNameKeluarga" style="max-width: 250px;">-</div>
                                                            <span class="text-secondary small" style="font-size: 0.7rem;" id="previewFileSizeKeluarga">-</span>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-icon-modern text-danger" id="btnRemoveFileKeluarga" title="Hapus File" style="background: transparent;">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-text small" id="link_file_surat_ket"></div>
                                            <div class="invalid-feedback" id="file_surat_ket_feedback"></div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4 pt-3 border-top gap-2">
                                        <button type="button" class="btn btn-light rounded-pill px-3 transition-base" onclick="resetFormKeluarga()">
                                            <i class="ti ti-rotate-clockwise-2 text-secondary"></i> Batal / Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm transition-base" id="btnSaveKeluarga">
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
                                    <h6 class="fw-bold mb-0 text-body">Daftar Anggota Keluarga</h6>
                                    <span class="text-secondary small">Anggota keluarga terdata di tunjangan</span>
                                </div>
                            </div>
                            
                            <div class="card-body p-0">
                                <!-- Shimmer Skeleton Loader -->
                                <div class="shimmer-wrapper d-none" id="shimmerLoaderKeluarga">
                                    <div class="shimmer-card"></div>
                                    <div class="shimmer-card"></div>
                                </div>

                                <!-- Profiles Grid Container -->
                                <div class="row g-3" id="familyContainer">
                                    <!-- Populated dynamically via JS -->
                                </div>

                                <!-- Empty State -->
                                <div class="text-center p-5 d-none bg-body-tertiary rounded-4" id="emptyKeluarga">
                                    <i class="ti ti-users-group fs-1 text-secondary opacity-50 mb-3"></i>
                                    <p class="text-secondary mb-0 fw-medium">Belum ada data anggota keluarga yang tercatat.</p>
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
/* Specific styling for Family Profile Cards */
.profile-card {
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 0.85rem;
    padding: 1.25rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    height: 100%;
}

[data-bs-theme="dark"] .profile-card {
    background: rgba(30, 41, 59, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.profile-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
    background: var(--bs-body-bg);
}

.avatar-badge {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform var(--transition-fast) ease;
    border: 2px solid white;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

[data-bs-theme="dark"] .avatar-badge {
    border-color: #1e293b;
}

.profile-card:hover .avatar-badge {
    transform: scale(1.08);
}

.profile-name {
    font-size: 0.95rem;
    font-weight: 700;
}

.profile-actions {
    opacity: 0.3;
    transition: opacity var(--transition-fast) ease;
}

.profile-card:hover .profile-actions {
    opacity: 1;
}

.badge-glow-success {
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.3) !important;
}

.badge-glow-secondary {
    border: 1px solid rgba(148, 163, 184, 0.3) !important;
}
</style>
@endpush

@push('scripts')
<script>
    $(function() {
        const modalKeluarga = new bootstrap.Modal(document.getElementById('modalKeluarga'));
        let currentAnggotaId = null;
        let currentKeluargaData = [];

        // Drag & Drop / File Input Elements
        const $uploadZoneK = $('#uploadZoneKeluarga');
        const $fileInputK = $('#file_surat_ket');
        const $uploadContentK = $('#uploadContentKeluarga');
        const $uploadPreviewK = $('#uploadPreviewKeluarga');
        const $previewFileNameK = $('#previewFileNameKeluarga');
        const $previewFileSizeK = $('#previewFileSizeKeluarga');
        const $previewFileIconK = $('#previewFileIconKeluarga');
        const $btnRemoveFileK = $('#btnRemoveFileKeluarga');

        $(document).on('click', '.btn-keluarga', function() {
            var id = $(this).data('id');
            currentAnggotaId = id;
            loadKeluarga(id);
        });

        function loadKeluarga(id) {
            $('#id_anggota_keluarga').val(id);
            $('#familyContainer').html('');
            $('#emptyKeluarga').addClass('d-none');
            $('#shimmerLoaderKeluarga').removeClass('d-none');
            
            $.get("{{ url('admin/anggota') }}/" + id + "/keluarga", function(data) {
                $('#modalKeluargaSubtitle').text('Anggota: ' + data.anggota.nama_anggota);
                currentKeluargaData = data.keluarga;
                
                // Populate Selects
                populateSelect('#id_ikatan_keluarga', data.ikatan_keluarga, 'id', 'nama', 'Pilih Hubungan');
                populateSelect('#id_status_kawin_keluarga', data.status_kawin, 'id', 'nama', 'Pilih Status Kawin');

                // Filter relationship options based on gender
                filterRelationship(data.anggota.jk);
                toggleChildFields();

                renderKeluargaCards(data.keluarga);
                modalKeluarga.show();
            }).fail(function(xhr) {
                Swal.fire('Error', 'Gagal memuat data keluarga.', 'error');
            }).always(function() {
                $('#shimmerLoaderKeluarga').addClass('d-none');
            });
        }

        function filterRelationship(genderAnggota) {
            var options = $('#id_ikatan_keluarga option');
            options.show();
            options.each(function() {
                var text = $(this).text();
                if (genderAnggota === 'L' && text === 'Suami') {
                    $(this).hide();
                } else if (genderAnggota === 'P' && text === 'Istri') {
                    $(this).hide();
                }
            });
        }

        // Auto-fill gender based on relations selection
        $('#id_ikatan_keluarga').change(function() {
            var text = $("#id_ikatan_keluarga option:selected").text();
            if (text === 'Suami') {
                $('#jk_keluarga').val('L');
            } else if (text === 'Istri') {
                $('#jk_keluarga').val('P');
            }
            toggleChildFields();
            checkFileSuratKet();
        });

        $('#status_anak').change(function() {
            toggleChildFields();
            checkFileSuratKet();
        });

        function toggleChildFields() {
            var relationText = $("#id_ikatan_keluarga option:selected").text();
            var isChild = relationText.toLowerCase().includes('anak');
            
            if (isChild) {
                $('#div_status_anak').show();
                $('#status_anak').prop('required', true);
                
                var statusAnak = $('#status_anak').val();
                if (statusAnak === 'AA') {
                    $('#div_sk_pengadilan').show();
                } else {
                    $('#div_sk_pengadilan').hide();
                    $('#no_sk_pengadilan').val('');
                }
            } else {
                $('#div_status_anak').hide();
                $('#status_anak').prop('required', false);
                $('#status_anak').val('');

                $('#div_sk_pengadilan').hide();
                $('#no_sk_pengadilan').val('');
            }
        }

        $('#tgl_lahir_keluarga').on('change', function() {
            var dobVal = $(this).val();
            calculateAge(dobVal);
            checkFileSuratKet();
        });

        function calculateAge(dobVal) {
            var dob = new Date(dobVal);
            var today = new Date();
            if (isNaN(dob.getTime())) {
                $('#umur_keluarga').val('');
                return 0;
            }
            var age = today.getFullYear() - dob.getFullYear();
            var m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            $('#umur_keluarga').val(age + ' Tahun');
            return age;
        }

        function checkFileSuratKet() {
            var relationText = $("#id_ikatan_keluarga option:selected").text();
            var isChild = relationText.toLowerCase().includes('anak') || ['AK', 'AA'].includes($('#status_anak').val());
            
            var dobVal = $('#tgl_lahir_keluarga').val();
            var age = 0;
            if (dobVal) {
                var dob = new Date(dobVal);
                var today = new Date();
                age = today.getFullYear() - dob.getFullYear();
                var m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
            }

            if (isChild && age >= 21) {
                $('#div_file_surat_ket').show();
            } else {
                $('#div_file_surat_ket').hide();
                if (!$('#id_keluarga').val()) {
                    $fileInputK.val('');
                    resetUploadZoneK();
                }
            }
        }

        function renderKeluargaCards(data) {
            var html = '';
            if(data.length === 0) {
                $('#emptyKeluarga').removeClass('d-none');
            } else {
                $('#emptyKeluarga').addClass('d-none');
                $.each(data, function(index, item) {
                    var isMale = item.jk == 'L';
                    var avatarBg = isMale ? 'rgba(14, 165, 233, 0.12)' : 'rgba(244, 63, 94, 0.12)';
                    var avatarColor = isMale ? '#0ea5e9' : '#f43f5e';
                    var avatarIcon = isMale ? 'ti-man' : 'ti-woman';
                    
                    var relation = item.ikatan_keluarga ? item.ikatan_keluarga.nama : 'Lainnya';
                    var statusTunjangan = item.status_tunjangan == 'Y' 
                        ? '<span class="badge bg-success-subtle text-success badge-glow-success rounded-pill px-2.5 py-1">Ditunjang</span>' 
                        : '<span class="badge bg-body-secondary text-secondary badge-glow-secondary rounded-pill px-2.5 py-1">Tidak</span>';
                    
                    var fileBtn = item.file_surat_ket 
                        ? '<a href="'+(window.Laravel?.storageUrl || '/storage')+'/'+item.file_surat_ket+'" target="_blank" class="btn btn-icon-modern text-primary ms-1" title="Lihat Surat Keterangan"><i class="ti ti-file-certificate"></i></a>' 
                        : '';

                    var calculatedAge = item.tgl_lahir ? calculateAgeLocal(item.tgl_lahir) + ' Thn' : 'n/a';
                    var jobDisplay = item.pekerjaan ? item.pekerjaan : '-';

                    var delay = index * 0.08;

                    html += '<div class="col-md-6 col-12 timeline-item" style="animation-delay: ' + delay + 's">';
                    html += '  <div class="card profile-card border-0">';
                    html += '    <div class="d-flex align-items-center gap-3">';
                    html += '      <div class="avatar-badge" style="background: ' + avatarBg + '; color: ' + avatarColor + '">';
                    html += '        <i class="ti ' + avatarIcon + ' fs-2"></i>';
                    html += '      </div>';
                    html += '      <div class="flex-grow-1 overflow-hidden">';
                    html += '        <div class="d-flex align-items-center justify-content-between mb-1">';
                    html += '          <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">' + relation + '</span>';
                    html += '          <div class="d-flex align-items-center">';
                    html += '            ' + statusTunjangan;
                    html += '            ' + fileBtn;
                    html += '          </div>';
                    html += '        </div>';
                    html += '        <h6 class="fw-bold mb-0 text-body text-truncate" title="' + item.nama + '">' + item.nama + '</h6>';
                    html += '        <div class="text-secondary small mb-1" style="font-size: 0.76rem;"><i class="ti ti-id me-1"></i>NIK: <b>' + item.nik + '</b></div>';
                    
                    var detailSub = [];
                    detailSub.push('Umur: <b>' + calculatedAge + '</b>');
                    detailSub.push('Kerja: <b>' + jobDisplay + '</b>');
                    if (item.status_anak) {
                        var subAnak = item.status_anak == 'AK' ? 'Kandung' : 'Angkat';
                        detailSub.push('Status: <b>' + subAnak + '</b>');
                    }
                    html += '        <div class="text-muted small mb-2 lh-sm" style="font-size: 0.74rem;">' + detailSub.join(' &bull; ') + '</div>';

                    if (item.no_sk_pengadilan) {
                        html += '        <div class="text-warning small mb-2" style="font-size: 0.74rem;"><i class="ti ti-scale me-1"></i>SK: <b>' + item.no_sk_pengadilan + '</b></div>';
                    }

                    html += '        <div class="d-flex align-items-center justify-content-end pt-2 border-top border-white border-opacity-10">';
                    html += '          <div class="profile-actions d-flex gap-1">';
                    html += '            <button type="button" class="btn-icon-modern text-primary btn-edit-keluarga" data-id="'+item.id+'" title="Edit"><i class="ti ti-edit"></i></button>';
                    html += '            <button type="button" class="btn-icon-modern text-danger btn-delete-keluarga" data-id="'+item.id+'" title="Hapus"><i class="ti ti-trash"></i></button>';
                    html += '          </div>';
                    html += '        </div>';
                    html += '      </div>';
                    html += '    </div>';
                    html += '  </div>';
                    html += '</div>';
                });
                $('#familyContainer').html(html);
            }
        }

        function calculateAgeLocal(dobVal) {
            var dob = new Date(dobVal);
            var today = new Date();
            var age = today.getFullYear() - dob.getFullYear();
            var m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            return age;
        }

        // Drag & Drop event bindings for Surat Keterangan file
        $fileInputK.on('click', function(e) {
            e.stopPropagation();
        });

        $uploadZoneK.on('click', function(e) {
            if ($(e.target).closest('#btnRemoveFileKeluarga').length === 0) {
                $fileInputK.trigger('click');
            }
        });

        $uploadZoneK.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $uploadZoneK.addClass('dragover');
        });

        $uploadZoneK.on('dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $uploadZoneK.removeClass('dragover');
        });

        $uploadZoneK.on('drop', function(e) {
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                $fileInputK[0].files = files;
                $fileInputK.trigger('change');
            }
        });

        $fileInputK.on('change', function() {
            const file = this.files[0];
            if (file) {
                $uploadContentK.addClass('d-none');
                $uploadPreviewK.removeClass('d-none').addClass('d-flex');
                $previewFileNameK.text(file.name);
                
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                $previewFileSizeK.text(sizeInMB + ' MB');

                if (file.type === 'application/pdf') {
                    $previewFileIconK.attr('class', 'ti ti-file-type-pdf text-danger fs-3');
                } else if (file.type.startsWith('image/')) {
                    $previewFileIconK.attr('class', 'ti ti-photo text-success fs-3');
                } else {
                    $previewFileIconK.attr('class', 'ti ti-file-certificate text-secondary fs-3');
                }
                
                $('#file_surat_ket_feedback').text('');
                $fileInputK.removeClass('is-invalid');
            }
        });

        $btnRemoveFileK.on('click', function(e) {
            e.preventDefault();
            resetUploadZoneK();
        });

        function resetUploadZoneK() {
            $fileInputK.val('');
            $uploadPreviewK.addClass('d-none').removeClass('d-flex');
            $uploadContentK.removeClass('d-none');
            $previewFileNameK.text('-');
            $previewFileSizeK.text('-');
        }

        // Validate rules (Max 2 children tunjangan limits & age >= 21) before submitting
        function validateAndSubmit(form) {
            try {
                var status = $('#status_tunjangan').val();
                
                if (status === 'Y') {
                    var currentId = $('#id_keluarga').val();
                    var supportedChildren = currentKeluargaData.filter(function(item) {
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
                        return;
                    }
                }

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
                            return;
                        }
                    }
                }

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

            var btn = $('#btnSaveKeluarga');
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
                    loadKeluarga(currentAnggotaId);
                    resetFormKeluarga();
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: response.success || 'Data keluarga berhasil disimpan'
                    });
                },
                error: function(xhr) {
                    var msg = 'Terjadi kesalahan saat menyimpan data.';
                    if(xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            if (key === 'file_surat_ket') {
                                $('#file_surat_ket_feedback').text(value[0]);
                                $fileInputK.addClass('is-invalid');
                            } else {
                                var $input = $('[name="'+key+'"]');
                                $input.addClass('is-invalid');
                                $input.siblings('.invalid-feedback').text(value[0]);
                            }
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

        $('#formKeluarga').submit(function(e) {
            e.preventDefault();
            validateAndSubmit(this);
        });

        $(document).on('click', '.btn-edit-keluarga', function() {
            var id = $(this).data('id');
            
            $('.premium-card').css('transform', 'scale(0.98)').delay(100).queue(function(next){
                $(this).css('transform', 'none');
                next();
            });

            $.get("{{ url('admin/keluarga') }}/" + id + "/edit", function(data) {
                $('#formHeaderKeluarga').addClass('edit-mode');
                $('#formHeaderIconKeluarga').attr('class', 'ti ti-edit fs-5');
                $('#formTitleKeluarga').text('Edit Data Anggota Keluarga');
                $('#modeBadgeKeluarga').text('Edit').removeClass('bg-opacity-20').addClass('bg-warning');

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
                
                // Show child specific fields if child
                toggleChildFields(); 
                $('#status_anak').val(data.status_anak);
                toggleChildFields(); // Update relation subfields again
                
                $('#status_tunjangan').val(data.status_tunjangan);
                $('#no_sk_pengadilan').val(data.no_sk_pengadilan);
                
                checkFileSuratKet(); // Check date and relation triggers

                // File handle
                if (data.file_surat_ket) {
                    $uploadContentK.addClass('d-none');
                    $uploadPreviewK.removeClass('d-none').addClass('d-flex');
                    $previewFileNameK.text(data.file_surat_ket.split('/').pop());
                    $previewFileSizeK.html('<a href="'+(window.Laravel?.storageUrl || '/storage')+'/'+data.file_surat_ket+'" target="_blank" class="text-primary text-decoration-none fw-bold"><i class="ti ti-eye"></i> Lihat Surat</a>');
                    $previewFileIconK.attr('class', 'ti ti-file-check text-success fs-3');
                    $('#link_file_surat_ket').html('');
                } else {
                    resetUploadZoneK();
                    $('#link_file_surat_ket').html('');
                }

                $('#btnSaveKeluarga').html('<i class="ti ti-check"></i> Perbarui Data').removeClass('btn-primary').addClass('btn-warning');
                $('.is-invalid').removeClass('is-invalid');
                
                // Scroll form container into view
                document.getElementById('formHeaderKeluarga').scrollIntoView({ behavior: 'smooth' });
            });
        });

        $(document).on('click', '.btn-delete-keluarga', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Anggota Keluarga?',
                text: "Data hubungan keluarga ini akan dihapus secara permanen.",
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
                        url: "{{ url('admin/keluarga') }}/" + id,
                        type: 'DELETE',
                        data: { "_token": "{{ csrf_token() }}" },
                        success: function(response) {
                            loadKeluarga(currentAnggotaId);
                            
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

        window.resetFormKeluarga = function() {
            $('#formKeluarga')[0].reset();
            $('#id_keluarga').val('');
            $('#id_anggota_keluarga').val(currentAnggotaId);
            resetUploadZoneK();
            $('#link_file_surat_ket').html('');
            $('#div_file_surat_ket').hide();
            $('#div_status_anak').hide();
            $('#div_sk_pengadilan').hide();
            
            // Revert Header UI back to Add mode
            $('#formHeaderKeluarga').removeClass('edit-mode');
            $('#formHeaderIconKeluarga').attr('class', 'ti ti-edit-circle fs-5');
            $('#formTitleKeluarga').text('Form Tambah Keluarga');
            $('#modeBadgeKeluarga').text('Tambah').addClass('bg-opacity-20').removeClass('bg-warning');
            
            $('#btnSaveKeluarga').html('<i class="ti ti-device-floppy"></i> Simpan Data').removeClass('btn-warning').addClass('btn-primary');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }
    });
</script>
@endpush
