<?php
use yupe\components\controllers\FrontController;

/**
 * Class PaymentController
 */
class PaymentController extends FrontController
{
    /**
     * @param null $id
     * @throws CException
     * @throws CHttpException
     */
    public function actionProcess($id = null)
    {
        /* @var $payment Payment */
        $payment = Payment::model()->findByPk($id);

        if (null === $payment && !$payment->module) {
            throw new CHttpException(404);
        }

        /** @var PaymentSystem $paymentSystem */
        if ($paymentSystem = Yii::app()->paymentManager->getPaymentSystemObject($payment->module)) {
            $result = $paymentSystem->processCheckout($payment, Yii::app()->getRequest());
            if ($result instanceof Order) {
                $this->redirect(['/order/order/view', 'url' => $result->url]);
            }
        }
    }
}
