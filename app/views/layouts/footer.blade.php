@section("footer")
<footer>
    <div class="footer">
        <div class="container">
            <div class="row">
                {{--
                <div class="col-xs-4">
                    {{ link_to_route('items.index', 'Free Downloads', NULL, array('class' => 'text-center')) }}
                <div class="col-xs-4">
                    {{ link_to_route('products.index', 'Order CDs, DVDs, and MP3s', NULL, array('class' => 'text-center')) }}
                </div>
                --}}
                <div class="col-xs-12">
                    &copy;&nbsp;2008&nbsp;-&nbsp;<?php echo date("Y"); ?> <strong>{{ HTML::link('http://www.workshopmultimedia.com', 'Workshop Multimedia', array('id' => 'wmmUrl', 'class' => 'text-center')); }}</strong>
                </div>
            </div>
        </div>
    </div> 
</footer>
@show