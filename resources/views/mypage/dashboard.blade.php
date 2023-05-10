@extends("layouts.main")

@php
	$user = Auth::user();
	$categories = App\Models\Category::where('user_id', Auth::id())->get();
	$items = App\Models\Item::where('user_id', Auth::id())->get();
@endphp

@section('content')
<div class="content-wrapper">	
	<div class="container-xxl flex-grow-1 container-p-y">
		<main id="main" class="main">
			<div class="pagetitle">
				<nav>
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="/">Yahoo</a></li>
						<li class="breadcrumb-item active">ダッシュボード</li>
					</ol>
				</nav>
			</div><!-- End Page Title -->
			<section class="section dashboard">
				<div class="row">
					<!-- Left side columns -->
					<div class="col-lg-12">
						<div class="row">
							<!-- Sales Card -->
							<div class="col-xxl-4 col-md-4">
								<div class="card info-card sales-card">
									<div class="card-body">
										<h4 class="card-title">カテゴリー </h4>
										<div class="d-flex align-items-center">
											<div class="d-flex align-items-center justify-content-center">
												<i class="bx bxs-category-alt text-primary"></i>
												{{ count($categories) }}
											</div>
										</div>
									</div>
								</div>
							</div><!-- End Sales Card -->
							<!-- Revenue Card -->
							<div class="col-xxl-4 col-md-4">
								<div class="card info-card revenue-card">
									<div class="card-body">
										<h4 class="card-title">商品 </h4>
										<div class="d-flex align-items-center">
											<div class="d-flex align-items-center justify-content-center">
												<span><i class='bx bxs-cart-alt text-primary'></i></span>
												{{ count($items) }}
											</div>
										</div>
									</div>
								</div>
							</div><!-- End Revenue Card -->
						</div>
					</div><!-- End Left side columns -->
				</div>
			</section>

		</main><!-- End #main -->
	</div>
</div>
@endsection

@push('scripts')  