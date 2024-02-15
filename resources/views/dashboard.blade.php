@extends('layouts/master')

@section('title',__(' User Dashboard'))

@section('content')

<section class="content">
    <div class="container-fluid dashboard-wrap">
        <div class="row">

    <div class="col-md-12">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert" style="background-image: linear-gradient( 180deg, #ffc634, #f36927);">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <p style=" color: white; font-weight: 600;"><strong>Profile!</strong> Update Profile Picture.<a href="/user/profile/myaccount" style="color: #fbff16; font-weight:bold;">click here </a>To Update</p>
                <small style="color: #e8e7e7;">Profile Image, Personal Information, Address</small>
            </div>
        </div>
    </div>
        <div class="col-md-12">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert" style="background-image: linear-gradient( 180deg, #ffc634, #f36927);">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <p style=" color: white; font-weight: 600;"><strong>Update KYC Details!</strong>  <a href="/user/profile/kyc" style="color: #fbff16; font-weight:bold;">click here</a> to Submit KYC Form.</p>
                <small style="color: #e8e7e7;">KYC:- Aadhar, PAN, Bank Passbook</small>

            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert" style="background-image: linear-gradient( 180deg, #ffc634, #f36927);">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <p style=" color: white; font-weight: 600;"> <strong>Update Bank Details!</strong> <a href="/user/profile/bankdetails" style="color: #fbff16; font-weight:bold;">click here</a> to Submit Bank Details.</p>
                <small style="color: #e8e7e7;">Bank:- Update Bank Details for Withdrawal</small>
            </div>
        </div>
    </div>

    <div class=" col-lg-4 col-md-12">
    <!-- Clock -->
    <div class="col-md-12">
        <!-- START WIDGET CLOCK -->
        <div class="widget widget-info widget-padding-sm border10px" style=" background-image: linear-gradient( 180deg, #000000, #16a2f2) !important;">
            <div class="widget-big-int plugin-clock" style="line-height: 75px;">10<span>:</span>31</div>
            <div class="widget-subtitle plugin-date">Thursday, November 17, 2022</div>
        </div>
        <!-- END WIDGET CLOCK -->
    </div>
    <!-- Profile -->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body profile" style="background: url('/Content/CPanel/assets/images/gallery/music-4.jpg') center center no-repeat;">
                <div class="profile-image">
                    <img src="{{asset('admin-assets/images/empty.png') }}" alt="MINAJ SHAIKH">
                </div>
                <div class="profile-data">
                    <div class="profile-data-name">MINAJ SHAIKH</div>
                    <div class="profile-data-title" style="color: #FFF;">AS672257</div>
                </div>
                
            </div>
            <p id="copy" style="display: none;">
                <a id="lnkRefLink" href="http://acushine.com/front/home/signupreferral?referral=07240639986501533610517" target="_blank" class="badge badge-success" style="float:left; margin-top:15px; ">
                    http://acushine.com/signupreferral?referral=07240639986501533610517
                </a>
            </p>
            <div class="panel-body list-group border-bottom">
                <div class="list-group-item">
                    Name
                    <label class="badge badge-primary text-right">MINAJ SHAIKH</label>
                </div>
                    <div class="list-group-item">
                        Copy Refferal Link
                        
                        <a onclick="copyToClipboard('#lnkRefLink')" class="btn-xs btn-success" style="float: right;"><span class="fa fa-copy"></span>Refer Now </a>
                        
                    </div>
                <div class="list-group-item">
                    Tracking Id(Login Id)
                    <label class="badge badge-primary text-right">AS672257 </label>
                </div>
                <a href="/user/wallet/pinlist">
                    <div class="list-group-item">
                        My Un Used PIN
                        <label class="badge badge-primary text-right">0 </label>
                    </div>
                </a>

                
                <script>
                    function copyToClipboard(element) {
                        var $temp = $("<input>");
                        $("body").append($temp);
                        $temp.val($(element).attr('href')).select();
                        document.execCommand("copy");
                        $temp.remove();
                        //alert("Refferal Link Copied");
                        sweetAlert("Copied!", "Refferal Link Copied", "success");
                    }
                </script>
            </div>
        </div>
    </div>

    <div class="col-md-12">
            <div class="form-horizontal">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">
                            <strong style="color: green; font-weight: bold; font-size: 18px;">Account is Active</strong>
                        </h3>
                    </div>
                    <div class="panel-body list-group">
                        <a href="/user/wallet/voucherstatement" class="list-group-item"><span class="fa fa-inr"></span>Vouchers Balance <span class="badge badge-success">0.00</span></a>
                        <a href="#" class="list-group-item"><span class="fa fa-calendar"></span>Account Activation On <span class="badge badge-success">17-01-2022 05:02 PM</span></a>
                        <a href="#" class="list-group-item"><span class="fa fa-clock-o"></span>Account Validity Date <span class="badge badge-danger">17-01-2023 05:02 PM</span></a>
                    </div>
                </div>
            </div>
            <!-- My Team -->
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">My Team <small>Team status</small></h3>
                </div>
                <div class="panel-body list-group">
                    <a href="/user/team/myreferrals" class="list-group-item">
                        <span class="fa fa-users"></span>
                        My Total Direct
                        <span class="badge badge-primary">
                            6
                        </span>
                    </a>
                </div>

                <div class="panel-body list-group">
                    <a href="#" class="list-group-item">
                        <span class="fa fa-list"></span>
                        My Total Downline
                        <span class="badge badge-primary">
                            10
                        </span>
                    </a>
                </div>
            </div>
    </div>

    <!-- Shopping -->

    </div>

    <div class="col-lg-4 col-md-12">
    <div class="col-md-12">
        <div class="widget widget-success widget-item-icon border10px" style="background-image: linear-gradient( 180deg, #bcc20f, #0e3903);">
            <div class="widget-item-left">
                
                <img src="{{asset('admin-assets/images/bag.png') }}" style="width:65px;">
            </div>
            <div class="widget-data">
                <div class="widget-int num-count">₹3124.86/-</div>
                <div class="widget-title">Shopping Wallet</div>
                <div class="widget-subtitle">You can use shopping wallet amount to purchase products</div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="widget widget-success widget-item-icon border10px" style="        background-image: linear-gradient( 180deg, #cf3824, #0e3903);">
            <div class="widget-item-left">
                
                <img src="{{asset('admin-assets/images/wallet.png') }}" style="width:65px;">
            </div>
            <div class="widget-data">
                <div class="widget-int num-count">₹577.10/-</div>
                <div class="widget-title">Cashback and Commission Wallet</div>
                <div class="widget-subtitle">You can withdraw amount to your bank</div>
            </div>
        </div>
    </div>
        <!-- Pins -->
        <div class="col-md-12">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Pin <small>All pin status</small></h3>
                </div>
                <div class="panel-body list-group">
                    <a href="/user/wallet/pinrequestlist" class="list-group-item">
                        <span class="glyphicon glyphicon-tag"></span>&nbsp;Pending Pin Request
                        <span class="badge badge-warning">
                            0
                        </span>
                    </a>
                    <a href="/user/wallet/pinlist" class="list-group-item">
                        <span class="glyphicon glyphicon-tag"></span>&nbsp;Total Pins
                        <span class="badge badge-primary">
                            1
                        </span>
                    </a>
                    <a href="/user/wallet/pinlist" class="list-group-item">
                        <span class="glyphicon glyphicon-tag"></span>&nbsp;Pin Used
                        <span class="badge badge-danger">
                            1
                        </span>
                    </a>
                    <a href="/user/wallet/pinlist" class="list-group-item">
                        <span class="glyphicon glyphicon-tag"></span>&nbsp;Pin Un-Used
                        <span class="badge badge-success">
                            0
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <!-- Earn Wallet -->
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Earn Wallet <small>All Earn Status</small></h3>
                </div>
                <div class="panel-body list-group">
                    <a href="/user/wallet/incomestatement" class="list-group-item"><span class="fa fa-inr"></span>Wallet Balance <span class="badge badge-info">577.10</span></a>
                    <a href="/user/wallet/withdrawalrequestlist" class="list-group-item"><span class="fa fa-inr"></span>Pending Withdrawal Amount <span class="badge badge-primary">0</span></a>
                    <a href="/user/wallet/withdrawalrequestlist" class="list-group-item"><span class="fa fa-inr"></span>Success Withdrawal Amount <span class="badge badge-success">0</span></a>
                </div>
            </div>
        </div>
        <!-- Income -->
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Income Status <small>All Income Status</small></h3>
                </div>
                <div class="panel-body list-group">
                    <a href="/user/finance/level" class="list-group-item"><span class="fa fa-inr"></span>All Level Income <span class="badge badge-info">382.50</span></a>
                    <a href="/user/finance/autopool" class="list-group-item"><span class="fa fa-inr"></span>All Autopool Income <span class="badge badge-info">30.00</span></a>
                    <a href="/user/finance/repurchase" class="list-group-item"><span class="fa fa-inr"></span>All Repurchase Income <span class="badge badge-info">164.60</span></a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12">
    <!-- Achivers -->




    <!-- NEWS -->
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading ui-draggable-handle">
                    <h3 class="panel-title">Latest News</h3>
                    <ul class="panel-controls">
                        <li><a href="/user/dashboard/news" class="control-danger">All</a></li>
                    </ul>
                </div>
                <div class="panel-body scroll mCustomScrollbar _mCS_4 mCS-autoHide _mCS_1 mCS_no_scrollbar" style="height: 230px;"><div id="mCSB_1" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0"><div id="mCSB_1_container" class="mCSB_container mCS_y_hidden mCS_no_scrollbar_y" style="position:relative; top:0; left:0;" dir="ltr">
                    <div id="mCSB_4" class="mCustomScrollBox mCS-light mCSB_vertical mCSB_inside" tabindex="0">
                        <div id="mCSB_4_container" class="mCSB_container" style="position: relative; top: 0px; left: 0px;" dir="ltr">

                                <h6>uyjujhjjjjjjjjjjjjjjjjjjj</h6>
                                <p>
                                    </p><p><span style="background-color: rgb(255, 156, 0);">ggggggggggggggggg</span>ggg</p>
                                <p></p>

                        </div>
                        <div id="mCSB_4_scrollbar_vertical" class="mCSB_scrollTools mCSB_4_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: block;">
                            <div class="mCSB_draggerContainer">
                                <div id="mCSB_4_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; display: block; height: 161px; max-height: 190px; top: 39px;" oncontextmenu="return false;">
                                    <div class="mCSB_dragger_bar" style="line-height: 30px;"></div>
                                </div>
                                <div class="mCSB_draggerRail"></div>
                            </div>
                        </div>
                    </div>
                </div><div id="mCSB_1_scrollbar_vertical" class="mCSB_scrollTools mCSB_1_scrollbar mCS-light mCSB_scrollTools_vertical" style="display: none;"><div class="mCSB_draggerContainer"><div id="mCSB_1_dragger_vertical" class="mCSB_dragger" style="position: absolute; min-height: 30px; height: 0px; top: 0px;" oncontextmenu="return false;"><div class="mCSB_dragger_bar" style="line-height: 30px;"></div></div><div class="mCSB_draggerRail"></div></div></div></div></div>
            </div>
            <div class="clearfix">&nbsp; </div>
        </div>

    <!-- ACHIVER SLIDER LIST -->

    <!-- Login History -->
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Last 10 Login History</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SrNo.</th>
                                <th>IP Address</th>
                                <th>Date and Time</th>
                            </tr>
                        </thead>
                        <tbody>

                                <tr>
                                    <td>1</td>
                                    <td>103.157.221.35</td>
                                    <td>17/11/2022 10:26:14</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>103.157.221.35</td>
                                    <td>17/11/2022 10:20:04</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>103.157.221.35</td>
                                    <td>17/11/2022 10:12:08</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>103.133.121.59</td>
                                    <td>15/11/2022 11:01:34</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>103.133.121.59</td>
                                    <td>15/11/2022 10:37:23</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>103.133.121.59</td>
                                    <td>15/11/2022 10:36:14</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>103.133.121.59</td>
                                    <td>15/11/2022 10:35:08</td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>103.133.121.59</td>
                                    <td>15/11/2022 10:34:23</td>
                                </tr>
                                <tr>
                                    <td>9</td>
                                    <td>103.133.121.59</td>
                                    <td>15/11/2022 10:29:06</td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>103.238.108.215</td>
                                    <td>15/09/2022 22:49:26</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</section>


@endsection


@push('custom_js')
<script src="{{ asset('admin-assets/js/chart.min.js')}}"></script>
<script src="{{ asset('admin-assets/js/echarts.js')}}"></script>
<script src="{{ asset('admin-assets/js/apexcharts.min.js') }}"></script>

@endpush