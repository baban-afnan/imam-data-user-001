<x-app-layout>
    <title>Imam Data Sub - Transaction History</title>

    <div class="page-body">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="page-title mb-4">
                <div class="row align-items-center">
                    <div class="col-12 col-sm-6">
                        <h3 class="fw-bold text-primary mb-1">Transaction History</h3>
                        <p class="text-muted small mb-0">
                            Track and manage your financial activity and service requests.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Transaction History Card -->
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-dark"><i class="ti ti-receipt me-2 text-primary"></i>Recent Transactions</h5>
                        </div>
                        
                        <div class="card-body p-0">
                            <!-- Filter Form -->
                            <div class="px-4 pb-4" id="filterRow">
                                <form class="row g-3 bg-light p-4 rounded-4 border-0 shadow-sm" method="GET" action="{{ route('transactions') }}">
                                    <div class="col-12 col-md-3">
                                        <label class="form-label small fw-bold text-dark text-uppercase">Reference ID</label>
                                        <input type="text" class="form-control bg-white border-0 shadow-sm" name="reference" value="{{ request('reference') }}" placeholder="Search Ref ID...">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label small fw-bold text-dark text-uppercase">Type</label>
                                        <select class="form-select bg-white border-0 shadow-sm" name="type">
                                            <option value="">All Types</option>
                                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Credit</option>
                                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                            <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                                            <option value="manual_credit" {{ request('type') == 'manual_credit' ? 'selected' : '' }}>Manual credit</option>
                                            <option value="manual_debit" {{ request('type') == 'manual_debit' ? 'selected' : '' }}>Manual Debit</option>
                                            <option value="bonus" {{ request('type') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label class="form-label small fw-bold text-dark text-uppercase">Service</label>
                                        <select class="form-select bg-white border-0 shadow-sm" name="service_type">
                                            <option value="">All Services</option>
                                            <option value="Airtime" {{ request('service_type') == 'Airtime' ? 'selected' : '' }}>Airtime</option>
                                            <option value="Data" {{ request('service_type') == 'Data' ? 'selected' : '' }}>Data</option>
                                            <option value="Electricity" {{ request('service_type') == 'Electricity' ? 'selected' : '' }}>Electricity</option>
                                            <option value="Cable" {{ request('service_type') == 'Cable' ? 'selected' : '' }}>Cable TV</option>
                                            <option value="Education" {{ request('service_type') == 'Education' ? 'selected' : '' }}>Education</option>
                                            <option value="Funding" {{ request('service_type') == 'Funding' ? 'selected' : '' }}>Wallet Funding</option>
                                            <option value="VNIN_TO_NIBSS" {{ request('service_type') == 'VNIN_TO_NIBSS' ? 'selected' : '' }}>VNIN TO NIBSS</option>
                                            <option value="BVN_SEARCH" {{ request('service_type') == 'BVN_SEARCH' ? 'selected' : '' }}>BVN Search</option>
                                            <option value="BVN_MODIFICATION" {{ request('service_type') == 'BVN_MODIFICATION' ? 'selected' : '' }}>BVN Modification</option>
                                            <option value="CRM" {{ request('service_type') == 'CRM' ? 'selected' : '' }}>CRM</option>
                                            <option value="BVN_USER" {{ request('service_type') == 'BVN_USER' ? 'selected' : '' }}>BVN User</option>
                                            <option value="APPROVAL_REQUEST" {{ request('service_type') == 'APPROVAL_REQUEST' ? 'selected' : '' }}>Approval Request</option>
                                            <option value="AFFIDAVIT" {{ request('service_type') == 'AFFIDAVIT' ? 'selected' : '' }}>Affidavit</option>
                                            <option value="NIN_SELFSERVICE" {{ request('service_type') == 'NIN_SELFSERVICE' ? 'selected' : '' }}>NIN Self Service</option>
                                            <option value="NIN_VALIDATION" {{ request('service_type') == 'NIN_VALIDATION' ? 'selected' : '' }}>NIN Validation</option>
                                            <option value="IPE" {{ request('service_type') == 'IPE' ? 'selected' : '' }}>IPE</option>
                                            <option value="NIN_MODIFICATION" {{ request('service_type') == 'NIN_MODIFICATION' ? 'selected' : '' }}>NIN Modification</option>
                                            <option value="TIN_INDIVIDUAL" {{ request('service_type') == 'TIN_INDIVIDUAL' ? 'selected' : '' }}>TIN Individual</option>
                                            <option value="not_selected" {{ request('service_type') == 'not_selected' ? 'selected' : '' }}>Not Selected</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label small fw-bold text-dark text-uppercase">From</label>
                                        <input type="date" class="form-control bg-white border-0 shadow-sm" name="date_from" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <label class="form-label small fw-bold text-dark text-uppercase">To</label>
                                        <input type="date" class="form-control bg-white border-0 shadow-sm" name="date_to" value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-12 col-md-2 d-flex align-items-end">
                                        <button class="btn btn-primary w-100 fw-semibold shadow-sm" type="submit">Filter</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Transactions Table -->
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-secondary small fw-semibold ps-4">#</th>
                                            <th class="text-secondary small fw-semibold">Date & Time</th>
                                            <th class="text-secondary small fw-semibold">Reference</th>
                                            <th class="text-secondary small fw-semibold">Description</th>
                                            <th class="text-secondary small fw-semibold text-center">Type</th>
                                            <th class="text-secondary small fw-semibold text-end">Amount</th>
                                            <th class="text-secondary small fw-semibold text-center">Status</th>
                                            <th class="text-secondary small fw-semibold pe-4 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($transactions as $index => $transaction)
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="text-muted small">{{ $transactions->firstItem() + $index }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-medium text-dark">{{ $transaction->created_at->format('d M Y') }}</span>
                                                        <small class="text-muted small">{{ $transaction->created_at->format('h:i A') }}</small>
                                                    </div>
                                                </td>
                                                <td><span class="text-primary fw-medium">#{{ substr($transaction->transaction_ref, 0, 8) }}...</span></td>
                                                <td>
                                                    <span class="d-inline-block text-truncate text-muted small" style="max-width: 200px;" title="{{ $transaction->description }}">
                                                        {{ $transaction->description }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if(in_array($transaction->type, ['credit', 'manual_credit', 'bonus']))
                                                        <span class="badge bg-success bg-opacity-10 text-success border-success-subtle rounded-pill px-3 py-1">
                                                            <i class="ti ti-arrow-down-left me-1"></i>Credit
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border-danger-subtle rounded-pill px-3 py-1">
                                                            <i class="ti ti-arrow-up-right me-1"></i>Debit
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-bold {{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus']) ? 'text-success' : 'text-danger' }}">
                                                        {{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus']) ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $statusClass = match($transaction->status) {
                                                            'completed', 'successful' => 'success',
                                                            'failed' => 'danger',
                                                            'pending' => 'warning',
                                                            default => 'info'
                                                        };
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass }} rounded-pill px-3">
                                                        {{ ucfirst($transaction->status) }}
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-center">
                                                    <button type="button" class="btn btn-icon btn-light text-primary rounded-circle shadow-sm"
                                                        data-bs-toggle="modal" data-bs-target="#txModal{{ $transaction->id }}">
                                                        <i class="ti ti-eye fs-20"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="ti ti-database-off fs-1 text-muted mb-2"></i>
                                                        <p class="text-muted mb-0">No transactions found matching your criteria.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 px-4 pb-4 d-flex justify-content-center">
                                {{ $transactions->withQueryString()->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Modals -->
    @foreach ($transactions as $transaction)
        <div class="modal fade" id="txModal{{ $transaction->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white py-3">
                        <h5 class="modal-title fw-bold text-white d-flex align-items-center">
                            <i class="ti ti-receipt me-2"></i>Receipt Details
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <div class="avatar avatar-xl bg-{{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus']) ? 'success' : 'danger' }} bg-opacity-10 rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                <i class="ti ti-{{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus']) ? 'arrow-down-left' : 'arrow-up-right' }} fs-1 text-{{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus']) ? 'success' : 'danger' }}"></i>
                            </div>
                            <h2 class="fw-bold {{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus']) ? 'text-success' : 'text-danger' }} mb-1">
                                {{ in_array($transaction->type, ['credit', 'manual_credit', 'bonus']) ? '+' : '-' }}₦{{ number_format($transaction->amount, 2) }}
                            </h2>
                            <p class="text-muted small">Transaction Reference: <span class="fw-medium">{{ $transaction->transaction_ref }}</span></p>
                        </div>
                        
                        <div class="bg-light rounded-4 p-3 mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Status</span>
                                <span class="badge bg-{{ $transaction->status == 'completed' || $transaction->status == 'successful' ? 'success' : ($transaction->status == 'failed' ? 'danger' : 'warning') }} rounded-pill px-3">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Type</span>
                                <span class="fw-bold text-dark small text-uppercase">{{ $transaction->type }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Date & Time</span>
                                <span class="fw-medium text-dark small">{{ $transaction->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <hr class="my-2 border-secondary-subtle">
                            <div class="mt-2">
                                <span class="text-muted small d-block mb-1">Description</span>
                                <p class="mb-0 text-dark small fw-medium">{{ $transaction->description }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                        <button type="button" class="btn btn-secondary rounded-pill px-4 shadow-sm" data-bs-dismiss="modal">Close</button>
                        @if($transaction->status == 'completed' || $transaction->status == 'successful')
                            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" onclick="window.print()">
                                <i class="ti ti-printer me-1"></i> Print Receipt
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <style>
        .hover-up:hover { transform: translateY(-3px); transition: all 0.3s ease; }
        .btn-icon { width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center; }
        .table thead th { text-transform: uppercase; letter-spacing: 0.5px; font-size: 0.75rem; }
        .table-nowrap td, .table-nowrap th { white-space: nowrap; }
        .bg-opacity-10 { --bs-bg-opacity: 0.1; }
        .fs-20 { font-size: 20px !important; }
    </style>
</x-app-layout>
