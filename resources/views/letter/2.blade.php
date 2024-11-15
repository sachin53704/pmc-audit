@include('letter.header')
    <section>
        <p>
            प्रति,<br>
            विभाग प्रमुख,<br>
            {{ $audit->department->name }} विभाग,<br>
            पनवेल महानगरपालिका.
        </p>
    </section>

    <section>
        <table>
            <tr>
                <td style="width: 20%;text-align: right; vertical-align: top;">विषय -</td>
                <td>सन {{ $audit->from->name }} ते {{ $audit->to->name }} या कालावधीतील अंतर्गत लेखा परीक्षण अहवालातील आक्षेपांची पूर्तता करून अनुपालन अहवाल सादर करण्याबाबत.</td>
            </tr>
            <tr>
                <td style="width: 20%;text-align: right; vertical-align: top;">संदर्भ -</td>
                <td>लेखापरीक्षण विभागाकडील पत्र जा. क्र. पमपा/अंतर्गत ले.प.वि.- &nbsp;&nbsp;/&nbsp;&nbsp;/२०२४- दिनांक {{ convertToMarathiNumerals(date('d')) }}/ {{ convertToMarathiNumerals(date('m')) }}/ २०२४</td>
            </tr>
        </table>
    </section>

    <section>
        <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;आपल्या विभागाचे सन {{ $audit->from->name }} ते {{ $audit->to->name }} या कालावधीचे अंतर्गत लेखापरीक्षण दिनांक -------- ते -------- या कालावधीत पूर्ण करण्यात आले आहे. सदर अंतर्गत प्रारूप लेखा परीक्षण अहवाल तयार करण्यात आले आहे.</p>
        <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;सदर लेखापरीक्षणात आपल्या विभागाशी निगडीत आक्षेपांची यादी सोबत जोडली आहे. तरी आपल्या विभागाशी संबंधित आक्षेपांची पूर्तता करून सोबत जोडलेल्या विहित नमुन्यात दोन प्रतीत अनुपालन अहवाल सादर करावा. अनुपालन अहवालासोबत आक्षेपांचे पूर्ततेसंबंधी आवश्यक कागदपत्र-अभिलेखे/दस्तऐवज सादर करावेत.</p>
        <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;सदर पत्र मिळालेच्या दिनांकापासून अनुपालन अहवाल १५ दिवसांचे आत लेखापरीक्षण विभागाकडे सादर करण्यात यावे. सदर विहीत कालावधीत अनुपालन प्राप्त झाले नसल्यास. सदर लेखापरीक्षण अहवाल अंतिम करून स्थायी समितीस मान्यतेस सादर करण्यात येईल.</p>
    </section>

    <section>
        
        <h4 style="text-align: right">
            (निलेश मु. नलावडे) <br> मुख्य लेखापरिक्षक <br> पनवेल महानगरपालिका
        </h4>
    </section>

    <section>
        <p>प्रत - उपायुक्त, ({{ $audit->department->name }} विभाग) पनवेल महानगरपालिका यांना माहिती व आवश्यक कार्यवाहीसाठी</p>
    </section>

    
</body>
</html>