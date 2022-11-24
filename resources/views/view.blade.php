<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<a href="{{route('googleLogin')}}">login with google</a>
<form method="post">
        <select name="partnerId" id="partnerId">
            @foreach($data as $singledata)
                <option>{{$singledata}}</option>
            @endforeach
        </select>
        <input type="date" name="firstdate" id="firstdate">
        <input type="date" name="lastdate" id="lastdate">
        <input type="button" value="submit" id="submit">
    </form>
</body>
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#submit').on('click',function(){
            var firstdate = $('#firstdate').val(); 
            var lastdate = $('#lastdate').val(); 
            var partnerId = $('#partnerId').val(); 

            var url = "{{route('getData')}}?startdate="+firstdate+"&enddate="+lastdate+"&partnerId="+partnerId;
            window.location.href=url;   
        });
    });
</script>
</html>