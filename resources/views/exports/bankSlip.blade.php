<!DOCTYPE html>
<html>
<head>
    <title>Bank Slip Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 5px 0;
        }
        .content {
            margin: 20px 0;
        }
        .content .section {
            margin-bottom: 20px;
        }
        .content .section h2 {
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details th, .details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details th {
            background-color: #f4f4f4;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <p>Bank Slip</p>
        </div>

        <div class="content">
            <div class="section">
                <h2>Debtor Information</h2>
                <p><strong>Name:</strong> {{ $bankSlip->debtor_name }}</p>
                <p><strong>Government Id:</strong> {{ $bankSlip->debtor_government_id }}</p>
                <p><strong>Email:</strong> {{ $bankSlip->debtor_email }}</p>
            </div>

            <div class="section">
                <h2>Bank Slip Details</h2>
                <table class="details">
                    <tr>
                        <th>Debt Id</th>
                        <td>{{ $bankSlip->debt_id }}</td>
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        <td>{{ $bankSlip->due_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <th>Amount Due</th>
                        <td>R$ {{ number_format($bankSlip->amount, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            <p>If you have any questions or need further assistance, please contact us at {{ config('mail.from.address') }}.</p>
            <p>Thank you for your business!</p>
            <p>Best regards,</p>
            <p>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
