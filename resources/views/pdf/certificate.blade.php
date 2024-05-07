<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $documentTitle }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600,700&display=swap" rel="stylesheet">

    <style>
        .montserrat {
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        @page {
            margin: 0px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0px;
        }

        .background {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
        }

        #bridge {
            position: absolute;
            z-index: 10;
            right: 0;
            left: 0;

            /* Setting jarak atas bawah placeholder */
            top: 385px;
            /* Setting jarak atas bawah placeholder */

            /* Setting Font Size */
            font-size: 21px;
            /* Setting Font Size */

            text-align: center;
            font-weight: 600;
            color: #3e3c3d;
        }

        #participant-name {
            position: absolute;
            z-index: 10;
            right: 0;
            left: 0;

            /* Setting jarak atas bawah placeholder */
            top: 315px;
            /* Setting jarak atas bawah placeholder */

            /* Setting Font Size */
            font-size: 36px;
            /* Setting Font Size */

            /* Kalau butuh uppercase */
            /* text-transform: uppercase; */

            text-align: center;
            font-weight: bold;
        }

        #category {
            position: absolute;
            z-index: 10;
            right: 0;
            left: 0;

            /* Setting jarak atas bawah placeholder */
            top: 412.4px;

            /* Setting Font Size */
            font-size: 24px;

            /* Kalau butuh uppercase */
            /* text-transform: uppercase; */

            text-align: center;
            color: #67a3d7;
            font-weight: bold;
        }

        #level {
            position: absolute;
            z-index: 10;
            right: 0;
            left: 0;

            /* Setting jarak atas bawah placeholder */
            top: 447.1px;

            /* Setting Font Size */
            font-size: 21.5px;

            /* Kalau butuh uppercase */
            /* text-transform: uppercase; */

            text-align: center;
            color: #59bf9f;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="content">
        {{-- Certificate Background --}}
        <img src="{{ $certificatePath }}" class="background">
        {{-- End Certificate Background --}}

        {{-- Participant Name --}}
        <p id="participant-name" class="montserrat">{{ $name }}</p>
        {{-- End Participant Name --}}

        {{-- Bridge --}}
        <p id="bridge" class="montserrat">Sebagai</p>
        {{-- End Bridge --}}

        {{-- Category --}}
        <p id="category" class="montserrat">Peserta dalam BUPIN Science Competition</p>
        {{-- End Category --}}

        {{-- Level --}}
        <p id="level" class="montserrat">{{ $level }} {{ $category }}</p>
        {{-- End Level --}}
    </div>
</body>

</html>
