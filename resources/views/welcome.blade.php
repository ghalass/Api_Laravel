<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>API</title>
</head>

<body class="">

</body>
<h1 class="text-center">Welcome API Home Page</h1>

@foreach ($routeCollection as $value)
    @if (str_starts_with($value->uri(), 'api/'))
        @if ($value->methods()[0] === 'GET')
            <a href={{ $value->uri() }}>
                <div>
                    <span>{{ $value->methods()[0] }}</span> |
                    <span>{{ $value->uri() }}</span>
                    {{-- <span>{{ $value->getName() }}</span> | --}}
                    {{-- <span>{{ $value->getActionName() }}</span> --}}
                </div>
            </a>
        @else
            <div>
                <span>{{ $value->methods()[0] }}</span> |
                <span>{{ $value->uri() }}</span>
                {{-- <span>{{ $value->getName() }}</span> | --}}
                {{-- <span>{{ $value->getActionName() }}</span> --}}
            </div>
        @endif
        <hr>
    @endif
@endforeach

</html>
