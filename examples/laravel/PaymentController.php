<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Kowts\Sisp\Sisp;

final class PaymentController
{
    public function __invoke(Sisp $sisp): Response
    {
        $request = $sisp->createPayment([
            'amount' => '1500',
            'merchantRef' => 'R'.date('YmdHis'),
            'merchantSession' => 'S'.date('YmdHis'),
        ]);

        return new Response($sisp->renderPaymentForm($request));
    }
}
