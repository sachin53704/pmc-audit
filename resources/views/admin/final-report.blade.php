<x-admin.layout>
    <x-slot name="title">Final Report</x-slot>
    <x-slot name="heading">Final Report</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Department</th>
                                    <th>Date</th>
                                    <th>File Description</th>
                                    <th>Remark</th>
                                    <th>View File</th>
                                    <th>Approved Questions</th>
                                    <th>Unapproved Questions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audits as $audit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $audit->department?->name }}</td>
                                        <td>{{ Carbon\Carbon::parse($audit->date)->format('d-m-Y') }}</td>
                                        <td>{{ Str::limit($audit->description, '85') }}</td>
                                        <td>{{ Str::limit($audit->remark, '85') }}</td>
                                        <td>
                                            <a href="{{ asset($audit->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                        </td>
                                        <td>{{ $audit->approved }}</td>
                                        <td>{{ $audit->unapproved }}</td>
                                    </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-admin.layout>



