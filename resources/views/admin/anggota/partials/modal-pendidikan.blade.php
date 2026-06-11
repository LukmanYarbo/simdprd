<div class="modal fade animate-fade-in" id="modalPendidikan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content modal-content-premium border-0 shadow-lg">
            <div class="modal-header modal-header-gradient border-bottom-0 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3">
                        <i class="ti ti-school fs-3"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-extrabold text-body">Kelola Data Pendidikan</h5>
                        <p class="text-secondary mb-0 small" id="modalPendidikanSubtitle">Memuat data anggota...</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <!-- Form Input Row (Top) -->
                    <div class="col-12 mb-4">
                        <div class="card premium-card border-0">
                            <div class="card-header premium-card-header border-0 d-flex align-items-center justify-content-between" id="formHeaderPendidikan">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ti ti-edit-circle fs-5" id="formHeaderIcon"></i>
                                    <h6 class="fw-bold mb-0 text-white" id="formTitlePendidikan">Form Tambah Pendidikan</h6>
                                </div>
                                <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-1 small" id="modeBadge" style="font-size: 0.75rem;">Tambah</span>
                            </div>
                            <div class="card-body p-4">
                                <form id="formPendidikan" enctype="multipart/form-data" class="needs-validation">
                                    @csrf
                                    <input type="hidden" id="id_pendidikan" name="id">
                                    <input type="hidden" id="id_anggota_pendidikan" name="id_anggota">
                                    
                                    <div class="row g-3">
                                        <!-- Tingkat Pendidikan & Nama Institusi -->
                                        <div class="col-md-6">
                                            <div class="form-floating-modern">
                                                <select class="form-select" id="id_jenis_pendidikan" name="id_jenis_pendidikan" required>
                                                    <option value="">Pilih Tingkat</option>
                                                </select>
                                                <label class="small text-secondary">Tingkat Pendidikan <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-certificate"></i></span>
                                                <div class="invalid-feedback">Tingkat pendidikan wajib dipilih.</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="tempat_pendidikan" name="tempat_pendidikan" placeholder="Nama Institusi" required>
                                                <label class="small text-secondary">Nama Institusi / Universitas <span class="text-danger">*</span></label>
                                                <span class="input-icon"><i class="ti ti-building-community"></i></span>
                                                <div class="invalid-feedback">Nama institusi wajib diisi.</div>
                                            </div>
                                        </div>

                                        <!-- Jurusan, Program Studi, Fakultas -->
                                        <div class="col-md-4">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="jurusan" name="jurusan" placeholder="Jurusan">
                                                <label class="small text-secondary">Jurusan</label>
                                                <span class="input-icon"><i class="ti ti-compass"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="program_studi" name="program_studi" placeholder="Program Studi">
                                                <label class="small text-secondary">Program Studi</label>
                                                <span class="input-icon"><i class="ti ti-book-2"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="fakultas" name="fakultas" placeholder="Fakultas">
                                                <label class="small text-secondary">Fakultas</label>
                                                <span class="input-icon"><i class="ti ti-award"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <!-- NIM, Tahun Masuk, Tahun Lulus -->
                                        <div class="col-md-4">
                                            <div class="form-floating-modern">
                                                <input type="text" class="form-control" id="no_induk" name="no_induk" placeholder="NIM">
                                                <label class="small text-secondary">NIM / No. Induk</label>
                                                <span class="input-icon"><i class="ti ti-id"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating-modern">
                                                <input type="number" class="form-control" id="tahun_masuk" name="tahun_masuk" placeholder="Thn Masuk" min="1900" max="2100">
                                                <label class="small text-secondary">Tahun Masuk</label>
                                                <span class="input-icon"><i class="ti ti-calendar"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating-modern">
                                                <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" placeholder="Thn Lulus" min="1900" max="2100">
                                                <label class="small text-secondary">Tahun Lulus</label>
                                                <span class="input-icon"><i class="ti ti-calendar-check"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <!-- No Seri Ijazah & Drag and Drop File Upload -->
                                        <div class="col-md-6">
                                            <div class="form-floating-modern h-100">
                                                <input type="text" class="form-control" id="no_ijazah" name="no_ijazah" placeholder="No. Ijazah">
                                                <label class="small text-secondary">No. Seri Ijazah</label>
                                                <span class="input-icon"><i class="ti ti-file-description"></i></span>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="upload-zone" id="uploadZone">
                                                <input type="file" class="d-none" id="file_ijazah" name="file_ijazah" accept=".pdf,image/*">
                                                <div class="upload-zone-content py-2 text-center" id="uploadContent">
                                                    <i class="ti ti-cloud-upload text-primary fs-3 mb-1"></i>
                                                    <p class="mb-0 fw-semibold small text-body">Pilih file ijazah atau seret ke sini (PDF/JPG/PNG)</p>
                                                    <span class="text-secondary small" style="font-size: 0.72rem;">Maksimal file 2MB</span>
                                                </div>
                                                <div class="upload-zone-preview d-none p-2 align-items-center justify-content-between bg-body-tertiary rounded-3 mt-1" id="uploadPreview">
                                                    <div class="d-flex align-items-center gap-2 overflow-hidden">
                                                        <i class="ti ti-file-certificate text-danger fs-3" id="previewFileIcon"></i>
                                                        <div class="overflow-hidden">
                                                            <div class="fw-semibold text-truncate small" id="previewFileName" style="max-width: 250px;">-</div>
                                                            <span class="text-secondary small" style="font-size: 0.7rem;" id="previewFileSize">-</span>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-icon-modern text-danger" id="btnRemoveFile" title="Hapus File" style="background: transparent;">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="form-text small" id="current_file_ijazah"></div>
                                            <div class="invalid-feedback" id="file_ijazah_feedback"></div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end mt-4 pt-3 border-top gap-2">
                                        <button type="button" class="btn btn-light rounded-pill px-3 transition-base" onclick="resetFormPendidikan()">
                                            <i class="ti ti-rotate-clockwise-2 text-secondary"></i> Batal / Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm transition-base" id="btnSavePendidikan">
                                            <i class="ti ti-device-floppy"></i> Simpan Data
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline Row (Bottom) -->
                    <div class="col-12 mt-2">
                        <div class="card border-0 bg-transparent">
                            <div class="card-header bg-transparent py-2 px-1 border-0 d-flex align-items-center justify-content-between mb-3 border-bottom border-white border-opacity-10 pb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-body">Riwayat Jenjang Pendidikan</h6>
                                    <span class="text-secondary small">Kronologi studi berurutan</span>
                                </div>
                            </div>
                            
                            <div class="card-body p-0">
                                <!-- Shimmer Skeleton Loader -->
                                <div class="shimmer-wrapper d-none" id="shimmerLoader">
                                    <div class="shimmer-card"></div>
                                    <div class="shimmer-card"></div>
                                </div>

                                <!-- Chronological Timeline -->
                                <div class="timeline-modern" id="timelinePendidikan">
                                    <!-- Populated dynamically via AJAX -->
                                </div>

                                <!-- Empty State -->
                                <div class="text-center p-5 d-none bg-body-tertiary rounded-4" id="emptyTimeline">
                                    <i class="ti ti-school-off fs-1 text-secondary opacity-50 mb-3"></i>
                                    <p class="text-secondary mb-0 fw-medium">Belum ada riwayat pendidikan yang tercatat.</p>
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
/* Modern Glassmorphic Card inside Modal */
.modal-content-premium {
    background: var(--glass-bg);
    backdrop-filter: var(--glass-backdrop);
    -webkit-backdrop-filter: var(--glass-backdrop);
    border: var(--glass-border);
    border-radius: 1.25rem;
    box-shadow: var(--glass-shadow);
}

