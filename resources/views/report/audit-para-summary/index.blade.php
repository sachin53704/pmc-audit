
<x-admin.layout>
    <x-slot name="title">Audit Para Summary Report</x-slot>
    <x-slot name="heading">Audit Para Summary Report</x-slot>
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
                                    <label for="from">Select From Date</label>
                                    <input type="date" value="{{ (isset(request()->from) && request()->from !="") ? request()->from : '' }}" name="from" class="form-control" id="from">
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <label for="to">Select To Date</label>
                                    <input type="date" value="{{ (isset(request()->to) && request()->to !="") ? request()->to : '' }}" name="to" class="form-control" id="to">
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <button class="btn btn-primary mt-4">Search</button>
                                    <button type="button" class="btn btn-success mt-4" id="generatePdf">PDF</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="buttons-datatables" class="table table-bordered nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center">Department</th>
                                        <th colspan="2" style="text-align: center">Total Count of</th>
                                        <th colspan="2" style="text-align: center">Solved Count of</th>
                                        <th colspan="2" style="text-align: center">Pending Count of</th>
                                    </tr>
                                    <tr>
                                        <th>Audit Para</th>
                                        <th>Sub units</th>
                                        <th>Audit Para</th>
                                        <th>Sub units</th>
                                        <th>Audit Para</th>
                                        <th>Sub units</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>{{ $report->name }}</td>
                                        <td>{{ $report->approved_para + $report->pending_para }}</td>
                                        <td>{{ $report->approved_subunit + $report->pending_subunit }}</td>
                                        <td>{{ $report->approved_para }}</td>
                                        <td>{{ $report->approved_subunit ?? 0 }}</td>
                                        <td>{{ $report->pending_para }}</td>
                                        <td>{{ $report->pending_subunit ?? 0 }}</td>
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
        $('#generatePdf').click(function(){
            
            var url = $('#serachForm').serialize();
            url = "{{ route('report.audit-para-summary-report') }}"+ '?pdf=Yes&'+ url
            window.open(
                url,
                '_blank'
            );

            
        });
    })
</script>
