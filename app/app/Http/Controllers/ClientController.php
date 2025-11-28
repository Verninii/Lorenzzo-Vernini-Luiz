<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function index(Request $request)
    {
        $query = Client::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        if (in_array($sort, ['id', 'name', 'email', 'document'])) {
            $query->orderBy($sort, $direction);
        }

        $perPage = (int) $request->get('per_page', 20);
        if (! in_array($perPage, [5, 10, 20, 50])) {
            $perPage = 20;
        }

        $clients = $query->paginate($perPage)->appends($request->query());

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:clients,email',
            'document' => 'nullable|string|max:20',
            'phone'    => 'nullable|string|max:20',
        ]);

        Client::create($data);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente criado com sucesso.');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }


    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:clients,email,' . $client->id,
            'document' => 'nullable|string|max:20',
            'phone'    => 'nullable|string|max:20',
        ]);

        $client->update($data);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente atualizado com sucesso.');
    }


    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente removido com sucesso.');
    }

    public function bulkDestroy(Request $request)
{
    $ids = $request->input('ids', []);

    if (empty($ids)) {
        return redirect()
            ->route('clients.index')
            ->with('warning', 'Nenhum cliente selecionado para exclusão.');
    }

    Client::whereIn('id', $ids)->delete();

    return redirect()
        ->route('clients.index')
        ->with('success', 'Clientes selecionados foram excluídos com sucesso.');
}
}
