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
				value="<?php if (!empty($judetAdmin)) echo $judetAdmin->username; ?>"
				name="username" />
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
				Judet:
			</label>

			<div class="col-sm-2">
				<select name="judet_id">
					<option>Neselectat</option>
					@foreach ($judete as $judet)
						<option 
						value="{{ $judet->id }}"
						<?php if (!empty($judetAdmin) && $judetAdmin->judet_id == $judet->id) echo 'selected'; ?>
						>
							{{ $judet->name }}
						</option>
					@endforeach
				</select>
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
				value="<?php if (!empty($judetAdmin)) echo $judetAdmin->full_name; ?>"
				name="full_name" />
			</div>
		</div>

		<div class="form-group row">
			<input type="submit" class="btn btn-primary" value="Creeaza" />
		</div>
	</form>
</div>