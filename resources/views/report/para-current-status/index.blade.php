
<x-admin.layout>
    <x-slot name="title">@lang('menu.para_current_status_report')</x-slot>
    <x-slot name="heading">@lang('menu.para_current_status_report')</x-slot>
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
                                        <th>Sr No.</th>
                                        <th>Department</th>
                                        <th>Subject</th>
                                        <th>HMM No.</th>
                                        <th>Auditor No.</th>
                                        <th>Para No.</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $report?->department?->name }}</td>
                                        <td>{{ $report->subject }}</td>
                                        <td>{{ $report->objection_no }}</td>
                                        <td>{{ $report?->user?->auditor_no }}</td>
                                        <td>{{ $report?->audit->audit_no }}</td>
                                        <td>
                                            @php $count = 1; @endphp
                                            @foreach($report->auditDepartmentAnswers as $auditAnswer)
                                             {{ $count++ . ". " .$auditAnswer->auditor_remark }}<br>
                                            @endforeach

                                            @if($count == 1)
                                            -
                                            @endif
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
        $('#generatePdf').click(function(){
            
            var url = $('#serachForm').serialize();
            url = "{{ route('report.para-current-status-report') }}"+ '?pdf=Yes&'+ url
            window.open(
                url,
                '_blank'
            );

            
        });
    })
</script>
