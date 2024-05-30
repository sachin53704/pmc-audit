<x-admin.layout>
    <x-slot name="title">Confirm Page View</x-slot>
    <x-slot name="heading">Confirm Page View</x-slot>
    {{-- <x-slot name="subheading">Test</x-slot> --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body my-5">
                    <div class="row my-5">
                        <div class="col-6 text-center">
                            <a href="{{ route('confirm-login-type', 1) }}" class="btn btn-primary btn-lg p-4">Programme Audit</a>
                        </div>
                        <div class="col-6 text-center">
                            <a href="{{ route('confirm-login-type', 2) }}" class="btn btn-primary btn-lg p-4">Receipt & Payment Verification</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    @push('scripts')
    @endpush

</x-admin.layout>
