<!DOCTYPE html>
<html lang="en">
  <head>
    @if(env('ONETRUST_API'))
        <!-- OneTrust Cookies Consent Notice start for brainshift.ai -->
        <script src="https://cdn.cookielaw.org/scripttemplates/otSDKStub.js" data-language="en-GB" type="text/javascript" charset="UTF-8" data-domain-script="@php echo env('ONETRUST_API') @endphp" ></script>
        <script type="text/javascript">
        function OptanonWrapper() { }
        </script>
        <!-- OneTrust Cookies Consent Notice end for brainshift.ai -->
    @endif
    @if(env('GTM_ID'))
        <!-- Start Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','@php echo env('GTM_ID') @endphp');</script>
        <!-- End Google Tag Manager -->
    @endif
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>
    <meta name="theme-color" content="#E60000">
    @inertiaHead
    @laravelPWA
  </head>
  <body>
    @inertia
  </body>
</html>
