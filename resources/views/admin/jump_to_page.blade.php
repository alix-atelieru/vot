<form method="GET" class="form-inline">
	<?php //print_r($requestDict); ?>
	<div class="form-group mx-sm-3 mb-2">
	    <label >Du-te la pagina</label>&nbsp;&nbsp;
	    <input type="text" class="form-control" name="page" value="{{ $page }}" />&nbsp;&nbsp; din {{ $pagesCount }}
 	</div>
	<input type="submit" value="Go" class="btn btn-primary mb-2"/>
	
	@if (!empty($prevPageUrl))
		<a href="{{ $prevPageUrl }}">
			Pagina anterioara&nbsp;
		</a>
	@endif

	@if (!empty($nextPageUrl))
		<a href="{{ $nextPageUrl }}">
			Pagina urmatoare&nbsp;
		</a>
	@endif

	<?php
	foreach ($requestDict as $name => $value) {
		if ($name == 'page') {
			continue;
		}
	?>
		<input type="hidden" name="<?php echo $name; ?>" value="<?php echo $value; ?>" />
	<?php
	}	
	?>	
</form>