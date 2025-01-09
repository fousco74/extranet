<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="//code.iconify.design/1/1.0.6/iconify.min.js"></script>
    @vite('resources/js/app.js')
    @inertiaHead
    @routes
    @vite('resources/css/app.css') 
</head>
<body class="max-sm p-o m-0">
    @inertia
</body>
</html>