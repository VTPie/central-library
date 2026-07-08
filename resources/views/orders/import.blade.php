@extends('layout')

@section('title', 'Central Library | Import Orders')

@section('content')
<div class="container">
    <h3 class="text-center mt-2">Import Order</h3>

    <div class="row mt-3">
        <div class="col-md-6">
            <form method="POST" action="{{ route('orders.import') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="file" class="form-label">Import new file</label>
                    <input type="file" name="order_file" id="file"
                           class="form-control @error('order_file') is-invalid @enderror"
                           accept=".csv">
                    @error('order_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Accepted format: <strong>.csv</strong>.</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Import</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Import History --}}
    <hr class="my-4">
    <h5 class="mb-3">Import History</h5>

    @if (isset($importHistory) && $importHistory->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Created Date</th>
                        <th>Imported By</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Detail</th>
                        <th>Error File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($importHistory as $record)
                        @php
                            $statusColors = [
                                'pending'    => 'secondary',
                                'processing' => 'primary',
                                'completed'  => 'success',
                                'failed'     => 'danger',
                            ];
                            $color = $statusColors[$record->status] ?? 'secondary';
                        @endphp
                        <tr data-import-row="{{ $record->id }}" data-status="{{ $record->status }}">
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $record->user->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $color }} text-capitalize" data-field="status-badge">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td style="min-width:140px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:10px">
                                        <div class="progress-bar bg-{{ $color }}"
                                             role="progressbar"
                                             data-field="progress-bar"
                                             style="width:{{ $record->progress }}%"
                                             aria-valuenow="{{ $record->progress }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-nowrap" data-field="progress-text">{{ $record->progress }}%</small>
                                </div>
                            </td>
                            <td>
                                <small data-field="detail">
                                    Total: <strong data-field="total-rows">{{ number_format($record->total_rows) }}</strong> &nbsp;|&nbsp;
                                    Processed: <strong class="text-success" data-field="processed-rows">{{ number_format($record->processed_rows) }}</strong> &nbsp;|&nbsp;
                                    Failed: <strong class="text-danger" data-field="failed-rows">{{ number_format($record->failed_rows) }}</strong>
                                </small>
                            </td>
                            <td data-field="error-file">
                                @if ($record->error_file_path)
                                    <a href="{{ route('orders.importErrorFile', $record->id) }}"
                                       class="btn btn-sm btn-outline-danger"
                                       title="Download error file">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($importHistory->hasPages())
            <nav aria-label="import history pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item {{ $importHistory->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $importHistory->previousPageUrl() }}">Previous</a>
                    </li>
                    @for ($i = 1; $i <= $importHistory->lastPage(); $i++)
                        <li class="page-item {{ $importHistory->currentPage() == $i ? 'active' : '' }}">
                            <a class="page-link" href="{{ $importHistory->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor
                    <li class="page-item {{ $importHistory->hasMorePages() ? '' : 'disabled' }}">
                        <a class="page-link" href="{{ $importHistory->nextPageUrl() }}">Next</a>
                    </li>
                </ul>
            </nav>
        @endif
    @else
        <p class="text-muted text-center">No import history yet.</p>
    @endif
</div>

@push('scripts')
<script>
    (function () {
        const statusColors = {
            pending: 'secondary',
            processing: 'primary',
            completed: 'success',
            failed: 'danger',
        };

        function pendingRowIds() {
            return Array.from(document.querySelectorAll('tr[data-import-row]'))
                .filter((row) => !['completed', 'failed'].includes(row.dataset.status))
                .map((row) => row.dataset.importRow);
        }

        function applyUpdate(record) {
            const row = document.querySelector(`tr[data-import-row="${record.id}"]`);
            if (!row) {
                return;
            }

            row.dataset.status = record.status;

            const color = statusColors[record.status] ?? 'secondary';

            const badge = row.querySelector('[data-field="status-badge"]');
            badge.textContent = record.status;
            badge.className = `badge bg-${color} text-capitalize`;

            const progressBar = row.querySelector('[data-field="progress-bar"]');
            progressBar.style.width = `${record.progress}%`;
            progressBar.setAttribute('aria-valuenow', record.progress);
            progressBar.className = `progress-bar bg-${color}`;

            row.querySelector('[data-field="progress-text"]').textContent = `${record.progress}%`;
            row.querySelector('[data-field="total-rows"]').textContent = record.total_rows.toLocaleString();
            row.querySelector('[data-field="processed-rows"]').textContent = record.processed_rows.toLocaleString();
            row.querySelector('[data-field="failed-rows"]').textContent = record.failed_rows.toLocaleString();
        }

        async function pollProgress() {
            const ids = pendingRowIds();

            if (ids.length === 0) {
                return;
            }

            try {
                const response = await fetch(`{{ route('orders.importProgress') }}?ids=${ids.join(',')}`, {
                    headers: { Accept: 'application/json' },
                });

                if (!response.ok) {
                    return;
                }

                const records = await response.json();
                records.forEach(applyUpdate);
            } catch (e) {
                // Ignore transient network errors; the next tick will retry.
            }
        }

        setInterval(pollProgress, 1000);
    })();
</script>
@endpush
@endsection
