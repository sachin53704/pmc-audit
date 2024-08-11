<x-admin.layout>
    <x-slot name="title">Department Program Audit Report</x-slot>
    <x-slot name="heading">Department Program Audit Report</x-slot>
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
                                    <th>Name</th>
                                    <th>Approved Questions</th>
                                    <th>Unapproved Questions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($objections as $objection)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $objection->name }}</td>
                                        <td>{{ $objection->approved }}</td>
                                        <td>{{ $objection->unapproved }}</td>
                                    </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')


    @endpush


</x-admin.layout>



