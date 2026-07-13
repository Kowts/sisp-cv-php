<?php

declare(strict_types=1);

namespace app\controllers;

use yii\web\Controller;
use yii\web\Response;

final class PaymentController extends Controller
{
    public function actionCreate(): Response
    {
        $request = \Yii::$app->sisp->createPayment([
            'amount' => '1500',
            'merchantRef' => 'R'.date('YmdHis'),
            'merchantSession' => 'S'.date('YmdHis'),
        ]);

        return \Yii::$app->response->sendContentAsFile(
            \Yii::$app->sisp->renderPaymentForm($request),
            'sisp-payment.html',
            ['mimeType' => 'text/html', 'inline' => true]
        );
    }
}
