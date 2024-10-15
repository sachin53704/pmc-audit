<x-admin.layout>
    <x-slot name="title">Complience Report</x-slot>
    <x-slot name="heading">Complience Report</x-slot>
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
                                    <th>Approved Objection</th>
                                    <th>Action</th>
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
                                        <td>
                                            <a href="javascript::void(0)" data-id="{{ $audit->id }}" class="btn btn-sm btn-primary approveQuestion"  data-bs-toggle="modal" data-bs-target=".questionStatusModel">Approve Objection</a>
                                        </td>
                                    </tr>
                                @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- view model --}}
    <div class="modal fade questionStatusModel" tabindex="-1" role="dialog" aria-labelledby="questionStatusModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="questionStatusModelLabel">Complience Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card-body" id="answerReportDetails">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sr no.</th>
                                    <th>Objection No</th>
                                    <th>Auditor No</th>
                                    <th>Objection</th>
                                    <th>Compliance</th>
                                </tr>
                            </thead>
                            <tbody id="responseQuestion">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    {{-- end off view model --}}


    @push('scripts')
        <script>
            $(document).ready(function(){
                $('body').on('click', '.approveQuestion', function(e){
                    e.preventDefault();

                    let id = $(this).attr('data-id');
                    $.ajax({
                        url: "{{ route('report-get-response-question') }}",
                        type: 'POST',
                        data: {
                            '_token': "{{ csrf_token() }}",
                            status: '1',
                            id: id
                        },
                        beforeSend: function()
                        {
                            $('#preloader').css('opacity', '0.5');
                            $('#preloader').css('visibility', 'visible');
                        },
                        success: function(data, textStatus, jqXHR) {
                            let html = "";
                            let count = 1;
                            $.each(data.objection, function(key, val){
                                html += `<tr>
                                    <td>${count}</td>
                                    <td>${(val.objection_no) ? val.objection_no : '-'}</td>
                                    <td>${(val.user.auditor_no) ? val.user.auditor_no : '-'}</td>
                                    <td>${(val.objection) ? val.objection : '-'}</td>
                                    <td>${(val.answer) ? val.answer : '-'}</td>
                                </tr>`;
                                count++;
                            })
                            $('#responseQuestion').html(html)                            
                        },
                        error: function(error, jqXHR, textStatus, errorThrown) {
                            alert("Some thing went wrong");
                        },
                        complete: function() {
                            $('#preloader').css('opacity', '0');
                            $('#preloader').css('visibility', 'hidden');
                        },
                    });
                });
            });
        </script>
    @endpush


</x-admin.layout>



