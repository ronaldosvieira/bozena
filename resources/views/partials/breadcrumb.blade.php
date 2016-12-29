<ol class="breadcrumb">
    <li><a href="{{ url('') }}">Home</a></li>
    @if (isset($itens))
        @foreach ($itens as $item)
            @if (isset($item['disabled']) and $item['disabled'])
                <li class="active">
                    {{ $item['nome'] }}
                </li>
            @else
                <li>
                    <a href="{{ route($item['route']) }}">
                        {{ $item['nome'] }}
                    </a>
                </li>
            @endif
        @endforeach
    @endif
</ol>