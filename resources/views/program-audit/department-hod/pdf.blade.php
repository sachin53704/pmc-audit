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
    </style>
</head>
<body>
    @foreach($objections as $objection)
    <table border="1" style="width:100%">
        <thead>
            <tr>
                <td><b>HMM NO.</b></td>
                <td>{{ $objection->objection_no }}</td>
            </tr>
        </thead>
        <tbody>
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
                <td><b>Zone</b></td>
                <td>{{ $objection->zone?->name }}</td>
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
            <tr>
                <td><b>Amount</b></td>
                <td>{{ $objection->amount ?? '-' }}</td>
            </tr>
            <tr>
                <td><b>Subject</b></td>
                <td>{{ $objection->subject }}</td>
            </tr>
        </tbody>
    </table>
    {!! $objection->description !!}
    <div class="page-break"></div>
    @endforeach
</body>
</html>