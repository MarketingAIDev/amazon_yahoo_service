@extends("layouts.mypage")
@section('content')
<style>
	table th, table td {
		text-align: center !important;
		vertical-align: middle !important;
	}
</style>
<?php
$applicationId = "dj00aiZpPVJpRFdoRUdpY3pUaSZzPWNvbnN1bWVyc2VjcmV0Jng9MWE-";
$secret = "jOr0rr7kmvXR9r5T6ERkXfN7KpGTu4vmd8TC8Ahv";
// echo base64_encode($applicationId.":".$secret);
?>

<div class="content-wrapper">	
	<div class="content" style="padding-top: 0.5rem;">
		<div class="col-12">
			<div class="card card-info card-outline">
			
			<iframe id="3" width="800" height="800" style="border:none;max-width:100%;margin:0;" allowTransparency="true" frameborder="0" src="https://fxtoshiinfo.xsrv.jp/api/v1/quiz/share/3"></iframe>
				<div class="card-body">
					<div class="row">
						<div id="table-wrapper" style="overflow: auto; width: 100%;">
							<table class="table table-bordered" style="width: 100%;" id="error-table">
							<!-- <table class="table table-bordered table-head-fixed" style="width: 100%;" id="error-table"> -->
								<thead>
									<tr>
										<th rowspan="1" colspan="1" style="width: 20px;">No</th>
										<th rowspan="1" colspan="1" style="width: 150px;">ASIN</th>
										<th rowspan="1" colspan="1" style="width: 150px;">JAN</th>
										<th rowspan="1" colspan="1" style="width: 400px;">エラー名</th>
									</tr>
								</thead>
								<tbody id="item-table-body">
									@foreach($items as $item)
									<tr data-productId="{{$item['id']}}">
										<td>{{ $loop->iteration + ($items->currentPage() - 1) * 50 }}</td>
										<td rowspan="1" colspan="1">{{$item['asin']}}</td>
										<td rowspan="1" colspan="1">{{$item['jan']}}</td>
                    <td rowspan="1" colspan="1">{{$item['name']}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>								
					</div>
				</div>
				<!-- /.card-body -->
				@if (count($items)) {{ $items->onEachSide(1)->links('mypage.pagination') }} @endif
				
				<!-- <div class="card-footer">
					<button type="button" class="btn btn-info float-left" onclick="deleteItem()">削除</button>
					<button type="button" class="btn btn-info float-right" data-toggle="modal" data-target="#addItem">追加</button>
				</div> -->
				<!-- /.card-footer -->
			</div>
		</div>
	</div>
</div>
@endsection
	
@section("script")
	
@endsection