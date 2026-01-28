<!-- Header -->
<div class="header">
    <div class="main-header">
        <!-- Header Left - Logo -->
        <div class="header-left">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" style="height: 20px;">
            </a>
            <a href="{{ route('dashboard') }}" class="dark-logo">
                <img src="{{ asset('assets/images/logo/logo.png') }}" alt="Logo" style="height: 40px;">
            </a>
        </div>

        <!-- Mobile Menu Button -->
        <a id="mobile_btn" class="mobile_btn" href="#sidebar">
            <span class="bar-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </a>

        <!-- Header User Section -->
        <div class="header-user">
            <div class="nav user-menu nav-list">
                <!-- Left Side - Search & Controls -->
                <div class="me-auto d-flex align-items-center" id="header-search">
                    <!-- Toggle Button -->
                    <a id="toggle_btn" href="javascript:void(0);" class="btn btn-menubar me-1">
                        <i class="ti ti-arrow-bar-to-left"></i>
                    </a>
                    
                    <!-- Search Bar -->
                    <div class="input-group input-group-flat d-inline-flex me-1">
                        <span class="input-icon-addon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Search in services">
                        <span class="input-group-text">
                            <kbd>CTRL + /</kbd>
                        </span>
                    </div>
                    
                    <!-- CRM Dropdown -->
                    <div class="dropdown crm-dropdown">
                        <a href="#" class="btn btn-menubar me-1" data-bs-toggle="dropdown">
                            <i class="ti ti-layout-grid"></i>
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-start">
                            <div class="card mb-0 border-0 shadow-none">
                                <div class="card-header">
                                    <h4 class="text-primary">Services</h4>
                                </div>
                                <div class="card-body pb-1">        
                                    <div class="row">
                                        <div class="col-sm-6">                            
                                            <a href="#" class="d-flex align-items-center justify-content-between p-2 crm-link mb-3">
                                                <span class="d-flex align-items-center me-3">
                                                    <i class="ti ti-user-shield text-primary me-2"></i>Contacts
                                                </span>
                                                <i class="ti ti-arrow-right"></i>
                                            </a>                            
                                            <a href="#" class="d-flex align-items-center justify-content-between p-2 crm-link mb-3">
                                                <span class="d-flex align-items-center me-3">
                                                    <i class="ti ti-heart-handshake text-primary me-2"></i>Deals
                                                </span>
                                                <i class="ti ti-arrow-right"></i>
                                            </a>                                
                                            <a href="#" class="d-flex align-items-center justify-content-between p-2 crm-link mb-3">
                                                <span class="d-flex align-items-center me-3">
                                                    <i class="ti ti-timeline-event-text text-primary me-2"></i>Pipeline
                                                </span>
                                                <i class="ti ti-arrow-right"></i>
                                            </a>        
                                        </div>
                                        <div class="col-sm-6">                            
                                            <a href="#" class="d-flex align-items-center justify-content-between p-2 crm-link mb-3">
                                                <span class="d-flex align-items-center me-3">
                                                    <i class="ti ti-building text-primary me-2"></i>Companies
                                                </span>
                                                <i class="ti ti-arrow-right"></i>
                                            </a>                                
                                            <a href="#" class="d-flex align-items-center justify-content-between p-2 crm-link mb-3">
                                                <span class="d-flex align-items-center me-3">
                                                    <i class="ti ti-user-check text-primary me-2"></i>Leads
                                                </span>
                                                <i class="ti ti-arrow-right"></i>
                                            </a>                                
                                            <a href="#" class="d-flex align-items-center justify-content-between p-2 crm-link mb-3">
                                                <span class="d-flex align-items-center me-3">
                                                    <i class="ti ti-activity text-primary me-2"></i>Activities
                                                </span>
                                                <i class="ti ti-arrow-right"></i>
                                            </a>        
                                        </div>
                                    </div>        
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Settings Button -->
                    <a href="{{ route('profile.edit') }}" class="btn btn-menubar">
                        <i class="ti ti-settings-cog"></i>
                    </a>    
                </div>

                <!-- Right Side - Icons & Profile -->
                <div class="d-flex align-items-center">
                    <!-- Fullscreen -->
                    <div class="me-1">
                        <a href="#" class="btn btn-menubar btnFullscreen">
                            <i class="ti ti-maximize"></i>
                        </a>
                    </div>
                    
                    <!-- Applications Dropdown -->
                    <div class="dropdown me-1">
                        <a href="#" class="btn btn-menubar" data-bs-toggle="dropdown">
                            <i class="ti ti-layout-grid-remove"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="card mb-0 border-0 shadow-none">
                                <div class="card-header">
                                    <h4 class="text-primary">Applications</h4>
                                </div>
                                <div class="card-body">                                            
                                    <a href="#" class="d-block pb-2">
                                        <span class="avatar avatar-md bg-primary-transparent me-2"><i class="ti ti-calendar text-primary"></i></span>Calendar
                                    </a>                                        
                                    <a href="#" class="d-block py-2">
                                        <span class="avatar avatar-md bg-primary-transparent me-2"><i class="ti ti-subtask text-primary"></i></span>To Do
                                    </a>                                        
                                    <a href="#" class="d-block py-2">
                                        <span class="avatar avatar-md bg-primary-transparent me-2"><i class="ti ti-notes text-primary"></i></span>Notes
                                    </a>                                        
                                    <a href="#" class="d-block py-2">
                                        <span class="avatar avatar-md bg-primary-transparent me-2"><i class="ti ti-folder text-primary"></i></span>File Manager
                                    </a>                                
                                    <a href="#" class="d-block py-2">
                                        <span class="avatar avatar-md bg-primary-transparent me-2"><i class="ti ti-layout-kanban text-primary"></i></span>Kanban
                                    </a>                                
                                    <a href="#" class="d-block py-2 pb-0">
                                        <span class="avatar avatar-md bg-primary-transparent me-2"><i class="ti ti-file-invoice text-primary"></i></span>Invoices
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chat -->
                    <div class="me-1">
                        <a href="#" class="btn btn-menubar position-relative">
                            <i class="ti ti-brand-hipchat"></i>
                            <span class="badge bg-primary rounded-pill d-flex align-items-center justify-content-center header-badge">0</span>
                        </a>
                    </div>
                    
                    <!-- Email -->
                    <div class="me-1">
                        <a href="#" class="btn btn-menubar">
                            <i class="ti ti-mail"></i>
                        </a>
                    </div>
                    
                    <!-- Notifications -->
                    <div class="me-1 notification_item">
                        <a href="#" class="btn btn-menubar position-relative me-1" id="notification_popup" data-bs-toggle="dropdown">
                            <i class="ti ti-bell"></i>
                            <span class="notification-status-dot"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end notification-dropdown p-4">
                            <div class="d-flex align-items-center justify-content-between border-bottom p-0 pb-3 mb-3">
                                <h4 class="notification-title">Notifications (0)</h4>
                                <div class="d-flex align-items-center">
                                    <a href="#" class="text-primary fs-15 me-3 lh-1">Mark all as read</a>
                                    <div class="dropdown">
                                        <a href="javascript:void(0);" class="bg-white dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ti ti-calendar-due me-1"></i>Today
                                        </a>
                                        <ul class="dropdown-menu mt-2 p-3">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">This Week</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Week</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Last Month</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex p-0">
                                <a href="#" class="btn btn-light w-100 me-2">Cancel</a>
                                <a href="#" class="btn btn-primary w-100">View All</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="dropdown profile-dropdown">
                        <a href="javascript:void(0);" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                            <span class="avatar avatar-sm online">
                                <img src="{{ Auth::user()->photo ?? asset('assets/img/profiles/avatar-31.jpg') }}" alt="User" class="img-fluid rounded-circle">
                            </span>
                        </a>
                        <div class="dropdown-menu shadow-none">
                            <div class="card mb-0">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-lg me-2 avatar-rounded">
                                            <img src="{{ Auth::user()->photo ?? asset('assets/img/profiles/avatar-31.jpg') }}" alt="User Avatar" class="rounded-circle">
                                        </span>
                                        <div>
                                            <h5 class="mb-0 text-primary">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                                            <p class="fs-12 fw-medium mb-0 text-muted">
                                                {{ Auth::user()->email }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a class="dropdown-item d-inline-flex align-items-center p-0 py-2" href="{{ route('profile.edit') }}">
                                        <i class="ti ti-user-circle me-1"></i>My Profile
                                    </a>
                                    <a class="dropdown-item d-inline-flex align-items-center p-0 py-2" href="{{ route('profile.edit') }}">
                                        <i class="ti ti-settings me-1"></i>Settings
                                    </a>
                                    <a class="dropdown-item d-inline-flex align-items-center p-0 py-2" href="{{ route('profile.edit') }}">
                                        <i class="ti ti-circle-arrow-up me-1"></i>My Account
                                    </a>
                                    <a class="dropdown-item d-inline-flex align-items-center p-0 py-2" href="#">
                                        <i class="ti ti-question-mark me-1"></i>Knowledge Base
                                    </a>
                                </div>
                                <div class="card-footer">
                                    <a class="dropdown-item d-inline-flex align-items-center p-0 py-2 text-danger" href="#" 
                                       onclick="confirmLogout(event, 'logout-form')">
                                        <i class="ti ti-login me-2"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>

        <!-- Mobile Menu -->
        <div class="dropdown mobile-user-menu">
            <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="{{ route('profile.edit') }}">My Profile</a>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">Settings</a>
                <a class="dropdown-item" href="#" 
                   onclick="confirmLogout(event, 'logout-form')">Logout</a>
            </div>
        </div>
        <!-- /Mobile Menu -->
    </div>
</div>
<!-- /Header -->

<script>
function confirmLogout(event, formId) {
    event.preventDefault();
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of your account.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0d5c3e',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>