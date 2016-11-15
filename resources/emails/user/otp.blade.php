<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head></head>

<body>
    <div class="container" style="font-family: 'Helvetica Neue', Helvetica, sans-serif; margin: 2em 0;">
        <div class="content" style="background-color: white; border-radius: 2px; color: #333333; margin: auto; margin-top: 2em; max-width: 500px; padding: 1em; text-align: left;">
            <h2>{{ $company->name }} - One Time Password Check</h2>
            <h3 style="color: grey;">E-mail verification</h3>

            <div class="hero-container" style="border-radius: 10px; line-height: 2; text-align: left;">
                Your verification code is: <br>
                <strong>{{ $password }}</strong>
            </div>
        </div>
    </div>
</body>

</html>