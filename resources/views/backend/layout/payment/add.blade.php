@extends('backend.home')

@section('js')
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<!-- Page level custom scripts -->
<script src="{{ asset('js/jquery.searchabledropdown-1.0.8.min.js') }}"></script>
<script>
	$(document).ready(function() {
		$("#tenant_id").searchable({
			maxListSize: 20,						// if list size are less than maxListSize, show them all
			maxMultiMatch: 10,						// how many matching entries should be displayed
			exactMatch: true,						// Exact matching on search
			wildcards: false,						// Support for wildcard characters (*, ?)
			ignoreCase: true,						// Ignore case sensitivity
			latency: 200,							// how many millis to wait until starting search
			warnMultiMatch: 'top {0} matches ...',	// string to append to a list of entries cut short by maxMultiMatch 
			warnNoMatch: 'no matches ...',			// string to show in the list when no entries match
			zIndex: 'auto'							// zIndex for elements generated by this plugin
	   	});
	});
	</script>

	<script type="text/javascript">
		$('#tenant_id').change(function(){
			var id = $(this).val();
			console.log( id );

			if( id > 0)
			{
				$.ajax({
				   type:"get",
				   url:"http://127.0.0.1:8000/get/lease_by_tenant_id/"+id,
				   'type': 'GET',
				   'dataType': 'JSON',
				   success:function(res)
				   {       
						if( res['error'] )
						{
							$("#property_id").empty();
							$("#unit_id").empty();
							$('#payment_amount').val( '' );
						}
						else
						{
							$("#property_id").append('<option value="'+res['property_id']+'">'+res['property_name']+'</option>');
							$("#unit_id").append('<option value="'+res['propertyunit_id']+'">'+res['propertyunit_name']+'</option>');
							$('#payment_amount').val( res['rentamount'] );
						}
						

				   }
				});
			}
			else
			{
				$("#unit_id").empty();
			}
		});
		</script>

		
@endsection

@section('content')

<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800">Add New Payment</h1>

	<div class="row">
		@if(session('status'))
			<div class="alert alert-info">
				{{ session('status') }}
			</div>
		@endif

		@if (Session::has('message'))
			<h4 class="text-info">{!! session('message') !!}</h4>
		@endif

		@if ($errors->any())
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
		@endif
		<div class="col-md-12">
			{{--  Payment Registration Form Start    --}}
			<form method="post" action="{{ route('payment_add_form_save') }}" enctype="multipart/form-data">
				@csrf

				<div class="form-group">
					<label for="tenant_id" class="form-label">Select Tenant <span class="required_field"> (*) </span></label>
					<select id="tenant_id" class="form-control" name="tenant_id" required>
						<option value="">Select Tenant</option>
						@foreach ( $data['tenants'] as $tenant )
							<option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="payment_amount"> Payment Amount <span class="required_field"> (*) </span> </label>
					<input type="number" name="payment_amount" id="payment_amount"  class="form-control"  placeholder="Payment Amount" required>
				</div>

				<div class="form-group">
					<label for="property_id" class="form-label">Select Property <span class="required_field"> (*) </span></label>
					<select id="property_id" class="form-control" name="property_id" required>
					</select>
				</div>

				<div class="form-group">
					<label for="unit_id" class="form-label">Select Unit ID </label>
					<select id="unit_id" class="form-control" name="unit_id">
					</select>
				</div>


				<div class="form-group">
					<label for="payment_date"> Payment Date  </label>
					<input type="date" name="payment_date" id="payment_date" class="form-control"  value="<?php echo date("Y-m-d"); ?>"  >
				</div>



				<div class="form-group">
					<label for="payment_purpose" class="form-label">Payment Purpose  <span class="required_field"> (*) </span></label>
					<select id="payment_purpose" class="form-control" name="payment_purpose">
							<option value="1">Rent</option>
							<option value="2">Prorated Rent</option>
							<option value="3">Security Deposit</option>
							<option value="4">Damage</option>
							<option value="5">Other</option>
					</select>
				</div>


				<div class="form-group">
					<label for="payment_status" class="form-label">Payment Status  <span class="required_field"> (*) </span></label>
					<select id="payment_status" class="form-control" name="payment_status">
							<option value="1">PENDING</option>
							<option value="2">RECORDED</option>
							<option value="3">DEPOSITED</option>
					</select>
				</div>

				<div class="form-group">
					<label for="note"> Payment Note </label>
					<textarea name="note" id="note" ></textarea> 
				</div>
				

				<button type="submit" class="btn btn-primary">Add Payment</button>
			</form>
			{{--  Payment Registration Form Start   --}}

		</div>
	</div>

</div>

@endsection
