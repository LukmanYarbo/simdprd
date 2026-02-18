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
                                    <div class="mb-3" id="div_file_surat_ket" style="display:none;">
                                        <label class="form-label small">Surat Keterangan (Wajib jika > 21 Tahun)</label>
                                        <input type="file" class="form-control form-control-sm" id="file_surat_ket" name="file_surat_ket" accept=".pdf,.jpg,.jpeg,.png">
                                        <div id="link_file_surat_ket" class="small mt-1"></div>
                                        <div class="invalid-feedback"></div>
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
                                        <th>Jenis Kelamin</th>
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

@push('scripts')
<script>
    $(function() {
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

        // Calculate Age and Toggle File Input
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
                if (!$('#id_keluarga').val()) { // Only clear if not editing (or logic to distinguish)
                     $('#file_surat_ket').val('');
                }
            }
        }

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
                
                if (data.file_surat_ket) {
                    $('#link_file_surat_ket').html('<a href="{{ asset('storage') }}/' + data.file_surat_ket + '" target="_blank" class="text-decoration-none"><i class="bi bi-file-earmark-text"></i> Lihat Surat Keterangan</a>');
                } else {
                    $('#link_file_surat_ket').html('');
                }
                
                toggleChildFields(); // Update visibility based on loaded data
                checkFileSuratKet(); // Check file input visibility

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
            $('#link_file_surat_ket').html('');
            $('#div_file_surat_ket').hide();
            $('#btnSaveKeluarga').html('<i class="bi bi-plus-lg"></i> Tambah');
            $('.is-invalid').removeClass('is-invalid');
        }
    });
</script>
@endpush