/* Premium Gradient Header */
.modal-header-gradient {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.08) 0%, rgba(14, 165, 233, 0.08) 100%);
    border-bottom: 1px solid rgba(var(--bs-primary-rgb), 0.08);
    border-radius: 1.25rem 1.25rem 0 0;
}

.premium-card {
    background: rgba(255, 255, 255, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 1rem;
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.02);
    transition: all var(--transition-base);
}

[data-bs-theme="dark"] .premium-card {
    background: rgba(30, 41, 59, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.04);
    box-shadow: 0 4px 25px rgba(0, 0, 0, 0.15);
}

.premium-card-header {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-primary-dark) 100%);
    color: white;
    border-radius: 1rem 1rem 0 0 !important;
    padding: 1rem 1.25rem;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.premium-card-header.edit-mode {
    background: linear-gradient(135deg, var(--bs-warning) 0%, #d97706 100%);
}

/* Floating Label & Custom Input Styling */
.form-floating-modern {
    position: relative;
}

.form-floating-modern .input-icon {
    position: absolute;
    left: 0.85rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--bs-secondary);
    font-size: 1.15rem;
    pointer-events: none;
    transition: color 0.3s ease;
    z-index: 4;
}

.form-floating-modern .form-control,
.form-floating-modern .form-select {
    padding-left: 2.6rem !important;
    border-radius: 0.75rem;
    border: 1px solid var(--bs-border-color);
    background-color: rgba(var(--bs-tertiary-bg-rgb), 0.45);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: calc(3.1rem + 2px);
    font-size: 0.875rem;
}

