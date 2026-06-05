@extends('layout')

@section('title', 'Заказы')

@section('styles')
<style>
    .order-card {
        display: flex;
        gap: 16px;
        background: var(--card);
        border: 1px solid var(--graphite);
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 12px;
    }
    .order-card.clickable { cursor: pointer; }
    .order-card.clickable:hover { outline: 2px solid var(--accent); }
    .order-info { flex: 1; font-size: 14px; line-height: 1.6; }
    .order-info .head { font-weight: 700; }
    .delivery-box {
        width: 150px;
        flex-shrink: 0;
        border: 1px solid var(--graphite);
        border-radius: 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        font-size: 13px;
        text-align: center;
    }
    .delivery-box b { font-size: 16px; }
    .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
</style>
@endsection

@section('content')
<div class="top-actions">
    <h1 style="margin-bottom:0">Заказы</h1>
    <div style="display:flex; gap:10px">
        <a class="btn btn-dark" href="{{ route('products.index') }}">Назад к товарам</a>
        @if (session('user.role') === 'Администратор')
            <a class="btn" href="{{ route('orders.create') }}">Добавить заказ</a>
        @endif
    </div>
</div>

@forelse ($orders as $order)
    <div class="order-card @if (session('user.role') === 'Администратор') clickable @endif"
        @if (session('user.role') === 'Администратор')
            onclick="window.location='{{ route('orders.edit', $order) }}'"
            title="Нажмите для редактирования заказа"
        @endif
    >
        <div class="order-info">
            <div class="head">{{ $order->composition }}</div>
            <div>{{ $order->status->name }}</div>
            <div>{{ $order->pickupPoint->address }}</div>
            <div>{{ $order->order_date->format('d.m.Y') }}</div>
        </div>
        <div class="delivery-box">
            <span>Дата доставки</span>
            <b>{{ $order->delivery_date->format('d.m.Y') }}</b>
            @if (session('user.role') === 'Администратор')
                <form method="POST" action="{{ route('orders.destroy', $order) }}"
                      onsubmit="return confirm('Внимание! Заказ №{{ $order->id }} будет удалён безвозвратно. Продолжить?')"
                      onclick="event.stopPropagation()">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="padding:5px 10px; font-size:12px">Удалить</button>
                </form>
            @endif
        </div>
    </div>
@empty
    <div class="flash warning">
        <span class="icon">⚠</span>
        <div>
            <b>Заказов нет</b>
            В системе пока нет ни одного заказа.
        </div>
    </div>
@endforelse
@endsection
