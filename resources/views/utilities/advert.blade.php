<div class="col-xl-6 d-none d-md-block">
    <div class="card h-100 border-0 shadow-lg position-relative overflow-hidden rounded-4 advert-card">
        
        <!-- Animated Background Gradient -->
        <div class="position-absolute w-100 h-100 top-0 start-0 advert-bg"></div>
        
        <!-- Decorative Elements -->
        <div class="position-absolute top-0 start-0 translate-middle rounded-circle bg-white opacity-10" style="width: 200px; height: 200px;"></div>
        <div class="position-absolute bottom-0 end-0 translate-middle rounded-circle bg-white opacity-10" style="width: 300px; height: 300px;"></div>
        <div class="position-absolute" style="top: 20%; right: 10%; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; animation: float 6s ease-in-out infinite;"></div>

        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-5 position-relative" style="z-index: 2;">
            
            <!-- Icon with Animation -->
            <div class="mb-4 icon-wrapper">
                <div class="icon-box bg-white rounded-circle shadow-lg d-flex align-items-center justify-content-center mx-auto" style="width: 90px; height: 90px;">
                    <i class="bi bi-broadcast text-success" style="font-size: 2.5rem; color: #0D5C3E !important;"></i>
                </div>
            </div>

            <!-- Title & Description -->
            <h2 class="fw-bold mb-3 text-white" style="font-size: 2rem; letter-spacing: -0.5px;">Stay Connected, Always</h2>
            <p class="lead mb-4 text-white" style="opacity: 0.9; font-size: 1.1rem; max-width: 450px;">
                Experience lightning-fast airtime top-ups with instant delivery. Never run out of talk time again!
            </p>

            <!-- Special Offer Card -->
            <div class="card border-0 rounded-4 p-4 mb-4 w-100 special-offer-card" style="max-width: 450px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px);">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="me-3">
                        <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-stars text-white fs-4"></i>
                        </div>
                    </div>
                    <div class="text-start flex-grow-1">
                        <h6 class="mb-1 fw-bold text-white" style="font-size: 1.1rem;">
                            <i class="bi bi-gift-fill text-warning me-1"></i> Special Bonus
                        </h6>
                        <p class="mb-0 text-white" style="opacity: 0.85; font-size: 0.95rem;">
                            Earn up to <strong>3% cashback</strong> on every recharge
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-3 w-100" style="max-width: 450px;">
                <a href="{{ route('buy-data') }}" class="btn btn-light btn-lg fw-semibold shadow-lg hover-lift" style="border-radius: 12px; padding: 1rem 2rem;">
                    <i class="bi bi-wifi me-2"></i> Buy Data Bundles
                </a>
                
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('electricity') }}" class="btn btn-outline-light w-100 hover-lift" style="border-radius: 12px; padding: 0.75rem; border-width: 2px; background-color: rgba(255,255,255,0.1);">
                            <i class="bi bi-lightbulb-fill me-1 text-warning"></i>
                            <div class="small text-white">Electricity</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('cable') }}" class="btn btn-outline-light w-100 hover-lift" style="border-radius: 12px; padding: 0.75rem; border-width: 2px; background-color: rgba(255,255,255,0.1);">
                            <i class="bi bi-tv-fill me-1 text-info"></i>
                            <div class="small text-white">Cable TV</div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Trust Indicators -->
            <div class="mt-4 d-flex gap-4 text-white" style="opacity: 0.8; font-size: 0.85rem;">
                <div>
                    <i class="bi bi-shield-check me-1"></i> Secure
                </div>
                <div>
                    <i class="bi bi-lightning-charge me-1"></i> Instant
                </div>
                <div>
                    <i class="bi bi-clock-history me-1"></i> 24/7
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .advert-bg {
        background: linear-gradient(135deg, #0D5C3E 0%, #0a4a31 50%, #073d28 100%);
        animation: gradientShift 15s ease infinite;
    }
    
    @keyframes gradientShift {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.95; }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(10deg); }
    }
    
    .icon-wrapper {
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .special-offer-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.2) !important;
    }
    
    .special-offer-card:hover {
        background: rgba(255,255,255,0.2) !important;
        transform: translateY(-2px);
    }
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .btn-outline-light:hover {
        background: white !important;
        color: #0D5C3E !important;
    }
    
    .opacity-10 {
        opacity: 0.1;
    }
    
    .advert-card {
        transition: transform 0.3s ease;
    }
    
    .advert-card:hover {
        transform: translateY(-5px);
    }
</style>
