<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCambio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitudCambioController extends Controller
{
    public function index(Request $request)
    {
        $query = SolicitudCambio::with([
            'usuario.departamento.oficina',
            'revisor'
        ])->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;

            $query->where(function ($q) use ($buscar) {
                $q->where('campo', 'LIKE', "%{$buscar}%")
                    ->orWhere('nuevo_valor', 'LIKE', "%{$buscar}%")
                    ->orWhere('valor_actual', 'LIKE', "%{$buscar}%")
                    ->orWhere('motivo', 'LIKE', "%{$buscar}%")
                    ->orWhereHas('usuario', function ($userQuery) use ($buscar) {
                        $userQuery
                            ->where('name', 'LIKE', "%{$buscar}%")
                            ->orWhere('email', 'LIKE', "%{$buscar}%");
                    });
            });
        }

        $solicitudes = $query->paginate(10);

        $seleccionada = null;

        if ($request->filled('solicitud')) {
            $seleccionada = SolicitudCambio::with([
                'usuario.departamento.oficina',
                'revisor'
            ])->find($request->solicitud);
        }

        if ($seleccionada === null && $solicitudes->total() > 0) {
            $primerId = $solicitudes->items()[0]->id;

            $seleccionada = SolicitudCambio::with([
                'usuario.departamento.oficina',
                'revisor'
            ])->find($primerId);
        }

        $total = SolicitudCambio::count();

        $pendientes = SolicitudCambio::where(
            'estado',
            'pendiente'
        )->count();

        $aprobadas = SolicitudCambio::where(
            'estado',
            'aprobada'
        )->count();

        $rechazadas = SolicitudCambio::where(
            'estado',
            'rechazada'
        )->count();

        return view('admin.cambios', compact(
            'solicitudes',
            'seleccionada',
            'total',
            'pendientes',
            'aprobadas',
            'rechazadas'
        ));
    }

    public function aprobar(
        Request $request,
        SolicitudCambio $solicitud
    ) {
        if ($solicitud->estado !== 'pendiente') {
            return back()->with(
                'error',
                'Esta solicitud ya fue revisada.'
            );
        }

        $request->validate([
            'comentario_admin' => [
                'nullable',
                'string',
                'max:1000'
            ],
        ]);

        $solicitud->estado = 'aprobada';
        $solicitud->comentario_admin = $request->comentario_admin;
        $solicitud->revisado_por = Auth::id();
        $solicitud->revisado_at = now();

        $solicitud->save();

        return redirect()
            ->route('cambiostecnologias', [
                'solicitud' => $solicitud->id
            ])
            ->with(
                'success',
                'La solicitud fue aprobada correctamente.'
            );
    }

    public function rechazar(
        Request $request,
        SolicitudCambio $solicitud
    ) {
        if ($solicitud->estado !== 'pendiente') {
            return back()->with(
                'error',
                'Esta solicitud ya fue revisada.'
            );
        }

        $request->validate([
            'comentario_admin' => [
                'required',
                'string',
                'max:1000'
            ],
        ]);

        $solicitud->estado = 'rechazada';
        $solicitud->comentario_admin = $request->comentario_admin;
        $solicitud->revisado_por = Auth::id();
        $solicitud->revisado_at = now();

        $solicitud->save();

        return redirect()
            ->route('cambiostecnologias', [
                'solicitud' => $solicitud->id
            ])
            ->with(
                'success',
                'La solicitud fue rechazada correctamente.'
            );
    }
}