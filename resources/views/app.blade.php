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
            'active': @json($active),
            'day_smena': @json($day_smena),
            'SMENA_DAY_JOB': @json(env('SMENA_DAY_JOB')), 
            'night_smena': @json($night_smena),
            'SMENA_NIGHT_JOB': @json(env('SMENA_NIGHT_JOB')),
            'table_link': @json($table_link),
            'excavators': @json($excavators),
            'tos': @json($tos),
            'pistali_frontals': @json($pistali_frontals),
            'pistali_mans': @json($pistali_mans),
            'frontend_frontals': @json($frontend_frontals),
            'frontend_mans': @json($frontend_mans),
            'gusaks': @json(env('GUSAKS_GROUPID')),
            'user_ip': @json($user_ip),
            'only_myip': @json($only_myip),
            'excavator_page': @json( env('EXCAVATORPAGE')),
            'DUMPTRUCKS': @json(env('DUMPTRUCKS')),
            'WATERTRUCKS': @json(env('WATERTRUCKS')),
            'API_LINK': @json(env('API_LINK')),
            'CAREER_ID': @json(env('CAREER_ID')),
        }
    </script>
    @vite('resources/ts/app.ts')
</head>

<body id="app"></body>
</html>