.form-floating-modern .form-control:focus,
.form-floating-modern .form-select:focus {
    background-color: var(--bs-body-bg);
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.15);
}

.form-floating-modern .form-control:focus ~ .input-icon,
.form-floating-modern .form-select:focus ~ .input-icon {
    color: var(--bs-primary);
}

.form-floating-modern label {
    padding-left: 2.6rem;
    font-size: 0.825rem;
    color: var(--bs-secondary-color);
    transition: transform .2s ease-in-out, opacity .2s ease-in-out;
}

.form-floating-modern .form-control:focus ~ label,
.form-floating-modern .form-control:not(:placeholder-shown) ~ label,
.form-floating-modern .form-select:focus ~ label,
.form-floating-modern .form-select:not([value=""]):valid ~ label {
    transform: scale(0.85) translateY(-0.75rem) translateX(0.15rem);
    padding-left: 2.6rem;
}

/* Modern Drag-and-Drop File Upload */
.upload-zone {
    border: 2px dashed rgba(var(--bs-primary-rgb), 0.3);
    border-radius: 0.75rem;
    padding: 0.8rem 1.25rem;
    background: rgba(var(--bs-primary-rgb), 0.02);
    cursor: pointer;
    transition: all var(--transition-base);
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(3.1rem + 2px);
}

.upload-zone:hover, .upload-zone.dragover {
    border-color: var(--bs-primary);
    background: rgba(var(--bs-primary-rgb), 0.06);
}

.btn-icon-modern {
    width: 2rem;
    height: 2rem;
    padding: 0 !important;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    border: none;
    background: rgba(var(--bs-tertiary-bg-rgb), 0.8);
    color: var(--bs-body-color);
    transition: all var(--transition-fast);
}

.btn-icon-modern:hover {
    background: var(--bs-primary);
    color: white;
}

.btn-icon-modern.text-danger:hover {
    background: var(--bs-danger);
    color: white;
}

/* Timeline Components */
.timeline-modern {
    position: relative;
    padding-left: 2rem;
    margin-left: 0.75rem;
}

.timeline-modern::before {
    content: '';
    position: absolute;
    left: 0;
    top: 8px;
    bottom: 8px;
    width: 2px;
    background: rgba(var(--bs-primary-rgb), 0.15);
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.45s forwards cubic-bezier(0.2, 0.8, 0.2, 1);
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 4px;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;
    background: var(--bs-body-bg);
    border: 3px solid var(--bs-primary);
    transform: translateX(-50%);
    box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.15);
    z-index: 2;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}

.timeline-item:hover .timeline-marker {
    transform: translateX(-50%) scale(1.2);
}

.timeline-card {
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 0.85rem;
    padding: 1.25rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.01);
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    position: relative;
    border-left: 4px solid var(--bs-primary);
}

[data-bs-theme="dark"] .timeline-card {
    background: rgba(30, 41, 59, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.05);
}

