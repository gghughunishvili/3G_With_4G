@extends('layouts.admin')
@section('content')
@if(Session::has('message'))
    <div class="alert alert-{{ Session::get('message_type') }} alert-dismissible">
    	<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
        {{ Session::get('message') }}
    </div>
@endif
<h1>Users</h1>
<hr/>
<div class="tag_bar">
{{ Form::open(array('url' => 'filterskill', 'method' => 'get', 'id' => 'tagsubmit')) }}
	<?php 
		$d = 0; $check = null;
	?>

	@foreach($skills as $skill)
		<?php $d++; ?>
		@if(in_array($skill->name, $tagname))
		<?php $check = 'checked';?>
		@endif
		<div class="skill_tag_wrapper_1">
			<input type="checkbox" id="{{ $d }}" class="checkbox1" name="skillFil[]" data-related-item="sk_level_inp_<?=$d ?>" value="{{$skill->id}}" {{ $check }}/>
	    	<label for="{{ $d }}">{{ $skill->name }}</label>

	    	<div class="hidden1 skill_level_selector_1">
	    		{{ Form::select('levelFil['.$skill->id.']', [0=>'ნებისმიერი', 89=>'შესანიშნავი', 74=>'ძალიან კარგი', 59=>'კარგი', 44=>'საშუალო', 29=>'საშუალოზე დაბალი'], '', ['class'=>'form-control skill_level_input_1', 'id' => 'sk_level_inp_'.$d,]); }}
			</div>
		</div>
        
    	<?php $check = null; ?>
     @endforeach
    <div class="col-sm-12 controls">
      <!--{{ Form::submit('Login', array('id' => 'btn-login', 'class' => 'btn btn-success pull-right')) }} -->
      <button type="submit" class="btn btn-primary btn-sm btn-info pull-right filterbutton" id="btn-login" >
        <span class="glyphicon glyphicon-tasks"></span> Filter
      </button>

    </div>
    <div class="clear"></div>
{{Form::close()}} 
</div>

<p class="text-right pull-right">
	<a href="{{ URL::to('admin/user/create') }}" class="btn btn-success">
		<i class="glyphicon glyphicon-plus"></i> Create User
	</a>
</p>
<div class="users_skills_data">
	@yield('usr_skl')
	
</div>

<a href="#" class="scrollToTop"></a>
<script type="text/javascript">

	$('.users_skills_data').on("click", "form.delete_user", function(e){
		if(confirm("Do you really want to delete user?")){

		}else{
			e.preventDefault();
		}	
	});

	function evaluate(){
	    var item = $(this);
	    var relatedItem = $("#" + item.attr("data-related-item")).parent();
	   
	    if(item.is(":checked")){
	        relatedItem.fadeIn('slow', 'linear');
	    }else{
	        relatedItem.fadeOut('fast', 'linear');   
	    }
	}

	$('input[type="checkbox"]').click(evaluate).each(evaluate);

    $(window).scroll(function(){
       $("#navbar").css({"top": ($(window).scrollTop()) + "px"});
        
       if ($(window).scrollTop() > 250){
		    $(".fixedheader1").css({"top": ($(window).scrollTop()) -250 + "px"});
		} else {
        $(".fixedheader1").css("top", "0px");
    }
    });
</script>


@stop