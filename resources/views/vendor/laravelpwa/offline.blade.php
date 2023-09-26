<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <meta name="theme-color" content="#E60000">
    @laravelPWA
</head>
<body>
<div class="main-container" id="container">
    <header class="site-header">
        <div class="site-header__inner">
            <a href="/" class="site-logo">
                <img src="../../images/logo.svg" alt="BrainShift">
            </a>
        </div>
    </header>
    <div class="main">
        <div class="wrap">
            <h1>
                You are currently not connected to any networks.
            </h1>
        </div>
    </div>
    <div class="footer">
        <div class="wrap footer__grid">
            <div class="footer__grid-info">
                <h2 class="f-sans">Our info</h2>
                <p> {{ Facades\App\Models\Settings::get('contact.company_name') }} </p>
                <p> {{ Facades\App\Models\Settings::get('contact.address1') }} </p>
                <p> {{ Facades\App\Models\Settings::get('contact.city') }} </p>
                <p> {{ Facades\App\Models\Settings::get('contact.county') }} </p>
                <p> {{ Facades\App\Models\Settings::get('contact.postcode') }} </p>
                <p>VAT:  {{ Facades\App\Models\Settings::get('contact.vat_number') }} </p>
            </div>
            <div class="footer__grid-buttons">
                <h2 class="footer__second-title f-sans">
                    Get in touch with us
                </h2>
                <p>
                    <a
                            href="tel:{{ Facades\App\Models\Settings::get('contact.phone') }}"
                            class="button button--thin footer-button--phone"
                    >
                        {{ Facades\App\Models\Settings::get('contact.phone') }}
                    </a>
                </p>
            </div>
            <div class="footer__copyright">
                <p>
                    {{ Facades\App\Models\Settings::get('contact.company_group') }}
                </p>
                <p>
                    All rights reserved {{ now()->year }}
                </p>
            </div>
        </div>
        <a
                href="#"
                class="footer__logo-container"
        >
            <img
                    class="footer__logo"
                    src="../../images/footer-logo.svg"
                    alt="Etex inspiring ways of living"
            />
        </a>
    </div>

</div>


</body>
</html>