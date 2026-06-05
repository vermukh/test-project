<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\PickupPoint;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index', [
            'orders' => Order::with(['status', 'pickupPoint', 'items.product'])->orderBy('id')->get(),
        ]);
    }

    public function create()
    {
        return view('orders.form', ['order' => null] + $this->dictionaries());
    }

    public function edit(Order $order)
    {
        return view('orders.form', ['order' => $order] + $this->dictionaries());
    }

    public function store(Request $request)
    {
        [$data, $items] = $this->validated($request);

        DB::transaction(function () use ($data, $items) {
            $order = Order::create($data + [
                
                'receive_code' => random_int(100, 999),
            ]);
            $this->saveItems($order, $items);
        });

        return redirect()->route('orders.index')->with('success', 'Заказ успешно добавлен.');
    }

    public function update(Request $request, Order $order)
    {
        [$data, $items] = $this->validated($request);

        DB::transaction(function () use ($order, $data, $items) {
            $order->update($data);
            $order->items()->delete();
            $this->saveItems($order, $items);
        });

        return redirect()->route('orders.index')
            ->with('success', 'Заказ №' . $order->id . ' успешно изменён.');
    }

    public function destroy(Order $order)
    {
        $order->delete(); 

        return redirect()->route('orders.index')
            ->with('success', 'Заказ №' . $order->id . ' удалён.');
    }

    private function dictionaries(): array
    {
        return [
            'statuses' => OrderStatus::orderBy('name')->get(),
            'points'   => PickupPoint::orderBy('id')->get(),
        ];
    }

    



    private function validated(Request $request): array
    {
        $data = $request->validate([
            'composition'     => 'required|string',
            'status_id'       => 'required|exists:order_statuses,id',
            'pickup_point_id' => 'required|exists:pickup_points,id',
            'order_date'      => 'required|date',
            'delivery_date'   => 'required|date|after_or_equal:order_date',
        ], [
            'composition.required'         => 'Укажите артикул заказа в формате: АРТИКУЛ, количество, АРТИКУЛ, количество.',
            'status_id.required'           => 'Выберите статус заказа из списка.',
            'pickup_point_id.required'     => 'Выберите адрес пункта выдачи из списка.',
            'order_date.required'          => 'Укажите дату заказа.',
            'order_date.date'              => 'Дата заказа должна быть корректной датой.',
            'delivery_date.required'       => 'Укажите дату выдачи.',
            'delivery_date.date'           => 'Дата выдачи должна быть корректной датой.',
            'delivery_date.after_or_equal' => 'Дата выдачи не может быть раньше даты заказа.',
        ]);

        
        $parts = array_map('trim', explode(',', $data['composition']));
        if (count($parts) % 2 !== 0) {
            return $this->compositionError();
        }

        $items = [];
        for ($i = 0; $i < count($parts); $i += 2) {
            $product = Product::where('article', $parts[$i])->first();
            $quantity = $parts[$i + 1];
            if (!$product || !ctype_digit($quantity) || (int) $quantity < 1) {
                return $this->compositionError($parts[$i]);
            }
            $items[$product->id] = ($items[$product->id] ?? 0) + (int) $quantity;
        }

        unset($data['composition']);

        return [$data, $items];
    }

    private function compositionError(?string $article = null): never
    {
        $message = $article
            ? 'Ошибка в составе заказа: товар с артикулом «' . $article
                . '» не найден или указано некорректное количество.'
            : 'Неверный формат состава заказа.';

        abort(redirect()->back()->withInput()->with(
            'error',
            $message . ' Используйте формат: АРТИКУЛ, количество, АРТИКУЛ, количество. Пример: PMEZMH, 2, BPV4MM, 1'
        ));
    }

    private function saveItems(Order $order, array $items): void
    {
        foreach ($items as $productId => $quantity) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $quantity,
            ]);
        }
    }
}
