<?php

namespace App\Jobs;

use App\Models\OrderImport;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use SplFileObject;

class ChunkOrderImportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $importRecordId,
        public string $filePath,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Get the import record to update
        $import = OrderImport::findOrFail(
            $this->importRecordId
        );

        // Update status to processing
        $import->update([
            'status' => config('app.import_statuses.processing'),
        ]);

        $fullPath = storage_path(
            'app/private/' . $this->filePath
        );

        try {
            if (!$this->hasValidHeader($fullPath)) {
                Log::error('ERROR: ', [
                    'method' => __METHOD__,
                    'line' => __LINE__,
                    'message' => 'CSV header does not match the expected order_import_columns format.',
                ]);

                $import->update([
                    'status' => config('app.import_statuses.failed'),
                ]);

                return;
            }

            // Update total rows count
            $totalRows = $this->countRows($fullPath);
            $import->update([
                'total_rows' => $totalRows,
            ]);

            $chunkSize = 1000;
            $jobs = [];

            for (
                $offset = 0;
                $offset < $totalRows;
                $offset += $chunkSize
            ) {
                $jobs[] = new ImportOrderJob(
                    importId: $import->id,
                    path: $fullPath,
                    offset: $offset,
                    limit: $chunkSize,
                );
            }

            if (empty($jobs)) {
                $import->update([
                    'status' => config('app.import_statuses.completed'),
                    'progress' => 100,
                ]);

                return;
            }

            // Dispatch chunk jobs as a batch so we know when the whole import is done
            Bus::batch($jobs)
                ->progress(function (Batch $batch) use ($import) {
                    // Called once per finished chunk job, with the batch's own
                    // atomically-tracked counters — safe from concurrent chunk writes.
                    $import->update([
                        'progress' => (int) $batch->progress(),
                    ]);
                })
                ->then(function () use ($import) {
                    $import->update([
                        'status' => config('app.import_statuses.completed'),
                        'progress' => 100,
                    ]);
                })
                ->catch(function () use ($import) {
                    $import->update([
                        'status' => config('app.import_statuses.failed'),
                    ]);
                })
                ->name('order-import-' . $import->id)
                ->dispatch();
        } catch (\Exception $e) {
            Log::error('ERROR: ', [
                'method' => __METHOD__,
                'line' => __LINE__,
                'message' => $e->getMessage(),
            ]);

            $import->update([
                'status' => config('app.import_statuses.failed'),
            ]);
        }
    }

    /**
     * Check the CSV header row matches the expected order_import_columns format exactly.
     */
    private function hasValidHeader(string $path): bool
    {
        $file = new SplFileObject($path);
        $header = str_getcsv($file->fgets());

        return $header === config('app.order_import_columns');
    }

    private function countRows(string $path): int
    {
        $count = 0;
        $file = new SplFileObject($path);

        while (!$file->eof()) {
            $line = $file->fgets();

            if (trim($line) !== '') {
                $count++;
            }
        }

        return max(0, $count - 1);
    }
}
