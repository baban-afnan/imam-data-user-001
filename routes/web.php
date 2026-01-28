<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentWebhookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Action\AirtimeController;
use App\Http\Controllers\Action\DataController;
use App\Http\Controllers\Action\EducationalController;
use App\Http\Controllers\NINverificationController;
use App\Http\Controllers\BvnverificationController;
use App\Http\Controllers\Agency\BvnServicesController;
use App\Http\Controllers\Agency\BvnModificationController;
use App\Http\Controllers\Agency\ManualSearchController;
use App\Http\Controllers\Agency\TinRegistrationController;
use App\Http\Controllers\Agency\NinValidationController;
use App\Http\Controllers\Agency\NinModificationController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/palmpay/webhook', [PaymentWebhookController::class, 'handleWebhook'])
    ->middleware('throttle:60,1');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
        Route::post('/pin', [ProfileController::class, 'updatePin'])->name('profile.pin');
        Route::post('/update-required', [ProfileController::class, 'updateRequired'])->name('profile.updateRequired');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // General Auth-Required Routes
    Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support');
    Route::get('/thankyou', function () {
        return view('thankyou');
    })->name('thankyou');

    // Wallet Routes
    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('wallet');
        Route::post('/create-virtual-account', [WalletController::class, 'createWallet'])->name('virtual.account.create');
        Route::post('/claim-bonus', [WalletController::class, 'claimBonus'])->name('wallet.claimBonus');
    });

    // Utility Bill Payment Group
    Route::group([], function () {
        // Airtime
        Route::get('/airtime', [AirtimeController::class, 'airtime'])->name('airtime');
        Route::post('/buy-airtime', [AirtimeController::class, 'buyAirtime'])->name('buyairtime');

        // Data
        Route::get('/data', [DataController::class, 'data'])->name('buy-data');
        Route::post('/buy-data', [DataController::class, 'buydata'])->name('buydata');
        Route::get('/fetch-data-bundles', [DataController::class, 'fetchBundles'])->name('fetch.bundles');
        Route::get('/fetch-data-bundles-price', [DataController::class, 'fetchBundlePrice'])->name('fetch.bundle.price');
        Route::post('/verify-pin', [DataController::class, 'verifyPin'])->name('verify.pin');

        Route::get('/sme-data', [DataController::class, 'sme_data'])->name('sme-data');
        Route::get('/fetch-data-type', [DataController::class, 'fetchDataType']);
        Route::get('/fetch-data-plan', [DataController::class, 'fetchDataPlan']);
        Route::get('/fetch-sme-data-bundles-price', [DataController::class, 'fetchSmeBundlePrice']);
        Route::post('/buy-sme-data', [DataController::class, 'buySMEdata'])->name('buy-sme-data');

        // Education
        Route::get('/education', [EducationalController::class, 'pin'])->name("education");
        Route::post('/buy-pin', [EducationalController::class, 'buypin'])->name('buypin');
        Route::get('/education/receipt/{transaction}', [EducationalController::class, 'receipt'])->name('education.receipt');
        Route::get('/get-variation', [EducationalController::class, 'getVariation'])->name('get-variation');

        Route::get('/jamb', [EducationalController::class, 'jamb'])->name('jamb');
        Route::post('/verify-jamb', [EducationalController::class, 'verifyJamb'])->name('verify.jamb');
        Route::post('/buy-jamb', [EducationalController::class, 'buyJamb'])->name('buyjamb');

        // Electricity
        Route::get('/electricity', [App\Http\Controllers\Action\ElectricityController::class, 'index'])->name('electricity');
        Route::post('/verify-electricity', [App\Http\Controllers\Action\ElectricityController::class, 'verifyMeter'])->name('verify.electricity');
        Route::post('/buy-electricity', [App\Http\Controllers\Action\ElectricityController::class, 'purchase'])->name('buy.electricity');

        // Cable
        Route::get('/cable', [App\Http\Controllers\Action\CableController::class, 'index'])->name('cable');
        Route::get('/cable/variations', [App\Http\Controllers\Action\CableController::class, 'getVariations'])->name('cable.variations');
        Route::post('/cable/verify', [App\Http\Controllers\Action\CableController::class, 'verifyIuc'])->name('verify.cable');
        Route::post('/cable/buy', [App\Http\Controllers\Action\CableController::class, 'purchase'])->name('buy.cable');
    });

    // Verification Services Group
    Route::group([], function () {
        // NIN Verification
        Route::prefix('nin-verification')->group(function () {
            Route::get('/', [NINverificationController::class, 'index'])->name('nin.verification.index');
            Route::post('/', [NINverificationController::class, 'store'])->name('nin.verification.store');
            Route::post('/{id}/status', [NINverificationController::class, 'updateStatus'])->name('nin.verification.status');
            Route::get('/standardSlip/{id}', [NINverificationController::class, 'standardSlip'])->name('standardSlip');
            Route::get('/premiumSlip/{id}', [NINverificationController::class, 'premiumSlip'])->name('premiumSlip');
        });

        // BVN Verification
        Route::prefix('bvn-verification')->group(function () {
            Route::get('/', [BvnverificationController::class, 'index'])->name('bvn.verification.index');
            Route::post('/', [BvnverificationController::class, 'store'])->name('bvn.verification.store');
            Route::get('/standardBVN/{id}', [BvnverificationController::class, 'standardBVN'])->name("standardBVN");
            Route::get('/premiumBVN/{id}', [BvnverificationController::class, 'premiumBVN'])->name("premiumBVN");
            Route::get('/plasticBVN/{id}', [BvnverificationController::class, 'plasticBVN'])->name("plasticBVN");
            Route::get('/vninSlip/{id}', [NINverificationController::class, 'vninSlip'])->name('vninSlip');
        });

        // TIN Registration
        Route::prefix('tin-reg')->group(function () {
            Route::get('/', [TinRegistrationController::class, 'index'])->name('tin.index');
            Route::post('/validate', [TinRegistrationController::class, 'validateTin'])->name('tin.validate');
            Route::post('/download', [TinRegistrationController::class, 'downloadSlip'])->name('tin.download');
        });

        // NIN Modification
        Route::prefix('nin-modification')->group(function () {
            Route::get('/', [NinModificationController::class, 'index'])->name('nin-modification');
            Route::post('/', [NinModificationController::class, 'store'])->name('nin-modification.store');
            Route::get('/check/{id}', [NinModificationController::class, 'checkStatus'])->name('nin-modification.check');
        });

        // NIN Validation & IPE
        Route::prefix('nin-validation')->group(function () {
            Route::get('/', [NinValidationController::class, 'index'])->name('nin-validation');
            Route::post('/', [NinValidationController::class, 'store'])->name('nin-validation.store');
            Route::get('/check/{id}', [NinValidationController::class, 'checkStatus'])->name('nin-validation.check');
        });

        // BVN Services & CRM
        Route::get('/bvn-crm', [BvnServicesController::class, 'index'])->name('bvn-crm');
        Route::post('/bvn-crm', [BvnServicesController::class, 'store'])->name('crm.store');

        Route::get('/send-vnin', [BvnServicesController::class, 'index'])->name('send-vnin');
        Route::post('/send-vnin', [BvnServicesController::class, 'store'])->name('send-vnin.store');

        Route::get('/modification-fields/{serviceId}', [BvnModificationController::class, 'getServiceFields'])->name('modification.fields');
        Route::get('/modification', [BvnModificationController::class, 'index'])->name('modification');
        Route::post('/modification', [BvnModificationController::class, 'store'])->name('modification.store');
        Route::get('/modification/check/{id}', [BvnModificationController::class, 'checkStatus'])->name('modification.check');

        Route::prefix('phone-search')->group(function () {
            Route::get('/', [ManualSearchController::class, 'index'])->name('phone.search.index');
            Route::post('/', [ManualSearchController::class, 'store'])->name('phone.search.store');
            Route::get('/{id}/details', [ManualSearchController::class, 'showDetails'])->name('phone.search.details');
        });
    });
});

require __DIR__.'/auth.php';





require __DIR__.'/auth.php';
