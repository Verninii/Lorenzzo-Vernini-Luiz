<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        // já trazendo cliente junto
        $orders = Order::with('client')->paginate(20);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'status'         => 'required|in:open,paid,canceled',
            'discount_value' => 'nullable|numeric|min:0',
            'items'          => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $order = null;

        DB::transaction(function () use (&$order, $data) {
            $order = Order::create([
                'client_id'      => $data['client_id'],
                'status'         => $data['status'],
                'discount_value' => $data['discount_value'] ?? 0,
                'total'          => 0,
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product   = Product::findOrFail($item['product_id']);
                $unitPrice = $product->price;
                $itemTotal = $unitPrice * $item['quantity'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'total'      => $itemTotal,
                ]);

                $total += $itemTotal;
            }

            $order->update([
                'total' => max(0, $total - $order->discount_value),
            ]);
        });

        // retorna o pedido com relações
        return response()->json(
            $order->load('client', 'items.product'),
            201
        );
    }

    public function show(Order $order)
    {
        $order->load('client', 'items.product');

        return response()->json($order);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status'         => 'sometimes|required|in:open,paid,canceled',
            'discount_value' => 'nullable|numeric|min:0',
        ]);

        if (isset($data['status'])) {
            $order->status = $data['status'];
        }

        if (array_key_exists('discount_value', $data)) {
            $order->discount_value = $data['discount_value'];
        }

        // recalcular total se mexeu no desconto
        $itemsTotal = $order->items()->sum('total');
        $order->total = max(0, $itemsTotal - $order->discount_value);

        $order->save();

        return response()->json($order->load('client', 'items.product'));
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(null, 204);
    }
}
