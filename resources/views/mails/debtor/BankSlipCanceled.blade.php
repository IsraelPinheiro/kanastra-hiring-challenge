<!DOCTYPE html>
<html>
    <head>
        <title>Bank Slip Cancelation for Debt {{ $bankSlip->debt_id }}</title>
    </head>
    <body>
        <h1>Bank Slip Cancelation for Debt {{ $bankSlip->debt_id }}</h1>
        <p>Dear {{ $bankSlip->debtor_name }},</p>

        <p>We'd like to inform you that the bank slip generated for your recent transaction has been cancelled. Please find the details below:</p>

        <p><strong>Debt Id:</strong> {{ $bankSlip->debt_id }}</p>
        <p><strong>Due Date:</strong> {{ $bankSlip->due_date->format('d/m/Y') }}</p>
        <p><strong>Amount:</strong> R$ {{ number_format($bankSlip->amount, 2, ',', '.') }}</p>

        <p>If you have any questions or need further assistance, please feel free to contact us at {{ config('mail.from.address') }}.</p>

        <p>We apologize for any inconvenience this may cause.</p>

        <p>Best regards,</p>
        <p>{{ config('app.name') }}</p>
    </body>
</html>
