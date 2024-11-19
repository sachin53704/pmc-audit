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
                                    <select name="department" id="department" class="form-select" required>
                                        <option value="">Select</option>
                                        <option {{ (isset(request()->department) && request()->department == "all") ? 'selected' : '' }} value="all">All</option>
                                        @foreach($departments as $department)
                                        <option {{ (isset(request()->department) && request()->department == $department->id) ? 'selected' : '' }} value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <label for="from">Select From Date</label>
                                    <input type="text" value="{{ (isset(request()->from) && request()->from !="") ? request()->from : '' }}" name="from" class="form-control fdatepicker" id="from" autocomplete="off" placeholder="Select from date" required>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <label for="to">Select To Date</label>
                                    <input type="text" value="{{ (isset(request()->to) && request()->to !="") ? request()->to : '' }}" name="to" class="form-control fdatepicker" autocomplete="off" placeholder="Select from date" id="to" required>
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-6 col-12">
                                    <button class="btn btn-primary mt-4">Search</button>
                                    <button type="button" class="btn btn-success mt-4" id="generatePdf">PDF</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                       
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Financial Year</th>
                                        <th>Total Audit Para</th>
                                        <th>Completed Audit Para</th>
                                        <th>Pending Audit Para</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $key => $report)
                                    @php $count = 0; @endphp
                                    @foreach($report->groupBy('from_year') as $keys => $department)
                                    @if($count == 0)
                                    <tr>
                                        <td style="text-align: center" rowspan="{{ count($report->groupBy('from_year')) }}">{{ $key }}</td>
                                        <td>{{ $keys }}</td>
                                        <td>{{ $department->sum('sub_unit') }}</td>
                                        <td>{{ $department->sum('completed_sub_unit') }}</td>
                                        <td>{{ $department->sum('pending_sub_unit') }}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{ $keys }}</td>
                                        <td>{{ $department->sum('sub_unit') }}</td>
                                        <td>{{ $department->sum('completed_sub_unit') }}</td>
                                        <td>{{ $department->sum('pending_sub_unit') }}</td>
                                    </tr>
                                    @endif
                                    @php $count = $count + 1; @endphp
                                    @endforeach
                                    @empty
                                        <tr>
                                            <th style="text-align: center" colspan="5">No Data Found</th>
                                        </tr>
                                    @endforelse
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
