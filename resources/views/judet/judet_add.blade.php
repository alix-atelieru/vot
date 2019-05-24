@include('judet/header')

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
		<?php if (!empty($judetAdmin)) { ?>
			<input type="hidden" name="id" value="{{ $judetAdmin->id }}" />
		<?php } ?>

		<div class="form-group row">
			<label class="col-sm-10 col-form-label dashed_row">
				Username:
			</label>
			<div class="col-sm-2">
				<input 
				class="form-control bold_text center_field_text" 
				type="text" 
				name="username" 
				value="<?php if (!empty($judetAdmin)) echo $judetAdmin->username; ?>"
				/>
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
				value="<?php if (!empty($judetAdmin)) echo $judetAdmin->full_name; ?>"
				/>
			</div>
		</div>

		<div class="form-group row">
			<input type="submit" class="btn btn-primary" value="Creaza" />
		</div>

	</form>
</div>