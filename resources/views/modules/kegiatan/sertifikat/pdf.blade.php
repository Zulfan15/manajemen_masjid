<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat - {{ $sertifikat->nomor_sertifikat }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        .certificate {
            width: 297mm;
            height: 210mm;
            padding: 30mm;
            box-sizing: border-box;
            position: relative;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .border {
            border: 8px double #2c5282;
            padding: 20px;
            height: 100%;
            position: relative;
        }
        
        .inner-border {
            border: 2px solid #2c5282;
            padding: 30px;
            height: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .logo {
            margin-bottom: 20px;
        }
        
        .header {
            margin-bottom: 30px;
        }
        
        h1 {
            font-size: 48px;
            color: #2c5282;
            margin: 0;
            font-weight: bold;
            letter-spacing: 3px;
        }
        
        .subtitle {
            font-size: 18px;
            color: #4a5568;
            margin-top: 10px;
        }
        
        .content {
            margin: 40px 0;
        }
        
        .intro-text {
            font-size: 16px;
            margin-bottom: 20px;
            color: #2d3748;
        }
        
        .participant-name {
            font-size: 36px;
            font-weight: bold;
            color: #1a202c;
            margin: 20px 0;
            text-decoration: underline;
            text-underline-offset: 8px;
        }
        
        .achievement-text {
            font-size: 16px;
            color: #2d3748;
            margin: 20px 0;
            line-height: 1.6;
        }
        
        .event-name {
            font-size: 22px;
            font-weight: bold;
            color: #2c5282;
            margin: 10px 0;
        }
        
        .event-details {
            font-size: 14px;
            color: #4a5568;
            margin: 15px 0;
        }
        
        .footer {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 20px;
        }
        
        .signature-line {
            border-top: 2px solid #2c5282;
            width: 200px;
            margin: 60px auto 10px;
        }
        
        .signature-name {
            font-size: 16px;
            font-weight: bold;
            color: #1a202c;
        }
        
        .signature-title {
            font-size: 14px;
            color: #4a5568;
        }
        
        .certificate-number {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            color: #718096;
        }
        
        .ornament {
            color: #2c5282;
            font-size: 24px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border">
            <div class="inner-border">
                <div class="logo">
                    <div style="font-size: 32px; color: #2c5282;">🕌</div>
                </div>
                
                <div class="header">
                    <h1>SERTIFIKAT</h1>
                    <div class="subtitle">Certificate of Participation</div>
                    <div class="ornament">✦ ✦ ✦</div>
                </div>
                
                <div class="content">
                    <div class="intro-text">
                        Dengan ini menyatakan bahwa:
                    </div>
                    
                    <div class="participant-name">
                        {{ $sertifikat->nama_peserta }}
                    </div>
                    
                    <div class="achievement-text">
                        Telah mengikuti kegiatan:
                    </div>
                    
                    <div class="event-name">
                        {{ $sertifikat->nama_kegiatan }}
                    </div>
                    
                    <div class="event-details">
                        Tanggal: {{ \Carbon\Carbon::parse($sertifikat->tanggal_kegiatan)->translatedFormat('d F Y') }}<br>
                        Tempat: {{ $sertifikat->tempat_kegiatan }}
                    </div>
                    
                    <div class="achievement-text">
                        dengan baik dan penuh tanggung jawab.
                    </div>
                </div>
                
                <div class="footer">
                    <div class="signature">
                        <div style="text-align: center; margin-bottom: 10px;">
                            <small style="color: #718096;">Dikeluarkan pada:</small><br>
                            {{ \Carbon\Carbon::parse($sertifikat->created_at)->translatedFormat('d F Y') }}
                        </div>
                        <div class="signature-line"></div>
                        <div class="signature-name">{{ $sertifikat->ttd_pejabat }}</div>
                        <div class="signature-title">{{ $sertifikat->jabatan_pejabat }}</div>
                    </div>
                </div>
                
                <div class="certificate-number">
                    No: {{ $sertifikat->nomor_sertifikat }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
