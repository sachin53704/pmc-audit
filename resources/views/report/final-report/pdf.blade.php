<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Audit Para Final Report</title>
    <style>
        body {
            font-family: 'freeserif', 'normal';
            padding: 0;
            margin: 0;
        }
        #header table{
            width: 100%;
        }

        #content table,#content td,#content th {
            border: 1px solid;
        }

        #content th{
            font-size: 15px;
        }

        #content table {
            width: 100%;
            border-collapse: collapse;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <section id="header">
        <table>
            <tr>
                <td>
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/admin/images/logo-dark.png'))) }}" style="width: 100px" alt="">
                </td>
                <td align="center">
                    <h2>Panvel Munciple Corporation</h2>
                    <h4>Audit Department</h4>
                    <h4>Audit Para Final Report</h4>
                    <h5>Form Date: {{ (request()->from != "") ? date('d-m-Y', strtotime(request()->from)) : '' }}  To Date: {{ (request()->to) ? date('d-m-Y', strtotime(request()->to)) : '' }}</h5>
                </td>
                <td>
                    <p>Date : {{ date('d-m-Y') }}</p>
                    <p>Time : {{ date('h:i:s A') }}</p>
                </td>
            </tr>
            
        </table>
    </section>

    <section id="content">
        <h6>Department : {{ $department }}</h6>
        
        @php $count = 1; @endphp
        @foreach($reports as $report)
        <section>
            <table>
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Date</th>
                        <th>From Year</th>
                        <th>To Year</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">{{ $report->auditObjection->audit->department->name }}</td>
                        <td align="center">{{ date('d-m-Y', strtotime($report->auditObjection->entry_date)) }}</td>
                        <td align="center">{{ $report->auditObjection->from->name }}</td>
                        <td align="center">{{ $report->auditObjection->to->name }}</td>
                    </tr>
                </tbody>
            </table>
            <div>
                {!! $report->pending_description !!}
            </div>
            <br>
            <table>
                <thead>
                    <tr>
                        <th>Hmm No.</th>
                        <th>Auditor No.</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td align="center">{{ $report->auditObjection->objection_no }}</td>
                        <td align="center">{{ $report->auditObjection->user->auditor_no }}</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <br><br>
        

        @php $count = $count + 1; @endphp
        @endforeach
    </section>
</body>
</html>