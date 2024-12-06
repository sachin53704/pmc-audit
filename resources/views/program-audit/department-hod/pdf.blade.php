<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        body {
            font-family: 'freeserif', 'normal';
            padding: 0;
            margin: 0;
        }

        #customTable {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customTable td, #customTable th {
            border: 1px solid #474646;
            padding: 8px;
        }

        #customTable tr:nth-child(even){background-color: #f2f2f2;}

        #customTable tr:hover {background-color: #ddd;}

        #customTable th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        }
    </style>
</head>
<body>
    @foreach($objections as $key => $objection)
    <table id="customTable" style="width:100%">
        
        <tbody>
            <tr>
                <td style="width: 30%"><b>HMM NO.</b></td>
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
    @endforeach
</body>
</html>