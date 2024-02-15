<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=no" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: #f1f1f1;
        }

        table {
            width: 600px;
            background: #fff;
            border-collapse: collapse;
            margin: auto;
        }

        table td {
            text-align: center;
            background: #fff;
            padding: 0 25px 30px;
        }

        img {
            padding: 15px 0;
            width: 100%;
            height: 80px;
            object-fit: contain;
        }

        h4 {
            margin: 20px 0 8px 0;
            color: #000;
            font-size: 22px;
            font-weight: 700;
        }

        h6 {
            margin-top: 0;
            font-size: 12px;
            font-weight: 500;
        }

        p {
            line-height: 1.5;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td>
                <a href="https://rakeshmandal.com" target="_blank">
                    <img src="https://i.ibb.co/PrrYkH1/logo.png" alt="logo-image">
                </a>
            </td>
        </tr>
        <tr>
            <td>
                <h4>Welcome to Nestmart Ecommerce</h4>
                <h6>We are excited to have you back!</h6>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    Hey {{ $data['name'] }},<br>
                    You can securely log in to your Nestmart Ecommerce account using the magic login link below:<br><br>
                    <a href="{{ route('verify.magic.link', ['token' => $data['magicLink']]) }}" target="_blank">Click here to log in with Magic Link</a><br><br>
                    <strong>Important:</strong> Do not share this magic login link with anyone for security purposes.<br><br>
                    Thanks so much,
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
