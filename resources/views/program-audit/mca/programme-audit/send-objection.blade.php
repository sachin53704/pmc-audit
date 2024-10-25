<x-admin.layout>
    <x-slot name="title">Send HMM</x-slot>
    <x-slot name="heading">Send HMM</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}


    <div class="row">
        <div class="col-lg-12">
            <form action="" id="addForm" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h5 class="modal-title">Send HMM </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Department</th>
                                        <th>Date</th>
                                        <th>File Description</th>
                                        <th>HMM No.</th>
                                        <th>Subject</th>
                                        <th>Entry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($audits as $audit)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="audit_id" value="{{ $audit->audit_id }}" >
                                                <input type="checkbox" class="form-check-input" name="id[]" value="{{ $audit->id }}" style="font-size: 15px;">
                                            </td>
                                            <td>{{ $audit->department?->name }}</td>
                                            <td>{{ Carbon\Carbon::parse($audit->audit?->date)->format('d-m-Y') }}</td>
                                            <td><span style="cursor: pointer" title="{{ $audit->audit?->description }}">{{ Str::limit($audit->audit?->description, '30') }}</span></td>
                                            <td>{{ $audit->objection_no }}</td>
                                            <td>{{ $audit->subject }}</td>
                                            <td>{{ Carbon\Carbon::parse($audit->entry_date)->format('d-m-Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" align="center"><h4>No Data Found</h4></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if(count($audits) > 0)
                    <div class="card-footer">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
                        <button class="btn btn-primary" type="submit" style="padding: 6px 8px 6px 10px">
                            <i data-feather="send"></i> Send
                        </button>
                    </div>
                    @endif
                </div>

            </form>
        </div>
    </div>


</x-admin.layout>


<script>
    // Submit Objection Form
    $("#addForm").submit(function(e) {
            e.preventDefault();

            var formdata = new FormData(this);

            $.ajax({
                url: '{{ route('objection.store-not-send-objection') }}',
                type: 'POST',
                data: formdata,
                contentType: false,
                processData: false,
                beforeSend: function()
                {
                    $('#preloader').css('opacity', '0.5');
                    $('#preloader').css('visibility', 'visible');
                },
                success: function(data)
                {
                    if (!data.error)
                        swal("Successful!", data.success, "success")
                            .then((action) => {
                                window.location.reload();
                            });
                    else
                        swal("Error!", data.error, "error");
                },
                statusCode: {
                    422: function(responseObject, textStatus, jqXHR) {
                        resetErrors();
                        printErrMsg(responseObject.responseJSON.errors);
                    },
                    500: function(responseObject, textStatus, errorThrown) {
                        swal("Error occured!", "Something went wrong please try again", "error");
                    }
                },
                complete: function() {
                    $('#preloader').css('opacity', '0');
                    $('#preloader').css('visibility', 'hidden');
                },
            });

        });
</script>