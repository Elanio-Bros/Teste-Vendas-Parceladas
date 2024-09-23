@extends('Base\app')

@include('Base\header')
@section('body')
    <table class="table table-striped">
        <thead>
            <tr>
                @yield('thead')
            </tr>
        </thead>
        <tbody>
            @yield('tbody')
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            @foreach ($links as $key => $link)
                @if ($key == 0)
                    @php
                        $link['label'] = 'Anterior';
                    @endphp
                @endif
                @if ($key + 1 == count($links))
                    @php
                        $link['label'] = 'Próximo';
                    @endphp
                @endif
                <li class="page-item"><a class="page-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
            @endforeach
        </ul>
    </nav>
@endsection
