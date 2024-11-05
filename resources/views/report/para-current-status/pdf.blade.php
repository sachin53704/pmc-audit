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
                    <h5>
                        @if((request()->from != "") && (request()->to != ""))
                        Form Date: {{ (request()->from != "") ? date('d-m-Y', strtotime(request()->from)) : '' }}  To Date: {{ (request()->to) ? date('d-m-Y', strtotime(request()->to)) : '' }}
                        @endif
                    </h5>
                </td>
                <td>
                    <p>Date : {{ date('d-m-Y') }}</p>
                    <p>Time : {{ date('h:i:s A') }}</p>
                </td>
            </tr>
            
        </table>
    </section>

    <section id="content">
        @foreach($reports as $key => $report)
        <h3>{{ $key }}</h3>
        <table>
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
                    <td align="center">{{ $key }}</td>
                    <td align="center">{{ $department->sum('sub_unit') }}</td>
                    <td align="center">{{ $department->sum('completed_sub_unit') }}</td>
                    <td align="center">{{ $department->sum('pending_sub_unit') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach
    </section>
</body>
</html>