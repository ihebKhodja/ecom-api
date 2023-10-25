<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="flex justify-center m-auto ">
        <h1>
            Page

            <div>

                @foreach ($pizzas as $pizza )
                <div>
                    <span> {{$pizza ->id}}</span>
                    <span> {{$pizza ->name}} </span>
                    <span> {{$pizza->type}} </span>
                    <span> {{$pizza->base}} </span>
                </div>
                @endforeach
            </div>
        </h1>


    </div>
</body>

</html>