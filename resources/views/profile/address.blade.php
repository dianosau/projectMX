@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card border-dark rounded-0 p-3 shadow-sm">
                <h5 class="fw-bold mb-3">Manage My Account</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-dark">My Profile</a></li>
                    <li class="mb-2"><a href="{{ route('address.index') }}" class="text-decoration-none text-primary fw-bold">Address Book</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-dark">My Payment Options</a></li>
                    <li class="mb-2"><a href="{{ route('orders.index') }}" class="text-decoration-none text-dark">My Orders</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Address Book</h4>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">+ Add New Address</button>
            </div>

            <div class="card p-4 border-dark rounded-0 shadow-sm">
                @if($addresses->isEmpty())
                    <p class="text-muted mb-0">Save your shipping address here.</p>
                @else
                    @foreach($addresses as $address)
                    <div class="border p-3 mb-3 rounded-3 position-relative">
                        <h6 class="fw-bold">{{ $address->recipient_name }} <span class="badge bg-light text-dark border ms-2">{{ $address->phone }}</span></h6>
                        <p class="small text-muted mb-0">{{ $address->full_address }}</p>
                        <div class="mt-2">
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                            @if(!$address->is_default)
                                <button class="btn btn-sm btn-link text-decoration-none">Set as Default</button>
                            @else
                                <span class="badge bg-success small">Default</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection