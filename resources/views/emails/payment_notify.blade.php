@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">Payment Notification</div>
        <div class="card-body">
            <p>Hello,</p>
            <p>Your wallet has been credited with ₦{{ $mail_data['amount'] }}.</p>
            <p><strong>Transaction Reference:</strong> {{ $mail_data['ref'] }}</p>
            <p><strong>Bank:</strong> {{ $mail_data['bankName'] }}</p>
            <p><strong>Type:</strong> {{ $mail_data['type'] }}</p>
            <p>Thank you for using our service.</p>
        </div>
    </div>
</div>
@endsection
