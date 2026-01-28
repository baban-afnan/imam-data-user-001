<div class="col-xl-6 d-none d-md-block">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
        <div class="card-header bg-gradient-primary text-white p-4 border-0" style="background: linear-gradient(135deg, #d46524ff 0%, #764ba2 100%);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title mb-1 fw-bold text-white"><i class="bi bi-wifi me-2"></i> Data Bundles Price List</h5>
                    <p class="mb-0 text-white-50 small">Explore our competitive rates across all networks.</p>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle p-2">
                    <i class="bi bi-tags-fill fs-4 text-white"></i>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="row g-0 h-100">
                <!-- Sidebar Tabs -->
                <div class="col-md-3 bg-light border-end">
                    <div class="nav flex-column nav-pills p-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @php
                            $networks = [
                                'mtn' => ['name' => 'MTN Data', 'img' => 'mtn.jpg', 'color' => '#ffc107'],
                                'airtel' => ['name' => 'Airtel Data', 'img' => 'Airtel.png', 'color' => '#dc3545'],
                                'glo' => ['name' => 'Glo Data', 'img' => 'glo.jpg', 'color' => '#28a745'],
                                '9mobile' => ['name' => '9Mobile Data', 'img' => '9Mobile.jpg', 'color' => '#006400'],
                                'smile' => ['name' => 'Smile Data', 'img' => 'smile.jpg', 'color' => '#e83e8c'], // Placeholder img
                                'spectranet' => ['name' => 'Spectranet', 'img' => 'spector.jpg', 'color' => '#007bff'], // Placeholder img
                            ];
                        @endphp

                        @foreach($networks as $key => $net)
                            <button class="nav-link mb-2 d-flex align-items-center p-3 rounded-3 {{ $loop->first ? 'active' : '' }} shadow-sm-hover transition-all" 
                                    id="v-pills-{{ $key }}-tab" data-bs-toggle="pill" data-bs-target="#v-pills-{{ $key }}" 
                                    type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <div class="avatar avatar-sm me-3">
                                    <img src="{{ asset('assets/img/apps/' . $net['img']) }}" alt="{{ $net['name'] }}" class="rounded-circle shadow-sm" style="width: 35px; height: 35px; object-fit: cover;">
                                </div>
                                <span class="fw-semibold">{{ $net['name'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Content Area -->
                <div class="col-md-9 bg-white">
                    <div class="tab-content p-4" id="v-pills-tabContent">
                        
                        <!-- Helper function for table -->
                        @php
                            function renderTable($priceList, $colorClass) {
                                if ($priceList->isEmpty()) {
                                    return '<div class="text-center py-5">
                                                <div class="mb-3"><i class="bi bi-inbox fs-1 text-muted opacity-25"></i></div>
                                                <h6 class="text-muted">No plans available at the moment.</h6>
                                            </div>';
                                }
                                $html = '<div class="table-responsive rounded-3 border shadow-sm">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="py-3 ps-4 text-uppercase small fw-bold text-muted">Plan Name</th>
                                                        <th class="py-3 pe-4 text-end text-uppercase small fw-bold text-muted">Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>';
                                foreach ($priceList as $data) {
                                    $html .= '<tr>
                                                <td class="ps-4 fw-medium text-dark">' . $data->name . '</td>
                                                <td class="pe-4 text-end">
                                                    <span class="badge bg-' . $colorClass . ' bg-opacity-10 text-' . $colorClass . ' px-3 py-2 rounded-pill fw-bold">
                                                        ₦' . number_format($data->variation_amount, 2) . '
                                                    </span>
                                                </td>
                                              </tr>';
                                }
                                $html .= '</tbody></table></div>';
                                // Add pagination if needed, though simple rendering is cleaner for this snippet
                                return $html;
                            }
                        @endphp

                        <div class="tab-pane fade show active" id="v-pills-mtn" role="tabpanel">
                            <h5 class="fw-bold text-dark mb-3">MTN Data Plans</h5>
                            {!! renderTable($priceList1, 'warning') !!}
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $priceList1->appends(request()->query())->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-airtel" role="tabpanel">
                            <h5 class="fw-bold text-dark mb-3">Airtel Data Plans</h5>
                            {!! renderTable($priceList2, 'danger') !!}
                             <div class="mt-3 d-flex justify-content-center">
                                {{ $priceList2->appends(request()->query())->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-glo" role="tabpanel">
                            <h5 class="fw-bold text-dark mb-3">Glo Data Plans</h5>
                            {!! renderTable($priceList3, 'success') !!}
                             <div class="mt-3 d-flex justify-content-center">
                                {{ $priceList3->appends(request()->query())->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-9mobile" role="tabpanel">
                            <h5 class="fw-bold text-dark mb-3">9Mobile Data Plans</h5>
                            {!! renderTable($priceList4, 'success') !!}
                             <div class="mt-3 d-flex justify-content-center">
                                {{ $priceList4->appends(request()->query())->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-smile" role="tabpanel">
                            <h5 class="fw-bold text-dark mb-3">Smile Data Plans</h5>
                            {!! renderTable($priceList5, 'info') !!}
                             <div class="mt-3 d-flex justify-content-center">
                                {{ $priceList5->appends(request()->query())->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-spectranet" role="tabpanel">
                            <h5 class="fw-bold text-dark mb-3">Spectranet Data Plans</h5>
                            {!! renderTable($priceList6, 'primary') !!}
                             <div class="mt-3 d-flex justify-content-center">
                                {{ $priceList6->appends(request()->query())->links('vendor.pagination.custom') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-pills .nav-link {
        color: #555;
        border-radius: 0;
        border-left: 4px solid transparent;
    }
    .nav-pills .nav-link.active {
        background-color: #f8f9fa;
        color: #0d6efd;
        border-left-color: #0d6efd;
    }
    .shadow-sm-hover:hover {
        background-color: #fff;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }
</style>