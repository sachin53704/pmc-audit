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
                    <h2>पनवेल महानगरपालिका</h2> 
                    <h5>ता. पनवेल, जि. रायगड, पनवेल ४१०२०६</h5> 
                    <h3>अंतर्गत लेखापरिक्षण विभाग</h3>
                </td>
                <td>
                    @for($i=1; $i <= 2; $i++)
                    &nbsp;&nbsp;&nbsp;
                    @endfor
                </td>
            </tr>            
        </table>
        <table>
            <tr>
                <td>पत्र जा. क्र. पमपा/अंतर्गत ले.प.वि./२०२४</td>
                <td>दिनांक / / २०२४</td>
            </tr>
        </table>
    </section>
    <section>
        <p>
            प्रति,<br>
            विभाग प्रमुख,<br>
            ------------------ विभाग,<br>
            पनवेल महानगरपालिका.
        </p>
    </section>

    <section>
        <table>
            <tr>
                <td>विषय -</td>
                <td>सन ------------ ते ------------ या कालावधीतील अंतर्गत लेखा परीक्षण अहवालातील आक्षेपांची पूर्तता करून अनुपालन अहवाल सादर करण्याबाबत.</td>
            </tr>
            <tr>
                <td>संदर्भ -</td>
                <td>लेखापरीक्षण विभागाकडील पत्र जा. क्र. पमपा/अंतर्गत ले.प.वि.- २०२४- दिनांक / /२०२४</td>
            </tr>
        </table>
    </section>

    <section>
        <p>आपल्या विभागाचे सन २०१६-१७ ते २०२१-२२ या कालावधीचे अंतर्गत लेखापरीक्षण दिनांक -- ते या कालावधीत पूर्ण करण्यात आले आहे. सदर अंतर्गत लेखा परीक्षण अहवालो तयार संधिमिकरण करण्यात आले अजून लेखापरीक्षण अहवालास महानगरपालिका स्थायी समिति ठरावानुसार हरिः तार मान्यता देण्यात आली आहे. आपल्या विभागाशी निगडीत.</p>
    </section>

    
</body>
</html>