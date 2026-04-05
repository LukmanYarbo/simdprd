@extends('layouts.admin')

@section('title', 'Parameter Gaji')

@section('content')
<div class="container-fluid">
    <x-breadcrumbs title="Parameter Gaji" :items="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard'), 'icon' => 'ti ti-home-2'],
        ['label' => 'Parameter Gaji', 'icon' => 'ti ti-calculator']
    ]" />

    <div class="card shadow-lg border-0 mb-4 mt-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white border-bottom">
            <h6 class="m-0 fw-bold text-primary"><i class="ti ti-calculator me-2"></i>Daftar Parameter Gaji</h6>
            @if($hasActive)
                <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm disabled" data-bs-toggle="tooltip" title="Tidak dapat menambah peraturan baru selagi ada peraturan yang masih aktif (Status Y)">
                    <i class="ti ti-plus me-1"></i> Tambah Parameter
                </button>
            @else
                <a href="{{ route('admin.parameter-gaji.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm transition-base">
                    <i class="ti ti-plus me-1"></i> Tambah Parameter
                </a>
            @endif
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="ti ti-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" width="100%" cellspacing="0">
                    <thead class="bg-body-tertiary">
                        <tr>
                            <th class="text-center" style="width:50px">No</th>
                            <th>No. Peraturan</th>
                            <th>Tgl Berlaku</th>
                            <th class="text-end">Gaji Pokok Ketua</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width:200px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($parameterGaji as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $item->no_peraturan }}</div>
                                @if($item->file)
                                    <a href="javascript:void(0)" onclick="previewPdf('{{ Storage::disk('public')->url($item->file) }}', '{{ $item->no_peraturan }}')" class="text-decoration-none small text-danger fw-medium">
                                        <i class="ti ti-file-type-pdf me-1"></i>Lihat Dokumen
                                    </a>
                                @endif
                            </td>
                            <td>{{ $item->tgl_berlaku->translatedFormat('d F Y') }}</td>
                            <td class="text-end">Rp {{ number_format($item->gajipokok_ketua, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @if($item->status == 'Y')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill">
                                        <i class="ti ti-circle-check me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">
                                        <i class="ti ti-circle-x me-1"></i>Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('admin.parameter-gaji.edit', $item->id) }}" class="btn btn-icon-only btn-sm btn-outline-primary" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    @if($item->status != 'Y')
                                    <button class="btn btn-icon-only btn-sm btn-outline-danger" onclick="confirmDelete({{ $item->id }})" title="Hapus">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $item->id }}" action="{{ route('admin.parameter-gaji.destroy', $item->id) }}" method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    @else
                                    <button class="btn btn-icon-only btn-sm btn-outline-secondary disabled" title="Tidak dapat menghapus peraturan aktif">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="ti ti-inbox fs-3 d-block mb-2"></i>
                                Belum ada data parameter gaji.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- PDF Preview Panel --}}
    <div class="card shadow-lg border-0 mb-4 overflow-hidden" id="pdfPreviewCard" style="display:none;">
        <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-danger d-flex align-items-center">
                <i class="ti ti-file-type-pdf me-2 fs-4"></i>
                <span>Preview: <span id="pdfTitle"></span></span>
            </h6>
            <div class="btn-group">
                <a id="pdfDownloadBtn" href="#" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 me-2">
                    <i class="ti ti-external-link me-1"></i>Buka di Tab Baru
                </a>
                <button class="btn btn-sm btn-light rounded-pill px-3" onclick="closePdfPreview()">
                    <i class="ti ti-x me-1"></i>Tutup
                </button>
            </div>
        </div>
        <div class="card-body p-0 bg-secondary bg-opacity-10">
            <iframe id="pdfFrame" src="" style="width:100%; height:80vh; border:none;" allowfullscreen></iframe>
        </div>
    </div>
</div>

<script>
    // Initialize tooltips on load
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    function previewPdf(url, title) {
        const card = document.getElementById('pdfPreviewCard');
        const frame = document.getElementById('pdfFrame');
        const downloadBtn = document.getElementById('pdfDownloadBtn');
        
        document.getElementById('pdfTitle').textContent = title;
        frame.src = url;
        downloadBtn.href = url;
        
        card.style.display = 'block';
        // Smooth scroll to preview panel
        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function closePdfPreview() {
        document.getElementById('pdfPreviewCard').style.display = 'none';
        document.getElementById('pdfFrame').src = '';
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection
