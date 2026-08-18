<?php

namespace App\Http\Controllers;

use App\Models\TicketU;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ticketController extends Controller
{
    public function create()
    {
        return view('user.ticket');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'        => 'required|string|max:255',
            'tipo_falla'    => 'required|string',
            'equipo'        => 'required_if:tipo_falla,Equipo|nullable|string|max:255',
            'prioridad'     => 'required|in:Critica,Alta,Media,Normal',
            'descripcion'   => 'required|string',
            'afecta_otros'  => 'required|boolean',
            'es_recurrente' => 'required|boolean',
            'comentarios'   => 'nullable|string',
            'evidencia.*'   => 'nullable|file|mimes:jpg,jpeg,png,pdf,mp4|max:10240',
        ]);

        $filePaths = [];

        if ($request->hasFile('evidencia')) {
            foreach ($request->file('evidencia') as $file) {
                $filePaths[] = $file->store('evidencia_tickets', 'public');
            }
        }

        $año = date('Y');

        do {
            $ultimoTicket = TicketU::where('folio', 'like', "TKT-{$año}-%")
                ->orderByDesc('id')
                ->first();

            if ($ultimoTicket) {
                $ultimoNumero = (int) substr($ultimoTicket->folio, -5);
                $numero = $ultimoNumero + 1;
            } else {
                $numero = 1;
            }

            $folio = 'TKT-' . $año . '-' . str_pad($numero, 5, '0', STR_PAD_LEFT);

        } while (TicketU::where('folio', $folio)->exists());

        TicketU::create([
            'folio'         => $folio,
            'user_id'       => Auth::id(),
            'titulo'        => $validated['titulo'],
            'tipo_falla'    => $validated['tipo_falla'],
            'equipo'        => $validated['equipo'] ?? null,
            'prioridad'     => $validated['prioridad'],
            'descripcion'   => $validated['descripcion'],
            'afecta_otros'  => $validated['afecta_otros'],
            'es_recurrente' => $validated['es_recurrente'],
            'comentarios'   => $validated['comentarios'] ?? null,
            'evidencia'     => $filePaths,
        ]);

        return redirect()
            ->route('ticketusuario')
            ->with('success', "Ticket {$folio} creado correctamente.");
    }
}