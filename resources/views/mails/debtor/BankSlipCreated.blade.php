<!DOCTYPE html>
<html>
    <head>
        <title>Bank Slip Generated for Debt {{ $bankSlip->debt_id }}</title>
    </head>
    <body>
        <h1>Bank Slip Generated for Debt {{ $bankSlip->debt_id }}</h1>
        <p>Dear {{ $bankSlip->debtor_name }},</p>

        <p>We are pleased to inform you that a bank slip has been generated for the payment of your recent transaction. Please find the details below:</p>

        <p><strong>Debt Id:</strong> {{ $bankSlip->debt_id }}</p>
        <p><strong>Due Date:</strong> {{ $bankSlip->due_date->format('d/m/Y') }}</p>
        <p><strong>Amount Due:</strong> R$ {{ number_format($bankSlip->amount, 2, ',', '.') }}</p>

        <p>If you have any questions or need further assistance, please feel free to contact us at {{ config('mail.from.address') }}.</p>

        <p>Thank you for your business!</p>

        <p>Best regards,</p>
        <p>{{ config('app.name') }}</p>
    </body>
</html>
