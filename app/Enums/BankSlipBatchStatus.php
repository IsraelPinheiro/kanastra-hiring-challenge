<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

/**
 * @method static string Pending()
 * @method static string Processing()
 * @method static string Processed()
 * @method static string Failed()
 * @method static string Canceled()
 */
enum BankSlipBatchStatus: string
{
    use InvokableCases;
    use Values;

    case Pending = 'pending';
    case Processing = 'processing';
    case Processed = 'processed';
    case Failed = 'failed';
    case Canceled = 'canceled';
}
