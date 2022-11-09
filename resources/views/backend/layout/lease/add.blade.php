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
			console.log( nid );
			var nid = $(this).val();
			if(nid > 0)
			{
				$.ajax({
				   type:"get",
				   url:"http://127.0.0.1:8000/property/unit/list/1",
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

	<!-- Page Heading -->
	<h1 class="h3 mb-4 text-gray-800">Add New Lease</h1>

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
			{{--  Lease Registration Form Start    --}}
			<form method="post" action="{{ route('lease_add_form_save') }}" enctype="multipart/form-data">
				@csrf

				<div class="form-group">
					<label for="tenant_id" class="form-label">Select Tenant <span class="required_field"> (*) </span></label>
					<select id="tenant_id" class="form-control" name="tenant_id">
						@foreach ( $data['tenants'] as $property )
							<option value="{{ $property->id }}">{{ $property->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="property_id" class="form-label">Select Property <span class="required_field"> (*) </span></label>
					<select id="property_id" class="form-control" name="property_id">
						<option value="">Select Property</option>
						@foreach ( $data['properties'] as $property )
							<option value="{{ $property->id }}">{{ $property->name }}</option>
						@endforeach
					</select>
				</div>

				<div class="form-group">
					<label for="unit_id" class="form-label">Select Unit ID <span class="required_field"> (*) </span></label>
					<select id="unit_id" class="form-control" name="unit_id">
						<option value="">Select Property Unit</option>
					</select>
				</div>

				<div class="form-group">
					<label for="rent_amount"> Rent Amount <span class="required_field"> (*) </span> </label>
					<input type="number" name="rent_amount" id="rent_amount"  class="form-control"  placeholder="Rent Amount">
				</div>

	

				<div class="form-group">
					<label for="security_deposit"> Security Deposit  </label>
					<input type="number" name="security_deposit" id="security_deposit"  class="form-control"  placeholder="Security Deposit Amount">
				</div>


				<div class="form-group">
					<label for="pet_security_deposit"> Pet Security Deposit  </label>
					<input type="number" name="pet_security_deposit" id="pet_security_deposit" class="form-control"  placeholder="Pet Security Deposit Amount" >
				</div>

				<div class="form-group">
					<label for="lease_start"> Lease Start  </label>
					<input type="date" name="lease_start" id="lease_start" class="form-control"   >
				</div>

				<div class="form-group">
					<label for="lease_start"> Lease End  </label>
					<input type="date" name="lease_end" id="lease_end" class="form-control"   >
				</div>


				<button type="submit" class="btn btn-primary">Add Lease</button>
			</form>
			{{--  Lease Registration Form Start   --}}

		</div>
	</div>

</div>

@endsection
