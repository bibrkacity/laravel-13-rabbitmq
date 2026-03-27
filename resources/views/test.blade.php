<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sending a message to the RabbitMQ queues</title>
</head>
<body>
<h2>Sending a message to the RabbitMQ queues</h2>
@if(session()->has('success'))
    <div style="color: green;">
        {{ session('success') }}
    </div>
@endif
<form action="{{route('send')}}" method="post">
    @csrf
<table>
    <tr>
        <td>Queue Name</td>
        <td>queue_name</td>
    </tr>
    <tr>
        <td>Message</td>
        <td><input type="text" name="message" size="50"></td>
    </tr>
    <tr>
        <td><input type="submit" value="send"></td>
    </tr>
</table>
</form>

</body>
</html>
