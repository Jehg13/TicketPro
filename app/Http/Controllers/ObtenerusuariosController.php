<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ObtenerusuariosController extends Controller
{
    public function index(Request $request)
    {
        $this->verificarPermiso();

        $consulta = $this->consultaUsuarios();

        if ($request->filled('estado')) {
            $estado = strtolower(trim((string) $request->estado));

            if ($estado === 'activos') {
                $consulta->where('users.active', 'Y');
            } elseif ($estado === 'inactivos') {
                $consulta->where('users.active', 'N');
            }
        }

        if ($request->filled('departamento')) {
            $departamento = trim((string) $request->departamento);

            if ($departamento !== '') {
                $consulta->whereRaw(
                    'LOWER(TRIM(departamentos.nombre)) = ?',
                    [strtolower($departamento)]
                );
            }
        }

        if ($request->filled('buscar')) {
            $buscar = trim((string) $request->buscar);

            if ($buscar !== '') {
                $consulta->where(function ($query) use ($buscar) {
                    $query->where('users.name', 'LIKE', "%{$buscar}%")
                        ->orWhere('users.login', 'LIKE', "%{$buscar}%")
                        ->orWhere('users.email', 'LIKE', "%{$buscar}%")
                        ->orWhere('numeros_empleado.numero_empleado', 'LIKE', "%{$buscar}%")
                        ->orWhere('empresas.empresa', 'LIKE', "%{$buscar}%")
                        ->orWhere('oficinas.nombre', 'LIKE', "%{$buscar}%")
                        ->orWhere('departamentos.nombre', 'LIKE', "%{$buscar}%");
                });
            }
        }

        $usuarios = $consulta
            ->orderBy('users.name', 'asc')
            ->paginate(10);

        $departamentos = DB::table('departamentos')
            ->whereNotNull('nombre')
            ->whereRaw("TRIM(nombre) <> ''")
            ->select('nombre')
            ->distinct()
            ->orderBy('nombre', 'asc')
            ->pluck('nombre');

        $totalUsuarios = DB::table('users')->count();

        $usuariosActivos = DB::table('users')
            ->where('active', 'Y')
            ->count();

        $usuariosInactivos = DB::table('users')
            ->where('active', 'N')
            ->count();

        $administradores = DB::table('users')
            ->whereRaw('UPPER(TRIM(priv_admin)) = ?', ['Y'])
            ->count();

        return view('admin.users', compact(
            'usuarios',
            'departamentos',
            'totalUsuarios',
            'usuariosActivos',
            'usuariosInactivos',
            'administradores'
        ));
    }

    public function show($login)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.'
            ], 401);
        }

        if (!$this->tienePermiso()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para acceder a esta información.'
            ], 403);
        }

        $login = trim((string) $login);

        $usuario = $this->consultaUsuarios()
            ->where('users.login', $login)
            ->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        $picture = !empty($usuario->picture)
            ? asset('storage/' . ltrim($usuario->picture, '/'))
            : asset('storage/profile-photos/user.png');

        $empresas = DB::table('empresas')
            ->select('id', 'empresa')
            ->orderBy('empresa')
            ->get();

        $oficinas = DB::table('oficinas')
            ->select('id', 'nombre', 'empresa_id')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'usuario' => [
                'login' => $usuario->login,
                'name' => $usuario->name,
                'email' => $usuario->email,
                'phone' => $usuario->phone,
                'role' => $usuario->role,
                'active' => strtoupper(trim((string) $usuario->active)) === 'Y',
                'picture' => $picture,
                'numero_empleado' => $usuario->numero_empleado,
                'empresa_id' => $usuario->empresa_id,
                'empresa' => $usuario->empresa ?? 'Sin empresa',
                'oficina_id' => $usuario->oficina_id,
                'oficina' => $usuario->oficina ?? 'Sin oficina',
                'departamento' => $usuario->departamento ?? 'Sin departamento',
                'priv_admin' => strtoupper(trim((string) $usuario->priv_admin)) === 'Y'
            ],
            'empresas' => $empresas,
            'oficinas' => $oficinas
        ]);
    }

    public function update(Request $request, $login)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.'
            ], 401);
        }

        if (!$this->tienePermiso()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para editar usuarios.'
            ], 403);
        }

        $login = trim((string) $login);

        $usuario = DB::table('users')
            ->where('login', $login)
            ->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'El usuario no existe.'
            ], 404);
        }

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:30'],
                'password' => ['nullable', 'string', 'min:6', 'max:255'],
                'numero_empleado' => ['required', 'string', 'max:50'],
                'role' => ['required', 'string', 'max:100'],
                'active' => ['required', 'in:Y,N'],
                'priv_admin' => ['required', 'in:Y,N'],
                'oficina_id' => ['required', 'integer', 'exists:oficinas,id'],
                'departamento' => ['nullable', 'string', 'max:255']
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Los datos enviados no son válidos.',
                'errors' => $e->errors()
            ], 422);
        }

        $nombre = trim((string) $validated['name']);
        $email = trim((string) $validated['email']);

        $phone = isset($validated['phone']) && trim((string) $validated['phone']) !== ''
            ? trim((string) $validated['phone'])
            : null;

        $password = $validated['password'] ?? null;
        $numeroEmpleado = trim((string) $validated['numero_empleado']);
        $role = trim((string) $validated['role']);
        $active = $validated['active'];
        $privAdmin = $validated['priv_admin'];
        $oficinaId = (int) $validated['oficina_id'];
        $departamentoNombre = trim((string) ($validated['departamento'] ?? ''));

        $numeroExiste = DB::table('numeros_empleado')
            ->where('numero_empleado', $numeroEmpleado)
            ->where('login', '!=', $login)
            ->exists();

        if ($numeroExiste) {
            return response()->json([
                'success' => false,
                'message' => 'El número de empleado ya está asignado a otro usuario.',
                'errors' => [
                    'numero_empleado' => [
                        'El número de empleado ya existe.'
                    ]
                ]
            ], 422);
        }

        $oficina = DB::table('oficinas')
            ->where('id', $oficinaId)
            ->first();

        if (!$oficina) {
            return response()->json([
                'success' => false,
                'message' => 'La oficina seleccionada no existe.'
            ], 422);
        }

        $empresa = DB::table('empresas')
            ->where('id', $oficina->empresa_id)
            ->first();

        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'La oficina seleccionada no tiene una empresa válida asignada.'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $datosUsuario = [
                'name' => $nombre,
                'email' => $email,
                'phone' => $phone,
                'role' => $role,
                'active' => $active,
                'priv_admin' => $privAdmin
            ];

            if ($password !== null && trim($password) !== '') {
                $datosUsuario['password'] = Hash::make($password);
            }

            DB::table('users')
                ->where('login', $login)
                ->update($datosUsuario);

            $numeroEmpleadoExiste = DB::table('numeros_empleado')
                ->where('login', $login)
                ->exists();

            if ($numeroEmpleadoExiste) {
                DB::table('numeros_empleado')
                    ->where('login', $login)
                    ->update([
                        'numero_empleado' => $numeroEmpleado,
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('numeros_empleado')
                    ->insert([
                        'login' => $login,
                        'numero_empleado' => $numeroEmpleado,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            }

            $departamentoExiste = DB::table('departamentos')
                ->where('usuario_departamento', $login)
                ->exists();

            if ($departamentoExiste) {
                if ($departamentoNombre === '') {
                    DB::table('departamentos')
                        ->where('usuario_departamento', $login)
                        ->update([
                            'oficina_id' => $oficinaId
                        ]);
                } else {
                    DB::table('departamentos')
                        ->where('usuario_departamento', $login)
                        ->update([
                            'nombre' => $departamentoNombre,
                            'oficina_id' => $oficinaId
                        ]);
                }
            } elseif ($departamentoNombre !== '') {
                DB::table('departamentos')
                    ->insert([
                        'usuario_departamento' => $login,
                        'nombre' => $departamentoNombre,
                        'oficina_id' => $oficinaId
                    ]);
            }

            DB::commit();

            $usuarioActualizado = $this->consultaUsuarios()
                ->where('users.login', $login)
                ->first();

            if (!$usuarioActualizado) {
                return response()->json([
                    'success' => true,
                    'message' => 'El usuario fue actualizado correctamente.'
                ]);
            }

            $picture = !empty($usuarioActualizado->picture)
                ? asset('storage/' . ltrim($usuarioActualizado->picture, '/'))
                : asset('storage/profile-photos/user.png');

            $usuarioActualizado = [
                'login' => $usuarioActualizado->login,
                'name' => $usuarioActualizado->name,
                'email' => $usuarioActualizado->email,
                'phone' => $usuarioActualizado->phone,
                'role' => $usuarioActualizado->role,
                'active' => strtoupper(trim((string) $usuarioActualizado->active)) === 'Y',
                'picture' => $picture,
                'numero_empleado' => $usuarioActualizado->numero_empleado,
                'empresa_id' => $usuarioActualizado->empresa_id,
                'empresa' => $usuarioActualizado->empresa ?? 'Sin empresa',
                'oficina_id' => $usuarioActualizado->oficina_id,
                'oficina' => $usuarioActualizado->oficina ?? 'Sin oficina',
                'departamento' => $usuarioActualizado->departamento ?? 'Sin departamento',
                'priv_admin' => strtoupper(trim((string) $usuarioActualizado->priv_admin)) === 'Y'
            ];

            return response()->json([
                'success' => true,
                'message' => 'El usuario fue actualizado correctamente.',
                'usuario' => $usuarioActualizado
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar el usuario.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   public function destroy(Request $request, $login)
{
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'No autenticado.'
        ], 401);
    }

    if (!$this->tienePermiso()) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes permiso para eliminar usuarios.'
        ], 403);
    }

    $login = trim((string) $login);

    try {
        $validated = $request->validate([
            'password' => ['required', 'string']
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Debes proporcionar tu contraseña.'
        ], 422);
    }

    $usuarioActual = Auth::user();

    try {
        if (!Hash::check($validated['password'], $usuarioActual->pswd)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña es incorrecta.'
            ], 422);
        }
    } catch (\Throwable $e) {
        report($e);

        return response()->json([
            'success' => false,
            'message' => 'La contraseña almacenada no tiene un formato válido.'
        ], 422);
    }

    $usuario = DB::table('users')
        ->where('login', $login)
        ->first();

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'El usuario no existe.'
        ], 404);
    }

    if (trim((string) $usuario->login) === trim((string) $usuarioActual->login)) {
        return response()->json([
            'success' => false,
            'message' => 'No puedes eliminar tu propia cuenta.'
        ], 422);
    }

    DB::beginTransaction();

    try {
        DB::table('numeros_empleado')
            ->where('login', $login)
            ->delete();

        DB::table('departamentos')
            ->where('usuario_departamento', $login)
            ->delete();

        DB::table('users')
            ->where('login', $login)
            ->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'El usuario fue eliminado correctamente.'
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();
        report($e);

        return response()->json([
            'success' => false,
            'message' => 'No se pudo eliminar el usuario.'
        ], 500);
    }
}
    public function empresas()
    {
        if (!Auth::check() || !$this->tienePermiso()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado.'
            ], 403);
        }

        $empresas = DB::table('empresas')
            ->select('id', 'empresa')
            ->orderBy('empresa')
            ->get();

        return response()->json([
            'success' => true,
            'empresas' => $empresas
        ]);
    }

    public function oficinasPorEmpresa($empresaId)
    {
        if (!Auth::check() || !$this->tienePermiso()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado.'
            ], 403);
        }

        $empresaId = (int) $empresaId;

        $oficinas = DB::table('oficinas')
            ->where('empresa_id', $empresaId)
            ->select('id', 'nombre', 'empresa_id')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'oficinas' => $oficinas
        ]);
    }

    public function departamentosPorOficina($oficinaId)
    {
        if (!Auth::check() || !$this->tienePermiso()) {
            return response()->json([
                'success' => false,
                'message' => 'No autorizado.'
            ], 403);
        }

        $oficinaId = (int) $oficinaId;

        $departamentos = DB::table('departamentos')
            ->where('oficina_id', $oficinaId)
            ->select(
                'id',
                'nombre',
                'oficina_id',
                'usuario_departamento'
            )
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'departamentos' => $departamentos
        ]);
    }

    private function consultaUsuarios()
    {
        return DB::table('users')
            ->leftJoin(
                'numeros_empleado',
                'numeros_empleado.login',
                '=',
                'users.login'
            )
            ->leftJoin(
                'departamentos',
                'departamentos.usuario_departamento',
                '=',
                'users.login'
            )
            ->leftJoin(
                'oficinas',
                'oficinas.id',
                '=',
                'departamentos.oficina_id'
            )
            ->leftJoin(
                'empresas',
                'empresas.id',
                '=',
                'oficinas.empresa_id'
            )
            ->select(
                'users.login',
                'users.name',
                'users.email',
                'users.phone',
                'users.active',
                'users.priv_admin',
                'users.picture',
                'users.role',
                'numeros_empleado.numero_empleado',
                'empresas.id as empresa_id',
                'empresas.empresa as empresa',
                'departamentos.nombre as departamento',
                'departamentos.oficina_id',
                'oficinas.nombre as oficina'
            );
    }

    private function tienePermiso()
    {
        if (!Auth::check()) {
            return false;
        }

        $usuarioActual = Auth::user();
        $role = strtolower(trim((string) $usuarioActual->role));
        $privAdmin = strtoupper(trim((string) $usuarioActual->priv_admin));

        return $role === 'gerente ti' && $privAdmin === 'Y';
    }

    private function verificarPermiso()
    {
        if (!$this->tienePermiso()) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
    }
}