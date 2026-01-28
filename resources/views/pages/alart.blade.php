 <!-- Alerts -->
            @foreach (['success' => 'check-circle', 'error' => 'exclamation-triangle'] as $type => $icon)
                @if(session($type))
                    <div class="alert alert-{{ $type === 'success' ? 'success' : 'danger' }} alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-{{ $icon }} me-2"></i> {{ session($type) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endforeach

            @if($errors->any())
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif