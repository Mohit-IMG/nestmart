@extends('layouts/master')

@section('title',__('Dashboard'))

@section('content')



<!-- <section class="content">
    <div class="container-fluid dashboard-wrap">
        <div class="row ">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                <a href="#">
                    <div class="card-board d-flex">
                        <div class="dash-icon">
                            <i class="fa fa-user text-white" aria-hidden="true"></i>
                        </div>
                        <div class="dash-content">
                            <div class="num-1 text-white">5</div>
                            <div class="num-2 text-white">Registred Sellers</div>
                            <div class="num-3 text-white">On our website</div>

                        </div>

                    </div>
                </a>
            </div>
            <div class="col-xl-3 col-lg-2 col-md-32 col-sm-12">
                <a href="#">
                    <div class="card-board d-flex">
                        <div class="dash-icon">
                            <i class="fa fa-user text-white" aria-hidden="true"></i>
                        </div>
                        <div class="dash-content">
                            <div class="num-1 text-white">5</div>
                            <div class="num-2 text-white">Registred Sellers</div>
                            <div class="num-3 text-white">On our website</div>

                        </div>

                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                <a href="#">
                    <div class="card-board-1">
                        <div class="dash-txt">
                            <div class="text-white dash-txt-1">14</div>
                            <div class="dash-txt-2 text-white">Company Direct Users</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-2">
                <a href="#">
                    <div class="card-board-1  card-board-2">
                        <div class="dash-txt">
                            <div class="text-white dash-txt-1">0</div>
                            <div class="dash-txt-2 text-white">Company Total Turnover</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                <a href="#">
                    <div class="card-board-1 card-board-2">
                        <div class="dash-txt">
                            <div class="text-white dash-txt-1">0.00</div>
                            <div class="dash-txt-2 text-white">Company Balance</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                <a href="#">
                    <div class="card-board-1">
                        <div class="dash-txt">
                            <div class="text-white dash-txt-1">0.00</div>
                            <div class="dash-txt-2 text-white">Total Level Income (Payout)</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                <a href="#">
                    <div class="card-board-1">
                        <div class="dash-txt">
                            <div class="text-white dash-txt-1">0.00</div>
                            <div class="dash-txt-2 text-white"> Total Repurchase Income (Payout)</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12">
                <a href="#">
                    <div class="card-board-1">
                        <div class="dash-txt">
                            <div class="text-white dash-txt-1">0.00</div>
                            <div class="dash-txt-2 text-white">Total Autopool Income (Payout)</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section> -->











@endsection


@push('custom_js')
<script src="{{ asset('admin-assets/js/chart.min.js')}}"></script>
<script src="{{ asset('admin-assets/js/echarts.js')}}"></script>
<script src="{{ asset('admin-assets/js/apexcharts.min.js') }}"></script>

@endpush