<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Lista de pedidos com filtro, ordenação e paginação
     */
    public function index(Request $request)
    {
        $query = Order::with('client');

        // Filtro por status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filtro por cliente
        if ($clientId = $request->get('client_id')) {
            $query->where('client_id', $clientId);
        }

        // Filtro por ID do pedido (busca rápida)
        if ($searchId = $request->get('order_id')) {
            $query->where('id', $searchId);
        }

        // Ordenação
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');

        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        if (in_array($sort, ['id', 'status', 'total', 'created_at'])) {
            $query->orderBy($sort, $direction);
        }

        // Itens por página
        $perPage = (int) $request->get('per_page', 20);
        if (! in_array($perPage, [5, 10, 20, 50])) {
            $perPage = 20;
        }

        $orders = $query->paginate($perPage)->appends($request->query());

        // lista de clientes para o filtro
        $clients = Client::orderBy('id')->get();

        return view('orders.index', compact('orders', 'clients'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $clients  = Client::orderBy('id')->get();
        $products = Product::orderBy('name')->get();

        return view('orders.create', compact('clients', 'products'));
    }

    /**
     * Armazenar pedido com itens
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'status'         => 'required|in:open,paid,canceled',
            'discount_value' => 'nullable|numeric|min:0',
            'product_ids'    => 'required|array',
            'product_ids.*'  => 'nullable|exists:products,id',
            'quantities'     => 'required|array',
            'quantities.*'   => 'nullable|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $discount = (float) $request->input('discount_value', 0);

            $order = Order::create([
                'client_id'      => $request->client_id,
                'status'         => $request->status,
                'discount_value' => $discount,
                'total'          => 0, // ajusta depois
            ]);

            $productIds = $request->input('product_ids', []);
            $quantities = $request->input('quantities', []);

            $total = 0;

            foreach ($productIds as $index => $productId) {
                $productId = $productId ?: null;
                $quantity  = $quantities[$index] ?? null;

                if (!$productId || !$quantity) {
                    continue; // pula linhas vazias
                }

                $product = Product::findOrFail($productId);
                $unitPrice = $product->price;
                $itemTotal = $unitPrice * $quantity;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'unit_price' => $unitPrice,
                    'total'      => $itemTotal,
                ]);

                $total += $itemTotal;
            }

            $order->update([
                'total' => max(0, $total - $order->discount_value),
            ]);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Pedido criado com sucesso.');
    }

    /**
     * Detalhes do pedido
     */
    public function show(Order $order)
    {
        $order->load('client', 'items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Formulário de edição (vamos permitir alterar status e desconto)
     */
    public function edit(Order $order)
    {
        $order->load('client', 'items.product');

        return view('orders.edit', compact('order'));
    }

    /**
     * Atualizar status e desconto
     */
    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'         => 'required|in:open,paid,canceled',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        $discount = (float) ($data['discount_value'] ?? 0);

        // recalcula total aplicando novo desconto
        $itemsTotal = $order->items()->sum('total');

        $order->update([
            'status'         => $data['status'],
            'discount_value' => $discount,
            'total'          => max(0, $itemsTotal - $discount),
        ]);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pedido atualizado com sucesso.');
    }

    /**
     * Excluir pedido
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Pedido removido com sucesso.');
    }
}
