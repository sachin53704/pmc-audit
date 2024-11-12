@php
    function convertToMarathiNumerals($input) {
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $marathiDigits = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
        
        // Replace each English digit with its Marathi equivalent
        return str_replace($englishDigits, $marathiDigits, $input);
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Letter</title>
    <style>
        body {
            font-family: 'freeserif', 'normal';
            padding: 0;
            margin: 0;
            font-size: 16px;
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
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/admin/images/logo-dark.png'))) }}" style="width: 100px; margin-left:18px" alt="">
                </td>
                <td align="right">
                    <div>
                        <h2>पनवेल महानगरपालिका &nbsp;&nbsp;</h2> 
                        <h4>ता. पनवेल, जि. रायगड, पनवेल ४१०२०६</h4> 
                        <h3>अंतर्गत लेखापरिक्षण विभाग&nbsp;&nbsp;&nbsp;&nbsp;</h3>
                    </div>
                </td>
                <td>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
            </tr>            
        </table>
        <hr>

        <table>
            <tr>
                <td>
                    पत्र जा. क्र. पमपा/अंतर्गत ले.प.वि./&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/२०२४
                </td>
                <td align="right">
                    दिनांक&nbsp;&nbsp;{{ convertToMarathiNumerals(date('d')) }}/ {{ convertToMarathiNumerals(date('m')) }}/ २०२४</p>
                </td>
            </tr>            
        </table>
    </section>