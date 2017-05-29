<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head></head>

<body>
  <div class="container" style="font-family: 'Helvetica Neue', Helvetica, sans-serif; margin: 2em 0;">
    <div class="content" style="background-color: white; border-radius: 2px; color: #333333; margin: auto; margin-top: 2em; max-width: 500px; padding: 1em; text-align: left;">
      <h2>{{ $dashboardName }}</h2>
      <h3 style="color: grey;">{{ $companyName }} Invitation</h3>

      <div class="hero-container" style="border-radius: 10px; line-height: 2; text-align: left;">
        <p>
          Hi {{ $name }}, you have been invited to join
          the <em>{{ $dashboardName }}</em> as a member of the company <em>{{ $companyName }}</em>
        </p>

        <a href="https://dashboard.idos.io/#/signup/{{ $signupHash }}" target="_blank" style="background-color: #1DB5EF; color: white; display: inline-block; margin-top: 2em; padding: 10px 20px; text-decoration: none;">Click here to join</a>

        <p>
          <em>
            This invitation expires in <strong>{{ date('d/m/Y', $invitation['expires']) }}</strong>.
          </em>
        </p>
      </div>
    </div>
  </div>
</body>

</html>
