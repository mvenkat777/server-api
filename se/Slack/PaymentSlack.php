<?php

namespace Platform\Slack;

class PaymentSlack {

	public function whenmakenewpayment($data){
		return "A payment has been created for you (".$data->email.") by (".$data->sender_email."). 
		Please click on the link to get redirected for payment ". $data->product_link	;
	}

	public function whenrequestwassuccessful($data){
		return "Thankyou for your order at Sourceeasy. We Have received your payment and now we will start 
				processing your product.";
	}

	public function whenrequestwasfailed($data){
		return "Thankyou for your order at Sourceeasy. Payment Processing failed due to problem from our side.
		Please make the process again. Click the link to get redirected ". $data->product_link;
	}
}