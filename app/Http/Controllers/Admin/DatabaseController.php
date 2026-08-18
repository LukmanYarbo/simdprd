<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DatabaseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view database_management'),
        ];
    }

    protected function backupPath(): string
    {
        return storage_path('app/backups');
    }

    public function backupIndex()
    {
        $backups = $this->backupFiles();

        return view('admin.database.backup', compact('backups'));
    }

    protected function backupFiles()
    {
        return collect(glob($this->backupPath() . '/*.sql') ?: [])
            ->map(function ($path) {
                return [
                    'name' => basename($path),
                    'size' => round(filesize($path) / 1024, 2),
                    'date' => filemtime($path),
                ];
            })
            ->sortByDesc('date')
            ->values();
    }

    public function createBackup(Request $request)
    {
        $path = $this->backupPath();
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filename = 'backup_' . now()->format('Y-m-d_His') . '.sql';

        $tables = collect(DB::select('SHOW TABLES'))
            ->map(fn ($t) => array_values((array) $t)[0]);

        $sql = "-- SIMDPRD Database Backup\n";
        $sql .= "-- Generated: " . now()->format('Y-m-d H:i:s') . "\n";
        $sql .= "-- Database: " . config('database.connections.' . config('database.default') . '.database') . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $create = DB::selectOne('SHOW CREATE TABLE `' . $table . '`');
            $createSql = array_values((array) $create)[1];

            $sql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $sql .= $createSql . ";\n\n";

            $rows = DB::table($table)->get();
            if ($rows->isEmpty()) {
                continue;
            }

            foreach ($rows as $row) {
                $columns = array_keys((array) $row);
                $values = array_map(function ($value) {
                    return $value === null ? 'NULL' : DB::getPdo()->quote($value);
                }, array_values((array) $row));

                $sql .= "INSERT INTO `{$table}` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
            }

            $sql .= "\n";
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        File::put($path . '/' . $filename, $sql);

        return redirect()->route('admin.database.backup')
            ->with('success', 'Backup berhasil dibuat: ' . $filename);
    }

    public function downloadBackup($file)
    {
        $path = $this->backupPath() . '/' . basename($file);

        abort_unless(file_exists($path), 404);

        return response()->download($path);
    }

    public function deleteBackup($file)
    {
        $path = $this->backupPath() . '/' . basename($file);

        if (file_exists($path)) {
            unlink($path);
        }

        return redirect()->route('admin.database.backup')
            ->with('success', 'Backup berhasil dihapus.');
    }

    public function restoreIndex()
    {
        $backups = $this->backupFiles();

        return view('admin.database.restore', compact('backups'));
    }

    public function restore(Request $request)
    {
        $request->validate([
            'source' => ['required', 'in:existing,upload'],
            'backup_file' => ['required_if:source,existing'],
            'uploaded_file' => ['required_if:source,upload', 'file', 'mimes:sql,txt'],
            'confirmation' => ['required', 'in:RESTORE'],
        ], [
            'source.required' => 'Pilih sumber file backup.',
            'backup_file.required_if' => 'Pilih file backup yang tersimpan.',
            'uploaded_file.required_if' => 'Pilih file backup yang akan diupload.',
            'uploaded_file.mimes' => 'File backup harus berformat .sql atau .txt.',
            'confirmation.required' => 'Ketik RESTORE untuk konfirmasi.',
            'confirmation.in' => 'Konfirmasi tidak valid. Ketik RESTORE dengan huruf besar.',
        ]);

        if ($request->source === 'existing') {
            $filename = basename($request->backup_file);
            $path = $this->backupPath() . '/' . $filename;

            abort_unless(file_exists($path), 404, 'File backup tidak ditemukan.');

            $sql = File::get($path);
        } else {
            $sql = $request->file('uploaded_file')->get();
        }

        $statements = $this->splitStatements($sql);

        if (empty($statements)) {
            return redirect()->route('admin.database.restore')
                ->with('error', 'File backup kosong atau tidak valid.');
        }

        $failed = [];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($statements as $statement) {
            try {
                DB::unprepared($statement);
            } catch (\Throwable $e) {
                $failed[] = $e->getMessage();
            }
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        if (!empty($failed)) {
            return redirect()->route('admin.database.restore')
                ->with('error', 'Restore gagal pada sebagian statement: ' . $failed[0]);
        }

        return redirect()->route('admin.database.restore')
            ->with('success', 'Database berhasil direstore.');
    }

    protected function splitStatements(string $sql): array
    {
        $sql = preg_replace('/^--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        $statements = [];
        $buffer = '';
        $inSingle = false;
        $inDouble = false;
        $inBacktick = false;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];

            if ($inSingle) {
                if ($char === "'" && $this->isQuoteTerminator($sql, $i)) {
                    $inSingle = false;
                }
                $buffer .= $char;
                continue;
            }

            if ($inDouble) {
                if ($char === '"' && $this->isQuoteTerminator($sql, $i)) {
                    $inDouble = false;
                }
                $buffer .= $char;
                continue;
            }

            if ($inBacktick) {
                if ($char === '`') {
                    $inBacktick = false;
                }
                $buffer .= $char;
                continue;
            }

            if ($char === "'") {
                $inSingle = true;
                $buffer .= $char;
                continue;
            }

            if ($char === '"') {
                $inDouble = true;
                $buffer .= $char;
                continue;
            }

            if ($char === '`') {
                $inBacktick = true;
                $buffer .= $char;
                continue;
            }

            if ($char === ';') {
                $statement = trim($buffer);
                if ($statement !== '') {
                    $statements[] = $statement;
                }
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        $statement = trim($buffer);
        if ($statement !== '') {
            $statements[] = $statement;
        }

        return $statements;
    }

    protected function isQuoteTerminator(string $sql, int $index): bool
    {
        $backslashes = 0;
        for ($i = $index - 1; $i >= 0 && $sql[$i] === '\\'; $i--) {
            $backslashes++;
        }

        return $backslashes % 2 === 0;
    }

    public function truncateIndex()
    {
        $tables = collect(DB::select('SHOW TABLES'))
            ->map(fn ($t) => array_values((array) $t)[0])
            ->reject(fn ($t) => in_array($t, $this->protectedTables()))
            ->values();

        return view('admin.database.truncate', [
            'tables' => $tables,
            'protectedTables' => $this->protectedTables(),
        ]);
    }

    public function truncate(Request $request)
    {
        $request->validate([
            'confirmation' => ['required', 'in:KOSONGKAN'],
        ], [
            'confirmation.required' => 'Ketik KOSONGKAN untuk konfirmasi.',
            'confirmation.in' => 'Konfirmasi tidak valid. Ketik KOSONGKAN dengan huruf besar.',
        ]);

        $tables = collect(DB::select('SHOW TABLES'))
            ->map(fn ($t) => array_values((array) $t)[0])
            ->reject(fn ($t) => in_array($t, $this->protectedTables()));

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($tables as $table) {
            DB::statement('TRUNCATE TABLE `' . $table . '`');
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        return redirect()->route('admin.database.truncate')
            ->with('success', 'Database berhasil dikosongkan. Tabel users, roles, dan permissions dipertahankan.');
    }

    protected function protectedTables(): array
    {
        return [
            'users',
            'roles',
            'permissions',
            'model_has_roles',
            'model_has_permissions',
            'role_has_permissions',
        ];
    }

    public function seed(Request $request)
    {
        Artisan::call('db:seed', ['--force' => true]);

        return redirect()->route('admin.database.truncate')
            ->with('success', 'Data dasar berhasil di-seed ulang.');
    }
}
