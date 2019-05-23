@include('national/header')

<div class="container">
	@if (session('success'))
		<div style="color:green">
			{{ session('success') }}
		</div>
	@endif

	@if (session('errorLabel'))
		<div style="color:red">
			{{ session('errorLabel') }}
		</div>
	@endif

	<form method="POST">
		{{ csrf_field() }}
		<input type="hidden" name="id" value="{{ $adminNational->id }}" />
		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row">
				Username:
			</label>
			<div class="col-sm-2">
				<input 
				class="form-control bold_text center_field_text" 
				type="text" 
				name="username" 
				value="<?php if (!empty($adminNational->username)) echo $adminNational->username;?>" />
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row">
				Parola:
			</label>

			<div class="col-sm-2">
				<input class="form-control bold_text center_field_text" type="password" name="password" />
			</div>
		</div>

		<div class="form-group row">
			
			<label class="col-sm-10 col-form-label dashed_row">
				Nume complet:
			</label>
			<div class="col-sm-2">
				<input 
				class="form-control bold_text center_field_text" 
				type="text" 
				name="full_name" 
				value="<?php if (!empty($adminNational->full_name)) echo $adminNational->full_name;?>"
				/>
			</div>
		</div>

		<div class="form-group row">
			<?php if (Route::currentRouteNamed('national.account.update.national.show')) { ?>
				<input type="submit" class="btn btn-primary" value="Update" />
			<?php } else { ?>
				<input type="submit" class="btn btn-primary" value="Creaza" />
			<?php } ?>
		</div>
	</form>
</div>