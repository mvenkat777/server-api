<?php

namespace Platform\Shipments\Mailer;

use Platform\App\Mailer\Mailer;

class ShipmentMailer extends Mailer
{
	// public function paymentLink($user, $data = [])
	// {

	// 	$view = 'emails.payment.link';
	// 	$subject = 'Sourceeasy Payment Link!';

	// 	return $this->sendTo($user, $subject, $view, $data);
	// }

	// public function paymentSuccess($user, $data = [])
	// {
	// 	$view = 'emails.payment.paymentSuccess';
	// 	$subject = 'Sourceeasy Payment Success Recipt ';
	// 	$view1 = 'emails.payment.payment';
	// 	$subject1 = 'Payment Recived';
	// 	$mail = json_encode(['email'=>'sourceeasy-stripe-payments@sourceeasy.com']);
	// 	$this->sendTo($user, $subject, $view, $data);
	// 	return $this->sendTo($mail, $subject1, $view1, $data)
	// }

	// public function paymentFailed($user, $data = [])
	// {

	// 	$view = 'emails.payment.paymentFailed';
	// 	$subject = 'Sourceeasy Payment Failed';

	// 	return $this->sendTo($user, $subject, $view, $data);
	// }

}
