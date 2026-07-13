<?php

declare(strict_types=1);

namespace App\Controller;

use Kowts\Sisp\Sisp;
use Symfony\Component\HttpFoundation\Response;

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
