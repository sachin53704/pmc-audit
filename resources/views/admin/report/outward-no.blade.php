<x-admin.layout>
    <x-slot name="title">Outward No Report</x-slot>
    <x-slot name="heading">Outward No Report</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="table table-bordered align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Department</th>
                                    <th>Outward No</th>
                                    <th>Subject</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outwardNos as $outwardNo)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $outwardNo->department?->name }}</td>
                                        <td>{{ str_pad($outwardNo->outward_no, 5,"0",STR_PAD_LEFT); }}</td>
                                        {{-- <td>{{ $outwardNo->outward_no }}</td> --}}
                                        <td>{{ $outwardNo->subject }}</td>
                                    </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-admin.layout>



