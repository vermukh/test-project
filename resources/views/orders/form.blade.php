@extends('layout')

@section('title', $order ? 'Редактирование заказа' : 'Добавление заказа')

@section('styles')
<style>
    .form-card {
        background: var(--card);
        border: 1px solid var(--line);
        border-top: 4px solid var(--accent);
        border-radius: 10px;
        padding: 26px;
        max-width: 680px;
    }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 18px; }
    .grid .full { grid-column: 1 / -1; }
    .grid input, .grid select { width: 100%; }
    .actions { margin-top: 20px; display: flex; gap: 10px; }
    .note { font-size: 12px; color: var(--muted); margin-top: 3px; }
</style>
@endsection

@section('content')
<h1>{{ $order ? 'Редактирование заказа №' . $order->id : 'Добавление заказа' }}</h1>

<div class="form-card">
    <form method="POST" action="{{ $order ? route('orders.update', $order) : route('orders.store') }}">
        @csrf
        @if ($order)
            @method('PUT')
        @endif

        <div class="grid">
            <div class="full">
                <label for="composition">Артикул *</label>
                <input type="text" id="composition" name="composition" required
                       value="{{ old('composition', $order->composition ?? '') }}"
                       placeholder="PMEZMH, 2, BPV4MM, 1">
                <div class="note">Состав заказа в формате: АРТИКУЛ, количество, АРТИКУЛ, количество</div>
            </div>
            <div>
                <label for="status_id">Статус заказа *</label>
                <select id="status_id" name="status_id" required>
                    <option value="">— выберите статус —</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}"
                            @selected(old('status_id', $order->status_id ?? null) == $status->id)>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="pickup_point_id">Адрес пункта выдачи *</label>
                <select id="pickup_point_id" name="pickup_point_id" required>
                    <option value="">— выберите адрес —</option>
                    @foreach ($points as $point)
                        <option value="{{ $point->id }}"
                            @selected(old('pickup_point_id', $order->pickup_point_id ?? null) == $point->id)>
                            {{ $point->address }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="order_date">Дата заказа *</label>
                <input type="date" id="order_date" name="order_date" required
                       value="{{ old('order_date', $order?->order_date?->format('Y-m-d')) }}">
            </div>
            <div>
                <label for="delivery_date">Дата выдачи *</label>
                <input type="date" id="delivery_date" name="delivery_date" required
                       value="{{ old('delivery_date', $order?->delivery_date?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn">{{ $order ? 'Сохранить изменения' : 'Добавить заказ' }}</button>
            <a class="btn btn-dark" href="{{ route('orders.index') }}">Назад</a>
        </div>
    </form>
</div>
@endsection
