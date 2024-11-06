<x-admin.layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="heading">Dashboard</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Compliance Objection</h3>
                    <a href="{{ route('answered-questions') }}" class="btn btn-primary btn-sm">View</a>
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
                                @foreach($complianceObjections as $complianceObjection)
                                <tr>
                                    <td>{{ $complianceObjection->department?->name }}</td>
                                    <td>{{ Carbon\Carbon::parse($complianceObjection->audit?->date)->format('d-m-Y') }}</td>
                                    <td>{{ $complianceObjection->objection_no }}</td>
                                    <td>{{ $complianceObjection->subject }}</td>
                                    <td>{{ Carbon\Carbon::parse($complianceObjection->entry_date)->format('d-m-Y') }}</td>
                                    <td>@if($complianceObjection->audit?->description) <span style="cursor: pointer" title="{{ $complianceObjection->audit?->description }}">{{ Str::limit($complianceObjection->audit?->description, '30') }}</span>@else - @endif</td>
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
                    <a href="{{ route('answered-questions') }}" class="btn btn-primary btn-sm">View</a>
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
                                    <td>{!! $pendingAuditObjection->pending_description !!}</td>
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


</x-admin.layout>

<script>
    $(document).ready(function(){
        $('.dashboardDataTable').DataTable({
            pageLength: 5,
        });
    });
</script>
