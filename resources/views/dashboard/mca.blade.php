<x-admin.layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="heading">Dashboard</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


        @if(session('LOGIN_TYPE') == 1)

        <div class="row">
            <div class="col-md-3 col-lg-3 col-6">
                <div class="card card-animate card-height-100 bg-warning ">
                     <a href="{{ route('hmmMcaStatus') }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-uppercase fw-medium text-white mb-0">Hmm</p>
                                    <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($hmms) }}">{{ count($hmms) }}</span></h2>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-warning text-white rounded-2 fs-2">
                                            <i class="bx bx-notepad"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </a>
                </div> <!-- end card-->
            </div> <!-- end col-->
    
            <div class="col-md-3 col-lg-3 col-6">
                <div class="card card-animate card-height-100 bg-success ">
                     <a href="{{ route('objection.clerk-send-hmm-draft') }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-uppercase fw-medium text-white mb-0">Hmm Draft</p>
                                    <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($hmmDrafts) }}">{{ count($hmmDrafts) }}</span></h2>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-success text-white rounded-2 fs-2">
                                            <i class="bx bx-notepad"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </a>
                </div> <!-- end card-->
            </div> <!-- end col-->


            <div class="col-md-3 col-lg-3 col-6">
                <div class="card card-animate card-height-100 bg-info ">
                     <a href="{{ route('draft-review') }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-uppercase fw-medium text-white mb-0">Compliance</p>
                                    <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($compliances) }}">{{ count($compliances) }}</span></h2>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-info text-white rounded-2 fs-2">
                                            <i class="bx bx-notepad"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </a>
                </div> <!-- end card-->
            </div> <!-- end col-->

            <div class="col-md-3 col-lg-3 col-6">
                <div class="card card-animate card-height-100 bg-primary ">
                     <a href="{{ route('pending-audit-objection.index') }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-uppercase fw-medium text-white mb-0">Pending Objection</p>
                                    <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($pendingAuditObjections) }}">{{ count($pendingAuditObjections) }}</span></h2>
                                </div>
                                <div>
                                    <div class="avatar-sm flex-shrink-0">
                                        <span class="avatar-title bg-primary text-white rounded-2 fs-2">
                                            <i class="bx bx-notepad"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </a>
                </div> <!-- end card-->
            </div> <!-- end col-->
        </div>


            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Hmm</h3>
                            <a href="{{ route('hmmMcaStatus') }}" class="btn btn-primary btn-sm">View</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table dashboardDataTable">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Hmm No.</th>
                                            <th>DYMCA Status</th>
                                            <th>MCA Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hmms as $hmm)
                                        <tr>
                                            <td>{{ $hmm->department->name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($hmm->audit->date)) }}</td>
                                            <td><span style="cursor: pointer" title="{{ $hmm->audit?->description }}">{{ Str::limit($hmm->audit?->description, '30') }}</span></td>
                                            <td>{{ $hmm->objection_no }}</td>
                                            <td>
                                                @if($hmm->dymca_status == "1")
                                                <span class="badge bg-success">Approve</span>
                                                @elseif($hmm->dymca_status == "2")
                                                <span class="badge bg-danger">Forward To Auditor</span>
                                                @else
                                                <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($hmm->mca_status == "1")
                                                <span class="badge bg-success">Approve</span>
                                                @elseif($hmm->mca_status == "2")
                                                <span class="badge bg-danger">Forward To Auditor</span>
                                                @else
                                                <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Hmm Draft</h3>
                            <a href="{{ route('objection.clerk-send-hmm-draft') }}" class="btn btn-primary btn-sm">View</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dashboardDataTable">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Date</th>
                                            <th>HMM No.</th>
                                            <th>Subject</th>
                                            <th>Entry Date</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($hmmDrafts as $hmmDraft)
                                        <tr>
                                            <td>{{ $hmmDraft->department?->name }}</td>
                                            <td>{{ Carbon\Carbon::parse($hmmDraft->audit?->date)->format('d-m-Y') }}</td>
                                            <td>{{ $hmmDraft->objection_no }}</td>
                                            <td>{{ $hmmDraft->subject }}</td>
                                            <td>{{ Carbon\Carbon::parse($hmmDraft->entry_date)->format('d-m-Y') }}</td>
                                            <td>@if($hmmDraft->audit?->description) <span style="cursor: pointer" title="{{ $hmmDraft->audit?->description }}">{{ Str::limit($hmmDraft->audit?->description, '30') }}</span>@else - @endif</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Compliance</h3>
                            <a href="{{ route('draft-review') }}" class="btn btn-primary btn-sm">View</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table dashboardDataTable">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>Date</th>
                                            <th>HMM No.</th>
                                            <th>Subject</th>
                                            <th>Entry Date</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($compliances as $compliance)
                                        <tr>
                                            <td>{{ $compliance->department->name }}</td>
                                            <td>{{ date('d-m-Y', strtotime($compliance->audit->date)) }}</td>
                                            <td>{{ $compliance->objection_no }}</td>
                                            <td>{{ $compliance->subject }}</td>
                                            <td>{{ date('d-m-Y', strtotime($compliance->entry_date)) }}</td>
                                            <td>@if($compliance->audit?->description) <span style="cursor: pointer" title="{{ $compliance->audit?->description }}">{{ Str::limit($compliance->audit?->description, '30') }}</span>@else - @endif</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title">Pending Objection</h3>
                            <a href="{{ route('pending-audit-objection.index') }}" class="btn btn-primary btn-sm">View</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table dashboardDataTable">
                                    <thead>
                                        <tr>
                                            <th>Department</th>
                                            <th>HMM No.</th>
                                            <th>Pending Objection</th>
                                            <th>Letter</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingAuditObjections as $pendingAuditObjection)
                                        <tr>
                                            <td>{{ $pendingAuditObjection->auditObjection->department->name }}</td>
                                            <td>{{ $pendingAuditObjection->auditObjection->objection_no }}</td>
                                            <td>
                                                {!! $pendingAuditObjection->pending_description !!}
                                            </td>
                                            <td>
                                                <a href="{{ asset('storage/'.$pendingAuditObjection->hmm_draft_letter) }}" class="btn btn-primary btn-sm">View Letter</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="row">
                <div class="col-xl-4 col-md-4">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Pending Receipts</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $pendingReceipts }}</h4>
                                    <a href="#" class="text-decoration-underline"></a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-file text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->


                <div class="col-xl-4 col-md-4">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Approved Receipts</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        {{-- <i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 % --}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $approvedReceipts }}</h4>
                                    <a href="#" class="text-decoration-underline"></a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-file text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->


                <div class="col-xl-4 col-md-4">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Rejected Receipts</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $rejectedReceipts }} </h4>
                                    <a href="#" class="text-decoration-underline"></a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-file text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div>

            <div class="row">
                <div class="col-xl-4 col-md-4">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Pending Payment Receipts</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                        {{-- <i class="ri-arrow-right-up-line fs-13 align-middle"></i> +16.24 % --}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $pendingPaymentReceipts }}</h4>
                                    <a href="#" class="text-decoration-underline"></a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-file text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->


                <div class="col-xl-4 col-md-4">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Approved Payment Receipts</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-danger fs-14 mb-0">
                                        {{-- <i class="ri-arrow-right-down-line fs-13 align-middle"></i> -3.57 % --}}
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $approvedPaymentReceipts }}</h4>
                                    <a href="#" class="text-decoration-underline"></a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-file text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->


                <div class="col-xl-4 col-md-4">
                    <!-- card -->
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Rejected Payment Receipts</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <h5 class="text-success fs-14 mb-0">
                                    </h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-end justify-content-between mt-4">
                                <div>
                                    <h4 class="fs-22 fw-semibold ff-secondary mb-4">{{ $rejectedPaymentReceipts }} </h4>
                                    <a href="#" class="text-decoration-underline"></a>
                                </div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-primary-subtle rounded fs-3">
                                        <i class="bx bx-file text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div>
        @endif


</x-admin.layout>

<script>
    $(document).ready(function(){
        $('.dashboardDataTable').DataTable({
            pageLength: 5,
        });
    });
</script>
