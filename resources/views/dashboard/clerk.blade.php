<x-admin.layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="heading">Dashboard</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}



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
