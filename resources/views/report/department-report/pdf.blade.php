<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Audit Para Summary Report</title>
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
                    <h4>Audit Para Summary Report</h4>
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
        <table>
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Department</th>
                    <th>Para No</th>
                    <th>Entry Date</th>
                    <th>Audit Para Category</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $report->department?->name }}</td>
                    <td>{{ $report->objection_no }}</td>
                    <td>{{ date('d-m-Y', strtotime($report->entry_date)) }}</td>
                    <td>{{ $report->auditParaCategory?->name }}</td>
                    <td>{{ $report->amount ?? 0 }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</body>
</html>