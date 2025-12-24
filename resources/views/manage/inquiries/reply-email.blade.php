@extends('oxygen::layouts.master-dashboard')

 <?php
$pageTitle='Inquiry Reply'
?>

@section ('content')
{{ lotus()->pageHeadline($pageTitle) }}

<form action="{{ entity_resource_path() }}" method="post" class="form-horizontal" enctype="multipart/form-data">
    {{ csrf_field() }}




    <div class="form-group row mx-auto">
        <div class="col-md-3 text-left text-md-right">
            <label for="email">Email</label>
        </div>
        <div class="col-md-7">
            <input type="text" value="{{$entity->email}}" name="email" class="form-control">
        </div>
    </div>

    <div class="form-group row mx-auto">
        <div class="col-md-3 text-left text-md-right">
            <label for="location">Reply</label>
        </div>
        <div class="col-md-7">
            <textarea name="message" class="form-control" rows="5"></textarea>
        </div>
    </div>

	<div class="form-group row mx-auto">
        <div class="col-md-7">
           <center> <button type="submit"  class="btn btn-primary mt-3 btn-md mx-2">Send</button></center>
        </div>
    </div>
</form>

@stop
