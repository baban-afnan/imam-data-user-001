<x-app-layout>
    <title>Imam Data Sub - {{ $title ?? 'NIN Validation & IPE' }}</title>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title mb-3">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <h3 class="fw-bold text-primary">NIN Validation & IPE</h3>
                        <p class="text-muted small mb-0">Submit requests for high-speed NIN Validation or IPE Clearance.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-3">
            <div class="row">

                {{-- Request Form Column --}}
                <div class="col-xl-5 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0 fw-bold text-white"><i class="bi bi-shield-check me-2"></i>New Submission</h5>
                        </div>

                        <div class="card-body p-4">
                            {{-- Alerts --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4">
                                    <i class="bi bi-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('nin-validation.store') }}" class="row g-4">
                                @csrf

                                {{-- Service Type --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Service Category <span class="text-danger">*</span></label>
                                    <select name="service_type_select" id="service_type_select" class="form-select border-primary-subtle" required>
                                        <option value="">-- Choose Category --</option>
                                        <option value="validation">NIN Validation</option>
                                        <option value="ipe">IPE Clearance</option>
                                    </select>
                                </div>

                                {{-- Service Field --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-bold">Specific Problem <span class="text-danger">*</span></label>
                                    <select name="service_field" id="service_field" class="form-select border-primary-subtle" required disabled>
                                        <option value="">-- Choose Field --</option>
                                    </select>
                                    <div class="mt-2 text-muted fst-italic small" id="field-description"></div>
                                </div>

                                {{-- Hidden Type --}}
                                <input type="hidden" name="service_type" id="service_type">

                                {{-- NIN / Tracking --}}
                                <div class="col-12" id="nin_wrapper" style="display: none;">
                                    <label class="form-label fw-bold">11-Digit NIN <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                        <input type="text" name="nin" class="form-control" placeholder="00000000000" maxlength="11" pattern="\d{11}">
                                    </div>
                                </div>

                                <div class="col-12" id="tracking_wrapper" style="display: none;">
                                    <label class="form-label fw-bold">Tracking ID <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                        <input type="text" name="tracking_id" class="form-control" placeholder="Enter IPE Tracking ID">
                                    </div>
                                </div>

                                {{-- Pricing --}}
                                <div class="col-12">
                                    <div class="card bg-light border-0 rounded-3">
                                        <div class="card-body py-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted d-block text-uppercase fw-bold small">Service Fee</small>
                                                <span class="h4 fw-bold text-primary mb-0" id="price_display">₦0.00</span>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block text-uppercase fw-bold small">Wallet Balance</small>
                                                <span class="fw-bold text-success">₦{{ number_format($wallet->balance ?? 0, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Note --}}
                                <div class="col-12">
                                    <div class="alert alert-soft-warning border-0 small mb-0">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <strong>Important:</strong> Validation requests are final and non-refundable once submitted to the API.
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg shadow-sm hover-up">
                                        <i class="bi bi-send-check-fill me-2"></i> Submit Validation Request
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Submission History Column --}}
                <div class="col-xl-7">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="bi bi-clock-history me-2 text-primary"></i> Request History
                            </h5>
                        </div>

                        <div class="card-body p-4">
                            <form class="row g-3 mb-4 bg-light p-3 rounded-3 border" method="GET">
                                <div class="col-md-6">
                                    <input class="form-control border-0 shadow-sm" name="search" type="text" placeholder="Search NIN or Tracking ID..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select border-0 shadow-sm" name="status">
                                        <option value="">All Statuses</option>
                                        @foreach (['pending', 'processing', 'successful', 'failed', 'rejected'] as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-primary w-100 shadow-sm" type="submit">
                                        <i class="bi bi-filter"></i>
                                    </button>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-primary text-white">
                                        <tr>
                                            <th>#</th>
                                            <th>Identifier</th>
                                            <th>Service</th>
                                            <th>Status</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($submissions as $submission)
                                            <tr>
                                                <td class="fw-bold text-muted">{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
                                                <td>
                                                    <span class="text-primary fw-medium">{{ $submission->nin ?? $submission->tracking_id }}</span>
                                                    <br><small class="text-muted">{{ $submission->reference }}</small>
                                                </td>
                                                <td><small class="fw-bold">{{ $submission->service_field_name }}</small></td>
                                                <td>
                                                    <span class="badge rounded-pill bg-{{ match($submission->status) {
                                                        'successful', 'success', 'resolved' => 'success',
                                                        'processing', 'in-progress' => 'primary',
                                                        'pending' => 'warning',
                                                        'failed', 'rejected', 'error' => 'danger',
                                                        default => 'secondary'
                                                    } }} px-3">
                                                        {{ ucfirst($submission->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group">
                                                        <a href="{{ route('nin-validation.check', $submission->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Refresh Status">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-icon btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#responseModal" data-response="{{ $submission->comment ?? 'No details available.' }}">
                                                            <i class="bi bi-eye-fill"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                    No validation requests found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 d-flex justify-content-center">
                                {{ $submissions->withQueryString()->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Response Modal --}}
    <div class="modal fade" id="responseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold"><i class="bi bi-chat-left-dots me-2"></i>Service Response</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="responseLoading" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <div id="beautifulResponse" class="mb-0"></div>
                    <pre id="responseContent" class="bg-light p-3 rounded-3 border mb-0 d-none" style="white-space: pre-wrap; font-size: 0.85rem; max-height: 300px; overflow-y: auto;"></pre>
                </div>
                <div class="modal-footer bg-light border-0">
                    <span id="encouragement" class="text-muted small me-auto"></span>
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Data Section --}}
    <script id="service-data" type="application/json">
        @json($services)
    </script>

    {{-- Custom Style --}}
    <style>
        .hover-up:hover { transform: translateY(-3px); transition: all 0.3s ease; }
        .alert-soft-warning { background-color: #fff3cd; color: #856404; }
        .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
        .table thead th { font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; }
        pre { font-family: 'Courier New', Courier, monospace; }
    </style>

    <script src="{{ asset('assets/js/ninvalidation.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</x-app-layout>
