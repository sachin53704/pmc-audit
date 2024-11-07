<x-admin.layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="heading">Dashboard</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}



    <div class="row">
        <div class="col-md-3">
            <div class="card card-animate card-height-100 bg-warning ">
                 <a href="{{ route('objection.send-hmm-draft') }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-uppercase fw-medium text-white mb-0">Hmm Draft</p>
                                <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{ count($audits) }}">{{ count($audits) }}</span></h2>
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
    </div>

    <div class="row">
        <div class="col-xl-12 col-md-12 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="card-title">Hmm Draft</h3>
                    <a href="{{ route('objection.send-hmm-draft') }}" class="btn btn-primary btn-sm">View</a>
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
                                @foreach($audits as $audit)
                                    <tr>
                                        <td>{{ $audit->department->name }}</td>
                                        <td>{{ date('d-m-Y', strtotime($audit->audit->date)) }}</td>
                                        <td>{{ $audit->objection_no }}</td>
                                        <td>{{ $audit->subject }}</td>
                                        <td>{{ date('d-m-Y', strtotime($audit->entry_date)) }}</td>
                                        <td>@if($audit->audit?->description) <span style="cursor: pointer" title="{{ $audit->audit?->description }}">{{ Str::limit($audit->audit?->description, '30') }}</span>@else - @endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- end col -->

    </div>

</x-admin.layout>

<script>
    $(document).ready(function(){
        $('.dashboardDataTable').DataTable({
            pageLength: 10,
        });
    });
</script>
