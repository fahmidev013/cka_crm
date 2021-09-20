<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php $mylink= 'password/resets/'.$data['type'].'/'.$data['token']?>
{{ __('Click here to reset your password') }}: <a href="{{ $link = url($mylink)}}"> {{ $link }} </a>

</body>
</html>
