<html>
	@include('admin/head')
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div>
				<ul class="navbar-nav mr-auto header-menu">
					<li class="{{ Route::currentRouteNamed('judet.observers.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.observers.show') }}">
							Observatori
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('judet.sections.show') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.sections.show') }}">
							Sectii
						</a>
					</li>

					<!--
					<li class="nav-item">
						<a class="btn btn-light" href="#">
							Ticketing
						</a>
					</li>
					-->

					<li class="{{ Route::currentRouteNamed('judet.observers.stats') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.observers.stats') }}">
							Raportare
						</a>
					</li>

					<li class="{{ Route::currentRouteNamed('judet.observers.quizes') ? 'nav-item active' : 'nav-item' }}">
						<a class="btn btn-light" href="{{ route('judet.observers.quizes') }}">
							Chestionare
						</a>
					</li>

					<div style="clear:both"></div>
				</ul>
			</div>
		</nav>