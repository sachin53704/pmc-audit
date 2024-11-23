<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Audit Para Current Status</title>
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
       
        <table>
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
                    <td>{{ $department->sum('pending_sub_unit') - $department->sum('submit_compliance') }}</td>
                    <td>{{ $department->sum('completed_sub_unit') }}</td>
                    <td>{{ $department->sum('pending_sub_unit') }}</td>
                </tr>
                @else
                <tr>
                    <td>{{ $keys }}</td>
                    <td>{{ $department->sum('sub_unit') }}</td>
                    <td>{{ $department->sum('pending_sub_unit') - $department->sum('submit_compliance') }}</td>
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
    </section>
</body>
</html>