@extends("layouts.main")

@section('content')
<div class="content-wrapper">
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="pagetitle">
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">Yahoo</a></li>
					<li class="breadcrumb-item active">ログ</li>
				</ol>
			</nav>
		</div><!-- End Page Title -->
		<div class="content" style="padding-top: 0.5rem;">
			<div class="col-12">
				<div class="card card-info card-outline">
					<div class="card-body">
						<div class="row">
							<div id="table-wrapper">
								@php
									$logs = App\Models\MailLog::paginate(10);
								@endphp
								<table class="table table-bordered">
								<!-- <table class="table table-bordered table-head-fixed" style="width: 100%;" id="item-table"> -->
									<thead>
										<tr>
											<th></th>
											<th>ユーザー</th>
											<!-- <th>カテゴリー</th> -->
											<th>ASIN</th>
											<th style="min-width: 300px; max-width: 400px;">通知内容</th>
											<th>通知時間</th>
										</tr>
									</thead>
									<tbody>
										@foreach($logs as $log)
										<tr data-id={{ $log->id }}>
											<td data-id={{ $log->id }}>{{ $loop->iteration + ($logs->currentPage() - 1) * 10 }}</td>
											<td>{{ App\Models\User::find($log['user_id'])->name }}</td>
											<!-- <td>{{-- $log['category_id'] --}}</td> -->
											<td>{{ $log['asin'] }}</td>
											<td style="min-width: 300px; max-width: 400px;"><?php echo $log['category_id']; ?></br>{{ $log['msg'] }}</td>
											<td>{{ $log['created_at'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
					@if (count($logs)) {{ $logs->onEachSide(1)->links('mypage.pagination') }} @endif
					<!-- /.card-footer -->
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	$(document).ready(function() {
		setInterval(() => {
			location.reload();
		}, 10 * 60 * 1000); 
	});
</script>
@endsection