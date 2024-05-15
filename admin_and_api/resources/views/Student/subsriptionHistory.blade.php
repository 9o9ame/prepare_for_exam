@extends('../Student/layout')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top d-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb  bread-heading"><a href="{{ route('student-dashboard') }}">
                                    <li class="">Dashboard</li>
                                </a>
                                <li class="breadcrumb-item active">&nbsp; | Subscription</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="row">
                    @foreach ($data as $key => $sub)
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0"><span class="card-title">Subscription Name: <strong>{{$sub['plan']->subscription_name ?? ''}}</strong></span><span class="float-right primary"> <span
                                            class="badge @if($sub->txn_status == 'success') badge-success @else badge-danger @endif">{{$sub->txn_status ?? ''}}</span></span></div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <p>Transaction Id
                                            {{$sub->txn_id ?? ''}}
                                        </p>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p>Amount : <strong>$ {{$sub['plan']->subscription_price ?? ''}}</strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p>Exam Count : <strong>{{$sub->no_of_exam ?? ''}}</strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p>Subject Count : <strong>{{$sub->no_of_subject ?? ''}}</strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p>Board Count : <strong>{{$sub->no_of_board ?? ''}}</strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p>TXN Date : <strong> {{$sub->created_at->format('d/m/Y')}}</strong></p>
                                            </div>
                                            <div class="col-md-4">
                                                <p>Validity : <strong>{{$sub['plan']->sv_month ?? ''}} Month</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
