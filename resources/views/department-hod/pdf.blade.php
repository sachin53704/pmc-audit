<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table border="1" style="width:100%">
        <thead>
            <tr>
                <th>HMM NO.</th>
                <td>{{ date('d-m-Y', strtotime($objection->objection_no)) }}</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th>Entry Date</th>
                <td>{{ date('d-m-Y', strtotime($objection->entry_date)) }}</td>
            </tr>
            <tr>
                <th>Department</th>
                <td>{{ $objection->department?->name }}</td>
            </tr>
            <tr>
                <th>From Year</th>
                <td>{{ $objection->from?->year }}</td>
            </tr>
            <tr>
                <th>To Year</th>
                <td>{{ $objection->to?->year }}</td>
            </tr>
            
            <tr>
                <th>Zone</th>
                <td>{{ $objection->zone?->name }}</td>
            </tr>

            <tr>
                <th>Audit Type</th>
                <td>{{ $objection->auditType?->name }}</td>
            </tr>
            <tr>
                <th>Severity</th>
                <td>{{ $objection->severity?->name }}</td>
            </tr>
            <tr>
                <th>Audit Para Category</th>
                <td>{{ $objection->auditParaCategory?->name }}</td>
            </tr>
            <tr>
                <th>Amount</th>
                <td>{{ $objection->amount }}</td>
            </tr>
            <tr>
                <th>Subject</th>
                <td>{{ $objection->subject }}</td>
            </tr>
        </tbody>
    </table>
    {!! $objection->description !!}
</body>
</html>