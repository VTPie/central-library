<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderImportRequest;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Order;
use App\Services\OrderImportService;
use App\Jobs\ChunkOrderImportJob;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected OrderImportService $orderImportService;

    public function __construct(OrderImportService $orderImportService)
    {
        $this->orderImportService = $orderImportService;
    }

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

    public function importForm()
    {
        try {
            $title = 'Import Orders';
            $importHistory = $this->orderImportService->getImportHistory(20);

            return view('orders.import', compact('title', 'importHistory'));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('orders.index')->with('error', 'Cannot load import history right now. Please try again later.');
        }
    }

    public function importTemplate()
    {
        $columns = config('app.order_import_columns');

        $csv = implode(',', $columns) . "\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="order-import-template.csv"',
        ]);
    }

    public function import(OrderImportRequest $request)
    {
        try {
            $path = $request->file('order_file')->store('orders-imports');

            $import_record = $this->orderImportService->createImportRecord([
                'user_id' => auth()->id(),
                'status'  => config('app.import_statuses.pending'),
            ]);

            if (!$import_record) {
                return redirect()->route('orders.importForm')->with('error', 'Failed to create import record.');
            }

            ChunkOrderImportJob::dispatch($import_record->id, $path);

            return redirect()->route('orders.importForm')->with('success', 'File uploaded successfully. Import process has started and you will be notified once it is completed.');
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method'  => __METHOD__,
                'line'    => __LINE__,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('orders.importForm')->with('error', 'Failed to start the import. Please try again.');
        }
    }

    public function importProgress(Request $request)
    {
        // Convert 1,2,3 to [1, 2, 3] and filter out any non-integer values
        $ids = array_filter(array_map('intval', explode(',', (string) $request->query('ids'))));

        if (empty($ids)) {
            return response()->json([]);
        }

        try {
            $records = $this->orderImportService->getProgress($ids);

            return response()->json(
                $records->map(function ($record) {
                    return [
                        'id' => $record->id,
                        'status' => $record->status,
                        'progress' => $record->progress,
                        'total_rows' => $record->total_rows,
                        'processed_rows' => $record->processed_rows,
                        'failed_rows' => $record->failed_rows,
                        'error_file_path' => $record->error_file_path,
                    ];
                })
            );
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Failed to fetch import progress.'], 500);
        }
    }
}
