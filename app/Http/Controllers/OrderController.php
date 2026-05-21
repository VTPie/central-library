<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Book;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('book')->paginate(50);

        return view('orders.index', compact('orders'));
    }

    public function add()
    {
        $book_list = Book::all();
        $title = 'Add New Order';

        return view('orders.order-form', compact('book_list', 'title'));
    }

    public function store(OrderRequest $request)
    {
        $book = Book::findOrFail($request->book_id);

        Order::create([
            'book_id'  => $request->book_id,
            'quantity' => $request->quantity,
            'total'    => $book->price * $request->quantity,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function edit(int $id)
    {
        $order = Order::findOrFail($id);
        $book_list = Book::all();
        $title = 'Edit Order';

        return view('orders.order-form', compact('order', 'book_list', 'title'));
    }

    public function update(OrderRequest $request, int $id)
    {
        $book  = Book::findOrFail($request->book_id);
        $order = Order::findOrFail($id);
        $order->update([
            'book_id'  => $request->book_id,
            'quantity' => $request->quantity,
            'total'    => $book->price * $request->quantity,
        ]);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy(int $id)
    {
        Order::findOrFail($id)->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
