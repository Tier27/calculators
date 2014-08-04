<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Time Calculator #1 | The Time Machine</title>
  <link rel="stylesheet" href="css/foundation.css" />
  <link rel="stylesheet" href="css/custom-style.css" />
  <link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
  <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
  <script src="js/vendor/modernizr.js"></script>
</head>

<div class="feature">
<div class="row">
	<div class="small-12 columns text-center">
		<h1>The Time Machine</h1>
	</div>
</div> <!--/.row-->
</div> <!--/.feature-->

<form id="calculator" data-abide class="panel">
  <div class="row medium number dropdown">
    <div class="small-2 columns">
      <label for="units">Units
        <select id="units">
	  <option value=60>Minutes</option>
	  <option value=1>Hours</option>
        </select>
      </label>
    </div>
    <div class="small-2 columns">
      <label for="period">Period
        <select id="period">
	  <option value=1>Daily</option>
	  <option value=7>Weekly</option>
        </select>
      </label>
    </div>
    <div class="small-2 columns">
      <label for="units-global">Global
	<input type="text" id="units-global" value="168" disabled>
      </label>
    </div>
    <div class="small-2 columns">
      <label for="units-local-cap">Local
	<input type="text" id="units-local-cap" value="168" disabled>
      </label>
    </div>
    <div class="small-4 columns">
      <label for="units-local-available">Available
	<input type="text" id="units-local-available" value="168" disabled>
      </label>
    </div>
  </div> <!--/.row-->

  <div class="row medium">
    <div class="small-4 columns">
      Task
    </div>
    <div class="small-2 columns">
      Global % 
    </div>
    <div class="small-2 columns">
      Local % 
    </div>
    <div class="small-2 columns">
      Units
    </div>
    <div class="small-1 columns">
      Lock
    </div>
    <div class="small-1 columns">
      Remove
    </div>
  </div>
  <div class="row medium task hide">
    <div class="small-4 columns">
      <input type="text" class="task name" value="Sleep">
    </div>
    <div class="small-2 columns">
      <input type="text" class="global percentage calculated" disabled>
    </div>
    <div class="small-2 columns">
      <input type="text" class="control percentage">
    </div>
    <div class="small-2 columns">
      <input type="text" class="control units">
    </div>
    <div class="small-1 columns">
      <div class="switch lock">
        <input name="lock[0]" type="radio" value="0" checked>
        <input name="lock[0]" type="radio" value="1">
        <span></span>
      </div>
    </div>
    <div class="small-1 columns">
      <span class="button btn-blue tiny remove task"><i class="fa fa-minus"></i></span>
    </div>
  </div>
  <div class="row medium task blank hide">
    <div class="small-4 columns">
      <input type="text" class="task name" value="">
    </div>
    <div class="small-2 columns">
      <input type="text" class="global percentage calculated" disabled>
    </div>
    <div class="small-2 columns">
      <input type="text" class="control local percentage">
    </div>
    <div class="small-2 columns">
      <input type="text" class="control units">
    </div>
    <div class="small-1 columns">
      <div class="switch lock">
        <input name="lock[i]" type="radio" value="0" checked>
        <input name="lock[i]" type="radio" value="1">
        <span></span>
      </div>
    </div>
    <div class="small-1 columns">
      <span class="button btn-blue tiny remove task"><i class="fa fa-minus"></i></span>
    </div>
  </div>
  <span class="button btn-blue btn-passport add task"><i class="fa fa-plus"></i></span>
</form>

<script type="text/javascript" src="js/vendor/jquery.js"></script>
<script>
global_data = {};
//jQuery(function($){
	var tasks = [];
	var units = {};
	units.global = 168;
	units.local = { percentage: '100', cap: units.global, available: units.global }
	$('#units').val('1');
	$('#period').val('7');
	$('#units, #period').change(function(){
		unit_multiplier = $('#units').val();
		period_multiplier = $('#period').val();
		units.local.available = unit_multiplier * period_multiplier * 24;
		//$('#units-local-cap').val(units.local.available);
		$('#units-local-available').val(units.local.available);
	});
	$('.add.task')
		.click(add_task)
		.trigger('click');

	//$('.remove.task').click(remove_task);
	//$('.control.percentage').change(partition_task);
	//$('.task.name').change(name_task);
	//$('.lock').click(lock_task)

	function add_task(){
		$task = $('.task.blank').clone()
		$task
			.removeClass('blank hide')
			.attr('data-id', tasks.length);
		$task
			.find('.task.name')
				.bind('change', name_task);
		$task
			.find('.remove.task')
				.bind('click', remove_task);
		$task
			.find('.control.percentage')
				.bind('change', partition_task);
		$task
			.find('.switch.lock')
				.click(lock_task)
				.find(' input').attr('name', 'lock[' + tasks.length + ']');
		$('.task.blank').before($task);
		tasks.push({name: '', percentage: 0, units: 0, locked: false});
	}

	function lock_task(){
		task = tasks[$(this).closest('.row.task').attr('data-id')];
		if( task.locked == true ) {
			units.local.cap += task.units;
		} else {
			units.local.cap -= task.units;
		}
		update_local_cap();
		task.locked = !task.locked;
		$.each($(this).closest('.row.task').find('.control'), function(index, value){
			$(this).prop('disabled', !$(this).prop('disabled'));
		});
	}

	function remove_task(){
		$(this).closest('.row.task').remove();
	}

	function partition_task(){
		task = tasks[$(this).closest('.row.task').attr('data-id')];
		task.percentage = $(this).val().replace('%','');
		ratio = parseInt($(this).val().replace('%', '')) / 100;
		console.log(ratio);
		units_used_on_this_task = ratio * units.local.cap;
		diff = units_used_on_this_task - task.units;
		task.units = units_used_on_this_task;
		$(this).closest('.row.task').find('.control.units').val(units_used_on_this_task);
		$(this).closest('.row.task').find('.global.percentage').val(100 * task.units / units.global + '%');
		units.local.available -= diff;
		update_units_available();	
	}

	function update_units_available(){
		$('#units-local-available').val(units.local.available);
	}

	function update_local_cap(){
		$('#units-local-cap').val(units.local.cap);
	}

	function name_task(){
		tasks[$(this).closest('.row.task').attr('data-id')].name = $(this).val();
	}
//});
</script>