.timeline-card:hover {
    transform: translateX(4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
    background: var(--bs-body-bg);
}

/* Dynamic Marker Levels styles */
.level-badge {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 0.25rem 0.5rem;
    border-radius: 0.5rem;
    letter-spacing: 0.05em;
}

.timeline-actions {
    opacity: 0.3;
    transition: opacity var(--transition-fast) ease;
}

.timeline-card:hover .timeline-actions {
    opacity: 1;
}

/* Timeline skeleton shimmer */
.shimmer-wrapper {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    width: 100%;
}
.shimmer-card {
    height: 110px;
    border-radius: 0.85rem;
    background: linear-gradient(90deg, rgba(255, 255, 255, 0.05) 25%, rgba(255, 255, 255, 0.1) 37%, rgba(255, 255, 255, 0.05) 63%);
    background-size: 400% 100%;
    animation: shimmer 1.4s ease infinite;
}
[data-bs-theme="light"] .shimmer-card {
    background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 37%, #f1f5f9 63%);
    background-size: 400% 100%;
}

@keyframes shimmer {
    0% { background-position: 100% 50%; }
    100% { background-position: 0 50%; }
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
    $(function() {
        const modalPendidikan = new bootstrap.Modal(document.getElementById('modalPendidikan'));
        let currentAnggotaIdPendidikan = null;

        // Drag & Drop / File Input Elements
        const $uploadZone = $('#uploadZone');
        const $fileInput = $('#file_ijazah');
        const $uploadContent = $('#uploadContent');
        const $uploadPreview = $('#uploadPreview');
        const $previewFileName = $('#previewFileName');
        const $previewFileSize = $('#previewFileSize');
        const $previewFileIcon = $('#previewFileIcon');
        const $btnRemoveFile = $('#btnRemoveFile');

        // Trigger Modal Opening
        $(document).on('click', '.btn-pendidikan', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            currentAnggotaIdPendidikan = id;
            loadPendidikan(id);
        });

        // Load Member Education Data via AJAX
        function loadPendidikan(id) {
            $('#id_anggota_pendidikan').val(id);
            $('#timelinePendidikan').html('');
            $('#emptyTimeline').addClass('d-none');
            $('#shimmerLoader').removeClass('d-none');
            
            $.get("{{ url('admin/anggota') }}/" + id + "/pendidikan", function(data) {
                $('#modalPendidikanSubtitle').text('Anggota: ' + data.anggota.nama_anggota);
                
                // Populate Select Option
                var select = $('#id_jenis_pendidikan');
                select.empty().append('<option value="">Pilih Tingkat</option>');
                $.each(data.jenis_pendidikan, function(key, val) {
                    select.append('<option value="'+val.id+'">'+val.nama+'</option>');
                });

                renderPendidikanTimeline(data.pendidikan);
                modalPendidikan.show();
            }).fail(function(xhr) {
                Swal.fire('Error', 'Gagal memuat data pendidikan.', 'error');
            }).always(function() {
                $('#shimmerLoader').addClass('d-none');
            });
        }

        // Color coding style based on level name
        function getLevelStyle(name) {
            name = name.toUpperCase();
            if (name.includes('S.III') || name.includes('DR') || name.includes('S.II')) {
                return {
                    color: '#8b5cf6', // Violet
                    bg: 'rgba(139, 92, 246, 0.15)',
                    border: '#a78bfa',
                    badge: 'bg-purple-subtle text-purple'
                };
            } else if (name.includes('S.I') || name.includes('D.') || name.includes('SARJANA')) {
                return {
                    color: '#6366f1', // Indigo
                    bg: 'rgba(99, 102, 241, 0.15)',
                    border: '#818cf8',
                    badge: 'bg-primary-subtle text-primary'
                };
            } else if (name.includes('SMA') || name.includes('SMK') || name.includes('SLTA')) {
                return {
                    color: '#0ea5e9', // Sky / Cyan
                    bg: 'rgba(14, 165, 233, 0.15)',
                    border: '#38bdf8',
                    badge: 'bg-info-subtle text-info'
                };
            } else if (name.includes('SMP') || name.includes('SLTP')) {
                return {
                    color: '#0d9488', // Teal
                    bg: 'rgba(13, 148, 136, 0.15)',
                    border: '#2dd4bf',
                    badge: 'bg-teal-subtle text-teal'
                };
            } else {
                return {
                    color: '#64748b', // Slate
                    bg: 'rgba(100, 116, 139, 0.15)',
                    border: '#94a3b8',
                    badge: 'bg-secondary-subtle text-secondary'
                };
            }
        }

        // Render Chronological Timeline View
        function renderPendidikanTimeline(data) {
            var html = '';
            if(data.length === 0) {
                $('#timelinePendidikan').html('');
                $('#emptyTimeline').removeClass('d-none');
            } else {
                $('#emptyTimeline').addClass('d-none');
                $.each(data, function(index, item) {
                    var levelName = item.jenis_pendidikan ? item.jenis_pendidikan.nama : 'Lainnya';
                    var style = getLevelStyle(levelName);
                    
                    var fileLink = item.file_ijazah 
                        ? '<a href="/storage/'+item.file_ijazah+'" target="_blank" class="btn btn-xs btn-outline-primary rounded-pill px-2.5 py-1 text-decoration-none d-inline-flex align-items-center gap-1.5"><i class="ti ti-file-type-pdf text-danger"></i> Ijazah</a>' 
                        : '<span class="text-secondary small" style="font-size: 0.75rem;"><i class="ti ti-ban text-muted"></i> No File</span>';
                    
                    var yearDisplay = '';
                    if (item.tahun_masuk && item.tahun_lulus) {
                        yearDisplay = item.tahun_masuk + ' — ' + item.tahun_lulus;
                    } else if (item.tahun_lulus) {
                        yearDisplay = 'Lulus ' + item.tahun_lulus;
                    } else if (item.tahun_masuk) {
                        yearDisplay = 'Masuk ' + item.tahun_masuk;
                    } else {
                        yearDisplay = 'Tahun n/a';
                    }

                    var extraDetails = [];
                    if (item.jurusan) extraDetails.push('Jurusan: <b>' + item.jurusan + '</b>');
                    if (item.program_studi) extraDetails.push('Prodi: <b>' + item.program_studi + '</b>');
                    if (item.fakultas) extraDetails.push('Fakultas: <b>' + item.fakultas + '</b>');
                    if (item.no_induk) extraDetails.push('NIM/Induk: <b>' + item.no_induk + '</b>');

                    var delay = index * 0.1;

                    html += '<div class="timeline-item" style="animation-delay: ' + delay + 's">';
                    html += '  <div class="timeline-marker" style="border-color: ' + style.color + '; box-shadow: 0 0 0 4px ' + style.bg + '"></div>';
                    html += '  <div class="timeline-card" style="border-left-color: ' + style.color + '">';
                    html += '    <div class="d-flex align-items-center justify-content-between mb-2">';
                    html += '      <span class="badge ' + style.badge + ' level-badge">' + levelName + '</span>';
                    html += '      <span class="text-secondary fw-semibold small" style="font-size: 0.78rem;"><i class="ti ti-calendar-event me-1"></i>' + yearDisplay + '</span>';
                    html += '    </div>';
                    html += '    <h6 class="fw-bold mb-1 text-body-emphasis">' + item.tempat_pendidikan + '</h6>';
                    
                    if (extraDetails.length > 0) {
                        html += '    <div class="text-secondary small mb-2 lh-sm" style="font-size: 0.8rem;">' + extraDetails.join(' &bull; ') + '</div>';
                    }
                    
                    if (item.no_ijazah) {
                        html += '    <div class="text-muted small mb-2" style="font-size: 0.75rem;"><i class="ti ti-license me-1"></i>No. Ijazah: <b>' + item.no_ijazah + '</b></div>';
                    }

                    html += '    <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top border-white border-opacity-10">';
                    html += '      <div>' + fileLink + '</div>';
                    html += '      <div class="timeline-actions d-flex gap-1">';
                    html += '        <button type="button" class="btn-icon-modern text-primary btn-edit-pendidikan" data-id="'+item.id+'" title="Edit"><i class="ti ti-edit"></i></button>';
                    html += '        <button type="button" class="btn-icon-modern text-danger btn-delete-pendidikan" data-id="'+item.id+'" title="Hapus"><i class="ti ti-trash"></i></button>';
                    html += '      </div>';
                    html += '    </div>';
                    html += '  </div>';
                    html += '</div>';
                });
                $('#timelinePendidikan').html(html);
            }
        }

        // Drag and Drop Zone Interactivity
        $fileInput.on('click', function(e) {
            e.stopPropagation();
        });

        $uploadZone.on('click', function(e) {
            if ($(e.target).closest('#btnRemoveFile').length === 0) {
                $fileInput.trigger('click');
            }
        });

        $uploadZone.on('dragover dragenter', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $uploadZone.addClass('dragover');
        });

        $uploadZone.on('dragleave drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $uploadZone.removeClass('dragover');
        });

        $uploadZone.on('drop', function(e) {
            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                $fileInput[0].files = files;
                $fileInput.trigger('change');
            }
        });

        $fileInput.on('change', function() {
            const file = this.files[0];
            if (file) {
                $uploadContent.addClass('d-none');
                $uploadPreview.removeClass('d-none').addClass('d-flex');
                $previewFileName.text(file.name);
                
                // Format file size
                const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                $previewFileSize.text(sizeInMB + ' MB');

                // Adjust icon based on type
                if (file.type === 'application/pdf') {
                    $previewFileIcon.attr('class', 'ti ti-file-type-pdf text-danger fs-3');
                } else if (file.type.startsWith('image/')) {
                    $previewFileIcon.attr('class', 'ti ti-photo text-success fs-3');
                } else {
                    $previewFileIcon.attr('class', 'ti ti-file-certificate text-secondary fs-3');
                }
                
                $('#file_ijazah_feedback').text('');
                $fileInput.removeClass('is-invalid');
            }
        });

        $btnRemoveFile.on('click', function(e) {
            e.preventDefault();
            resetUploadZone();
        });

        function resetUploadZone() {
            $fileInput.val('');
            $uploadPreview.addClass('d-none').removeClass('d-flex');
            $uploadContent.removeClass('d-none');
            $previewFileName.text('-');
            $previewFileSize.text('-');
        }

        // Submit Form via AJAX (Add or Edit)
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

            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                data: formData,
                url: url,
                type: "POST",
                contentType: false,
                processData: false,
                success: function(response) {
                    loadPendidikan(currentAnggotaIdPendidikan);
                    resetFormPendidikan();
                    
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    
                    Toast.fire({
                        icon: 'success',
                        title: response.success || 'Data pendidikan berhasil disimpan'
                    });
                },
                error: function(xhr) {
                    var msg = 'Terjadi kesalahan saat menyimpan data.';
                    if(xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            if (key === 'file_ijazah') {
                                $('#file_ijazah_feedback').text(value[0]);
                                $fileInput.addClass('is-invalid');
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
        });

        // Edit Button Click (populate Form)
        $(document).on('click', '.btn-edit-pendidikan', function() {
            var id = $(this).data('id');
            
            // Pulse form container to show activity
            $('.premium-card').css('transform', 'scale(0.98)').delay(100).queue(function(next){
                $(this).css('transform', 'none');
                next();
            });

            $.get("{{ url('admin/pendidikan') }}/" + id + "/edit", function(data) {
                // Change UI Header styling to Edit mode
                $('#formHeaderPendidikan').addClass('edit-mode');
                $('#formHeaderIcon').attr('class', 'ti ti-edit fs-5');
                $('#formTitlePendidikan').text('Edit Data Pendidikan');
                $('#modeBadge').text('Edit').removeClass('bg-opacity-20').addClass('bg-warning');

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
                
                // File handle
                if(data.file_ijazah) {
                    $uploadContent.addClass('d-none');
                    $uploadPreview.removeClass('d-none').addClass('d-flex');
                    $previewFileName.text(data.file_ijazah.split('/').pop());
                    $previewFileSize.html('<a href="/storage/'+data.file_ijazah+'" target="_blank" class="text-primary text-decoration-none fw-bold"><i class="ti ti-eye"></i> Lihat File</a>');
                    $previewFileIcon.attr('class', 'ti ti-file-check text-success fs-3');
                    $('#current_file_ijazah').html('');
                } else {
                    resetUploadZone();
                    $('#current_file_ijazah').html('');
                }

                $('#btnSavePendidikan').html('<i class="ti ti-check"></i> Perbarui Data').removeClass('btn-primary').addClass('btn-warning');
                $('.is-invalid').removeClass('is-invalid');
                
                // Scroll form container into view
                document.getElementById('formHeaderPendidikan').scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Delete Button Click
        $(document).on('click', '.btn-delete-pendidikan', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Pendidikan?',
                text: "Data riwayat pendidikan ini akan dihapus permanen.",
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
                        url: "{{ url('admin/pendidikan') }}/" + id,
                        type: 'DELETE',
                        data: { "_token": "{{ csrf_token() }}" },
                        success: function(response) {
                            loadPendidikan(currentAnggotaIdPendidikan);
                            
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

        // Reset/Cancel Action
        window.resetFormPendidikan = function() {
            $('#formPendidikan')[0].reset();
            $('#id_pendidikan').val('');
            $('#id_anggota_pendidikan').val(currentAnggotaIdPendidikan);
            resetUploadZone();
            $('#current_file_ijazah').html('');
            
            // Revert Header UI back to Add mode
            $('#formHeaderPendidikan').removeClass('edit-mode');
            $('#formHeaderIcon').attr('class', 'ti ti-edit-circle fs-5');
            $('#formTitlePendidikan').text('Form Tambah Pendidikan');
            $('#modeBadge').text('Tambah').addClass('bg-opacity-20').removeClass('bg-warning');
            
            $('#btnSavePendidikan').html('<i class="ti ti-device-floppy"></i> Simpan Data').removeClass('btn-warning').addClass('btn-primary');
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }
    });
</script>
@endpush
