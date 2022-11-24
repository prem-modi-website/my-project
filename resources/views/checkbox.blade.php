<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://phpcoder.tech/multiselect/css/jquery.multiselect.css">

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-2">
        <select name="languageSelect[]" multiple id="languageSelect">
            <option value="C++">C++</option>
            <option value="C#">C#</option>
            <option value="Java">Java</option>
            <option value="Objective-C">Objective-C</option>
            <option value="JavaScript">JavaScript</option>
            <option value="Perl">Perl</option>
            <option value="PHP">PHP</option>
            <option value="Ruby on Rails">Ruby on Rails</option>
            <option value="Android">Android</option>
            <option value="iOS">iOS</option>
            <option value="HTML">HTML</option>
            <option value="XML">XML</option>
        </select>
</div>
</div>
</div>
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- JS & CSS library of MultiSelect plugin -->
<script src="https://phpcoder.tech/multiselect/js/jquery.multiselect.js"></script>

    <script>
        $(function() {
            jQuery('#languageSelect').multiselect({
                    columns: 1,
                    placeholder: 'Select Languages',
                });
    });
    </script>
</body>
</html>