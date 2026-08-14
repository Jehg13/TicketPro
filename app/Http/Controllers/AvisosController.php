<?php

namespace App\Http\Controllers;

use App\Models\Aviso;
use App\Models\User;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvisosController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        $departamentos = Departamento::with('oficina')
            ->whereHas('oficina', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->orderBy('nombre')
            ->get();

        $usuarios = User::with('departamento.oficina')
            ->whereHas('departamento.oficina', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->orderBy('name')
            ->get();

        $avisos = Aviso::with('publicadoPor')
            ->orderByDesc('fijado')
            ->orderByDesc('fecha_inicio')
            ->get();

        return view('admin.avisos', compact(
            'avisos',
            'departamentos',
            'usuarios'
        ));
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return back()
                ->withInput()
                ->withErrors([
                    'aplica_a' => 'No se pudo determinar la empresa del usuario actual.'
                ]);
        }

        $validated = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:255'
            ],

            'tipo' => [
                'required',
                'in:mantenimiento,incidente,informativo,general'
            ],

            'importancia' => [
                'required',
                'in:critica,alta,media,normal'
            ],

            'fecha_inicio' => [
                'required',
                'date'
            ],

            'hora_inicio' => [
                'required',
                'date_format:H:i'
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio'
            ],

            'hora_fin' => [
                'nullable',
                'date_format:H:i'
            ],

            'aplica_a' => [
                'required',
                'in:todos,departamento,usuarios'
            ],

            'descripcion' => [
                'required',
                'string',
                'max:1000'
            ],

            'mostrar_notificaciones' => [
                'nullable',
                'boolean'
            ],

            'fijado' => [
                'nullable',
                'boolean'
            ],

            'archivo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,mp4',
                'max:20480'
            ],
        ]);

        $validated['fecha_inicio'] =
            $request->fecha_inicio . ' ' . $request->hora_inicio . ':00';

        if ($request->filled('fecha_fin') && $request->filled('hora_fin')) {
            $validated['fecha_fin'] =
                $request->fecha_fin . ' ' . $request->hora_fin . ':00';
        } else {
            $validated['fecha_fin'] = null;
        }

        $validated['mostrar_notificaciones'] =
            $request->boolean('mostrar_notificaciones');

        $validated['fijado'] =
            $request->boolean('fijado');

        if ($request->hasFile('archivo')) {
            $validated['archivo'] = $request
                ->file('archivo')
                ->store('avisos', 'public');
        } else {
            $validated['archivo'] = null;
        }

        $validated['afecta_a'] = $this->obtenerAfectados(
            $request,
            $empresaId
        );

        $validated['publicado_por'] = Auth::id();

        Aviso::create($validated);

        return redirect()
            ->route('avisostecnologias')
            ->with('success', 'Aviso publicado correctamente.');
    }

    public function update(Request $request, Aviso $aviso)
    {
        $usuario = Auth::user();

        $empresaId = $usuario->departamento?->oficina?->empresa_id;

        if (!$empresaId) {
            return back()
                ->withInput()
                ->withErrors([
                    'aplica_a' =>
                        'No se pudo determinar la empresa del usuario actual.'
                ]);
        }

        $validated = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:255'
            ],

            'tipo' => [
                'required',
                'in:mantenimiento,incidente,informativo,general'
            ],

            'importancia' => [
                'required',
                'in:critica,alta,media,normal'
            ],

            'fecha_inicio' => [
                'required',
                'date'
            ],

            'hora_inicio' => [
                'required',
                'date_format:H:i'
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio'
            ],

            'hora_fin' => [
                'nullable',
                'date_format:H:i'
            ],

            'aplica_a' => [
                'required',
                'in:todos,departamento,usuarios'
            ],

            'descripcion' => [
                'required',
                'string',
                'max:1000'
            ],

            'mostrar_notificaciones' => [
                'nullable',
                'boolean'
            ],

            'fijado' => [
                'nullable',
                'boolean'
            ],

            'archivo' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf,mp4',
                'max:20480'
            ],
        ]);

        $validated['fecha_inicio'] =
            $request->fecha_inicio . ' ' . $request->hora_inicio . ':00';

        if ($request->filled('fecha_fin') && $request->filled('hora_fin')) {
            $validated['fecha_fin'] =
                $request->fecha_fin . ' ' . $request->hora_fin . ':00';
        } else {
            $validated['fecha_fin'] = null;
        }

        $validated['mostrar_notificaciones'] =
            $request->boolean('mostrar_notificaciones');

        $validated['fijado'] =
            $request->boolean('fijado');

        if ($request->hasFile('archivo')) {
            if ($aviso->archivo) {
                Storage::disk('public')->delete($aviso->archivo);
            }

            $validated['archivo'] = $request
                ->file('archivo')
                ->store('avisos', 'public');
        } else {
            unset($validated['archivo']);
        }

        $validated['afecta_a'] = $this->obtenerAfectados(
            $request,
            $empresaId
        );

        $aviso->update($validated);

        return redirect()
            ->route('avisostecnologias')
            ->with('success', 'Aviso actualizado correctamente.');
    }

    public function destroy(Aviso $aviso)
    {
        if ($aviso->archivo) {
            Storage::disk('public')->delete($aviso->archivo);
        }

        $aviso->delete();

        return redirect()
            ->route('avisostecnologias')
            ->with('success', 'Aviso eliminado correctamente.');
    }

    private function obtenerAfectados(Request $request, $empresaId)
    {
        if ($request->aplica_a === 'todos') {
            return [
                'tipo' => 'todos',
                'empresa_id' => $empresaId
            ];
        }

        if ($request->aplica_a === 'departamento') {
            $departamentoIds = $request->input('afecta_a', []);

            if (!is_array($departamentoIds)) {
                $departamentoIds = [];
            }

            $departamentoIds = array_values(
                array_unique(
                    array_map('intval', $departamentoIds)
                )
            );

            if (empty($departamentoIds)) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Debes seleccionar al menos un departamento.'
                        ])
                );
            }

            $cantidadValidos = Departamento::whereIn(
                'id',
                $departamentoIds
            )
                ->whereHas('oficina', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->count();

            if ($cantidadValidos !== count($departamentoIds)) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Uno o más departamentos no pertenecen a tu empresa.'
                        ])
                );
            }

            return [
                'tipo' => 'departamentos',
                'ids' => $departamentoIds
            ];
        }

        if ($request->aplica_a === 'usuarios') {
            $usuarioIds = $request->input('afecta_a', []);

            if (!is_array($usuarioIds)) {
                $usuarioIds = [];
            }

            $usuarioIds = array_values(
                array_unique(
                    array_map('intval', $usuarioIds)
                )
            );

            if (empty($usuarioIds)) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Debes seleccionar al menos un usuario.'
                        ])
                );
            }

            $cantidadValidos = User::whereIn(
                'id',
                $usuarioIds
            )
                ->whereHas('departamento.oficina', function ($query) use ($empresaId) {
                    $query->where('empresa_id', $empresaId);
                })
                ->count();

            if ($cantidadValidos !== count($usuarioIds)) {
                abort(
                    back()
                        ->withInput()
                        ->withErrors([
                            'afecta_a' =>
                                'Uno o más usuarios no pertenecen a tu empresa.'
                        ])
                );
            }

            return [
                'tipo' => 'usuarios',
                'ids' => $usuarioIds
            ];
        }

        return [];
    }
}