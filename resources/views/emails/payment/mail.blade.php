@extends('emails.payment.masterPayment')
@section('body')
        <?php echo \Session::get('content'); ?>
@stop

