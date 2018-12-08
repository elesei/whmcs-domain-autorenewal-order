<?php
add_hook('ShoppingCartValidateDomainsConfig', 1, function($vars) {
    foreach ($vars['donotrenew'] as $key => $val) {
        $_SESSION["cart"]["domains"][$key]["donotrenew"] = $val;
    }
});

add_hook('CartTotalAdjustment', 1, function($vars) {
    if (isset($_SESSION["cart"]) && isset($_SESSION["cart"]["domains"])) {
        foreach ($_SESSION["cart"]["domains"] as $ind => $domain) {
            if (isset($domain['donotrenew'])) {
                $donotrenew = (boolean)$domain['donotrenew'];
                $_SESSION["cart"]["domains"][$ind]["donotrenew"] = $donotrenew;
                if ($donotrenew) {
                    \WHMCS\Domain\Domain::where([
                        ['domain', $domain['domain']],
                        ['type', ucfirst($domain['type'])],
                        ['registrationperiod', $domain['regperiod']],
                        ['registrationdate', \Carbon\Carbon::now()->toDateString()]
                    ])->update(['donotrenew' => 1]);
                }
            }
        }
    }
});