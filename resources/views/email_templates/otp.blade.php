<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        p {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTP Verification</h2>
        <p>Your One-Time Password (OTP) for Verification</p>
        <p style="font-size: 18px;">Your OTP: <span style="color: #007bff;">{{ $data['otp'] }}</span></p>
        <p>Please use the provided OTP to complete the verification process. This step helps us confirm the authenticity of your account and ensure that only authorized individuals have access.</p>
        <p style="font-size: 18px; color: #007bff;">Please refrain from sharing this OTP with anyone, as it is meant solely for your use. If you didn't request this OTP, or if you have any concerns about the security of your account, please contact our support team immediately.</p>
        <p>Thank you for choosing our services. Your security is our top priority.</p>
    </div>
</body>
</html>
