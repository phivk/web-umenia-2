@extends('layouts.master')

@section('title')
@parent
| autori
@stop

@section('content')

<section class="top-section">
    <div class="catalog-body">
        <div class="container">
        </div>
    </div>
</section>

<section class="authors">
    <div class="authors-body">
        <div class="container">
            @if (empty($cc))
            {{ Form::open(array('id'=>'filter', 'method' => 'get')) }}
            {{ Form::hidden('search', @$search) }}
            <div class="row">
                <!-- <h3>Filter: </h3> -->
                <div  class="col-sm-3">
                        {{ Form::select('role', array('' => '') + $roles,  @$input['role'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Rola')) }}
                </div>
                <div  class="col-sm-3">
                        {{ Form::select('nationality', array('' => '') + $nationalities, @$input['nationality'], array('class'=> 'chosen-select form-control', 'data-placeholder' => 'Príslušnosť')) }}
                </div>
            </div>
            <div class="row bottom-space" style="padding-top: 20px;">
                <div  class="col-sm-3">
                        
                        <p><a class="btn btn-default btn-outline  uppercase sans" href="{{ URL::to('autori')}}">zobraziť všetkých autorov</a></p>
                        <!-- {{ Form::hidden('search', @$search); }} -->
                </div>
                <div class="col-sm-1 text-right year-range">
                        <b class="sans" id="from_year">{{ !empty($input['year-range']) ? reset((explode(',', $input['year-range']))) : '1500' }}</b> 
                </div>
                <div class="col-sm-7 year-range">
                        <input id="year-range" name="year-range" type="text" class="span2" data-slider-min="1500" data-slider-max="2014" data-slider-step="5" data-slider-value="[{{ !empty($input['year-range']) ? $input['year-range'] : '1500,2014' }}]"/> 
                </div>
                <div class="col-sm-1 text-left year-range">
                        <b class="sans" id="until_year">{{ !empty($input['year-range']) ? end((explode(',', $input['year-range']))) : '2014' }}</b>
                </div>
            </div>
             {{ Form::close() }}
             @endif
            <div class="row">
            	<div class="col-sm-12 container-author">
                    @if (!empty($search))
                        <h3>Nájdení autori pre &bdquo;{{ $search }}&ldquo; (<span data-searchd-total-hits>{{ $authors->total() }}</span>) </h3> 
                    @else
                		<h3>Nájdení autori ({{ $authors->total() }}) </h3>
                    @endif
                    @if ($authors->count() == 0)
                        <p class="text-center">Momentálne žiadni autori</p>
                    @endif
                    <?php $authors = $authors->paginate(18) ?>
                	@foreach ($authors as $i=>$author)
                     <div class="row author">   
    	                <div class="col-sm-2">
    	                	<a href="{{ $author->getDetailUrl() }}">
    	                		<img src="{{ $author->getImagePath() }}" class="img-responsive img-circle" alt="{{ $author->name }}">	                		
    	                	</a>
                        </div>
                        <div class="col-sm-5">
                            <div class="author-title">
                                <a href="{{ $author->getDetailUrl() }}" {{ (!empty($search))  ? 
                                    'data-searchd-result="title/'.$author->id.'" data-searchd-title="'. $author->formatedName.'"' 
                                    : '' }}>
                                    <strong>{{ $author->formatedName }}</strong>
                                </a>
                            </div>
                            <div>
                                {{ $author->birth_year }} {{ $author->birth_place }} 
                                @if ($author->death_year)
                                    &ndash; {{ $author->death_year }} {{ $author->death_place }} 
                                @endif
                            </div>
                            <div>
                                @foreach ($author->roles as $role)
                                    {{$role->role }} 
                                @endforeach
                            </div>
                            <div>
                                <a href="{{ url_to('katalog', ['author' => $author->name]) }}"><strong>{{ $author->items->count() }}</strong></a> diel
                            </div>

                        </div>
                        <div class="col-sm-5" >
                            <div class="artworks-preview">
                            @foreach ($author->items->slice(0,5) as $item)
                                <img src="{{ $item->getImagePath() }}" class="img-responsive-width" alt="{{implode(', ', $item->authors)}} - {{ $item->title }}">
                            @endforeach
                            </div>
    	                </div>
                    </div>
                	@endforeach
                    <div class="col-sm-12 text-center">
                        {{ $authors->appends(@Input::except('page'))->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


@stop

@section('javascript')

{{ HTML::script('js/bootstrap-slider.min.js') }}
{{ HTML::script('js/chosen.jquery.min.js') }}
{{ HTML::script('js/slick.min.js') }}

<script type="text/javascript">

$(document).ready(function(){

    $("#year-range").slider({
        // value: [1500, 2014],
        tooltip: 'hide'
    }).on('slideStop', function(event) {
        $(this).closest('form').submit();
    }).on('slide', function(event) {
        var rozsah = $("#year-range").val().split(',');
        $('#from_year').html(rozsah[0]);
        $('#until_year').html(rozsah[1]);
    });

    $(".chosen-select").chosen({allow_single_deselect: true})

    $(".chosen-select").change(function() {
        $(this).closest('form').submit();
    });

    $('.artworks-preview').slick({
        // dots: true,
        lazyLoad: 'ondemand',
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        // centerMode: true,
        variableWidth: true
    });

    $container.infinitescroll({
        navSelector     : ".pagination",
        nextSelector    : ".pagination a:last",
        authorSelector    : ".author",
        debug           : false,
        dataType        : 'html',
        donetext        : 'boli načítaní všetci autori',
        path            : undefined,
        bufferPx     : 200,
        loading: {
            msgText: "<em>Načítavam ďalších autorov...</em>",
            finishedMsg: 'A to je všetko'
        }
    }, function(newElements, data, url){
        var $newElems = jQuery( newElements ).hide(); 
        $newElems.imagesLoaded(function(){
            $newElems.fadeIn();
            $container.isotope( 'appended', $newElems );
        });
    });

});

</script>
@stop