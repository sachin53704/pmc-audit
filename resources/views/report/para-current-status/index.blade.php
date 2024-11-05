<x-admin.layout>
    <x-slot name="title"> @lang('menu.para_current_status_report')</x-slot>
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
                        @foreach($reports as $key => $report)
                        <h3>{{ $key }}</h3>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Financial Year</th>
                                        <th>Total Para Audit</th>
                                        <th>Completed Para Audit</th>
                                        <th>Pending Para Audit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report->groupBy('from_year') as $key => $department)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ $department->sum('sub_unit') }}</td>
                                        <td>{{ $department->sum('completed_sub_unit') }}</td>
                                        <td>{{ $department->sum('pending_sub_unit') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endforeach
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
