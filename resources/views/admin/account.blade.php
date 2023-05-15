@extends("layouts.main")

@section('css')
<style>
	table th, table td {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>
@endsection

@section('content')
<div class="content-wrapper">
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="pagetitle">
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="/">Yahoo</a></li>
					<li class="breadcrumb-item active">アカウント</li>
				</ol>
			</nav>
		</div><!-- End Page Title -->
		<div class="content" style="padding-top: 0.5rem;">
			<div class="col-12">
				<div class="card card-info card-outline">
					<div class="card-body">
						<div class="row">
							<div id="table-wrapper" style="overflow: auto; width: 100%;">
								<table class="table table-bordered" style="width: 100%;" id="item-table">
								<!-- <table class="table table-bordered table-head-fixed" style="width: 100%;" id="item-table"> -->
									<thead>
										<tr>
											<th rowspan="1" colspan="1" style="width: 50px;">操作</th>
											<th rowspan="1" colspan="1" style="width: 100px;">ユーザー名</th>
											<th rowspan="1" colspan="1" style="width: 250px;">メール</th>
											<th rowspan="1" colspan="1" style="width: 150px;">役割</th>
											<th rowspan="1" colspan="1" style="width: 150px;">パーミッション</th>
										</tr>
									</thead>
									<tbody id="item-table-body">
										@foreach($users as $user)
										@if ($user['role'] == 'admin') @continue @endif
										<tr data-id={{ $user->id }}>
											<td>
												<span
													data-id={{ $user->id }}
													data-bs-toggle="modal" 
													data-bs-target="#confirmModal">
													<i class='bx bxs-trash text-danger'></i>
												</span>
											</td>
											<td rowspan="1" colspan="1">{{$user['name']}}</td>
											<td rowspan="1" colspan="1">{{$user['email']}}</td>
											<td rowspan="1" colspan="1">{{$user['role']}}</td>
											<td rowspan="1" colspan="1">
												<div class="form-group">
													<div class="custom-control custom-switch">
														<input type="checkbox" class="custom-control-input permission" id={{"customSwitch".$user->id}} @if($user['is_permitted']) checked @endif>
														<label class="custom-control-label" for={{"customSwitch".$user->id}}></label>
													</div>
												</div>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
					
					<!-- /.card-footer -->
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-modal="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12 mb-3 text-center">
						<h4>本当にデータを削除しますか?</h4>
					</div>
				</div>
			</div>
			<div class="modal-footer" id="btns">
				<!-- <button type="button" class="btn btn-primary">削除</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">キャンセル</button> -->
			</div>
		</div>
	</div>
</div>
@endsection
	
@section("script")
	<script>

		$('#confirmModal').on('shown.bs.modal', function(e) {
			var target = e.relatedTarget.dataset;
			$('#btns').html(
				`<button type="button" class="btn btn-primary" onclick="deleteAccount(${target.id})">削除</button>
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">キャンセル</button>`
			);
		}).on('hidden.bs.modal', function(e) {

		});

		const deleteAccount = (userId) => {
			$.ajax({
				url: '{{ route("delete_account") }}',
				type: 'get',
				data: {
					id: userId
				},
				success: function() {
					toastr.success('データが正常に削除されました。');
					$(`tr[data-id="${userId}"]`).remove();
					$('#confirmModal').modal('hide');
				}
			});
		};

		$(document).ready(function() {
			$('.permission').on('click', function(event) {
				let isPermitted = (event.target.checked == true) ? 1 : 0;
				$.ajax({
					url: '{{ route("permit_account") }}',
					type:'get',
					data: {
						id: event.target.id.replace("customSwitch", ""),
						isPermitted: isPermitted
					},
					success: function(response) {
						toastr.success('操作は成功しました。');
					}
				});
			});
		});
	</script>
@endsection
