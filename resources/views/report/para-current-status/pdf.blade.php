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
        <h6>Department : {{ $department }}</h6>
        <table>
            <thead>
                <tr>
                    <th>Sr No.</th>
                    <th>Department</th>
                    <th>Subject</th>
                    <th>HMM No.</th>
                    <th>Auditor No.</th>
                    <th>Para No.</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reports as $report)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $report?->department?->name }}</td>
                    <td>{{ $report->subject }}</td>
                    <td>{{ $report->objection_no }}</td>
                    <td>{{ $report?->user?->auditor_no }}</td>
                    <td>{{ $report?->audit->audit_no }}</td>
                    <td>
                        @php $count = 1; @endphp
                        @foreach($report->auditDepartmentAnswers as $auditAnswer)
                            {{ $count++ . ". " .$auditAnswer->auditor_remark }}<br>
                        @endforeach

                        @if($count == 1)
                        -
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</body>
</html>