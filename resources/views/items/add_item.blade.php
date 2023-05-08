@extends("layouts.main")

@section('content')
<div class="content-wrapper">
	<div class="container-xxl flex-grow-1 container-p-y">
		<div class="nav-align-top mb-4">
			<ul class="nav nav-tabs nav-fill" role="tablist">
				<li class="nav-item">
					<button
						type="button"
						class="nav-link active"
						role="tab"
						data-bs-toggle="tab"
						data-bs-target="#navs-tabs-justified-csv"
						aria-controls="navs-tabs-justified-csv"
						aria-selected="true"
					>
						CSV
					</button>
				</li>
				<li class="nav-item">
					<button
						type="button"
						class="nav-link"
						role="tab"
						data-bs-toggle="tab"
						data-bs-target="#navs-tabs-justified-amazon"
						aria-controls="navs-tabs-justified-amazon"
						aria-selected="false"
					>
						Amazon情報入力
					</button>
				</li>
				<li class="nav-item">
					<button
						type="button"
						class="nav-link"
						role="tab"
						data-bs-toggle="tab"
						data-bs-target="#navs-tabs-justified-yahoo"
						aria-controls="navs-tabs-justified-yahoo"
						aria-selected="false"
					>
						Yahoo情報入力
					</button>
				</li>
			</ul>	  
			<div class="tab-content">
				<div class="tab-pane fade show active" id="navs-tabs-justified-csv" role="tabpanel">
					<form class="form-horizontal">
						<div class="card-body" style="padding:0px">
							<!-- <div class="form-group row">
								<div class="col-sm-1">
									<div class="icheck-primary d-inline float-left mr-4">
										<input type="radio" id="radioASIN" name="code_kind" value="1" checked />
										<label for="radioASIN">ASIN</label>
									</div>
								</div>
								<div class="col-sm-1 mb-3">
									<div class="icheck-primary d-inline float-left">
										<input type="radio" id="radioJAN" name="code_kind" value="0" />
										<label for="radioJAN">JAN</label>
									</div>
								</div>
							</div> -->

							<div class="form-group row mb-3">
								<label for="csv_load" class="col-md-2 col-form-label">CSV選択</label>
								<div class="col-md-10">
									<input type="file" class="form-control" id="csv_load" name="csv_load">
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="fall_pro" class="col-md-2 col-form-label">下落(%)</label>
								<div class="col-md-10">
									<input class="form-control" min='0' max='100' type="number" value="{{ $user->fall_pro }}" id="fall_pro" name="fall_pro" />
								</div>
							</div>
							
							<div class="form-group row mb-3">
								<label for="web_hook" class="col-md-2 col-form-label">Web Hook</label>
								<div class="col-md-10">
									<input class="form-control" type="text" id="web_hook" name="web_hook" value="{{ $user->web_hook }}" />
								</div>
							</div>
							
							<div class="form-group row" id="count">
								<div class="col-sm-2">
									<p class="float-right">
										<span id="progress-num">0</span> 件/ <span id="total-num">0</span> 件
									</p>
								</div>
								<div class="progress col-sm-10 mt-2">
									<div class="progress-bar progress-bar-animated bg-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="progress">
										<span id="percent-num">0%</span>
									</div>
								</div>
							</div>
						</div><!-- /.card-body -->

						<div class="card-footer">
							<button type="button" class="btn btn-info float-left" id="add_csv" onclick="addCsv();">登録</button>
						</div><!-- /.card-footer -->
					</form>
				</div>
				
				<div class="tab-pane fade" id="navs-tabs-justified-amazon" role="tabpanel">
					<form class="form-horizontal">
						<div class="card-body" style="padding:0px">

							<div class="form-group row mb-3">
								<label for="access_key" class="col-md-2 col-form-label">アクセスキー</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="access_key" name="access_key" value="{{ $user->access_key }}"/>
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="secret_key" class="col-md-2 col-form-label">シークレット キー</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="secret_key" name="secret_key" value="{{ $user->secret_key }}" />
								</div>
							</div>
							<div class="form-group row mb-3">
								<label for="partner_tag" class="col-md-2 col-form-label">パートナータグ</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="partner_tag" name="partner_tag" value="{{ $user->partner_tag }}" />
								</div>
							</div>
						</div><!-- /.card-body -->

						<div class="card-footer">
							<button type="button" class="btn btn-info float-left" onclick="saveAmazon();">保存</button>
						</div><!-- /.card-footer -->
					</form>
				</div>

				<div class="tab-pane fade" id="navs-tabs-justified-yahoo" role="tabpanel">
					<form class="form-horizontal">
						<div class="card-body" style="padding:0px">

							<div class="form-group row mb-3">
								<label for="token" class="col-md-2 col-form-label">Yahoo ID1</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="token" name="token" value="{{ $user->yahoo_token }}"/>
								</div>
							</div>

							<div class="form-group row mb-3">
								<label for="token1" class="col-md-2 col-form-label">Yahoo ID2</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="token1" name="token1" value="{{ $user->yahoo_token1 }}" />
								</div>
							</div>
							<div class="form-group row mb-3">
								<label for="token2" class="col-md-2 col-form-label">Yahoo ID3</label>
								<div class="col-md-10">
									<input type="text" class="form-control" id="token2" name="token2" value="{{ $user->yahoo_token2 }}" />
								</div>
							</div>
						</div><!-- /.card-body -->

						<div class="card-footer">
							<button type="button" class="btn btn-info float-left" onclick="saveYahoo();">保存</button>
						</div><!-- /.card-footer -->
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	var index = <?php echo $user->id; ?>;
	var isPermitted = <?php echo $user['is_permitted']; ?>;
	var isReg = 0;
	var user = <?php echo $user; ?>;
	var existingNumber = 0;

	const saveYahoo = () => {
		$.ajax({
			url: '{{ route("register_yahoo") }}',
			type: 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				token: $('input[name="token"]').val(),
				token1: $('input[name="token1"]').val(),
				token2: $('input[name="token2"]').val(),
			},
			success: function() {
				toastr.success('Yahoo情報が正常に保存されました。');
			}
		});
	};

	const saveAmazon = () => {
		$.ajax({
			url: '{{ route("register_amazon") }}',
			type: 'post',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {
				access_key: $('input[name="access_key"]').val(),
				secret_key: $('input[name="secret_key"]').val(),
				partner_tag: $('input[name="partner_tag"]').val(),
			},
			success: function() {
				toastr.success('Amazon情報が正常に保存されました。');
			}
		});
	};

	// const saveInfo = () => {
	// 	e.preventDefault();
	// 	let percent = $('select[name="register"]').val();
	// 	$.ajax({
	// 		url: './register_tracking',
	// 		type: 'get',
	// 		data: {
	// 			percent: $('select[name="register"]').val(),
	// 			lower: $('input[name="y_lower_bound"]').val(),
	// 			upper: $('input[name="y_upper_bound"]').val(),
	// 			ex_key: $('input[name="exclusion_key"]').val(),
	// 			fee: $('input[name="fee_include"]:checked').val()
	// 		},
	// 		success: function() {
	// 			toastr.success('設定が正常に保存されました。');
	// 		}
	// 	});
	// };

	const setRegState = (state) => {
		$.ajax({
			url: 'set_registering_state',
			type: 'get',
			data: {
				state: state
			}
		});
	};

	var newCsvResult = [];
	var scanInterval;
	var csvFile = '';
	$('body').on('change', '#csv_load', async function(e) {
		await $.ajax({
			url: 'get_registering_state',
			type: 'get',
			success: function(response) {
				isReg = response;
			}
		});
		
		if (isReg == 1) {
			toastr.error('別のファイルのアップロードが進行中です。<br/>少々お待ちください。');
			return;
		} else if (isReg == 0 || isReg == 2) {
			newCsvResult = [];
			var csv = $('#csv_load');
			csvFile = e.target.files[0];

			$('#progress-num').html('0');
			$('#percent-num').html('0%');
			$('#progress').attr('aria-valuenow', 0);
			$('#progress').css('width', '0%');

			var ext = csv.val().split(".").pop().toLowerCase();
			if ($.inArray(ext, ["csv"]) === -1) {
				toastr.error('CSVファイルを選択してください。');
				return false;
			}
			
			if (csvFile !== undefined) {
				reader = new FileReader();
				reader.onload = function (e) {
					$('#count').css('visibility', 'visible');
					csvResult = e.target.result.split(/\n/);

					for (const i of csvResult) {
						let code = i.split('\r');
						code = i.split('"');

						if (code.length == 1) {
							code = i.split('\r');
							if (code[0] != '') {
								newCsvResult.push(code[0]);
							}
						} else {
							newCsvResult.push(code[1]);
						}
					}
					
					if (newCsvResult[0] == 'ASIN') { newCsvResult.shift(); }

					$('#csv-name').html(csvFile.name);
					$('#total-num').html(newCsvResult.length);
				}
				reader.readAsText(csvFile);
			}
		}
	});

	const scanDB = () => {
		$.ajax({
			url: "./scan",
			type: "get",
			success: function(response) {
				$('#progress-num').html(response.register_number);
				let percent = Math.floor(response.register_number / response.len * 100);
				$('#percent-num').html(percent + '%');
				$('#progress').attr('aria-valuenow', percent);
				$('#progress').css('width', percent + '%');
				if (percent == 100) {
					clearInterval(scanInterval);
					toastr.success('正常に登録されました。');
					$('#csv_load').attr('disabled', false);
					$('#add_csv').attr('disabled', false);

					setTimeout(function() { location.href = '{{ route("item_list") }}'; }, 2000);
				}
			}
		});
	};

	const addCsv = async () => {
		if (!isPermitted) {
			toastr.error('管理者の承認をお待ちください。');
			return;
		}

		if (!newCsvResult.length) return;

		await $.ajax({
			url: '{{ route("register_exhibition") }}',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'post',
			data: {
				fall_pro: $('input[name="fall_pro"]').val(),
				web_hook: $('input[name="web_hook"]').val(),
			},
			success: function() {
				toastr.success('出品情報が正常に保存されました。');
			}
		});

		await $.ajax({
			url: '{{ route("get_allitems") }}',
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			type: 'post',
			function(response) {
				existingNumber = response.length;
			}
		});
		
		if (Number(existingNumber) > 4999) {
			toastr.error('5000件（登録上限数）登録しております。登録済みの商品を削除して再度お試しください。');
			return;
		}

		if (Number(existingNumber) + Number(newCsvResult.length) > 5000) {
			var diff = 5000 - Number(existingNumber);
			toastr.info(existingNumber + '件登録していますので' + diff + '件登録を開始します。');
			var newCsvResults = newCsvResult.slice(0, diff);
		} else {
			toastr.info(existingNumber + '件登録していますので' + newCsvResult.length + '件登録を開始します。');
			var newCsvResults = newCsvResult;
		}

		console.log(newCsvResults.length);
		$('#total-num').html(newCsvResults.length);

		let postData = {
			len: newCsvResults.length,
			name: csvFile.name
		};

		await $.ajax({
			url: './save_name_index',
			type: 'get',
			data: {
				len: newCsvResults.length,
				name: csvFile.name
			}
		});

		await setRegState(1);
		$.ajax({
			url: "https://xs786968.xsrv.jp/fmproxy/api/v1/yahoos/get_info",
			type: "post",
			data: {
				index: index,
				code_kind: $('input[name="code_kind"]:checked').val(),
				code: JSON.stringify(newCsvResults),
			},
			error: function(error) {
				setRegState(2);
				clearInterval(scanInterval);
			}
		});

		scanInterval = setInterval(scanDB, 5000);
		$("#csv_load").attr('disabled', true);
		$("#add_csv").attr('disabled', true);
	};

	const changePercent = (e) => {
		$.ajax({
			url: './change_percent',
			type: 'get',
			data: {
				pro: $(e.target).val()
			}
		});
	};
	
	$(document).ready(function() {
		isReg = <?php echo $user->is_registering; ?>;
		user = <?php echo $user; ?>;
		if (isReg == 1) {
			scanDB();
			setInterval(scanDB, 5000);
			$('#csv-name').html(user.name);
			$('#total-num').html(user.len);
			$('#csv_load').attr('disabled', true);
			$('#add_csv').attr('disabled', true);
		}
	});
</script>
@endsection