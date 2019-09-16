<?php

namespace Platform\Message;

class PaymentMessage {

	/*
	 * Should be not more than 160 characters
	 *
	 */
	
	public function whenmakenewpayment($data){
		return "Thank you for your order at (sourceeasy.com). We have received your payment";
	}

	public function whenrequestwassuccessful($data){
		return "Payment Received by (sourceeasy.com).";
	}

	public function whenrequestwasfailed($data){
		return "Payment Failed (sourceeasy.com). Please try again";
	}
}