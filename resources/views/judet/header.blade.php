<html>
	@include('admin/head')
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div>
				<ul class="navbar-nav mr-auto header-menu">
					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('judet.observers.show') }}">
							Observatori
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('judet.sections.show') }}">
							Sectii
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="#">
							Ticketing
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="#">
							Raportare
						</a>
					</li>
					<div style="clear:both"></div>
				</ul>
			</div>
		</nav>