
<!DOCTYPE html>
<html>
<head>
    <title>【{{ env('APP_NAME') }}】パスワード再設定のお知らせ</title>
</head>
<body>
  
    <br>
    {{$details["name"]}}　様<br>    
    <br>
    <br>
    いつも{{ env('APP_NAME') }}をご利用いただき、誠にありがとうございます。<br>
    パスワードの再設定は下記のURLよりお願いいたします。<br>
    <br>
    {{$details["pwr_url"]}}<br>
    <br>
    -------------------------------------------------------------<br>
    <br>
    今後とも{{ env('APP_NAME') }}をよろしくお願いいたします。<br>
    ご利用いただくなかでお困りごとがございましたらお気軽にご連絡ください。<br>
    https://gies0315.com/contact-2/<br>
    <br>

</body>
</html>