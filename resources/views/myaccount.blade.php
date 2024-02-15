@extends('layouts/app')

@section('title', __(' Home'))

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <style>
        .custom-tooltip-container {
            position: relative;
            display: inline-block;
        }

        .custom-tooltip {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #333;
            color: #fff;
            padding: 5px;
            border-radius: 3px;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s linear;
            /* Add a smooth transition */
            z-index: 999;
            /* Ensure it's above other elements */
            white-space: nowrap;
            /* Prevent line break */
        }

        .custom-tooltip-container:hover .custom-tooltip {
            visibility: visible;
            opacity: 1;
        }
    </style>
    <style>
        .gradient-custom-2 {
            /* fallback for old browsers */
            background: #a1c4fd;

            /* Chrome 10-25, Safari 5.1-6 */
            background: -webkit-linear-gradient(to right, rgba(161, 196, 253, 1), rgba(194, 233, 251, 1));

            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera` 12+, Safari 7+ */
            background: linear-gradient(to right, rgba(161, 196, 253, 1), rgba(194, 233, 251, 1))
        }

        #progressbar-1 {
            color: #455A64;
        }

        #progressbar-1 li {
            list-style-type: none;
            font-size: 13px;
            width: 33.33%;
            float: left;
            position: relative;
        }

        #progressbar-1 #order-step1:before {
            content: "1";
            color: #fff;
            width: 29px;
            margin-left: 22px;
            padding-left: 11px;
        }

        #progressbar-1 #order-step2:before {
            content: "2";
            color: #fff;
            width: 29px;
        }

        #progressbar-1 #order-step3:before {
            content: "3";
            color: #fff;
            width: 29px;
            margin-right: 22px;
            text-align: center;
        }

        #progressbar-1 li:before {
            line-height: 29px;
            display: block;
            font-size: 12px;
            background: #455A64;
            border-radius: 50%;
            margin: auto;
        }

        #progressbar-1 li:after {
            content: '';
            width: 121%;
            height: 2px;
            background: #455A64;
            position: absolute;
            left: 0%;
            right: 0%;
            top: 15px;
            z-index: -1;
        }

        #progressbar-1 li:nth-child(2):after {
            left: 50%
        }

        #progressbar-1 li:nth-child(1):after {
            left: 25%;
            width: 121%
        }

        #progressbar-1 li:nth-child(3):after {
            left: 25%;
            width: 50%;
        }

        #progressbar-1 li.active:before,
        #progressbar-1 li.active:after {
            background: #1266f1;
        }

        .card-stepper {
            z-index: 0
        }

        .order-box {
            max-width: 650px !important;
        }
    </style>
    <style>
        /* Your custom styles can go here */

        .avatar-upload {
            position: relative;
            max-width: 205px;
            margin: 50px auto;
        }

        .avatar-upload .avatar-edit {
            position: absolute;
            right: 12px;
            z-index: 1;
            top: 10px;
        }

        .avatar-upload .avatar-edit input {
            display: none;
        }

        .avatar-upload .avatar-edit input+label {
            display: inline-block;
            width: 34px;
            height: 34px;
            margin-bottom: 0;
            border-radius: 100%;
            background: #FFFFFF;
            border: 1px solid transparent;
            box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            font-weight: normal;
            transition: all 0.2s ease-in-out;
        }

        .avatar-upload .avatar-edit input+label:hover {
            background: #f1f1f1;
            border-color: #d6d6d6;
        }

        .avatar-upload .avatar-edit input+label:after {
            content: "\f040";
            font-family: 'FontAwesome';
            color: #757575;
            position: absolute;
            top: 10px;
            left: 0;
            right: 0;
            text-align: center;
            margin: auto;
        }

        .avatar-upload .avatar-preview {
            width: 100px;
            height: 100px;
            position: relative;
            border-radius: 50%;
            overflow: hidden;
        }

        .avatar-upload .avatar-preview>div {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .avatar-upload {
            margin: 0 auto;
            text-align: center;
        }

        .avatar-upload .avatar-edit {
            display: inline-block;
            margin-right: 10px;
        }

        .avatar-upload .avatar-preview {
            display: inline-block;
        }
    </style>

    <style>
        .coupon-edit {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            border-radius: 20px;
            background: orange;
            border: none;
            color: #fff;
            height: auto;
            display: flex;
            position: relative;
            align-items: center;
            margin: 10px auto;
            justify-content: center;
        }

        .coupon-edit h1 {
            font-size: 4vw;
            /* Responsive font size */
            margin-bottom: 0px;
        }

        .coupon-edit span {
            font-size: 2.5vw;
            /* Responsive font size */
        }

        .image,
        .image2 {
            position: absolute;
            opacity: 0.1;
            width: 100%;
            height: 100%;
        }

        .image {
            left: 0;
            top: 0;
        }

        .image2 {
            bottom: 0;
            right: 0;
        }

        /* Media Query for Responsive Design */
        @media screen and (max-width: 600px) {
            .coupon-edit {
                padding: 5px;
            }

            .coupon-edit h1 {
                font-size: 6vw;
                /* Adjust font size for smaller screens */
            }

            .coupon-edit span {
                font-size: 3vw;
                /* Adjust font size for smaller screens */
            }
        }
    </style>


    <main class="main pages">
        <div class="page-header breadcrumb-wrap">
            <div class="container">
                <div class="breadcrumb">
                    <a href="{{ url('/') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>Home</a>
                    <span></span> My Account
                </div>
            </div>
        </div>
        <div class="page-content pt-150 pb-150">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 m-auto">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="dashboard-menu">
                                    <ul class="nav flex-column" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab"
                                                href="#dashboard" role="tab" aria-controls="dashboard"
                                                aria-selected="false"><i
                                                    class="fi-rs-settings-sliders mr-10"></i>Dashboard</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders"
                                                role="tab" aria-controls="orders" aria-selected="false"><i
                                                    class="fi-rs-shopping-bag mr-10"></i>Orders</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="address-tab" data-bs-toggle="tab" href="#coupon"
                                                role="tab" aria-controls="address" aria-selected="true"><i
                                                    class="fa fa-gift mr-10" style="font-size:25px"></i>Coupon &
                                                Vouchers</a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link" id="address-tab" data-bs-toggle="tab" href="#address"
                                                role="tab" aria-controls="address" aria-selected="true"><i
                                                    class="fi-rs-marker mr-10"></i>My Address</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="account-detail-tab" data-bs-toggle="tab"
                                                href="#account-detail" role="tab" aria-controls="account-detail"
                                                aria-selected="true"><i class="fi-rs-user mr-10"></i>Account details</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('logout') }}"><i
                                                    class="fi-rs-sign-out mr-10"></i>Logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="tab-content account dashboard-content pl-50">
                                    <div class="tab-pane fade active show" id="dashboard" role="tabpanel"
                                        aria-labelledby="dashboard-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="mb-0">Hello {{ \Auth::user()->name }}!</h3>
                                            </div>
                                            <div class="card-body">
                                                <p>
                                                    From your account dashboard. you can easily check &amp; view your <a
                                                        href="#">recent orders</a>,<br />
                                                    manage your <a href="#">shipping and billing addresses</a> and <a
                                                        href="#">edit your password and account details.</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="mb-0">Your Orders</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <style>
                                                        .center-align {
                                                            text-align: center;
                                                        }
                                                    </style>

                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Order Id</th>
                                                                <th>Image</th>
                                                                <th>Product</th>
                                                                <th>Date</th>
                                                                <th>Total</th>
                                                                <th colspan="2" class="center-align">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($orders as $order)
                                                                <tr>
                                                                    <td>{{ $order->order_id }}</td>
                                                                    <td><img class="img-fluid"
                                                                            src="{{ $order->product_image }}" alt=""
                                                                            width="80" height="80"></td>
                                                                    <td>{{ $order->product_name }}</td>
                                                                    <td>{{ date('Y-m-d', strtotime($order->created_at)) }}
                                                                    </td>
                                                                    <td>₹{{ round($order->amount) }} for {{ $order->qty }}
                                                                        item</td>
                                                                    <td class="text-center">
                                                                        <button type="button"
                                                                            class="btn btn-info btn-lg p-2"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#exampleModal"
                                                                            data-order='{{ json_encode($order) }}'
                                                                            title="View Order Details">
                                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        </button>
                                                                    </td>
                                                                    @if ($order->return_status != '8')
                                                                        @if ($order->order_status == '9' && in_array($order->payment_status, ['9', '1']))
                                                                            <td class="text-center">
                                                                                <a href="{{ url('generate-and-download-invoice') }}/{{ $order->order_id }}"
                                                                                    class="btn btn-success btn-lg p-2"
                                                                                    target="_blank"
                                                                                    title="Download Invoice">
                                                                                    <i class="fa fa-file-text"
                                                                                        aria-hidden="true"></i>
                                                                                </a>
                                                                            </td>
                                                                        @endif
                                                                    @endif

                                                                    <td class="text-center">
                                                                        <div class="custom-tooltip-container">
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-lg p-2 cancelOrderModalTrigger"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#cancelOrderModal"
                                                                                data-order-id="{{ $order->id }}"
                                                                                @if ($order->order_status == '8') disabled @endif>
                                                                                @if ($order->return_status == 'Approved')
                                                                                    <i class="fa fa-check"
                                                                                        aria-hidden="true"></i>
                                                                                @elseif($order->return_status == 'Rejected')
                                                                                    <i class="fa fa-times"
                                                                                        aria-hidden="true"></i>
                                                                                @else
                                                                                    <i class="fa fa-times"
                                                                                        aria-hidden="true"></i>
                                                                                @endif
                                                                            </button>
                                                                            @if ($order->order_status == '8')
                                                                                <div class="custom-tooltip">
                                                                                    {{ $order->return_status == 'Approved' ? 'Approved' : ($order->return_status == 'Rejected' ? 'Rejected' : 'Cancel Order') }}
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                            <script>
                                                                // Initialize Bootstrap tooltips
                                                                $(function() {
                                                                    $('[data-bs-toggle="tooltip"]').tooltip();
                                                                });
                                                            </script>

                                                        </tbody>
                                                    </table>





                                                    <div class="modal fade" id="cancelOrderModal" tabindex="-1"
                                                        aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="cancelOrderModalLabel">
                                                                        Cancel Order</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal"
                                                                        aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form id="cancelOrderForm">
                                                                        <!-- Added form element with an ID -->

                                                                        <!-- Radio inputs for cancellation reasons -->
                                                                        <div class="mb-3">
                                                                            <label class="form-check-label">
                                                                                <input type="radio"
                                                                                    class="form-check-input"
                                                                                    name="cancelReason" value="damaged">
                                                                                Product is damaged.
                                                                            </label>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-check-label">
                                                                                <input type="radio"
                                                                                    class="form-check-input"
                                                                                    name="cancelReason" value="fake">
                                                                                Fake product.
                                                                            </label>
                                                                        </div>
                                                                        <div class="mb-3">
                                                                            <label class="form-check-label">
                                                                                <input type="radio"
                                                                                    class="form-check-input"
                                                                                    name="cancelReason"
                                                                                    value="poorQuality"> Product quality is
                                                                                poor.
                                                                            </label>
                                                                        </div>


                                                                        <label for="cancelReason">Write (if your reason not
                                                                            listed above):</label>
                                                                        <textarea class="form-control" id="cancelReason" name="cancelReasonComments" rows="3" required></textarea>

                                                                        <!-- Added hidden input field for CSRF token -->
                                                                        <input type="hidden" name="_token"
                                                                            value="{{ csrf_token() }}">
                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-danger"
                                                                        id="confirmCancelOrderBtn">Confirm Cancel</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="modal fade" id="exampleModal" role="dialog"
                                                        data-bs-backdrop="static">
                                                        <div class="modal-dialog order-box  modal-dialog-centered">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Order Status</h4>

                                                                    @php $product = \App\Models\Variantproduct::where('id',)->first(); @endphp
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="container h-100 p-0">
                                                                        <div
                                                                            class="row d-flex justify-content-center align-items-center h-100">
                                                                            <div class="col-md-10 col-lg-8 col-xl-12">
                                                                                <div class="card card-stepper"
                                                                                    style="border-radius: 16px;">
                                                                                    <div class="card-header p-2">
                                                                                        <div
                                                                                            class="d-flex justify-content-between align-items-center">
                                                                                            <div>
                                                                                                <p class="text-muted mb-2">
                                                                                                    Order ID <span
                                                                                                        class="fw-bold text-body"
                                                                                                        id="order-order_id">1222528743</span>
                                                                                                </p>
                                                                                                <p class="text-muted mb-0">
                                                                                                    Place On <span
                                                                                                        class="fw-bold text-body"
                                                                                                        id="order-date"></span>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div>
                                                                                                <h6 class="mb-0"><a
                                                                                                        href="#"
                                                                                                        id="product-details-link">View
                                                                                                        Details</a></h6>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-body p-2">
                                                                                        <div
                                                                                            class="row d-flex flex-row mb-4 pb-2">
                                                                                            <div class="col-8">
                                                                                                <h5 class="bold"
                                                                                                    id="order-product_name">
                                                                                                </h5>
                                                                                                <p class="text-muted"> Qt:
                                                                                                    <span
                                                                                                        id="order-quantity"></span>
                                                                                                    item</p>
                                                                                                <h4 class="mb-3"> ₹ <span
                                                                                                        id="order-amount"></span>
                                                                                                    via (COD) </h4>
                                                                                                <p class="text-muted">
                                                                                                    Tracking Status on:
                                                                                                    <span class="text-body"
                                                                                                        id="order-time"></span>
                                                                                                    Today</p>
                                                                                            </div>
                                                                                            <div class="col-4">
                                                                                                <img class="align-self-center img-fluid"
                                                                                                    src=""
                                                                                                    id="order-product_image"
                                                                                                    style="width:150px;height:150px;">
                                                                                            </div>
                                                                                        </div>
                                                                                        <ul id="progressbar-1"
                                                                                            class="mx-0 mt-0 mb-5 px-0 pt-0 pb-4">
                                                                                            <li class="active"
                                                                                                id="order-step1"><span
                                                                                                    style="margin-left: 22px; margin-top: 12px;">PLACED</span>
                                                                                            </li>
                                                                                            <li class=""
                                                                                                id="order-step2">
                                                                                                <span>SHIPPED</span></li>
                                                                                            <li class=""
                                                                                                id="order-step3"><span
                                                                                                    style="margin-right: 22px;">DELIVERED</span>
                                                                                            </li>
                                                                                        </ul>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="address" role="tabpanel"
                                        aria-labelledby="address-tab">
                                        <div class="row">
                                            @foreach ($billingAddress as $address)
                                                <div class="col-lg-6">
                                                    <div class="card mb-3 mb-lg-0">
                                                        <div class="card-header">
                                                            <h3 class="mb-0">Billing Address</h3>
                                                        </div>
                                                        <div class="card-body">
                                                            <p>{{ $address->address_line1 }}</p>
                                                            <p>{{ $address->address_line2 }},</p>
                                                            <p>{{ \App\Helpers\commonHelper::getCityNameById($address->city_id) }}
                                                            </p>
                                                            <p>{{ \App\Helpers\commonHelper::getStateNameById($address->state_id) }}
                                                            </p>
                                                            <p>{{ $address->pincode }}</p>
                                                            <p>{{ $address->country }}</p>
                                                            <td class="text-center">
                                                                <button type="button"
                                                                    class="btn btn-info btn-lg p-2 viewAddressModalTrigger"
                                                                    data-bs-toggle="modal" data-bs-target="#addressModal"
                                                                    data-address='{{ json_encode($address) }}'>
                                                                    View <span class="ps-2"><i class="fa fa-eye"
                                                                            aria-hidden="true"></i></span>
                                                                </button>
                                                            </td>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach


                                            <div class="modal fade" id="addressModal" tabindex="-1" role="dialog"
                                                data-bs-backdrop="static" aria-labelledby="addressModalLabel"
                                                aria-hidden="true">
                                                <!-- Modal Content -->
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="addressModalLabel"> Update Billing
                                                                Address</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="billingDetailsForm">
                                                                @csrf
                                                                <div class="row billing-details">
                                                                    <h4 class="mb-30">Billing Details</h4>
                                                                    <div class="row">
                                                                    </div>
                                                                    <div class="row shipping_calculator">
                                                                        <div class="form-group col-lg-6">
                                                                            <div class="custom_select">
                                                                                <label for="state">State</label>
                                                                                <select
                                                                                    class="selectbox state statehtml form-control"
                                                                                    name="state_id" onchange="getCity()">
                                                                                    <option value="">--Select state--
                                                                                    </option>
                                                                                    @foreach ($states as $state)
                                                                                        <option
                                                                                            value="{{ $state->id }}">
                                                                                            {{ ucfirst($state->name) }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-lg-6">
                                                                            <label for="city">City</label>
                                                                            <select class="selectbox cityHtml form-control"
                                                                                name="city_id">
                                                                                <option value="">--Select city--
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <input type="tel" name="pincode"
                                                                            class="form-control addressRequired"
                                                                            placeholder="Pin Code*" minlength="5"
                                                                            maxlength="6"
                                                                            onkeypress="return /[0-9 ]/i.test(event.key)">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <input type="text" name="address_1"
                                                                            placeholder="Address 1*"
                                                                            class="form-control addressRequired">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <input type="text" name="address_2"
                                                                            placeholder="Address 2 (Optional)"
                                                                            class="form-control">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary"
                                                                id="updateBillingDetailsBtn">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="addressModal" tabindex="-1" role="dialog"
                                                aria-labelledby="addressModalLabel" aria-hidden="true">
                                                <!-- Modal Content -->
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="addressModalLabel"> Update Billing
                                                                Address</h5>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="billingDetailsForm">
                                                                @csrf
                                                                <div class="row billing-details">
                                                                    <h4 class="mb-30">Billing Details</h4>
                                                                    <div class="row">
                                                                    </div>
                                                                    <div class="row shipping_calculator">
                                                                        <div class="form-group col-lg-6">
                                                                            <div class="custom_select">
                                                                                <label for="state">State</label>
                                                                                <select
                                                                                    class="selectbox state statehtml form-control"
                                                                                    name="state_id" onchange="getCity()">
                                                                                    <option value="">--Select state--
                                                                                    </option>
                                                                                    @foreach ($states as $state)
                                                                                        <option
                                                                                            value="{{ $state->id }}">
                                                                                            {{ ucfirst($state->name) }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-lg-6">
                                                                            <label for="city">City</label>
                                                                            <select class="selectbox cityHtml form-control"
                                                                                name="city_id">
                                                                                <option value="">--Select city--
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-lg-6">
                                                                        <input type="tel" name="pincode"
                                                                            class="form-control addressRequired"
                                                                            placeholder="Pin Code*" minlength="5"
                                                                            maxlength="6"
                                                                            onkeypress="return /[0-9 ]/i.test(event.key)">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <input type="text" name="address_1"
                                                                            placeholder="Address 1*"
                                                                            class="form-control addressRequired">
                                                                    </div>
                                                                    <div class="form-group col-md-12">
                                                                        <input type="text" name="address_2"
                                                                            placeholder="Address 2 (Optional)"
                                                                            class="form-control">
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal" id="closeModal">Close</button>
                                                            <button type="button" class="btn btn-primary"
                                                                id="updateBillingDetailsBtn">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- --- --}}
                                            <div class="col-lg-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="mb-0">Shipping Address</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <address>
                                                            4299 Express Lane<br />
                                                            Sarasota, <br />FL 34249 USA <br />Phone: 1.941.227.4444
                                                        </address>
                                                        <p>Sarasota</p>
                                                        <a href="#" class="btn-small">Edit</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="coupon" role="tabpanel" aria-labelledby="address-tab">
                                        <div class="d-flex justify-content-center align-items-center container">
                                            @if (!empty($couponArray))
                                                @foreach ($couponArray as $coupon)
                                                    @php
                                                        $couponVal = \App\Models\Coupon::where('code', $coupon)->first();
                                                    @endphp
                                                    <div class="d-flex card coupon-edit text-center mr-3">
                                                        <!-- Adjust mr-3 for the desired gap -->
                                                        <h1>{{ round($couponVal->value) }}% OFF</h1>
                                                        <span class="d-block">On Everything</span>
                                                        <div class="mt-4">
                                                            <small>With Code: {{ $coupon }}</small>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center">No coupons available.</div>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="tab-pane fade" id="account-detail" role="tabpanel"
                                        aria-labelledby="account-detail-tab">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Account Details</h5>
                                            </div>
                                            <div class="card-body">
                                                <form id="formsubmit" action="{{ route('user.updateProfile') }}"
                                                    method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="form-group col-md-12 text-center">
                                                            <div class="avatar-upload mb-2">
                                                                <div class="avatar-edit">
                                                                    <input type='file' id="imageUpload"
                                                                        name="profileimage" accept=".png, .jpg, .jpeg" />
                                                                    <label for="imageUpload"></label>
                                                                </div>
                                                                <div class="avatar-preview">
                                                                    @php
                                                                        $profileImage = !empty(Auth::user()->profileimage) ? asset('uploads/profile_images/' . Auth::user()->profileimage) : asset('http://i.pravatar.cc/500?img=7');
                                                                    @endphp
                                                                    <div id="imagePreview"
                                                                        style="background-image: url('{{ $profileImage }}');">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <h6>
                                                                @if (!empty(Auth::user()->profileimage))
                                                                    Change Profile Picture
                                                                @else
                                                                    Select an Image
                                                                @endif
                                                            </h6>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>First Name <span class="required">*</span></label>
                                                            <input type="text" name="name" class="form-control"
                                                                placeholder="Enter your name"
                                                                value="{{ Auth::user()->name }}" required
                                                                autocomplete="off" id="name" />
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Business Name <span class="required">*</span></label>
                                                            <input type="text" name="bname" class="form-control"
                                                                placeholder="Enter business name"
                                                                value="{{ Auth::user()->business_name }}" required
                                                                autocomplete="off" id="bname" />
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Phone No. <span class="required">*</span></label>
                                                            <input type="tel" placeholder="Enter your phone"
                                                                onkeypress="return /[0-9 ]/i.test(event.key)"
                                                                maxLength="10" name="mobile"
                                                                value="{{ Auth::user()->mobile }}" required
                                                                autocomplete="off" id="mobile" />
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Email Address <span class="required">*</span></label>
                                                            <input type="email" name="email" class="form-control"
                                                                placeholder="Enter your email"
                                                                value="{{ Auth::user()->email }}" disabled />
                                                        </div>

                                                        @if (is_null(Auth::user()->password))
                                                            <div class="form-group col-md-12">
                                                                <label>Password <span class="required">*</span></label>
                                                                <input name="password" type="password"
                                                                    placeholder="Enter your password" required
                                                                    autocomplete="off" />
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label>Confirm Password <span
                                                                        class="required">*</span></label>
                                                                <input name="password_confirmation" type="password"
                                                                    placeholder="Enter your confirm password" required
                                                                    autocomplete="off" />
                                                            </div>
                                                        @endif

                                                        <div class="col-md-12">
                                                            <button type="submit"
                                                                class="btn btn-fill-out btn-block hover-up font-weight-bold"
                                                                id="formsubmitSubmit"
                                                                style="display: flex;align-items: center;">Submit &amp;
                                                                Update &nbsp;&nbsp;&nbsp;
                                                                <pre class="spinner-border spinner-border-sm"
                                                                    style="color:white;font-size: 100%;position:relative;top:21%;right:7%;display:none" id="registerLoader"></pre>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>






                                                <script>
                                                    function readURL(input) {
                                                        if (input.files && input.files[0]) {
                                                            var reader = new FileReader();
                                                            reader.onload = function(e) {
                                                                $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                                                                $('#imagePreview').hide();
                                                                $('#imagePreview').fadeIn(650);
                                                            }
                                                            reader.readAsDataURL(input.files[0]);
                                                        }
                                                    }

                                                    $("#imageUpload").change(function() {
                                                        readURL(this);
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>

@endsection

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $(document).ready(function() {
        $("#formsubmit").submit(function(e) {
            e.preventDefault();

            var formId = $(this).attr('id');
            var formAction = $(this).attr('action');
            var profileImage = $('#imageUpload').prop('files')[0];

            if (!profileImage) {
                toastr.error('Please select a profile image.');
                return;
            }

            var formData = new FormData(this);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            $.ajax({
                url: formAction,
                data: formData,
                type: 'post',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#registerLoader').css('display', 'inline-block');
                    $('#' + formId + 'Submit').prop('disabled', true);
                },
                error: function(xhr, textStatus) {
                    $('#registerLoader').css('display', 'none');
                    $('#' + formId + 'Submit').prop('disabled', false);
                    console.error(xhr.statusText);

                    // Display error message using Toastr
                    toastr.error('Error: ' + xhr.statusText);
                },
                success: function(data) {
                    $('#registerLoader').css('display', 'none');
                    $('#' + formId + 'Submit').prop('disabled', false);
                    if (data.user) {
                        console.log(data);
                        // find("#name").text(data.user.name);
                        $("#name").attr('value', data.user.name);
                        $("#bname").attr('value', data.user.business_name);
                        $("#mobile").attr('value', data.user.mobile);
                    }
                    if (data.error == false) {
                        $('#formsubmit')[0].reset();

                        // Display success message using Toastr
                        toastr.success(data.msg);
                        // window.location.reload();
                    } else {
                        if (data.error == true) {
                            // Display error message using Toastr
                            toastr.error(data.msg);
                        }
                    }
                },
                timeout: 5000
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var orderData = button.data('order');
            var productId = orderData.product_id;
            var modal = $(this);
            var formattedDate = new Date(orderData.created_at).toISOString().split('T')[0];

            if (orderData.order_status == '10') {
                modal.find('#order-step2').attr('class', 'active text-center');
                modal.find('#order-step1').attr('class', 'active');
                modal.find('#order-step3').attr('class', 'text-muted text-end');
            }

            if (orderData.order_status == '9') {
                modal.find('#order-step1').attr('class', 'active');
                modal.find('#order-step2').attr('class', 'active text-center');
                modal.find('#order-step3').attr('class', 'active text-muted text-end');
            }

            if (orderData.order_status == '1') {
                modal.find('#order-step1').attr('class', 'active');
                modal.find('#order-step2 text-center').attr('class', '');
                modal.find('#order-step3').attr('class', 'text-muted text-end');
            }

            // Populate modal with order details
            modal.find('#order-order_id').text(orderData.order_id);
            modal.find('#order-product_name').text(orderData.product_name);
            modal.find('#order-date').text(formattedDate);
            modal.find('#order-quantity').text(orderData.qty);
            modal.find('#order-amount').text(orderData.amount);
            modal.find('#order-product_image').attr('src', orderData.product_image);
            modal.find('#order-time').text(new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            }));


            // Fetch product slug asynchronously
            $.ajax({
                url: "/get-product-slug/" + productId,
                method: 'GET',
                success: function(response) {

                    var productDetailSlug = response.product_slug;

                    var productDetailsLink = "/product_detail/" + productDetailSlug;
                    $('#product-details-link').attr('href', productDetailsLink);
                },
                error: function(error) {
                    console.error('Error fetching product slug:', error);
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function() {

        var currentAddressData;

        $('.viewAddressModalTrigger').on('click', function() {
            currentAddressData = $(this).data('address');
            var modal = $('#addressModal');

            modal.find('[name="address_1"]').val(currentAddressData.address_line1);
            modal.find('[name="address_2"]').val(currentAddressData.address_line2);
            modal.find('[name="pincode"]').val(currentAddressData.pincode);
            modal.find('.statehtml').val(currentAddressData.state_id);
            modal.find('.cityHtml').val(currentAddressData.city_id);

            // Fetch cities on state change
            $.ajax({
                url: baseUrl + '/get-city?state_id=' + currentAddressData.state_id,
                dataType: 'json',
                type: 'get',
                success: function(data) {
                    $('.cityHtml').html(data.html);
                    $('.cityHtml').val(currentAddressData.city_id);
                },
                error: function(xhr, textStatus) {
                    console.error('Error fetching cities:', textStatus);
                },
                cache: false,
                timeout: 5000
            });

            modal.modal('show');
        });

        // Update Billing Details Button Click Event
        $('#updateBillingDetailsBtn').on('click', function() {
            // Retrieve updated values from form
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Validate required fields
            var address1 = $('[name="address_1"]').val();
            var pincode = $('[name="pincode"]').val();
            var stateId = $('.statehtml').val();
            var cityId = $('.cityHtml').val();
            var missingFields = [];

            if (!address1) {
                missingFields.push('Address 1');
            }

            if (!pincode) {
                missingFields.push('Pincode');
            }

            if (!stateId) {
                missingFields.push('State');
            }

            if (!cityId) {
                missingFields.push('City');
            }

            if (missingFields.length > 0) {
                var errorMessage = 'Please fill in the following required fields: ' + missingFields
                    .join(', ');
                alert(errorMessage);
                return;
            }

            var updatedAddress = {
                address_line1: address1,
                address_line2: $('[name="address_2"]').val(),
                pincode: pincode,
                state_id: stateId,
                city_id: cityId,
            };

            // send data to update
            $.ajax({
                url: '/update-address/' + currentAddressData.id,
                method: 'POST',
                data: updatedAddress,
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // Include CSRF token in headers
                },
                success: function(response) {
                    console.log('Address updated successfully:', response);
                    // Optionally, update the UI or perform any other actions
                    $('#addressModal').modal('hide');
                },
                error: function(error) {
                    console.error('Error updating address:', error);
                }
            });
        });

        // Fetch cities on state change
        $('.statehtml').change(function() {
            $.ajax({
                url: baseUrl + '/get-city?state_id=' + $(this).val(),
                dataType: 'json',
                type: 'get',
                error: function(xhr, textStatus) {
                    if (xhr && xhr.responseJSON.message) {
                        showMsg('error', xhr.status + ': ' + xhr.responseJSON.message);
                    } else {
                        showMsg('error', xhr.status + ': ' + xhr.statusText);
                    }
                },
                success: function(data) {
                    $('.cityHtml').html(data.html);
                    // Pre-select city based on existing data
                    $('.cityHtml').val(currentAddressData.city_id);
                },
                cache: false,
                timeout: 5000
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        var currentOrderId;

        // Cancel Order Modal
        $(document).on('click', '.cancelOrderModalTrigger', function() {
            currentOrderId = $(this).data('order-id');
            $('#cancelOrderModal').modal('show');
        });

        // Confirm cancellation button click event
        $('#confirmCancelOrderBtn').on('click', function() {
            var radioReason = $('input[name="cancelReason"]:checked').val();
            var textReason = $('#cancelReason').val();
            var csrfToken = $('#cancelOrderForm [name="_token"]').val();

            if ((radioReason && !textReason) || (!radioReason && textReason)) {
                // Either radio or text reason is provided, but not both

                $.ajax({
                    url: '/cancel-order/' + currentOrderId,
                    method: 'POST',
                    data: {
                        cancelReason: radioReason || textReason,
                        _token: csrfToken
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#cancelOrderModal').modal('hide');
                    },
                    error: function(error) {
                        alert(response.message);
                    }
                });
            } else {
                alert('Please provide either a radio reason or a text reason, not both.');
            }
        });

        // Add an event listener to the input field to unselect the radio button
        $('#cancelReason').on('click', function() {
            $('input[name="cancelReason"]').prop('checked', false);
        });

    });
</script>
