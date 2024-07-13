<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

/**
 * @method static string AwaitingPayment()
 * @method static string Paid()
 * @method static string Canceled()
 */
enum BankSlipStatus: string
{
    use InvokableCases;
    use Values;

    case AwaitingPayment = 'awaiting_payment';
    case Paid = 'paid';
    case Canceled = 'canceled';
}
