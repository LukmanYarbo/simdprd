<?php
/**
 * Standalone Storage Link Generator for Laravel on Hosting (Shared Hosting, CPanel, VPS)
 * Place this file in your public folder (e.g., public/storage-link.php or public_html/storage-link.php)
 * And access it in your browser: https://yourdomain.com/storage-link.php
 */

header('Content-Type: text/html; charset=utf-8');

echo '<html><head><title>Laravel Storage Link Helper</title>';
echo '<style>
    body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; margin: 40px; background: #0f172a; color: #cbd5e1; }
    .card { background: #1e293b; padding: 40px; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); max-width: 650px; margin: 50px auto; border: 1px solid #334155; }
    h1 { color: #38bdf8; font-size: 26px; margin-top: 0; margin-bottom: 25px; border-bottom: 1px solid #334155; padding-bottom: 15px; }
    .success { color: #4ade80; background: rgba(74, 222, 128, 0.1); border: 1px solid rgba(74, 222, 128, 0.2); padding: 15px; border-radius: 8px; margin: 20px 0; }
    .error { color: #f87171; background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); padding: 15px; border-radius: 8px; margin: 20px 0; }
    code { background: #0f172a; padding: 3px 8px; border-radius: 6px; font-family: "Courier New", Courier, monospace; font-size: 14px; color: #e2e8f0; border: 1px solid #1e293b; }
    ul { padding-left: 20px; }
    li { margin-bottom: 8px; }
    .btn { display: inline-block; background: #0ea5e9; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-top: 20px; transition: all 0.2s ease; border: none; cursor: pointer; }
    .btn:hover { background: #0284c7; transform: translateY(-1px); }
    .info-box { background: rgba(56, 189, 248, 0.05); border-left: 4px solid #38bdf8; padding: 15px; border-radius: 0 8px 8px 0; margin: 20px 0; }
</style></head><body>';
echo '<div class="card">';
echo '<h1>Laravel Storage Link Generator</h1>';

// Determine the public path and the storage path
$currentDir = __DIR__;
$publicStoragePath = $currentDir . '/storage';

// 1. Try default Laravel layout: storage/app/public is sibling to public folder's parent
$targetStoragePath = realpath($currentDir . '/../storage/app/public');

// 2. Try shared hosting layout: if rest of laravel files are in a sibling folder (e.g. laravel-app/storage/app/public)
if (!$targetStoragePath) {
    // Search for storage folder in common locations
    $possibleTargets = [
        $currentDir . '/../storage/app/public', // standard
        $currentDir . '/../simdprd/storage/app/public', // subfolder name
        $currentDir . '/../../storage/app/public', // public_html is subfolder of root, rest of laravel is parallel
        $currentDir . '/../../simdprd/storage/app/public',
    ];
    
    foreach ($possibleTargets as $possibleTarget) {
        if (is_dir($possibleTarget)) {
            $targetStoragePath = realpath($possibleTarget);
            break;
        }
    }
}

if (!$targetStoragePath) {
    echo '<div class="error"><strong>Gagal Mendeteksi Storage:</strong> Tidak dapat menemukan folder <code>storage/app/public</code> Laravel Anda. Silakan pastikan folder tersebut ada dan izin folder (permissions) sudah benar.</div>';
    echo '<p>Lokasi pencarian:</p><ul>';
    echo '<li><code>' . htmlentities($currentDir . '/../storage/app/public') . '</code></li>';
    echo '</ul>';
    echo '</div></body></html>';
    exit;
}

echo '<p><strong>Lokasi Publik (Link):</strong><br><code>' . htmlentities($publicStoragePath) . '</code></p>';
echo '<p><strong>Lokasi Target (Storage):</strong><br><code>' . htmlentities($targetStoragePath) . '</code></p>';

// Handle request to create symlink
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // If symlink already exists, remove it or rename it
    if (file_exists($publicStoragePath)) {
        if (is_link($publicStoragePath)) {
            unlink($publicStoragePath);
            echo '<p>Symbolic link lama yang rusak telah dihapus.</p>';
        } else {
            // It's a real directory, rename it
            $backupName = $publicStoragePath . '_backup_' . time();
            rename($publicStoragePath, $backupName);
            echo '<div class="success">Folder <code>storage</code> fisik yang ada telah di-backup menjadi <code>' . basename($backupName) . '</code>.</div>';
        }
    }

    // Attempt to create symlink
    if (symlink($targetStoragePath, $publicStoragePath)) {
        echo '<div class="success"><strong>Berhasil!</strong> Symbolic link telah sukses dibuat. Sekarang semua asset & file upload Anda akan muncul dengan benar di hosting.</div>';
        echo '<div class="info-box"><strong>Penting:</strong> Silakan hapus file <code>storage-link.php</code> ini demi keamanan setelah Anda selesai menggunakannya.</div>';
    } else {
        // Fallback: Try copy/exec if symlink is disabled on host
        echo '<div class="error"><strong>Gagal Membuat Symlink:</strong> Fungsi PHP <code>symlink()</code> dinonaktifkan atau ditolak oleh hosting Anda.</div>';
        echo '<p><strong>Solusi Alternatif:</strong></p>';
        echo '<ul>';
        echo '<li>Hubungi provider hosting Anda untuk mengaktifkan fungsi <code>symlink()</code> di PHP.</li>';
        echo '<li>Atau buat script cron job di cPanel dengan perintah: <br><code>ln -s ' . escapeshellarg($targetStoragePath) . ' ' . escapeshellarg($publicStoragePath) . '</code></li>';
        echo '</ul>';
    }
} else {
    echo '<p>Silakan klik tombol di bawah untuk membuat link asset:</p>';
    echo '<a href="?action=create" class="btn">Buat Storage Link</a>';
}

echo '</div></body></html>';
