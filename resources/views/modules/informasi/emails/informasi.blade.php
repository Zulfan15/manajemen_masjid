<!DOCTYPE html>
<html>
<body style="font-family: Arial;">

    <h2 style="color: #2d6a4f;">{{ $title }}</h2>

    <p>{!! nl2br(e($content)) !!}</p>

    <p>
        <a href="{{ $url }}" 
           style="background:#2d6a4f;color:white;padding:10px 16px;
                  text-decoration:none;border-radius:6px;">
            Baca Selengkapnya
        </a>
    </p>

    <p>Wassalamualaikum,<br>Masjid Al-Ikhlas</p>

</body>
</html>
