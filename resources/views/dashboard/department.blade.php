<x-admin.layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="heading">Dashboard</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}



    @if(session('LOGIN_TYPE') == 1)

    <div class="row">
        <div class="col-md-3 col-lg-3 col-6">
            <div class="card card-animate card-height-100 bg-warning ">
                 <a href="{{ route('compliance.create') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-uppercase fw-medium text-white mb-0">HMM Objection</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($hmmObjections) }}">{{ count($hmmObjections) }}</span></h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-warning text-primary rounded-2 fs-2">
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
                 <a href="{{ route('pending-audit-objection.index') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-uppercase fw-medium text-white mb-0">Pending Compliance</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($pendingAuditObjections) }}">{{ count($pendingAuditObjections) }}</span></h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-success text-primary rounded-2 fs-2">
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
                    <h3 class="card-title">HMM Objection</h3>
                    <a href="{{ route('compliance.create') }}" class="btn btn-primary btn-sm">View</a>
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
                                @foreach($hmmObjections as $hmmObjection)
                                <tr>
                                    <td>{{ $hmmObjection->department?->name }}</td>
                                    <td>{{ Carbon\Carbon::parse($hmmObjection->audit?->date)->format('d-m-Y') }}</td>
                                    <td>{{ $hmmObjection->objection_no }}</td>
                                    <td>{{ $hmmObjection->subject }}</td>
                                    <td>{{ Carbon\Carbon::parse($hmmObjection->entry_date)->format('d-m-Y') }}</td>
                                    <td>@if($hmmObjection->audit?->description) <span style="cursor: pointer" title="{{ $hmmObjection->audit?->description }}">{{ Str::limit($hmmObjection->audit?->description, '30') }}</span>@else - @endif</td>
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
                    <h3 class="card-title">Pending Compliance</h3>
                    <a href="{{ route('pending-audit-objection.index') }}" class="btn btn-primary btn-sm">View</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dashboardDataTable">
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
                                    <td>{{ $pendingAuditObjection->auditObjection->department?->name }}</td>
                                    <td>{{ $pendingAuditObjection->auditObjection->objection_no }}</td>
                                    <td>{{ $pendingAuditObjection->pending_description }}</td>
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
        @if($user->department_id == 1)
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
    @endif


</x-admin.layout>
<script>
    $(document).ready(function(){
        $('.dashboardDataTable').DataTable({
            pageLength: 5,
        });
    });
</script>