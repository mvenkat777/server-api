<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Payment Receipt Link</title>
</head>
<body>
<div style="font:14px/1.4285714 Arial,sans-serif;color:#333;max-width:920px;">
    <table style="width:100%;border-collapse:collapse">
        <tbody>
        <tr>
            <td style="background:#e0e0e0;padding:10px 10px 0;font:14px/1.4285714 Arial,sans-serif">
                <table style="width:100%;border-collapse:collapse">
                    <tbody>
                    <tr>
                        <td style="font:14px/1.4285714 Arial,sans-serif;padding:0;background-color:#ffffff;border-radius:5px">
                            <div style="border:1px solid #cccccc;border-radius:5px;padding:20px">
                                <table style="width:100%;border-collapse:collapse">
                                    <tbody>
                                    <tr>
                                        <td style="font:14px/1.4285714 Arial,sans-serif;padding:0">
                                            <table style="width:100%;border-collapse:collapse">
                                                <tbody>
                                                <tr>
                                                    <td style="font:14px/1.4285714 Arial,sans-serif;padding:0 0 0 10px">
                                                        <table style="width:100%;border-collapse:collapse">
                                                            <tbody>
                                                            <tr>
                                                                <td style="font:14px/1.4285714 Arial,sans-serif;padding:0;line-height:1">
                                                                    <h3>Thank you for your payment! Sourceeasy Invoice of {{$data->product_name}} of ${{$data->amount}}</h3>

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font:14px/1.4285714 Arial,sans-serif;padding:5px 0 0;line-height:1.2">
                                                                    <p style="font-size:16px;">Dear {{$data->name}},<br/><br/>
                                                                        Thank you for your recent payment of ${{$data->amount}} for {{$data->product_name}}! We appreciate your business. </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font:14px/1.4285714 Arial,sans-serif;padding:10px 0 20px">
                                                                    <table style="width:100%;border-collapse:collapse">
                                                                        <tbody>
                                                                        <tr>
                                                                        	<td style="width:25%"></td>
                                                                            <td style="text-align:left;font-weight:bold;padding:5px;width:25%;">
                                                                                Receipt No :
                                                                            </td>
                                                                            <td style="padding:5px;width:25%;">
                                                                                {{$data->id}}
                                                                            </td>
                                                                            <td style="width:25%"></td>
                                                                        </tr>
                                                                        <tr>
                                                                        	<td style="width:25%"></td>
                                                                            <td style="text-align:left;font-weight:bold;padding:5px;width:25%;">
                                                                               Payment received on :
                                                                            </td>
                                                                            <td style="padding:5px;width:25%;">
                                                                                 {{$data->updated_at}}
                                                                            </td>
                                                                            <td style="width:25%"></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><p style="font-size:16px;">If you have any questions or comments, please contact your Sourceeasy representative({{$data->sender_email}}). </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div style="margin-top:5px;">
                                                                        <span style="font-weight:bold;">Sincerely,<br> 
                                                                        <span style="font-size:15px;">The Sourceeasy Team</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="color:#707070">
                            <table style="width:100%;border-collapse:collapse">
                                <tbody>
                                <tr>

                                    <td style="padding:0">
                                        <table>
                                        	<tbody>
                                        		<tr>
                                        			<td style="width:25%"></td>
                                        			<td style="width:25%"></td>
                                        			<td style="width:25%"></td>
                                        			<td style="width:25%">
                                        				 <img src="https://sourceeasycdn.s3.amazonaws.com/www/img/logo-blue-trans.png"
                                                 			alt="sourceeasy" height="40px" />	
                                        			</td>
                                        		</tr>
                                        	</tbody>
                                        </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>


