<x-app-layout>
    <title>Imam Data Sub - {{ $title ?? 'IPE Clearance' }}</title>

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title mb-3">
                <div class="row align-items-center">
                    <div class="col-sm-6 col-12">
                        <h3 class="fw-bold text-dark">IPE Clearance</h3>
                        <p class="text-muted small mb-0">Submit requests for IPE Clearance services.</p>
                    </div>
                    <div class="col-sm-6 col-12 text-end">
                        <button class="btn btn-primary rounded-pill shadow-sm" onclick="batchCheck()">
                            <i class="ti ti-refresh me-2"></i>Check All Pending
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-3">
            <div class="row">
                {{-- Request Form Column --}}
                <div class="col-xl-5 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="card-header bg-white py-3 border-bottom-0">
                            <h5 class="mb-0 fw-bold text-primary"><i class="ti ti-shield-check me-2"></i>New IPE Request</h5>
                        </div>

                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('ipe.store') }}" class="row g-4" id="ipeForm" onsubmit="return handleFormSubmit(event)">
                                @csrf

                                {{-- Service Field --}}
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Service Type <span class="text-danger">*</span></label>
                                    <select name="service_field" id="service_field" class="form-select border-light-subtle shadow-sm rounded-3" required>
                                        <option value="">-- Choose Service --</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service['id'] }}" 
                                                    data-price="{{ $service['price'] }}"
                                                    data-field-code="{{ $service['field_code'] ?? '002' }}">
                                                {{ $service['name'] }} - ₦{{ number_format($service['price'], 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="field_code" id="field_code" value="">
                                </div>

                                {{-- Tracking ID --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Tracking ID <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-light-subtle"><i class="ti ti-hash text-muted"></i></span>
                                        <input type="text" name="tracking_id" class="form-control border-light-subtle shadow-sm" 
                                               placeholder="Enter IPE Tracking ID" required 
                                               minlength="10" maxlength="50"
                                               pattern="[A-Z0-9]+" title="Only uppercase letters and numbers">
                                    </div>
                                    <small class="text-muted fst-italic mt-1 d-block">From your IPE enrollment slip (e.g., 0T3NU7RSKMCHTT4)</small>
                                </div>
                                
                                {{-- Description/Reference --}}
                                <div class="col-12">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Reference / Description</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-light-subtle"><i class="ti ti-file-text text-muted"></i></span>
                                        <input type="text" name="description" class="form-control border-light-subtle shadow-sm" 
                                               placeholder="Optional reference note">
                                    </div>
                                </div>

                                {{-- Pricing --}}
                                <div class="col-12">
                                    <div class="card bg-primary bg-opacity-10 border-0 rounded-4 mt-2">
                                        <div class="card-body py-3">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <small class="text-primary fw-bold text-uppercase small">Service Fee</small>
                                                    <h3 class="fw-bold text-primary mb-0" id="price_display">₦0.00</h3>
                                                </div>
                                                <div class="col-6 text-end border-start border-primary border-opacity-25">
                                                    <small class="text-muted fw-bold text-uppercase small">Balance</small>
                                                    <h5 class="fw-bold text-success mb-0 d-flex align-items-center justify-content-end gap-1">
                                                        <i class="ti ti-wallet"></i> 
                                                        ₦{{ number_format($wallet->balance ?? 0, 2) }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow hover-up" id="submitBtn">
                                        <span id="submitText">Submit Request</span>
                                        <i class="ti ti-send ms-2"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Submission History Column --}}
                <div class="col-xl-7">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                        <div class="card-header bg-white py-3 border-bottom-0 d-flex align-items-center justify-content-between">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="ti ti-history me-2 text-primary"></i> Request History
                            </h5>
                        </div>

                        <div class="card-body p-0">
                            {{-- Filter Form --}}
                            <div class="px-3 pb-3">
                                <form class="row g-2 bg-light p-2 rounded-3 border border-light-subtle" method="GET">
                                    <div class="col-md-5">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text border-0 bg-white"><i class="ti ti-search text-muted"></i></span>
                                            <input class="form-control border-0 shadow-none" name="search" type="text" 
                                                   placeholder="Search Tracking ID..." value="{{ request('search') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select form-select-sm border-0 shadow-none bg-white" name="status">
                                            <option value="">All Statuses</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary btn-sm w-100 rounded-pill" type="submit">
                                            Apply Filter
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 text-muted small fw-bold text-uppercase">Tracking ID</th>
                                            <th class="text-muted small fw-bold text-uppercase">Service</th>
                                            <th class="text-muted small fw-bold text-uppercase">Amount</th>
                                            <th class="text-muted small fw-bold text-uppercase">Status</th>
                                            <th class="text-end pe-4 text-muted small fw-bold text-uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($submissions as $submission)
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="text-dark fw-bold d-block">{{ $submission->tracking_id }}</span>
                                                    @if($submission->description)
                                                        <small class="text-muted fs-11">{{ Str::limit($submission->description, 20) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="d-block text-dark fs-13">{{ $submission->service_field_name }}</span>
                                                    <small class="text-muted fs-11"><i class="ti ti-calendar me-1"></i>{{ $submission->created_at->format('M d, H:i') }}</small>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-dark">₦{{ number_format($submission->amount, 2) }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $status = strtolower($submission->status);
                                                        $badgeClass = match($status) {
                                                            'successful', 'success', 'resolved', 'completed' => 'bg-success-subtle text-success border-success-subtle',
                                                            'processing', 'in-progress' => 'bg-primary-subtle text-primary border-primary-subtle',
                                                            'pending' => 'bg-warning-subtle text-warning border-warning-subtle',
                                                            'failed', 'rejected', 'error', 'cancelled' => 'bg-danger-subtle text-danger border-danger-subtle',
                                                            default => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                                        };
                                                        $icon = match($status) {
                                                            'successful', 'success', 'resolved', 'completed' => 'ti-circle-check-filled',
                                                            'processing', 'in-progress' => 'ti-loader-2',
                                                            'pending' => 'ti-clock-filled',
                                                            'failed', 'rejected', 'error', 'cancelled' => 'ti-circle-x-filled',
                                                            default => 'ti-help-circle',
                                                        };
                                                    @endphp
                                                    <span class="badge border rounded-pill px-2 py-1 {{ $badgeClass }}">
                                                        <i class="ti {{ $icon }} me-1"></i>{{ ucfirst($submission->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('ipe.check', $submission->id) }}" 
                                                           class="btn btn-sm btn-light text-primary rounded-circle status-check-btn" 
                                                           title="Check Status"
                                                           data-action="check-status">
                                                            <i class="ti ti-refresh"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-light text-info rounded-circle" 
                                                                title="View Details"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#commentModal"
                                                                data-comment="{{ $submission->comment ?? $submission->description ?? 'No details available.' }}"
                                                                data-file-url="">
                                                            <i class="ti ti-eye"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-5">
                                                    <div class="py-4">
                                                        <div class="avatar avatar-xl bg-light rounded-circle mb-3 mx-auto">
                                                            <i class="ti ti-inbox fs-2 text-muted"></i>
                                                        </div>
                                                        <h6 class="fw-bold">No Requests Found</h6>
                                                        <p class="small text-muted mb-0">Your IPE requests will appear here.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="p-3 border-top">
                                {{ $submissions->withQueryString()->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Include the Request Response Modal --}}
    @include('pages.comment')

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Price Update Logic
            const serviceSelect = document.getElementById('service_field');
            const priceDisplay = document.getElementById('price_display');
            const fieldCodeInput = document.getElementById('field_code');
            
            if (serviceSelect && priceDisplay) {
                serviceSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const price = selectedOption.getAttribute('data-price');
                    const fieldCode = selectedOption.getAttribute('data-field-code');
                    
                    if (price) {
                        priceDisplay.textContent = '₦' + new Intl.NumberFormat('en-NG', { 
                            minimumFractionDigits: 2 
                        }).format(price);
                        fieldCodeInput.value = fieldCode;
                    } else {
                        priceDisplay.textContent = '₦0.00';
                        fieldCodeInput.value = '';
                    }
                });
            }

            // Session Messages via SweetAlert
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    timer: 4000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif
        });

        // Form Submission Handler
        function handleFormSubmit(event) {
            const form = event.target;
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            
            // Show loading state on button
            submitBtn.disabled = true;
            submitText.textContent = 'Processing...';

            // Show SweetAlert Loading
            Swal.fire({
                title: 'Processing Request',
                text: 'Please wait while we submit your request...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            return true; // Use default form submission
        }

        // Single Status Check - Event Delegation
        document.addEventListener('click', function(e) {
            const statusCheckBtn = e.target.closest('[data-action="check-status"]');
            if (statusCheckBtn) {
                e.preventDefault();
                const url = statusCheckBtn.getAttribute('href');
                
                Swal.fire({
                    title: 'Check Status?',
                    text: "This will update the status from the server.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Check it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Checking...',
                            text: 'Please wait...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        window.location.href = url;
                    }
                });
            }
        });
        
        // Batch Check
        function batchCheck() {
            Swal.fire({
                title: 'Check All Pending?',
                text: "We will check the status of up to 20 pending items.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Start Batch Check!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Batch Processing...',
                        html: 'Checking statuses... <br><b>Please do not close this page.</b>',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('/ipe/batch-check', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Batch Check Complete',
                                text: data.message,
                                confirmButtonText: 'Great!'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Batch Check Failed',
                            text: error.message || 'An error occurred while communicating with the server.',
                        });
                    });
                }
            });
        }
    </script>
    <style>
        .hover-up:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }
    </style>
    @endpush
</x-app-layout>