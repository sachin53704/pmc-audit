@include('letter.header')
    <section>
        <p>
            प्रति,<br>
            मा. मुख्य लेखापरीक्षक<br>
            अंतर्गत लेखापरीक्षण विभाग,<br>
            पनवेल महानगरपालिका.
        </p>
    </section>

    <section>
        <table>
            <tr>
                <td style="width: 20%;text-align: right; vertical-align: top;">विषय -</td>
                <td>सन {{ $audit->from->name }} ते {{ $audit->from->name }} या कालावधीतील अंतर्गत लेखा परीक्षण अहवालातील आक्षेपांची पूर्तता करून अनुपालन अहवाल सादर करण्याबाबत.</td>
            </tr>
            <tr>
                <td style="width: 20%;text-align: right; vertical-align: top;">संदर्भ -</td>
                <td>लेखापरीक्षण विभागाकडील पत्र जा. क्र. पमपा/अंतर्गत ले.प.वि.- २०२४- दिनांक {{ convertToMarathiNumerals(date('d')) }}/ {{ convertToMarathiNumerals(date('m')) }}/ २०२४</td>
            </tr>
        </table>
    </section>

    <section>
        <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;आपल्या विभागाकडील उपरोक्त संदर्भिय पत्रान्वये सन {{ $audit->from->name }} ते {{ $audit->from->name }} या कालावधीतील अंतर्गत लेखापरीक्षण अहवालात समाविष्ट करण्यात आलेले एकूण प्रारूप परिच्छेद पूर्तता करण्यासाठी {{ $audit->department->name }} या विभागाकडे सादर करण्यात आले होते.</p>
        <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;सदर प्रारूप परिच्छेदामध्ये घेण्यात आलेल्या आक्षेपांची पूर्तता करण्यात आली असून सोबत विहित नमुन्यात अनुपालन अहवाल दोन प्रतीत सादर केला आहे.</p>
        <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;तरी कृपया अनुपालन मान्य करून सदरचे परिच्छेद वगळण्यास विनंती आहे.</p>
    </section>
    <p>
        सोबत - परिच्छेद क्र. {{ $audit->from->name }} ते {{ $audit->from->name }}
    </p>

    <section>
        
        <h4 style="text-align: right">
            <img src="{{ public_path('storage/'.$signature) }}" style="width: 140px;height: 50px;" alt="">
            <br>
            उपायुक्त <br> {{ $audit->department->name }} विभाग<br> पनवेल महानगर पालिका
        </h4>
    </section>
</div>


    
</body>
</html>