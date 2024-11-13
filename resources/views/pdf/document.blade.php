<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $name }}</title>
    <style>
        table, td, th {
            border: 1px solid;
        }
        table {
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table style="width: 100%">
        <thead>
            <tr>
                <th>Hmm No</th>
                <th>Entry Date</th>
                <th>Department</th>
                <th>Zone</th>
                <th>From Year</th>
                <th>To Year</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $objectionNo }}</td>
                <td>{{ $entryDate }}</td>
                <td>{{ $department }}</td>
                <td>{{ $zone }}</td>
                <td>{{ $from }}</td>
                <td>{{ $to }}</td>
            
            </tr>
        </tbody>
    </table>
    <br>
    {!! $data->$column !!}
</body>
</html>