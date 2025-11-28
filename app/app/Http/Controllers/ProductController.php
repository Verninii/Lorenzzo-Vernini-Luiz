<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lista de produtos com filtro, ordenação e paginação
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filtro: nome ou descrição
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Ordenação
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        if (in_array($sort, ['id', 'name', 'price'])) {
            $query->orderBy($sort, $direction);
        }

        // Itens por página
        $perPage = (int) $request->get('per_page', 20);
        if (! in_array($perPage, [10, 20, 50, 100])) {
            $perPage = 20;
        }

        $products = $query->paginate(5)->appends($request->query());

        return view('products.index', compact('products'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Armazenar novo produto
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
        ]);

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Produto criado com sucesso.');
    }

    /**
     * Detalhes
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Formulário de edição
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Atualizar produto
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
        ]);

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Deletar produto
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produto removido com sucesso.');
    }
}
