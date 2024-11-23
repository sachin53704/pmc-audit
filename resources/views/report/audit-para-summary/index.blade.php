
<x-admin.layout>
    <x-slot name="title">@lang('menu.programme_audit_para_summary')</x-slot>
    <x-slot name="heading">@lang('menu.programme_audit_para_summary')</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}

       


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <form method="get" id="serachForm">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <label for="department">Select Department <span class="text-danger">*</span></label>
                                    <select name="department" id="department" class="form-select">
                                        <option value="">All</option>
                                        @foreach($departments as $department)
                                        <option {{ (isset(request()->department) && request()->department == $department->id) ? 'selected' : '' }} value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <label for="from">Select From Date <span class="text-danger">*</span></label>
                                    <input type="text" required value="{{ (isset(request()->from) && request()->from !="") ? request()->from : '' }}" name="from" class="form-control fdatepicker" id="from" autocomplete="off" placeholder="Select from date">
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <label for="to">Select To Date <span class="text-danger">*</span></label>
                                    <input type="text" required value="{{ (isset(request()->to) && request()->to !="") ? request()->to : '' }}" name="to" class="form-control fdatepicker" id="to" autocomplete="off" placeholder="Select to date">
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
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $report?->auditObjection?->department?->name }}</td>
                                        <td>{{ $report->auditObjection?->subject }}</td>
                                        <td>{{ $report->auditObjection?->objection_no }}</td>
                                        <td>{{ $report->auditObjection?->user?->auditor_no }}</td>
                                        <td>
                                            {!! $report->pending_description !!}
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
            
            var from = $('#from').val();
            var to = $('#to').val();
            if(from == ""){
                alert('Please select from date');
                return false;
            }

            if(to == ""){
                alert('Please select to date');
                return true;
            }

            var url = $('#serachForm').serialize();
            url = "{{ route('report.audit-para-summary-report') }}"+ '?pdf=Yes&'+ url
            window.open(
                url,
                '_blank'
            );

            
        });
    })
</script>