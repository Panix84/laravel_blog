@extends('main')

@section('title', 'Επεξεργασία Tag')

@section('stylesheets')
	
	{!! Html::style('css/parsley.css') !!}

@endsection

@section('content')
	<div class="container">	
		<div class="row">
		
			<div class="col-md-12">

				{!! Form::model($itag, ['route' => ['itags.update', $itag->id], 'method' => 'PUT', 'data-parsley-validate' => '']) !!}
						
					<div class="form-group">
						{{ Form::label('name', 'Όνομα:') }}
						{{ Form::text('name', null, array('class' => 'form-control', 'required' => '', 'maxlength' => '255')) }}
					</div>

					<div class="form-group">
						{{ Form::label('slug', 'Slug:') }}
						{{ Form::text('slug', null, array('class' => 'form-control', 'required' => '', 'minlength' => '5', 'maxlength' => '255')) }}
					</div>

					<div class="form-group">
						{{ Form::label('meta_title', 'Meta Title:') }}
						{{ Form::text('meta_title', null, array('class' => 'form-control meta_title', 'required' => '', 'maxlength' => '70')) }}
						<div class="meta_title_counter_outer">Απαιτείται ένα maximum των <span class="meta_title_counter"></span> χαρακτήρων.</div>
					</div>

					<div class="form-group">
						{{ Form::label('meta_desscription', 'Meta Description:') }}
						{{ Form::textarea('meta_desscription', null, array('class' => 'form-control meta_desscription', 'maxlength' => '160')) }}
						<div class="meta_desscription_counter_outer">Απαιτείται ένα maximum των <span class="meta_desscription_counter"></span> χαρακτήρων.</div>
					</div>

					<div class="form-group">
						{{ Form::label('meta_keywords', 'Meta Keywords:') }}
						{{ Form::text('meta_keywords', null, array('class' => 'form-control meta_keywords', 'required' => '')) }}
					</div>

					{{ Form::submit('Αποθήκευση Αλλαγών', array('class' => 'btn btn-success pull-right')) }}

				{{ Form::close() }}

			</div>
		
		</div>
	</div>
@endsection

@section('scripts')

	{!! Html::script('js/parsley.min.js') !!}

	<script type="text/javascript">
		(function ($) {
		    $.fn.extend({
		        limiter: function (minLimit, maxLimit, elem) {
		            $(this).on("keydown keyup focus keypress", function (e) {
		                setCount(this, elem, e);
		            });

		            function setCount(src, elem, e) {
		                var chars = src.value.length;
		                if (chars == maxLimit) {
		                    //e.preventDefault();
		                     elem.html(maxLimit - chars);
		                    elem.addClass('maxLimit');
		                    return false;
		                     
		                } else if (chars > maxLimit) {
		                    src.value = src.value.substr(0, maxLimit);
		                    chars = maxLimit;
		                    elem.addClass('maxLimit');
		                } else {
		                    elem.removeClass('maxLimit');
		                }
		                if (chars < minLimit) {
		                    elem.addClass('minLimit');
		                } else {
		                    elem.removeClass('minLimit');
		                }
		                elem.html(maxLimit - chars);
		            }
		            setCount($(this)[0], elem);
		        }
		    });
		})(jQuery);

		var elem = $(".meta_title_counter");
		var elem2 = $(".meta_desscription_counter");
		$(".meta_title").limiter(0, 70, elem);
		$(".meta_desscription").limiter(0, 160, elem2);
	</script>

@endsection