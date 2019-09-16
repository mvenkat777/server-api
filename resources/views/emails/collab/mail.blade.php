@extends('emails.collab.masterCollab')
@section('body')
        <?php echo \Session::get('content'); ?>
@stop

