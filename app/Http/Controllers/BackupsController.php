<?php

namespace App\Http\Controllers;

use App\Models\Backups;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class BackupsController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $this->autorizar($usuario);

        $configuracion = Backups::where('login', $usuario->login)
            ->where('es_configuracion', true)
            ->first();

        $backups = Backups::where('login', $usuario->login)
            ->where('es_configuracion', false)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $backupsTodos = Backups::where('login', $usuario->login)
            ->where('es_configuracion', false)
            ->get();

        $totalBackups = $backupsTodos->count();

        $bytesTotales = 0;

        $disk = Storage::disk('local');

        foreach ($backupsTodos as $backup) {
            if (
                !empty($backup->archivo) &&
                $disk->exists($backup->archivo)
            ) {
                $bytesTotales += $disk->size($backup->archivo);
            }
        }

        $espacioUsado = $this->formatearBytes($bytesTotales);

        $ultimoBackupRegistro = Backups::where('login', $usuario->login)
            ->where('es_configuracion', false)
            ->where('estado', 'completado')
            ->whereNotNull('fecha_finalizacion')
            ->orderByDesc('fecha_finalizacion')
            ->first();

        $ultimoBackup = $ultimoBackupRegistro
            ? Carbon::parse($ultimoBackupRegistro->fecha_finalizacion)
                ->format('d/m/Y H:i')
            : 'Nunca';

        $proximoBackup = 'No programado';

        if ($configuracion && $configuracion->activo) {
            if (
                !$configuracion->proxima_ejecucion ||
                Carbon::parse($configuracion->proxima_ejecucion)->isPast()
            ) {
                $configuracion->update([
                    'proxima_ejecucion' => $this->calcularProximaEjecucion(
                        $configuracion
                    ),
                ]);

                $configuracion->refresh();
            }

            if ($configuracion->proxima_ejecucion) {
                $proximoBackup = Carbon::parse(
                    $configuracion->proxima_ejecucion
                )->format('d/m/Y H:i');
            }
        }

        return view('admin.backups', compact(
            'configuracion',
            'backups',
            'espacioUsado',
            'ultimoBackup',
            'proximoBackup',
            'totalBackups'
        ));
    }

    public function guardarConfiguracion(Request $request)
    {
        $usuario = Auth::user();

        $this->autorizar($usuario);

        $validated = $request->validate([
            'activo' => [
                'required',
                'boolean',
            ],
            'frecuencia' => [
                'required',
                'in:diario,semanal,mensual',
            ],
            'hora' => [
                'required',
                'date_format:H:i',
            ],
            'dia_semana' => [
                'nullable',
                'integer',
                'between:1,7',
            ],
            'dia_mes' => [
                'nullable',
                'integer',
                'between:1,31',
            ],
        ]);

        if ($validated['frecuencia'] === 'diario') {
            $validated['dia_semana'] = null;
            $validated['dia_mes'] = null;
        }

        if ($validated['frecuencia'] === 'semanal') {
            $validated['dia_mes'] = null;

            $validated['dia_semana'] = !empty($validated['dia_semana'])
                ? (int) $validated['dia_semana']
                : 1;
        }

        if ($validated['frecuencia'] === 'mensual') {
            $validated['dia_semana'] = null;

            $validated['dia_mes'] = !empty($validated['dia_mes'])
                ? (int) $validated['dia_mes']
                : 1;
        }

        $configuracion = Backups::where('login', $usuario->login)
            ->where('es_configuracion', true)
            ->first();

        if (!$configuracion) {
            $configuracion = Backups::create([
                'login' => $usuario->login,
                'nombre' => 'Configuración de backups',
                'archivo' => null,
                'tipo' => 'automatico',
                'frecuencia' => $validated['frecuencia'],
                'es_configuracion' => true,
                'activo' => (bool) $validated['activo'],
                'hora' => $validated['hora'],
                'dia_semana' => $validated['dia_semana'],
                'dia_mes' => $validated['dia_mes'],
                'estado' => 'pendiente',
                'tamaño' => null,
                'fecha_inicio' => null,
                'fecha_finalizacion' => null,
                'ultima_ejecucion' => null,
                'proxima_ejecucion' => null,
                'mensaje' => 'Configuración de backups automáticos.',
            ]);
        } else {
            $configuracion->update([
                'frecuencia' => $validated['frecuencia'],
                'activo' => (bool) $validated['activo'],
                'hora' => $validated['hora'],
                'dia_semana' => $validated['dia_semana'],
                'dia_mes' => $validated['dia_mes'],
            ]);
        }

        $configuracion->refresh();

        $configuracion->update([
            'proxima_ejecucion' => $this->calcularProximaEjecucion(
                $configuracion
            ),
        ]);

        return redirect()
            ->route('backups')
            ->with(
                'success',
                'Configuración de backups actualizada correctamente.'
            );
    }

    public function activar()
    {
        $usuario = Auth::user();

        $this->autorizar($usuario);

        $configuracion = Backups::where('login', $usuario->login)
            ->where('es_configuracion', true)
            ->first();

        if (!$configuracion) {
            $configuracion = Backups::create([
                'login' => $usuario->login,
                'nombre' => 'Configuración de backups',
                'archivo' => null,
                'tipo' => 'automatico',
                'frecuencia' => 'semanal',
                'es_configuracion' => true,
                'activo' => true,
                'hora' => '02:00',
                'dia_semana' => 1,
                'dia_mes' => null,
                'estado' => 'pendiente',
                'tamaño' => null,
                'fecha_inicio' => null,
                'fecha_finalizacion' => null,
                'ultima_ejecucion' => null,
                'proxima_ejecucion' => null,
                'mensaje' => 'Configuración de backups automáticos.',
            ]);
        } else {
            $configuracion->update([
                'activo' => true,
            ]);
        }

        $configuracion->refresh();

        $configuracion->update([
            'proxima_ejecucion' => $this->calcularProximaEjecucion(
                $configuracion
            ),
        ]);

        return redirect()
            ->route('backups')
            ->with(
                'success',
                'Backups automáticos activados correctamente.'
            );
    }

    public function desactivar()
    {
        $usuario = Auth::user();

        $this->autorizar($usuario);

        Backups::where('login', $usuario->login)
            ->where('es_configuracion', true)
            ->update([
                'activo' => false,
                'proxima_ejecucion' => null,
            ]);

        return redirect()
            ->route('backups')
            ->with(
                'success',
                'Backups automáticos desactivados.'
            );
    }

    public function crearManual()
    {
        $usuario = Auth::user();

        $this->autorizar($usuario);

        return $this->generarBackup(
            $usuario,
            'manual'
        );
    }

    public function ejecutarAutomatico()
    {
        $configuraciones = Backups::where('es_configuracion', true)
            ->where('activo', true)
            ->whereNotNull('proxima_ejecucion')
            ->where('proxima_ejecucion', '<=', now())
            ->get();

        foreach ($configuraciones as $configuracion) {
            $usuario = \App\Models\User::where(
                'login',
                $configuracion->login
            )->first();

            if (!$usuario) {
                continue;
            }

            try {
                $this->generarBackup(
                    $usuario,
                    'automatico',
                    $configuracion
                );
            } catch (\Throwable $e) {
                $configuracion->update([
                    'estado' => 'error',
                    'mensaje' => $e->getMessage(),
                    'fecha_finalizacion' => now(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Proceso de backups automáticos ejecutado correctamente.',
        ]);
    }

    private function generarBackup(
        $usuario,
        string $tipo = 'manual',
        ?Backups $configuracion = null
    ) {
        $nombreArchivo = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';

        $backup = Backups::create([
            'login' => $usuario->login,
            'nombre' => $nombreArchivo,
            'archivo' => null,
            'tipo' => $tipo,
            'frecuencia' => $tipo === 'automatico'
                ? $configuracion?->frecuencia
                : null,
            'es_configuracion' => false,
            'activo' => false,
            'hora' => null,
            'dia_semana' => null,
            'dia_mes' => null,
            'estado' => 'pendiente',
            'tamaño' => null,
            'fecha_inicio' => now(),
            'fecha_finalizacion' => null,
            'ultima_ejecucion' => null,
            'proxima_ejecucion' => null,
            'mensaje' => 'Generando backup...',
        ]);

        $disk = Storage::disk('local');

        $rutaRelativa = 'backups/' . $nombreArchivo;

        try {
            $disk->makeDirectory('backups');

            $rutaCompleta = $disk->path($rutaRelativa);

            $database = config('database.connections.mysql');

            $username = $database['username'] ?? 'root';

            $password = $database['password'] ?? '';

            $databaseName = $database['database'] ?? null;

            $host = $database['host'] ?? '127.0.0.1';

            $port = $database['port'] ?? 3306;

            if (!$databaseName) {
                throw new \RuntimeException(
                    'No se encontró el nombre de la base de datos.'
                );
            }

            $mysqldump = env(
                'MYSQLDUMP_PATH',
                'C:/Program Files/MySQL/MySQL Server 8.0/bin/mysqldump.exe'
            );

            if (!file_exists($mysqldump)) {
                throw new \RuntimeException(
                    'No se encontró mysqldump.exe en: ' . $mysqldump
                );
            }

            $environment = getenv();

            if (!is_array($environment)) {
                $environment = [];
            }

            $environment['MYSQL_PWD'] = $password;

            $process = new Process([
                $mysqldump,
                '--protocol=TCP',
                '--host=' . $host,
                '--port=' . $port,
                '--user=' . $username,
                '--single-transaction',
                '--routines',
                '--triggers',
                '--result-file=' . $rutaCompleta,
                $databaseName,
            ]);

            $process->setEnv($environment);

            $process->setTimeout(300);

            $process->run();

            $errorOutput = trim(
                $process->getErrorOutput()
            );

            if (!$process->isSuccessful()) {
                if ($disk->exists($rutaRelativa)) {
                    $disk->delete($rutaRelativa);
                }

                throw new \RuntimeException(
                    $errorOutput ?: 'mysqldump no pudo generar el backup.'
                );
            }

            if (
                !$disk->exists($rutaRelativa) ||
                $disk->size($rutaRelativa) <= 0
            ) {
                throw new \RuntimeException(
                    'mysqldump terminó correctamente, pero el archivo generado está vacío o no existe.'
                );
            }

            $bytes = $disk->size($rutaRelativa);

            $tamano = $this->formatearBytes($bytes);

            $backup->update([
                'archivo' => $rutaRelativa,
                'estado' => 'completado',
                'tamaño' => $tamano,
                'fecha_finalizacion' => now(),
                'ultima_ejecucion' => now(),
                'mensaje' => 'Backup generado correctamente.',
            ]);

            if ($tipo === 'automatico' && $configuracion) {
                $configuracion->update([
                    'estado' => 'completado',
                    'ultima_ejecucion' => now(),
                    'proxima_ejecucion' => $this->calcularProximaEjecucion(
                        $configuracion,
                        now()
                    ),
                    'mensaje' => 'Backup automático generado correctamente.',
                ]);
            }

            if ($tipo === 'manual') {
                return redirect()
                    ->route('backups')
                    ->with(
                        'success',
                        'Backup creado correctamente.'
                    );
            }

            return $backup;
        } catch (\Throwable $e) {
            if ($disk->exists($rutaRelativa)) {
                $disk->delete($rutaRelativa);
            }

            $backup->update([
                'estado' => 'error',
                'fecha_finalizacion' => now(),
                'mensaje' => $e->getMessage(),
            ]);

            if ($tipo === 'automatico' && $configuracion) {
                $configuracion->update([
                    'estado' => 'error',
                    'mensaje' => $e->getMessage(),
                    'proxima_ejecucion' => $this->calcularProximaEjecucion(
                        $configuracion,
                        now()
                    ),
                ]);
            }

            if ($tipo === 'manual') {
                return redirect()
                    ->route('backups')
                    ->with(
                        'error',
                        'No se pudo crear el backup: ' . $e->getMessage()
                    );
            }

            throw $e;
        }
    }

    public function descargar($id)
    {
        $usuario = Auth::user();

        $this->autorizar($usuario);

        $backup = Backups::where('login', $usuario->login)
            ->where('es_configuracion', false)
            ->findOrFail($id);

        if (
            empty($backup->archivo) ||
            !Storage::disk('local')->exists($backup->archivo)
        ) {
            return redirect()
                ->route('backups')
                ->with(
                    'error',
                    'El archivo del backup no está disponible.'
                );
        }

        return Storage::disk('local')->download(
            $backup->archivo,
            $backup->nombre
        );
    }

    public function destroy($id)
    {
        $usuario = Auth::user();

        $this->autorizar($usuario);

        $backup = Backups::where('login', $usuario->login)
            ->where('es_configuracion', false)
            ->findOrFail($id);

        if (
            !empty($backup->archivo) &&
            Storage::disk('local')->exists($backup->archivo)
        ) {
            Storage::disk('local')->delete($backup->archivo);
        }

        $backup->delete();

        return redirect()
            ->route('backups')
            ->with(
                'success',
                'Backup eliminado correctamente.'
            );
    }

    private function calcularProximaEjecucion(
        Backups $configuracion,
        ?Carbon $base = null
    ) {
        if (
            !$configuracion->activo ||
            !$configuracion->hora
        ) {
            return null;
        }

        $ahora = $base
            ? $base->copy()
            : now();

        $hora = substr(
            (string) $configuracion->hora,
            0,
            5
        );

        if (!preg_match(
            '/^(?:[01]\d|2[0-3]):[0-5]\d$/',
            $hora
        )) {
            return null;
        }

        if ($configuracion->frecuencia === 'diario') {
            $fecha = $ahora->copy()
                ->setTimeFromTimeString($hora);

            if ($fecha->lessThanOrEqualTo($ahora)) {
                $fecha->addDay();
            }

            return $fecha;
        }

        if ($configuracion->frecuencia === 'semanal') {
            $diaSemana = (int) (
                $configuracion->dia_semana ?: 1
            );

            $diaSemana = max(
                1,
                min($diaSemana, 7)
            );

            $fecha = $ahora->copy()
                ->startOfDay();

            $dias = (
                $diaSemana -
                $fecha->dayOfWeekIso +
                7
            ) % 7;

            $fecha->addDays($dias)
                ->setTimeFromTimeString($hora);

            if ($fecha->lessThanOrEqualTo($ahora)) {
                $fecha->addDays(7);
            }

            return $fecha;
        }

        if ($configuracion->frecuencia === 'mensual') {
            $diaMes = (int) (
                $configuracion->dia_mes ?: 1
            );

            $diaMes = max(
                1,
                min($diaMes, 31)
            );

            $fecha = $ahora->copy()
                ->startOfMonth();

            $ultimoDia = $fecha
                ->copy()
                ->endOfMonth()
                ->day;

            $diaReal = min(
                $diaMes,
                $ultimoDia
            );

            $fecha
                ->day($diaReal)
                ->setTimeFromTimeString($hora);

            if ($fecha->lessThanOrEqualTo($ahora)) {
                $fecha = $ahora
                    ->copy()
                    ->addMonthNoOverflow()
                    ->startOfMonth();

                $ultimoDia = $fecha
                    ->copy()
                    ->endOfMonth()
                    ->day;

                $diaReal = min(
                    $diaMes,
                    $ultimoDia
                );

                $fecha
                    ->day($diaReal)
                    ->setTimeFromTimeString($hora);
            }

            return $fecha;
        }

        return null;
    }

    private function formatearBytes($bytes)
    {
        $bytes = (float) $bytes;

        if ($bytes <= 0) {
            return '0 B';
        }

        $unidades = [
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
        ];

        $i = (int) floor(
            log($bytes, 1024)
        );

        $i = min(
            $i,
            count($unidades) - 1
        );

        return round(
            $bytes / pow(1024, $i),
            2
        ) . ' ' . $unidades[$i];
    }

    private function autorizar($usuario)
    {
        abort_unless(
            $usuario &&
            trim((string) $usuario->role) === 'Gerente Ti' &&
            strtoupper(
                trim((string) $usuario->priv_admin)
            ) === 'Y',
            403
        );
    }
}