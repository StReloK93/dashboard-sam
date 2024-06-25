<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/awesome/css/all.min.css">
    <title>Avtoag'dargichlar yagona oynasi</title>
    <script>
        var settings = {
            'oil': @json($oil),
            'smena': @json($smena),
            'uat': @json($uat),
            'day_smena': @json($day_smena),
            'day_smena_job': @json($day_smena_job),
            'night_smena': @json($night_smena),
            'night_smena_job': @json($night_smena_job),
            'table_link': @json($table_link),
        }
    </script>
    @vite('resources/ts/app.ts')
</head>

<body id="app"></body>

</html>