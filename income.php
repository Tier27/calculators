<!doctype html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Financial Calculator #1 | ESM</title>
  <link rel="stylesheet" href="css/foundation.css" />
  <link rel="stylesheet" href="css/custom-style.css" />
  <link href='http://fonts.googleapis.com/css?family=Courgette' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700' rel='stylesheet' type='text/css'>
  <link href="//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
  <script src="js/vendor/modernizr.js"></script>
</head>

<form id="calculator" data-abide class="panel">
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="hours-expected">Hours required
        <select id="hours-expected">
	<?php for( $i=30; $i<=60; $i+=5 ) : ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
	<?php endfor; ?>
        </select>
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="percentage-to-company">Percentage to company
        <select id="percentage-to-company">
	<?php for( $i=30; $i<=60; $i+=5 ) : ?>
          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
	<?php endfor; ?>
        </select>
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="hours-worked">Hours worked
	<input type="text" name="hours-worked" id="hours-worked" value="50">
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="hours-billable-ratio">Percentage of hours billable
	<input type="text" name="hours-billable-ratio" id="hours-billable-ratio" value="50">
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="hours-billable">Hours billable
	<input type="text" name="hours-billable" id="hours-billable" value="30" disabled>
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="hourly-rate">Hourly rate
	<input type="text" name="hourly-rate" id="hourly-rate" value="$40">
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="period">Period
        <select id="period">
	  <option value='1'>Weekly</option>
	  <option value='52'>Annually</option>
        </select>
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="gross-income">Revenue
	<input type="text" name="gross-income" id="gross-income" value="$0" disabled>
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
  <div class="row medium number dropdown">
    <div class="small-12 columns">
      <label for="gross-income">Salary
	<input type="text" name="net-income" id="net-income" value="$0" disabled>
      </label>
      <small class="error">Option is required.</small>
    </div>
  </div> <!--/.row-->
</form>

<script type="text/javascript" src="js/vendor/jquery.js"></script>
<script>
global_data = {};
//jQuery(function($){
	$('#hours-expected').val('40');
	$('#percentage-to-company').val('40');
	$('#hours-worked').val('60');
	$('#hours-billable-ratio').val('50%');
	$('#hours-billable').val('30');
	$('#hourly-rate').val('40');

	$('select, input').change(function(){
		calculate_income();
	});

	function calculate_income(){
		calculate_hours_billable();
		hours_expected = $('#hours-expected').val();
		percentage_to_company = $('#percentage-to-company').val().replace('%', '') / 100;
		hours_worked = $('#hours-worked').val();
		hours_billable_ratio = $('#hours-billable-ratio').val().replace('%', '') / 100;
		console.log(hours_billable_ratio);
		hours_billable = $('#hours-billable').val();
		hourly_rate = $('#hourly-rate').val();
		period = $('#period').val();

		gross_income = hours_billable * hourly_rate;
		gross_income = Math.round(gross_income)
		$('#gross-income').val('$' + gross_income * period);

		contribution_ratio = hours_expected / hours_worked;
		scaled_percentage_to_company = percentage_to_company * contribution_ratio;
		net_income = gross_income * (1 - scaled_percentage_to_company);
		net_income = Math.round(net_income)
		$('#net-income').val('$' + net_income * period);
	}

	function calculate_hours_billable() {
		hours_worked = $('#hours-worked').val();
		hours_billable_ratio = $('#hours-billable-ratio').val().replace('%', '') / 100;
		hours_billable = hours_worked * hours_billable_ratio;
		$('#hours-billable').val( hours_billable );
		return hours_billable;
	}
//});
</script>
