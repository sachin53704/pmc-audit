
<x-admin.layout>
    <x-slot name="title">Audit Para Department Wise Report</x-slot>
    <x-slot name="heading">Audit Para Department Wise Report</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}

       


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <form method="get" id="serachForm">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <label for="department">Select Department</label>
                                    <select name="department" id="department" class="form-select">
                                        <option value="">All</option>
                                        @foreach($departments as $department)
                                        <option {{ (isset(request()->department) && request()->department == $department->id) ? 'selected' : '' }} value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <label for="from">Select Financial year</label>
                                    <select name="year" id="year" class="form-select">
                                        <option value="">All</option>
                                        @foreach($financialYears as $financialYear)
                                        <option {{ (isset(request()->year) && request()->year == $financialYear->id) ? 'selected' : '' }} value="{{ $financialYear->id }}">{{ $financialYear->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    {{-- <button class="btn btn-primary mt-4">Search</button> --}}
                                    <button type="button" class="btn btn-success mt-4" id="generatePdf">PDF</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- <div class="card-body">
                        <div class="table-responsive">
                            <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Department</th>
                                        <th>Para No</th>
                                        <th>Entry Date</th>
                                        <th>Audit Para Category</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $report->department?->name }}</td>
                                        <td>{{ $report->objection_no }}</td>
                                        <td>{{ date('d-m-Y', strtotime($report->entry_date)) }}</td>
                                        <td>{{ $report->auditParaCategory?->name }}</td>
                                        <td>{{ $report->amount ?? 0 }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>




</x-admin.layout>
<script>
    $(document).ready(function(){
        $('#generatePdf').click(function(){
            
            var url = $('#serachForm').serialize();
            url = "{{ route('report.final-report') }}"+ '?pdf=Yes&'+ url
            window.open(
                url,
                '_blank'
            );

            
        });
    })
</script>
