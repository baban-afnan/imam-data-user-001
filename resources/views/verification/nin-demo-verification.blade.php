<x-app-layout>
    <title>Digital data Verify - {{ $title ?? 'Demographic Verification' }}</title>
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-title mb-3">
                <div class="row">
                    <div class="col-sm-6 col-12">
                        <h3 class="fw-bold text-primary">NIN Demographic Verification</h3>
                        <p class="text-muted small mb-0">Verify identity details using demographic information.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row mt-3">
                <!-- Demographic Verification Form -->
                <div class="col-xl-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-white"><i class="bi bi-person-badge me-2"></i>Verify Identity</h5>
                            <span class="badge bg-light text-primary fw-semibold">Demographic</span>
                        </div>

                        <div class="card-body">
                            <div class="text-center mb-3">
                                <p class="text-muted small mb-0">
                                    Enter the details exactly as they appear on the NIN record.
                                </p>
                            </div>

                            {{-- Alerts --}}
                            @if (session('status') && session('message'))
                                <div class="alert alert-{{ session('status') === 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
                                    {{ session('message') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0 small text-start">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('nin.demo.store') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6 text-start">
                                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                        <input class="form-control" name="firstName" type="text"
                                            placeholder="Enter First Name" required value="{{ old('firstName') }}">
                                    </div>
                                    <div class="col-md-6 text-start">
                                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                        <input class="form-control" name="lastName" type="text"
                                            placeholder="Enter Last Name" required value="{{ old('lastName') }}">
                                    </div>

                                    <div class="col-md-6 text-start">
                                        <label class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select" name="gender" required>
                                            <option value="" disabled selected>Select Gender</option>
                                            <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                                            <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 text-start">
                                        <label class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
                                        <input class="form-control" name="dateOfBirth" type="text"
                                            placeholder="DD-MM-YYYY" required value="{{ old('dateOfBirth') }}">
                                        <small class="text-muted">Format: 20-02-1966</small>
                                    </div>

                                    <div class="col-12">
                                        <div class="alert alert-info py-2 mb-0 d-flex justify-content-between align-items-center">
                                            <span class="fw-semibold">Service Fee:</span>
                                            <strong class="fs-15">₦{{ number_format($demoPrice ?? 0, 2) }}</strong>
                                        </div>
                                        <div class="text-end mt-1">
                                            <small class="text-muted">
                                                Wallet Balance: <strong class="text-success">₦{{ number_format($wallet->balance ?? 0, 2) }}</strong>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-12 d-grid mt-3">
                                        <button class="btn btn-primary btn-lg fw-semibold" type="submit">
                                            <i class="bi bi-search me-2"></i> Verify Demographic
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Verification Info -->
                <div class="col-xl-6">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-person-lines-fill me-2"></i>Verification Result</h5>
                        </div>

                        <div class="card-body">
                            @if (session('verification'))
                                <div class="alert alert-success text-center mb-3">
                                    <i class="bi bi-check-circle-fill me-2"></i> <strong>Verification Successful!</strong>
                                </div>

                                <div class="text-center mb-4">
                                    <div class="d-inline-block p-1 border rounded bg-white shadow-sm">
                                        @if (!empty(session('verification')['data']['photo']))
                                            <img src="data:image/jpeg;base64,{{ session('verification')['data']['photo'] }}"
                                                alt="ID Photo" class="img-fluid rounded"
                                                style="max-height:180px; min-width: 150px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('assets/images/corrupt.jpg') }}" alt="No Image"
                                                class="img-fluid rounded" style="max-height:180px;">
                                        @endif
                                    </div>
                                    <div class="mt-2 fw-bold text-muted small">PASSPORT PHOTOGRAPH</div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped align-middle">
                                        <tbody>
                                            <tr>
                                                <th class="w-40 bg-light">NIN Number</th>
                                                <td class="fw-bold text-primary">{{ session('verification')['data']['nin'] }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">First Name</th>
                                                <td>{{ session('verification')['data']['firstName'] }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Last Name</th>
                                                <td>{{ session('verification')['data']['surname'] }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Middle Name</th>
                                                <td>{{ session('verification')['data']['middleName'] ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Date of Birth</th>
                                                <td>
                                                    {{ !empty(session('verification')['data']['birthDate'])
                                                        ? session('verification')['data']['birthDate']
                                                        : 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Gender</th>
                                                <td>{{ strtoupper(session('verification')['data']['gender'] ?? 'N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">Phone</th>
                                                <td>{{ session('verification')['data']['telephoneNo'] ?? 'N/A' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <hr class="my-4">

                                <h6 class="fw-bold mb-3 text-center text-secondary">Download Slips (Charges Apply)</h6>
                                <div class="d-flex flex-wrap justify-content-center gap-2">

                                    <button onclick="confirmDownload('{{ route('nin.demo.regularSlip', session('verification')['data']['nin']) }}', 'Regular Slip', {{ $regularSlipPrice ?? 0 }})" 
                                        class="btn btn-info btn-wave text-white">
                                        <i class="bi bi-file-earmark-richtext me-1"></i> Regular <br>
                                        <small class="badge bg-dark bg-opacity-25">₦{{ number_format($regularSlipPrice ?? 0, 2) }}</small>
                                    </button>

                                    <button onclick="confirmDownload('{{ route('nin.demo.standardSlip', session('verification')['data']['nin']) }}', 'Standard Slip', {{ $standardSlipPrice ?? 0 }})" 
                                        class="btn btn-warning btn-wave text-white">
                                        <i class="bi bi-file-earmark-text me-1"></i> Standard <br>
                                        <small class="badge bg-dark bg-opacity-25">₦{{ number_format($standardSlipPrice ?? 0, 2) }}</small>
                                    </button>

                                    <button onclick="confirmDownload('{{ route('nin.demo.premiumSlip', session('verification')['data']['nin']) }}', 'Premium Slip', {{ $premiumSlipPrice ?? 0 }})" 
                                        class="btn btn-primary btn-wave">
                                        <i class="bi bi-file-earmark-richtext me-1"></i> Premium <br>
                                        <small class="badge bg-dark bg-opacity-25">₦{{ number_format($premiumSlipPrice ?? 0, 2) }}</small>
                                    </button>
                                </div>

                            @else
                                <div class="text-center py-5">
                                    <img src="{{ asset('assets/img/apps/thankyou.png') }}" width="120" alt="Search Icon" class="opacity-50 mb-3">
                                    <h6 class="text-muted">Verification results will appear here.</h6>
                                    <p class="small text-muted">Enter demographic details on the left to get started.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Slip Download Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // AI Voice Notification for Success
        @if (session('status') === 'success')
            window.addEventListener('load', () => {
                const speak = () => {
                    const message = "wow verification is successful Id number is valid";
                    const utterance = new SpeechSynthesisUtterance(message);
                    
                    const voices = window.speechSynthesis.getVoices();
                    if (voices.length === 0) return false;

                    const femaleVoice = voices.find(voice => 
                        voice.name.toLowerCase().includes('female') || 
                        voice.name.toLowerCase().includes('google uk english female') ||
                        voice.name.toLowerCase().includes('samantha') ||
                        voice.name.toLowerCase().includes('victoria')
                    );
                    
                    if (femaleVoice) utterance.voice = femaleVoice;
                    utterance.rate = 1.0;
                    utterance.pitch = 1.1;
                    window.speechSynthesis.speak(utterance);
                    return true;
                };

                if (!speak()) {
                    window.speechSynthesis.onvoiceschanged = speak;
                }
            });
        @endif

        function confirmDownload(url, type, price) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You will be charged ₦${price.toLocaleString()} for the ${type}.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, download it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>

</x-app-layout>
