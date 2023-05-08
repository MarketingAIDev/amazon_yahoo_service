@extends("layouts.auth")

@section("content")
	<div class="login-box">
		<div class="login-logo">
			<a href="#" style="color: white; text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;">{{ env('APP_NAME') }}</a>
		</div>

		<div class="card">
			<div class="card-body login-card-body">
				<div id="contact-page" class="container">
					<div class="bg">
						<div class="row">
							<div class="col-sm-12">
								<h3 class="title text-center">パスワードを変更する</h3>
							</div>
						</div>
						<div class="row center">
							<div class="login-form col-sm-12">          
                <form method="POST" action="{{ route('password.update') }}">
                  <!-- <form method="POST" action="{{ route('password.update') }}"> -->
                  @csrf

                  <input type="hidden" name="token" value="{{ $_GET['token'] }}">

                  <div class="form-group">
                    <label for="email">メルアドレス</label>
                    <input type="email" class="form-control" name="email" placeholder="abc@gmail.com" required autofocus />
                  </div>

                  <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" class="form-control" name="password"  placeholder="********" required autocomplete="password" />
                  </div>

                  <div class="form-group">
                    <label for="password_confirmation">パスワード確認</label>
                    <input type="password" class="form-control" name="password_confirmation"  placeholder="********" required autocomplete="new-password" />
                  </div>

                  <div class="flex items-center justify-end mt-4">
                    <button class="btn btn-primary float-right">
                      パスワードをリセット
                    </button>
                  </div>
                </form>
							</div>
						</div>
					</div>	
				</div><!--/#contact-page-->
			</div>
			<!-- /.login-card-body -->
		</div>
		
	</div>
@endsection