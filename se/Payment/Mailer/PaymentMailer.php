<?php

namespace Platform\Payment\Mailer;

use Platform\App\Mailer\Mailer;

class PaymentMailer extends Mailer
{
	public function paymentLink($user, $data = [])
	{
		$view = 'emails.payment.link';
		$view1 = 'emails.payment.acknowledgement';
		$subject = 'Sourceeasy Payment Link!';
		$subject1 = 'Customer order payment request';
		$mailToPayer =  $this->sendTo($user, $subject, $view, $data);
		$user = \Auth::user();
		$mailToRequestingPerson = $this->sendTo($user, $subject1, $view1, $data);
		return $mailToPayer; 
	}

	public function paymentSuccess($user, $data = [])
	{
		$view = 'emails.payment.paymentSuccess';
		$subject = 'Sourceeasy Payment Success Recipt ';
		$view1 = 'emails.payment.payment';
		$subject1 = 'Payment Received';
		$mail = json_decode(json_encode(['email'=>'sourceeasy-stripe-payments@sourceeasy.com']));
		$this->sendTo($user, $subject, $view, $data);
		$this->sendTo($mail, $subject1, $view1, $data);
		return "Mail Sent Successfully";
	}

	public function paymentFailed($user, $data = [])
	{
		$view = 'emails.payment.paymentFailure';
		$subject = 'Sourceeasy Payment Failed';
		return $this->sendTo($user, $subject, $view, $data);
	}

}
