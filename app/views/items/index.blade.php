@section('main')
    @include('items.partials._search')
        <div class="row">
            <div class="items-container">
        
                @foreach ( $items as $item )
                    @if ( $item->category == 'M' )
                        <div class="item item-0">
                    @elseif ( $item->category == 'S' )
                        <div class="item item-3">
                    @elseif ( $item->category == 'C' )
                        <div class="item item-6">
                    @else
                        <div class="item item-7">
                    @endif
                        <h2>{{ $item->session_title }}</h2>
                        <h3>{{ $item->speaker_name }}</h3>
                        <h3>{{ date('l, F d, Y', strtotime($item->session_date)) }}</h3>
                        <p>{{ link_to_route('download', 'Download', array('item' => $item->id)) }}</p>
                    </div>
                @endforeach
        
            </div>
                @if ( $items->links() )
                <span style="text-align: center;">
                    {{ $items->links() }}
                </span>
                @endif
        </div>
@stop