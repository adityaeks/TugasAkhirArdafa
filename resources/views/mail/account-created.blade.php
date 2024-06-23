<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            padding: 20px;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
        }

        .header {
            font-size: 24px;
            color: #444;
        }

        .content {
            margin-top: 20px;
            line-height: 1.6;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #aaa;
        }

        .welcome-button-container {
            text-align: center;
            /* Mengatur konten agar berada di tengah */
        }

        .welcome-button {
            background-color: #e64946;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            /* Tetap inline-block agar lebar sesuai dengan konten */
            font-size: 14px;
            margin: 10px auto;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .welcome-button:hover {
            background-color: #a63c3c;
            /* Warna merah yang lebih gelap */
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            Selamat Datang di UMKM Lowayu!
        </div>
        <div class="content">
            Halo, <strong>{{ $name }}</strong>!<br>
            Terima kasih telah memilih untuk berbelanja di UMKM Lowayu. Kami bangga dapat menyajikan produk-produk
            berkualitas tinggi yang dibuat langsung oleh tangan-tangan terampil pengrajin lokal kami. Setiap pembelian
            Anda membantu mendukung dan mengembangkan ekonomi lokal serta memberdayakan para pelaku UMKM di
            Lowayu.
            <div class="welcome-button-container">
                <a href="http://127.0.0.1:8000" class="welcome-button">Kunjungi UMKM Lowayu</a>
            </div>
            Nikmati berbagai penawaran eksklusif dan produk unik yang hanya bisa Anda temukan di sini. Jangan lupa untuk
            mengikuti kami di media sosial untuk mendapatkan informasi terbaru tentang produk dan promo kami!
            <br><br>
        </div>
        <div class="footer">
            UMKM Lowayu - Membangun Bersama Komunitas Lokal
        </div>
    </div>
</body>

</html>
