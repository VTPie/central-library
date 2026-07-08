<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderImport;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use SplFileObject;

class ImportOrderJob implements ShouldQueue
{
    use Batchable;
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $importId,
        public string $path,
        public int $offset,
        public int $limit,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $file = new SplFileObject($this->path);
        $header = config('app.order_import_columns');

        $processed = 0;
        $failed = 0;
        $rows = [];

        // seek() moves the internal line pointer to the given zero-indexed line number
        $file->seek($this->offset + 1);

        // eof() stops the loop once the pointer passes the last line of the file
        while (!$file->eof() && $processed + $failed < $this->limit) {
            // fgets() reads the current line and advances the pointer to the next one
            $line = $file->fgets();

            if ($line === false || trim($line) === '') {
                break;
            }

            $data = array_combine($header, str_getcsv($line));

            $bookId = $data['book_id'] ?? null;
            $quantity = $data['quantity'] ?? null;

            $book = $bookId ? Book::find($bookId) : null;

            if (!$book || !is_numeric($quantity) || $quantity <= 0) {
                $failed++;
                continue;
            }

            $rows[] = [
                'book_id' => $book->id,
                'quantity' => (int) $quantity,
                'total' => $book->price * (int) $quantity,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $processed++;
        }

        if (!empty($rows)) {
            DB::transaction(function () use ($rows) {
                Order::insert($rows);
            });
        }

        $import = OrderImport::findOrFail($this->importId);
        $import->increment('processed_rows', $processed);
        $import->increment('failed_rows', $failed);
    }
}
