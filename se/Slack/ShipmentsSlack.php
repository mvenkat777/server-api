<?php
namespace Platform\Slack;

use App\User;
use Carbon\Carbon;

class ShipmentsSlack{

	public function whencreatenewshipment($data){
		// dd("dfghj");
		return "A new shipment ".$data->shipment_type." is created by: ".$this->getUserDetails($data->user_id).
		" on ".Carbon::parse($data->shipped_date)->format('m-d-Y').
		" . For more details please follow link ". $data->shipment_link;
	}

	public function getUserDetails($id){
		$user = User::where('id','=',$id)->first();
		return $user->display_name;
	}
}