<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
<div>
    <h4>Final decision on {{$form_type['type']}} No {{$form_id}}</h4>
</div>
<hr>
<div>
    <h6>Dear Mr/Mrs. {{ $created_by_user['name']}} ,</h6>
</div>
<div>
    <h6>The {{$form_type['type']}} application made on the {{ date('d-m-Y',strtotime($created_at)) }} has been {{$status['type']}} by the {{$Activity_organization['title']}} </h6>
    <h6>A pdf of the application and other supporting documents has been attached to this email</h6>
</div>
</body>
</html>