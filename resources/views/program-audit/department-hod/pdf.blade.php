<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg" data-body-image="img-1" data-preloader="enable" data-sidebar-visibility="show" data-layout-style="default" data-layout-width="fluid" data-layout-position="fixed">

    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name') }} | View Details</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />

        <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">
        <!--datatable css-->
        <link rel="stylesheet" href="{{ asset('admin/datatables/1.11.5/css/dataTables.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin/datatables/responsive/2.2.9/css/responsive.bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('admin/datatables/buttons/2.2.2/css/buttons.dataTables.min.css') }}">
        <link href="{{ asset('admin/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('admin/js/layout.js') }}"></script>
        <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet" />
        <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('admin/css/all.min.css') }}" />
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
    </head>

    <body>

        <div class="row d-flex justify-content-center">
            <div class="col-9">
                <div class="card">
                    {{-- <div class="card-header"> --}}
                        {{-- <div class="d-flex justify-content-between">
                            <h4 class="card-title">View Details</h4>
                            <button class="btn btn-primary btn-sm" onclick="printCard()">Print</button>
                        </div> --}}
                    {{-- </div> --}}
                    <div class="card-body">
                        <div class="d-flex justify-content-center">
                            <canvas style="border:1px solid #000" id="pdf-render"></canvas>
                        </div>
                    
                        <div class="page-break mt-5"></div>
                        @php
                            $pdfFile = "";
                        @endphp

                        <div style="padding: 0px 3%;">
                            @foreach($objections as $key => $objection)
                            <table class="table table-bordered table-striped" style="border: 1px solid #000">
                                
                                <tbody>
                                    <tr>
                                        <td style="width: 25%"><b>HMM NO.</b></td>
                                        <td>{{ $objection->objection_no }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Entry Date</b></td>
                                        <td>{{ date('d-m-Y', strtotime($objection->entry_date)) }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Department</b></td>
                                        <td>{{ $objection->department?->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>From Year</b></td>
                                        <td>{{ $objection->from?->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>To Year</b></td>
                                        <td>{{ $objection->to?->name }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>Audit Type</b></td>
                                        <td>{{ $objection->auditType?->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Severity</b></td>
                                        <td>{{ $objection->severity?->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Audit Para Category</b></td>
                                        <td>{{ $objection->auditParaCategory?->name }}</td>
                                    </tr>
                                    @if($objection->amount)
                                    <tr>
                                        <td><b>Amount</b></td>
                                        <td>{{ $objection->amount ?? '-' }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td><b>Subject</b></td>
                                        <td>{{ $objection->subject }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            
                            {!! $objection->description !!}
                            @if(count($objections) != $key + 1)
                            <div class="page-break"></div>
                            @endif
                            <br>
                            <br>
                            @php
                                $pdfFile = $objection->$file;
                            @endphp
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
                    

        <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

        <script>
            const url = "{{ asset('storage/'.$pdfFile) }}";

            // Initialize PDF.js
            const pdfjsLib = window['pdfjs-dist/build/pdf'];

            // Load PDF
            pdfjsLib.getDocument(url).promise.then(pdf => {
                pdf.getPage(1).then(page => {
                    const canvas = document.getElementById('pdf-render');
                    const context = canvas.getContext('2d');
                    const viewport = page.getViewport({ scale: 1.5 });

                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    page.render({
                        canvasContext: context,
                        viewport: viewport
                    });
                });
            });
        </script>
    </body>
</html>