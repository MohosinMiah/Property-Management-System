@extends('backend.home')
@section('js')
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<!-- Page level custom scripts -->
<script src="{{ asset('js/jquery.searchabledropdown-1.0.8.min.js') }}"></script>
<script>
	$(document).ready(function() {
		$("select").searchable({
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
		$('#property_id').change(function(){
			var id = $(this).val();
			if( id > 0)
			{
				$.ajax({
				   type:"get",
				   url:"http://127.0.0.1:8000/property/unit/list/"+id,
				   'type': 'GET',
				   'dataType': 'JSON',
				   success:function(res)
				   {       
						if(res)
						{
							$("#unit_id").empty();
							$.each(res,function(key,value){
								console.log(value);
								$("#unit_id").append('<option value="'+value.id+'">'+value.name+'</option>');
							});
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

	<?php $lease = $data['lease']; ?>

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800">Edit Lease</h1>


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
			{{--  lease Registration Form Start   --}}
			<form method="post" action="{{ route('lease_update_save' , $lease->id ) }}" enctype="multipart/form-data">
				@csrf
				
				<div class="form-group">
					<label for="tenant_id" class="form-label">Select Tenant <span class="required_field"> (*) </span></label>
					<select id="tenant_id" class="form-control" name="tenant_id">
						@foreach ( $data['tenants'] as $tenant )
							<option value="{{ $tenant->id }}" <?php if( $tenant->id == $lease->tenant_id ) { echo "selected"; } ?> >{{ $tenant->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="property_id" class="form-label">Select Property <span class="required_field"> (*) </span></label>
					<select id="property_id" class="form-control" name="property_id">
						<option value="">Select Property</option>
						@foreach ( $data['properties'] as $property )
							<option value="{{ $property->id }}" <?php if( $property->id == $lease->property_id ) { echo "selected"; } ?> >{{ $property->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="unit_id" class="form-label">Select Unit ID <span class="required_field"> (*) </span></label>
					<select id="unit_id" class="form-control" name="unit_id">
						<option value="">Select Property Unit</option>
						@foreach ( $data['propertyUnits'] as $propertyUnit )
							<option value="{{ $propertyUnit->id }}" <?php if( $propertyUnit->id == $lease->unit_id ) { echo "selected"; } ?> >{{ $propertyUnit->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="rent_amount"> Rent Amount <span class="required_field"> (*) </span> </label>
					<input type="number" name="rent_amount" id="rent_amount"  class="form-control"  value="{{ $lease->rent_amount }}" >
				</div>

	

				<div class="form-group">
					<label for="security_deposit"> Security Deposit  </label>
					<input type="number" name="security_deposit" id="security_deposit"  class="form-control"  value="{{ $lease->security_deposit }}" >
				</div>


				<div class="form-group">
					<label for="pet_security_deposit"> Pet Security Deposit  </label>
					<input type="number" name="pet_security_deposit" id="pet_security_deposit" class="form-control"  value="{{ $lease->pet_security_deposit }}" >
				</div>

				<hr>
				<div class="form-group">
					<label for="invoice_starting_date"> Invoice Start Date  </label>
					<input type="date" name="invoice_starting_date" id="invoice_starting_date" class="form-control" value="{{ $lease->invoice_starting_date }}"   >
				</div>

				<div class="form-group">
					<label for="invoice_amount"> Invoice Amount  </label>
					<input type="number" name="invoice_amount" id="invoice_amount"  class="form-control" value="{{ $lease->invoice_amount }}"  >
				</div>

				<div class="form-group">
					<label for="prorated_amount"> Prorated Rent  </label>
					<input type="number" name="prorated_amount" id="prorated_amount"  class="form-control" value="{{ $lease->prorated_amount }}" >
				</div>

				<div class="form-group">
					<label for="prorated_starting_date"> Prorated Start Date  </label>
					<input type="date" name="prorated_starting_date" id="prorated_starting_date" class="form-control" value="{{ $lease->prorated_starting_date }}"  >
				</div>

				<hr>

				<div class="form-group">
					<label for="lease_start"> Lease Start  </label>
					<input type="date" name="lease_start" id="lease_start" class="form-control" value="{{ $lease->lease_start }}"  >
				</div>

				<div class="form-group">
					<label for="lease_start"> Lease End  </label>
					<input type="date" name="lease_end" id="lease_end" class="form-control"  value="{{ $lease->lease_end }}"   >
				</div>

				<div class="form-group">
					<label for="termination_date"> Tarminated Date  </label>
					<input type="date" name="termination_date" id="termination_date" class="form-control"  value="{{ $lease->termination_date }}"   >
				</div>

				<div class="form-group">
					<label for="isActive"> Lease Status </label>
						<select class="form-control" name="isActive" id="isActive" required>
							<option value="1" <?php if( $lease->isActive == 1 ) { echo "selected"; } ?>>Active</option>
							<option value="2" <?php if( $lease->isActive == 2 ) { echo "selected"; } ?>>Deactive</option>
						</select>
				</div>

				<button type="submit" class="btn btn-primary">Update lease</button>
			</form>
			{{--  lease Registration Form Start   --}}

		</div>
	</div>

</div>

@endsection
