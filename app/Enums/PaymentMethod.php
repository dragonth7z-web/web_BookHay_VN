<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case COD          = 'cod';
    case VNPay        = 'vnpay';
    case Momo         = 'momo';
    case BankTransfer = 'bank_transfer';

    public function defaultPaymentStatus(): PaymentStatus
    {
        return $this === self::COD
            ? PaymentStatus::Unpaid
            : PaymentStatus::Paid;
    }
}
