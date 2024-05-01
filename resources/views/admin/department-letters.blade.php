<x-admin.layout>
    <x-slot name="title">Received Letters</x-slot>
    <x-slot name="heading">Received Letters</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}



        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="">
                                    <button id="addToTable" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                    <button id="btnCancel" class="btn btn-danger" style="display:none;">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
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
                                        <th>View Audit File</th>
                                        <th>Status</th>
                                        <th>View Letter</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($audits as $audit)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $audit->department?->name }}</td>
                                            <td>{{ Carbon\Carbon::parse($audit->date)->format('d m Y') }}</td>
                                            <td>{{ Str::limit($audit->description, '85') }}</td>
                                            <td>{{ Str::limit($audit->remark, '85') }}</td>
                                            <td>
                                                <a href="{{ asset($audit->file_path) }}" target="_blank" class="btn btn-primary btn-sm">View File</a>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $audit->status_name }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ asset($audit->dl_file_path) }}" target="_blank" class="btn btn-primary btn-sm">View Letter</a>
                                            </td>
                                            <td>{{ Str::limit($audit->dl_description, '85') }}</td>
                                            <td>
                                                @if($audit->status == 3 || $audit->status == 1)
                                                    <button class="btn btn-secondary edit-element px-2 py-1" title="Edit audit" data-id="{{ $audit->id }}"><i data-feather="edit"></i></button>
                                                @endif
                                                @if($audit->status == 1)
                                                    <button class="btn btn-danger rem-element px-2 py-1" title="Delete audit" data-id="{{ $audit->id }}"><i data-feather="trash-2"></i> </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



</x-admin.layout>
