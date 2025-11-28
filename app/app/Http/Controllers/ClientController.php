<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        // Filtro simples por nome, email ou documento
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('document', 'like', "%{$search}%");
            });
        }

        // Ordenação genérica
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        if (in_array($sort, ['id', 'name', 'email', 'document'])) {
            $query->orderBy($sort, $direction);
        }

        $clients = $query->paginate(5)->appends($request->query());

        return view('clients.index', compact('clients'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Salvar novo cliente
     */
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

    /**
     * Detalhe (vamos usar depois, por enquanto pode ser simples)
     */
    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    /**
     * Formulário de edição
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Atualizar cliente
     */
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

    /**
     * Deletar cliente
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente removido com sucesso.');
    }
}
