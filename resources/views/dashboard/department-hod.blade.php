<x-admin.layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="heading">Dashboard</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}



    @if(session('LOGIN_TYPE') == 1)
    <div class="row">
        <div class="col-md-3 col-lg-3 col-6">
            <div class="card card-animate card-height-100 bg-warning ">
                 <a href="{{ route('objection.forward-objection-to-department') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-uppercase fw-medium text-white mb-0">HMM</p>
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
                 <a href="{{ route('draft-review') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-uppercase fw-medium text-white mb-0">Compliance</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($compliances) }}">{{ count($compliances) }}</span></h2>
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
            <div class="card card-animate card-height-100 bg-danger ">
                 <a href="{{ route('pending-audit-objection.index') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-uppercase fw-medium text-white mb-0">Pending Compliance</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($pendingAuditObjections) }}">{{ count($pendingAuditObjections) }}</span></h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-danger text-white rounded-2 fs-2">
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
                    <h3 class="card-title">HMM</h3>
                    <a href="{{ route('objection.forward-objection-to-department') }}" class="btn btn-primary btn-sm">View</a>
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
                                @foreach($hmms as $hmm)
                                <tr>
                                    <td>{{ $hmm->department?->name }}</td>
                                    <td>{{ Carbon\Carbon::parse($hmm->audit?->date)->format('d-m-Y') }}</td>
                                    <td>{{ $hmm->objection_no }}</td>
                                    <td>{{ $hmm->subject }}</td>
                                    <td>{{ Carbon\Carbon::parse($hmm->entry_date)->format('d-m-Y') }}</td>
                                    <td>@if($hmm->audit?->description) <span style="cursor: pointer" title="{{ $hmm->audit?->description }}">{{ Str::limit($hmm->audit?->description, '30') }}</span>@else - @endif</td>
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
                                @foreach($compliances as $compliance)
                                <tr>
                                    <td>{{ $compliance->department?->name }}</td>
                                    <td>{{ Carbon\Carbon::parse($compliance->audit?->date)->format('d-m-Y') }}</td>
                                    <td>{{ $compliance->objection_no }}</td>
                                    <td>{{ $compliance->subject }}</td>
                                    <td>{{ Carbon\Carbon::parse($compliance->entry_date)->format('d-m-Y') }}</td>
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
        
    @endif


</x-admin.layout>
<script>
    $(document).ready(function(){
        $('.dashboardDataTable').DataTable({
            pageLength: 5,
        });
    });
</script>