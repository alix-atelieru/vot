<html>
	@include('admin/head')
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div>
				<ul class="navbar-nav mr-auto header-menu">
					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('superadmin.observers.show') }}">
							Observatori
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('superadmin.admins.judet.add.show') }}">
							Adauga admini nationali/judeteni
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('superadmin.sections.show') }}">
							Sectii
						</a>
					</li>

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('superadmin.mass-sms.show') }}">
							Mass SMS
						</a>
					</li>

					<!--
					<li class="nav-item">
						<a class="btn btn-light" href="#">
							Ticketing
						</a>
					</li>
					-->

					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('superadmin.observers.stats') }}">
							Raportare
						</a>
					</li>
					
					<li class="nav-item">
						<a class="btn btn-light" href="{{ route('superadmin.observers.quizes') }}">
							Chestionare
						</a>
					</li>
					<div style="clear:both"></div>
				</ul>
			</div>
		</nav>