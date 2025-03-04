<?php
// Start the session before any output
session_start();

// Get the cart ID from the URL - this is the only parameter we want in the URL
$cartId = isset($_GET['cartId']) ? $_GET['cartId'] : 'defaultCartId123';

// Check if data was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Store all POST data in the session under the cart ID key
    $_SESSION['cart_data'][$cartId] = [
        'parkId' => $_POST['parkId'] ?? 'defaultParkId123',
        'amount' => $_POST['amount'] ?? '',
        'minPayment' => $_POST['minPayment'] ?? '',
        'name' => $_POST['name'] ?? 'John Doe',
        'state' => $_POST['state'] ?? 'CA',
        'type' => $_POST['type'] ?? 'SHIPPING',
        'country' => $_POST['country'] ?? 'US',
        'city' => $_POST['city'] ?? 'San Francisco',
        'address1' => $_POST['address1'] ?? '123 Main St',
        'postal' => $_POST['postal'] ?? '94105',
        'email' => $_POST['email'] ?? 'test@gmail.com',
        'phone' => $_POST['phone'] ?? '123-456-7890'
    ];
    
    // Redirect to the same page with only the cart ID in the URL
    header("Location: booking-checkout.php?cartId=$cartId");
    exit();
}

// Try to load data from session if available
$data = isset($_SESSION['cart_data'][$cartId]) ? $_SESSION['cart_data'][$cartId] : [];

// Set default values or use session data if available
$parkId = $data['parkId'] ?? 'defaultParkId123';
$amount = $data['amount'] ?? '';
$minPayment = $data['minPayment'] ?? '';
$name = $data['name'] ?? 'John Doe';
$state = $data['state'] ?? 'CA';
$type = $data['type'] ?? 'SHIPPING';
$country = $data['country'] ?? 'US';
$city = $data['city'] ?? 'San Francisco';
$address1 = $data['address1'] ?? '123 Main St';
$postal = $data['postal'] ?? '94105';
$email = $data['email'] ?? 'test@gmail.com';
$phone = $data['phone'] ?? '123-456-7890';
$fullAddress = $address1 . ", " . $city . ", " . $state . " " . $postal . ", " . $country;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <style>
        .overlay {
        display: none;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: rgba(0, 0, 0, 0.5) !important;
        z-index: 1000 !important;
        }

        .spinner {
        display: none;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        width: 50px !important;
        height: 50px !important;
        margin-top: -25px !important;
        margin-left: -25px !important;

        border: 8px solid rgba(255, 255, 255, 0.3) !important;
        border-top: 8px solid #fff !important;
        border-radius: 50% !important;
        animation: spin 1s linear infinite !important;
        }

        /* Modal Container */
        .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        }

        /* Modal Content Box */
        .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #888;
        width: 40%;
        max-width: 400px;
        text-align: center;
        }

        /* Close Button */
        .close {
        color: #aaa;
        float: right;
        font-size: 20px;
        margin-right: 10px;
        cursor: pointer;
        }

        /* Text in Modal */
        .modal p {
        margin-top: 20px !important;
        margin-bottom: 20px !important;
        font-size: 16px;
        }

        /* OK Button */
        #closeModalButton {
        margin-top: 10px !important;
        background-color: #f44336;
        color: white;
        padding: 6px 15px !important;
        border: none;
        border-radius: 12px !important;
        cursor: pointer;
        font-size: 12px !important;
        }

        #closeModalButton:hover {
        background-color: #d32f2f;
        }

        .app-checkout-summary-site-title {
        font-size: .875rem !important;
        }

        .app-checkout-summary-site-dates,
        .app-checkout-summary-site-guests {
        font-weight: normal !important;
        font-size: .940rem !important;
        }

        .checkout-summary-item,
        .checkout-summary-item * {
            pointer-events: none !important;
        }


        .billing-form-field-toggle {
        background: 0 0 !important;
        border: none !important;
        color: #049959 !important;
        cursor: pointer !important;
        font-size: .8125rem !important;
        padding: 0 !important;
        text-decoration: underline !important;
        transition: color .1s ease-in-out, text-decoration-style .1s ease-in-out !important
        }

        /* .app-checkout-summary-site-guests {
        padding-bottom: 20px !important;
        } */

        .checkout-form-field-texting-detail {
        font-size: .875rem !important;
        margin-top: 10px !important;
        margin-left: calc(8px + .9375rem) !important
        }

        .checkout-form-field-texting-detail a {
        text-decoration: underline !important
        }

        .checkout-form-field-text-opt-in {
        margin-top: 10px !important;
        }

        .checkout-form-billing-address {
        border-radius: 4px !important;
        background: #f8f8f8 !important;
        margin-top: 20px !important;
        padding: 20px 20px 24px !important
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p {
        margin: 0 !important
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
        line-height: 1.25 !important
        }

        .checkout-form-billing-address-title {
        color: #767676 !important;
        font-size: 1rem !important;
        font-weight: 700 !important;
        margin-bottom: 16px !important
        }

        .checkout-form-field.mod-address-2 {
        margin-top: 10px !important
        }

        button {
        border-radius: 0 !important
        }

        [type=reset],
        [type=submit],
        button,
        html [type=button] {
        appearance: button !important
        }

        .checkout-form-field-toggle {
        background: 0 0 !important;
        border: none !important;
        color: #049959 !important;
        cursor: pointer !important;
        font-size: .8125rem !important;
        padding: 0 !important;
        text-decoration: underline !important;
        transition: color .1s ease-in-out, text-decoration-style .1s ease-in-out !important
        }

        button,
        select {
        text-transform: none
        }

        .checkout-form-field-input-dropdown {
        background: #f5f5f5 !important;
        border: 1px solid #bbb !important;
        border-radius: 3px !important;
        cursor: pointer !important;
        display: block !important;
        height: 2.5rem !important;
        max-width: 100% !important;
        padding: 0 12px !important
        }

        .checkout-form-field {
        display: block !important;
        max-width: 450px !important
        }

        .checkout-form-field+.checkout-form-field {
        margin-top: 16px !important
        }

        label {
        display: inline-block !important;
        margin-bottom: 0 !important
        }

        .checkout-form-field-label {
        display: block !important;
        font-size: .8125rem !important;
        font-weight: 700 !important;
        margin-bottom: 2px !important
        }

        *,
        ::after,
        ::before {
        box-sizing: border-box !important
        }

        [role=button],
        a,
        area,
        button,
        input:not([type=range]),
        label,
        select,
        summary,
        textarea {
        touch-action: manipulation !important
        }

        button,
        input,
        optgroup,
        select,
        textarea {
        margin: 0 !important;
        font-family: inherit !important;
        font-size: inherit !important;
        line-height: inherit !important
        }

        button,
        input {
        overflow: visible !important
        }

        .checkout-form-field-input {
        border: 1px solid #bbb !important;
        border-radius: 3px !important;
        display: block !important;
        min-height: 2.25rem !important;
        min-width: 0 !important;
        padding: 6px 10px !important;
        width: 100% !important
        }

        .checkout-form-field-input.mod-postal-code {
        max-width: 170px !important
        }

        .checkout-promo-code-form-field-input {
        text-transform: uppercase;
        }

        .checkout-content {
        padding-bottom: 60px !important;
        }

        .checkout-policy, .app-checkout-policy-park-terms-link, 
        .app-checkout-policy-campspot-terms-link, .app-checkout-policy-campspot-privacy-link {
        font-family: proxima-nova, helvetica, arial, sans-serif !important;
        font-size: .875rem !important;
        }

        .sensible-weather-terms-and-conditions, .sensible-weather-privacy-policy, .underline {
        font-size: .875rem !important;
        }

        .underline {
        font-size: .688rem !important;
        }

        .icon-paw,
        .icon-wheelchair {
        position: relative !important;
        top: .125em !important;
        }
        .checkout-summary-add-on {
        margin-top: 12px !important;
        }
        .checkout-summary-item {
        width: 100% !important;
        }
        .checkout-summary-item-title {
        font-size: .9375rem !important;
        font-weight: 700 !important; 
        line-height: 1.25 !important
        }
        .checkout-summary-item-price {
        font-size: .9375rem !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
        padding-left: 10px !important;
        text-align: right !important;
        vertical-align: top !important
        }
        .checkout-summary-item-details {
        font-size: .875rem
        }
        table {
        border-collapse: collapse
        }

        .checkout-summary-title {
        color: #767676;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 20px
        }
        .checkout-summary-site {
        margin-bottom: 20px
        }
        /**/

        /*! Exported with https://markflow.app */
        /*! Inherited styles */
        .checkout-content {
        border-collapse: separate !important;
        border-spacing: 0px 0px !important;
        caption-side: top !important;
        color: #000 !important;
        cursor: auto !important;
        direction: ltr !important;
        empty-cells: show !important;
        font: 16px/24px proxima-nova, helvetica, arial, sans-serif !important;
        letter-spacing: normal !important;
        list-style: none !important;
        orphans: 2 !important;
        quotes: auto !important;
        speak: normal !important;
        text-align: left !important;
        text-indent: 0 !important;
        text-transform: none !important;
        visibility: visible !important;
        white-space: normal !important;
        widows: 2 !important;
        word-spacing: 0px !important
        }

        .checkout {
        box-sizing: content-box !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding-left: 60px !important;
        padding-right: 60px !important;
        max-width: 1050px !important;
        padding-top: 120px !important
        }

        .checkout-heading {
        padding-bottom: 40px !important
        }

        .checkout-heading-title {
        font-size: 2rem !important;
        font-weight: 700 !important
        }

        .checkout-content {
        display: flex !important
        }

        .checkout-summary {
        flex-shrink: 0 !important;
        margin-left: 30px !important;
        margin-bottom: 80px !important;
        order: 2 !important;
        width: 370px !important
        }

        .checkout-summary-sticky {
        position: sticky !important;
        top: 40px !important
        }

        .checkout-summary-content {
        background: #fff !important;
        border: 1px solid #ccc !important;
        border-radius: 3px !important;
        padding: 30px !important
        }

        .checkout-summary-title {
        color: #767676 !important;
        font-size: 1rem !important;
        font-weight: 700 !important;
        margin-bottom: 20px !important
        }

        .checkout-summary-site {
        margin-bottom: 20px !important
        }

        .checkout-summary-item {
        width: 100% !important
        }

        .checkout-summary-sensible-weather {
        margin-top: 12px !important;
        padding: 12px 0 !important;
        border-top: 1px solid #ddd !important;
        border-bottom: 1px solid #ddd !important;
        }

        .checkout-summary-item-title {
        font-size: .9375rem !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
        }

        .checkout-summary-item-price {
        font-size: .9375rem !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
        padding-left: 10px !important;
        text-align: right !important;
        vertical-align: top !important
        }

        .checkout-summary-item-details {
        font-size: .875rem !important
        }

        table {
        border-collapse: collapse !important
        }

        .checkout-summary-totals {
        font-size: .9375rem !important;
        font-weight: 700 !important;
        width: 100% !important
        } 

        th {
        text-align: inherit !important
        }

        .checkout-summary-totals-amount {
        text-align: right !important
        }

        .checkout-summary-policies {
        font-size: .875rem !important;
        margin-top: 20px !important
        } 

        .checkout-summary-policies a {
        cursor: pointer !important;
        display: block !important;
        width: fit-content !important;
        font-size: inherit !important;
        line-height: inherit !important;
        }

        .checkout-form {
        flex-grow: 1 !important;
        order: 1 !important
        }

        .checkout-form-field.mod-address-2 {
        margin-top: 10px !important
        }

        .checkout-form-field-toggle {
        background: 0 0 !important;
        border: none !important;
        color: #049959 !important;
        cursor: pointer !important;
        font-size: .8125rem !important;
        padding: 0 !important;
        text-decoration: underline !important;
        transition: color .1s ease-in-out, text-decoration-style .1s ease-in-out !important
        }

        .checkout-form-field-input.mod-postal-code {
        max-width: 170px !important
        }

        .checkout-form-field.mod-email {
        margin-top: 30px !important
        } 

        .checkout-form-field.mod-phone {
        max-width: 240px !important
        }

        .checkout-form-field-texting-toggle {
        align-items: center !important;
        background: 0 0 !important;
        border: none !important;
        color: #049959 !important;
        cursor: pointer !important;
        font-size: .875rem !important;
        padding: 0 !important;
        text-decoration: underline !important;
        transition: color .1s ease-in-out !important
        }

        svg:not(:root) {
        overflow: hidden !important
        }

        .checkout-form-field-texting-toggle-icon {
        bottom: 1px !important;
        margin-left: 4px !important;
        position: relative !important
        }

        .checkout-form-field-texting-toggle-icon-path {
        fill: rgb(4, 153, 89) !important
        }

        .checkout-form-field-input-dropdown {
        background: #f5f5f5 !important;
        border: 1px solid #bbb !important;
        border-radius: 3px !important;
        cursor: pointer !important;
        display: block !important;
        height: 2.5rem !important;
        max-width: 100% !important;
        padding: 0 12px !important
        }

        .checkout-form-field-input-dropdown.mod-full {
        width: 100% !important
        }

        .checkout-form-field {
        display: block !important;
        max-width: 450px !important
        } 

        .checkout-form-field+.checkout-form-field {
        margin-top: 16px !important
        }

        .checkout-form-field-label {
        display: block !important;
        font-size: .8125rem !important;
        font-weight: 700 !important;
        margin-bottom: 2px !important
        }

        .checkout-form-field-label-note {
        color: #767676 !important;
        float: right !important;
        font-size: .8125rem !important;
        font-weight: 400 !important
        }

        textarea {
        overflow: auto !important;
        resize: vertical !important
        }

        .checkout-form-field-input {
        border: 1px solid #bbb !important;
        border-radius: 3px !important;
        display: block !important;
        min-height: 2.25rem !important;
        min-width: 0 !important;
        padding: 6px 10px !important;
        width: 100% !important
        }

        .checkout-form-section-group+.checkout-form-section-group {
        border-top: 1px solid #ddd !important;
        margin-top: 44px !important;
        padding-top: 40px !important
        }

        img {
        vertical-align: middle !important;
        border-style: none !important
        }

        .sensible-weather-logo[_ngcontent-campspot-aggregator-c115] {
        margin-top: -24px !important
        }

        .checkout-form-sensible-weather[_ngcontent-campspot-aggregator-c115] {
        font-size: .875rem !important;
        font-weight: 500 !important
        }

        dl,
        ol,
        ul {
        margin-top: 0 !important;
        margin-bottom: 0 !important
        }

        .checkout-form-sensible-weather[_ngcontent-campspot-aggregator-c115] ul[_ngcontent-campspot-aggregator-c115] {
        padding-inline-start: 20px !important
        }

        .checkout-form-sensible-weather[_ngcontent-campspot-aggregator-c115] .sensible-weather-privacy-policy[_ngcontent-campspot-aggregator-c115],
        .checkout-form-sensible-weather[_ngcontent-campspot-aggregator-c115] .sensible-weather-terms-and-conditions[_ngcontent-campspot-aggregator-c115] {
        text-decoration: underline !important
        }

        .checkout-form-field-checkbox {
        display: flex !important;
        font-size: .9375rem !important;
        width: fit-content !important
        }

        input[type=checkbox],
        input[type=radio] {
        box-sizing: border-box !important;
        padding: 0 !important
        }

        .checkout-form-field-checkbox input {
        flex-shrink: 0 !important;
        height: 1em !important;
        margin-right: 8px !important;
        margin-top: .25em !important;
        width: 1em !important;
        }

        label {
        display: inline-block !important;
        margin-bottom: 0 !important
        }

        .checkout-form-sensible-weather[_ngcontent-campspot-aggregator-c115] .price[_ngcontent-campspot-aggregator-c115] {
        color: #049959;
        font-weight: 700 !important
        }

        .checkout-form-sensible-weather[_ngcontent-campspot-aggregator-c115]>[_ngcontent-campspot-aggregator-c115]:not(:first-child) {
        margin-top: 15px !important
        }

        .checkout-form-sensible-weather[_ngcontent-campspot-aggregator-c115] .smaller[_ngcontent-campspot-aggregator-c115] {
        font-size: .688rem !important
        }

        a {
        background-color: transparent !important;
        color: #049959;
        text-decoration: none !important;
        transition: color .1s ease-in-out, text-decoration-style .1s ease-in-out !important
        }

        .checkout-form-sensible-weather[_ngcontent-campspot-aggregator-c115] .underline[_ngcontent-campspot-aggregator-c115] {
        text-decoration: underline !important
        }

        .checkout-form-recaptcha-card-connect,
        .checkout-form-submit {
        margin-top: 40px !important
        }

        [role=button],
        a,
        area,
        button,
        input:not([type=range]),
        label,
        select,
        summary,
        textarea {
        touch-action: manipulation !important
        }

        button {
        border-radius: 0 !important
        }

        button,
        input,
        optgroup,
        select,
        textarea {
        margin: 0 !important;
        font-family: inherit !important;
        font-size: inherit !important;
        line-height: inherit !important
        }

        button,
        input {
        overflow: visible !important
        }

        button,
        select {
        text-transform: none !important
        }

        [type=reset],
        [type=submit],
        button,
        html [type=button] {
        appearance: button !important
        }

        .checkout-form-submit-button {
        cursor: pointer !important;
        transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
        user-select: none !important;
        white-space: nowrap !important;
        background: #1cbb64 !important;
        border: 1px solid #1cbb64 !important;
        box-shadow: rgba(0, 0, 0, .12) 0 1px 1px !important;
        color: #fff !important;
        font-weight: 600 !important;
        border-radius: 4px !important;
        font-size: 1.125rem !important;
        min-height: 60px !important;
        min-width: 250px !important;
        padding: 12px 30px !important
        }

        article,
        aside,
        dialog,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        main,
        nav,
        section {
        display: block !important
        }

        .checkout-form-section {
        background: #fff !important;
        border-radius: 3px !important;
        box-shadow: rgba(0, 0, 0, .08) 0 1px 1px, rgba(0, 0, 0, .12) 0 1px 3px 1px !important;
        margin-bottom: 30px !important;
        padding: 40px !important;
        position: relative !important
        } 

        .checkout-form-section.mod-disabled {
        background: 0 0 !important;
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        box-shadow: none !important
        }

        *,
        ::after,
        ::before {
        box-sizing: border-box !important
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p {
        margin: 0 !important
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
        line-height: 1.25 !important
        }

        .checkout-form-section-title {
        color: #767676 !important;
        font-size: 1rem !important;
        margin-bottom: 24px !important
        }

        .checkout-form-section-title.mod-disabled,
        .checkout-form-section-title.mod-static {
        margin-bottom: 0 !important
        }

        @media (max-width:1199px) {
        .checkout {
            padding-left: 40px !important;
            padding-right: 40px !important
        }
        }

        @media (max-width:575px) {
        .checkout {
            padding-top: 50px !important
        }

        .checkout-heading {
            padding-bottom: 30px !important
        }
        }

        @media (max-width:1024px) {
        .checkout {
            max-width: 720px !important
        }

        .checkout-content {
            display: block !important
        }

        .checkout-summary {
            margin: 0 0 30px !important;
            order: 1 !important;
            width: 100% !important
        } 

        .checkout-summary-sticky {
            position: static !important
        }
        }

        @media (max-width:767px) {
        .checkout {
            padding-left: 20px !important;
            padding-right: 20px !important
        }

        .checkout-heading-title {
            font-size: 1.75rem !important
        }

        .checkout-summary {
            margin-bottom: 24px !important
        }

        .checkout-summary-content {
            padding: 40px !important
        }
        }

        @media (max-width:1024px) {
        .checkout-form {
            order: 2 !important
        }
        }

        @media (max-width:575px) {
        .checkout-summary-content {
            padding: 20px !important
        }

        .checkout-form-submit-button {
            width: 100% !important
        }

        .checkout-form-section {
            padding: 20px 20px 24px !important
        }
        }

        /* 2 */
        .checkout-form-readonly-edit {
        cursor: pointer !important;
        transition: background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
        user-select: none !important;
        white-space: nowrap !important;
        background: #fff !important;
        border: 1px solid #ccc !important;
        border-radius: 3px !important;
        font-size: .8125rem !important;
        min-width: 60px !important;
        min-height: 1.75rem !important;
        padding: 0 10px !important;
        position: absolute !important;
        right: 40px !important;
        top: 32px !important
        }

        .checkout-form-readonly-field+.checkout-form-readonly-field {
        margin-top: 16px !important
        } 

        .checkout-form-readonly-field-label {
        font-size: .8125rem !important;
        font-weight: 700 !important
        }

        .checkout-form-payment-amount.is-selected,
        .checkout-form-payment-method.is-selected {
        border: 2px solid #06f !important
        }

        .checkout-form-payment-amount-selectable.is-selected,
        .checkout-form-payment-method-selectable.is-selected {
        min-height: 88px !important;
        padding: 21px 23px !important
        }

        .checkout-form-payment-amount-selectable-value {
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        line-height: 1.25 !important
        }

        .checkout-form-payment-amount,
        .checkout-form-payment-method {
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
        transition: border-color .1s ease-in-out !important
        }

        .checkout-form-payment-amount+.checkout-form-payment-amount,
        .checkout-form-payment-method+.checkout-form-payment-method {
        margin-top: 16px !important
        }

        .checkout-form-payment-amount-selectable,
        .checkout-form-payment-method-selectable {
        align-items: center !important;
        cursor: pointer !important;
        display: flex !important;
        min-height: 90px !important;
        padding: 22px 24px !important
        }

        .checkout-form-payment-amount-selectable-radio,
        .checkout-form-payment-method-selectable-radio {
        margin-right: 20px !important
        }

        input[type=checkbox],
        input[type=radio] {
        box-sizing: border-box !important;
        padding: 0 !important
        }

        .checkout-form-payment-amount-selectable-radio input,
        .checkout-form-payment-method-selectable-radio input {
        cursor: pointer !important;
        display: block !important;
        flex-shrink: 0 !important;
        height: 16px !important;
        width: 16px !important
        }

        .checkout-form-payment-amount-selectable-label {
        color: #666 !important;
        font-size: .8125rem !important;
        font-weight: 600 !important;
        margin-bottom: 2px !important
        }

        .checkout-form-payment-amount-selectable-custom-amount {
        position: relative !important
        }

        .checkout-form-payment-amount-selectable-custom-amount-input {
        border: 1px solid #bbb !important;
        border-radius: 3px !important;
        display: block !important;
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        max-width: 170px !important;
        min-height: 2.25rem !important;
        min-width: 0 !important;
        transition: .15s ease-in-out !important;
        padding: 6px 10px 6px 32px !important;
        width: 100% !important
        }

        .checkout-form-field-footnote {
        color: #767676 !important;
        font-size: .8125rem !important;
        line-height: 1.25 !important;
        margin-top: 4px !important
        }

        .checkout-promo-code {
        margin-top: 20px !important
        }

        .checkout-promo-code-toggle {
        background: 0 0 !important;
        border: none !important;
        color: #049959;
        cursor: pointer !important;
        font-size: .8125rem !important;
        padding: 0 !important;
        transition: color .1s ease-in-out, text-decoration-style .1s ease-in-out !important;
        text-decoration: underline !important
        }

        .checkout-promo-code-form {
        background: #f8f8f8 !important;
        border-radius: 3px !important;
        margin-top: 4px !important;
        padding: 12px 16px !important
        }

        .checkout-promo-code-form-label {
        display: block !important;
        font-size: .8125rem !important;
        font-weight: 700 !important;
        margin-bottom: 2px !important
        }

        .checkout-promo-code-form-field {
        max-width: 300px !important;
        position: relative !important
        }

        .checkout-promo-code-form-field-input {
        border: 1px solid #bbb !important;
        border-radius: 3px !important;
        display: block !important;
        font-size: .875rem !important;
        min-height: 2rem !important;
        padding: 6px 10px !important;
        width: 100% !important
        }

        .checkout-promo-code-feedback {
        color: #707070 !important;
        font-size: .8125rem !important;
        line-height: 1.25 !important;
        margin-top: 4px !important
        }

        .checkout-form-recaptcha-card-connect,
        .checkout-form-submit {
        margin-top: 40px !important
        }

        /* summary  */

        /*! Exported with https://markflow.app */
        /*! Inherited styles */
        body {
        border-collapse: separate !important;
        border-spacing: 0px 0px !important;
        caption-side: top !important;
        color: #000 !important;
        cursor: auto !important;
        direction: ltr !important;
        empty-cells: show !important;
        font: 16px/24px proxima-nova, helvetica, arial, sans-serif !important;
        letter-spacing: normal !important;
        list-style: none !important;
        orphans: 2 !important;
        quotes: auto !important;
        speak: normal !important;
        text-align: left !important;
        text-indent: 0 !important;
        text-transform: none !important;
        visibility: visible !important;
        white-space: normal !important;
        widows: 2 !important;
        word-spacing: 0px
        }

        .checkout-summary-content {
        background: #fff !important;
        border: 1px solid #ccc !important;
        border-radius: 3px !important;
        padding: 30px !important
        }

        .checkout-summary-title {
        color: #767676 !important;
        font-size: 1rem !important;
        font-weight: 700 !important;
        margin-bottom: 20px !important
        }

        .checkout-summary-site {
        margin-bottom: 20px !important
        }

        .checkout-summary-sensible-weather {
        margin-top: 12px !important;
        padding: 12px 0 !important;
        border-top: 1px solid #ddd !important;
        border-bottom: 1px solid #ddd !important
        }

        .checkout-summary-item {
        width: 100% !important
        }

        .checkout-summary-item-title {
        font-size: .9375rem !important;
        font-weight: 700 !important;
        line-height: 1.25 !important
        }

        .checkout-summary-item-price {
        font-size: .9375rem !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
        padding-left: 10px !important;
        text-align: right !important;
        vertical-align: top !important
        }

        .checkout-summary-item-details {
        font-size: .875rem !important
        }

        table {
        border-collapse: collapse !important
        } 

        .checkout-summary-totals {
        font-size: .9375rem !important;
        font-weight: 700 !important;
        width: 100% !important
        }

        th {
        text-align: inherit !important
        }

        .checkout-summary-totals-amount {
        text-align: right !important
        }

        .checkout-summary-policies {
        font-size: .875rem !important;
        margin-top: 20px !important
        }

        *,
        ::after,
        ::before {
        box-sizing: border-box !important
        }

        .checkout-content a {
        background-color: transparent !important;
        color: #049959;
        text-decoration: none !important;
        transition: color .1s ease-in-out, text-decoration-style .1s ease-in-out !important
        }

        [role=button],
        a,
        area,
        button,
        input:not([type=range]),
        label,
        select,
        summary,
        textarea {
        touch-action: manipulation !important
        }

        .checkout-summary-policies a {
        cursor: pointer !important;
        display: block !important;
        width: fit-content !important
        }

        @media (max-width:767px) {
        .checkout-summary-content {
            padding: 40px !important
        }
        }

        @media (max-width:575px) {
        .checkout-summary-content {
            padding: 20px !important
        }
        }

        /* Custom */
        table tbody>tr:nth-child(odd)>td,
        table tbody>tr:nth-child(odd)>th {
        background-color: inherit !important;
        }

        table td,
        table th {
        padding: 0px !important;
        vertical-align: auto !important;
        border: 0px !important;
        }

        /* Payment Method */
        .checkout-form-field-cardconnect-iframe {
        display: block !important;
        height: 38px !important;
        width: 100% !important
        }

        .checkout-form-field-cc-exp {
        align-items: center !important;
        border: 1px solid #bbb !important;
        border-radius: 3px !important;
        display: flex !important;
        width: 120px !important
        }

        .checkout-form-field-cc-exp-divider {
        color: #767676 !important;
        padding: 0 4px !important
        }

        .checkout-form-field-input.mod-cc-exp {
        border: none !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        text-align: center !important
        }

        .checkout-form-field-input.mod-cc-cvv {
        max-width: 120px !important
        }

        .checkout-form-field-checkbox.mod-billing-address {
        margin-top: 30px !important
        }

        .checkout-form-agreement {
        font-size: .875rem !important;
        display: flex !important
        }

        .checkout-form-field-checkbox {
        display: flex !important;
        font-size: .9375rem !important;
        width: fit-content !important;
        margin-top: 5px !important;
        }

        input[type=checkbox],
        input[type=radio] {
        box-sizing: border-box !important;
        padding: 0 !important
        } 
    </style>
</head>
<body>
    <div class="a">
        <div class="checkout">
            <section id="app-checkout-heading-title" class="checkout-heading">
                <h1 class="checkout-heading-title app-heading-title"> Checkout </h1>
            </section>
            <section class="checkout-content">
                <div class="checkout-summary">
                    <div class="checkout-summary-sticky">
                        <div class="checkout-summary-content">
                            <div class="checkout-summary-title"> Order Summary </div>
                            <div class="checkout-summary-site">
                                <table class="checkout-summary-item">
                                    <tbody id="order-summary-table-body">
                                        <!-- Dynamic content will be injected here by JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                            <table class="checkout-summary-totals">
                                <tbody>
                                    <tr>
                                        <th scope="row"> Order Total </th>
                                        <td class="checkout-summary-totals-amount app-checkout-total checkout-summary-order-total">
                                            $<span id="order-total"><?php echo htmlspecialchars($amount); ?></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="checkout-summary-policies">
                                <a target="_blank" class="app-order-summary-park-cancellation-policy-link" href="/cancellation-policy-?>"> Cancellation Policy </a>
                                <a target="_blank" class="app-order-summary-park-refund-policy-link" href="/refund-policy-?>"> Refund Policy </a>
                            </div>
                        </div>
                    </div>
                </div>
                <form novalidate="" class="checkout-form ng-untouched ng-pristine ng-valid" data-gtm-form-interact-id="0">
                    <card-connect-checkout>
                        <section class="checkout-form-error-summary">
                            <!-- Error summary if needed -->
                        </section>
                        <section class="checkout-form-section">
                            <h1 class="checkout-form-section-title"> 1. Guest Information </h1>
                            <div>
                            <div class="checkout-form-section-group">
                                <div id="guest-full-name" class="checkout-form-field">
                                <label for="guest-full-name-input" class="checkout-form-field-label"> Full Name* </label>
                                <input type="text" name="guest-full-name" required id="guest-full-name-input" aria-describedby="guest-full-name-error" class="checkout-form-field-input ng-untouched ng-pristine ng-valid" value="<?php echo htmlspecialchars($name); ?>">
                     
                                </div>
                                <div id="guest-address" class="checkout-form-field">
                                <label for="guest-address-line-1" class="checkout-form-field-label"> Address - Line 1* </label>
                                <input type="text" name="guest-address-line-1" required id="guest-address-line-1" aria-describedby="guest-address-line-1-error" class="checkout-form-field-input ng-untouched ng-pristine ng-valid" value="<?php echo htmlspecialchars($address1); ?>">
                   
                                </div>
      
                                <div class="checkout-form-field mod-address-2">
                                    <button class="checkout-form-field-toggle"> Add Address Line 2 </button>
                                </div>
                                
                                <div class="checkout-form-field">
                                <label for="guest-country" class="checkout-form-field-label"> Country* </label>
                                <select id="guest-country" name="guest-country" class="checkout-form-field-input-dropdown app-guest-info-country-select ng-untouched ng-pristine ng-valid">
                                    <option> United States </option>
                                    <option> Canada </option><!---->
                                    <option> Afghanistan </option>
                                    <option> Åland Islands </option>
                                    <option> Albania </option>
                                    <option> Algeria </option>
                                    <option> American Samoa </option>
                                    <option> Andorra </option>
                                    <option> Angola </option>
                                    <option> Anguilla </option>
                                    <option> Antarctica </option>
                                    <option> Antigua and Barbuda </option>
                                    <option> Argentina </option>
                                    <option> Armenia </option>
                                    <option> Aruba </option>
                                    <option> Australia </option>
                                    <option> Austria </option>
                                    <option> Azerbaijan </option>
                                    <option> Bahamas </option>
                                    <option> Bahrain </option>
                                    <option> Bangladesh </option>
                                    <option> Barbados </option>
                                    <option> Belarus </option>
                                    <option> Belgium </option>
                                    <option> Belize </option>
                                    <option> Benin </option>
                                    <option> Bermuda </option>
                                    <option> Bhutan </option>
                                    <option> Bolivia </option>
                                    <option> Bosnia and Herzegovina </option>
                                    <option> Botswana </option>
                                    <option> Bouvet Island </option>
                                    <option> Brazil </option>
                                    <option> British Indian Ocean Territory </option>
                                    <option> British Virgin Islands </option>
                                    <option> Brunei </option>
                                    <option> Bulgaria </option>
                                    <option> Burkina Faso </option>
                                    <option> Burundi </option>
                                    <option> Cambodia </option>
                                    <option> Cameroon </option>
                                    <option> Cape Verde </option>
                                    <option> Cayman Islands </option>
                                    <option> Central African Republic </option>
                                    <option> Chad </option>
                                    <option> Chile </option>
                                    <option> China </option>
                                    <option> Christmas Island </option>
                                    <option> Cocos (Keeling) Islands </option>
                                    <option> Colombia </option>
                                    <option> Comoros </option>
                                    <option> Cook Islands </option>
                                    <option> Costa Rica </option>
                                    <option> Croatia </option>
                                    <option> Cuba </option>
                                    <option> Curaçao </option>
                                    <option> Cyprus </option>
                                    <option> Czechia </option>
                                    <option> Denmark </option>
                                    <option> Djibouti </option>
                                    <option> Dominica </option>
                                    <option> Dominican Republic </option>
                                    <option> DR Congo </option>
                                    <option> Ecuador </option>
                                    <option> Egypt </option>
                                    <option> El Salvador </option>
                                    <option> Equatorial Guinea </option>
                                    <option> Eritrea </option>
                                    <option> Estonia </option>
                                    <option> Ethiopia </option>
                                    <option> Falkland Islands </option>
                                    <option> Faroe Islands </option>
                                    <option> Fiji </option>
                                    <option> Finland </option>
                                    <option> France </option>
                                    <option> French Guiana </option>
                                    <option> French Polynesia </option>
                                    <option> French Southern and Antarctic Lands </option>
                                    <option> Gabon </option>
                                    <option> Gambia </option>
                                    <option> Georgia </option>
                                    <option> Germany </option>
                                    <option> Ghana </option>
                                    <option> Gibraltar </option>
                                    <option> Greece </option>
                                    <option> Greenland </option>
                                    <option> Grenada </option>
                                    <option> Guadeloupe </option>
                                    <option> Guam </option>
                                    <option> Guatemala </option>
                                    <option> Guernsey </option>
                                    <option> Guinea </option>
                                    <option> Guinea-Bissau </option>
                                    <option> Guyana </option>
                                    <option> Haiti </option>
                                    <option> Heard Island and McDonald Islands </option>
                                    <option> Honduras </option>
                                    <option> Hong Kong </option>
                                    <option> Hungary </option>
                                    <option> Iceland </option>
                                    <option> India </option>
                                    <option> Indonesia </option>
                                    <option> Iran </option>
                                    <option> Iraq </option>
                                    <option> Ireland </option>
                                    <option> Isle of Man </option>
                                    <option> Israel </option>
                                    <option> Italy </option>
                                    <option> Ivory Coast </option>
                                    <option> Jamaica </option>
                                    <option> Japan </option>
                                    <option> Jersey </option>
                                    <option> Jordan </option>
                                    <option> Kazakhstan </option>
                                    <option> Kenya </option>
                                    <option> Kiribati </option>
                                    <option> Kosovo </option>
                                    <option> Kuwait </option>
                                    <option> Kyrgyzstan </option>
                                    <option> Laos </option>
                                    <option> Latvia </option>
                                    <option> Lebanon </option>
                                    <option> Lesotho </option>
                                    <option> Liberia </option>
                                    <option> Libya </option>
                                    <option> Liechtenstein </option>
                                    <option> Lithuania </option>
                                    <option> Luxembourg </option>
                                    <option> Macau </option>
                                    <option> Macedonia </option>
                                    <option> Madagascar </option>
                                    <option> Malawi </option>
                                    <option> Malaysia </option>
                                    <option> Maldives </option>
                                    <option> Mali </option>
                                    <option> Malta </option>
                                    <option> Marshall Islands </option>
                                    <option> Martinique </option>
                                    <option> Mauritania </option>
                                    <option> Mauritius </option>
                                    <option> Mayotte </option>
                                    <option> Mexico </option>
                                    <option> Micronesia </option>
                                    <option> Moldova </option>
                                    <option> Monaco </option>
                                    <option> Mongolia </option>
                                    <option> Montenegro </option>
                                    <option> Montserrat </option>
                                    <option> Morocco </option>
                                    <option> Mozambique </option>
                                    <option> Myanmar </option>
                                    <option> Namibia </option>
                                    <option> Nauru </option>
                                    <option> Nepal </option>
                                    <option> Netherlands </option>
                                    <option> New Caledonia </option>
                                    <option> New Zealand </option>
                                    <option> Nicaragua </option>
                                    <option> Niger </option>
                                    <option> Nigeria </option>
                                    <option> Niue </option>
                                    <option> Norfolk Island </option>
                                    <option> North Korea </option>
                                    <option> Northern Mariana Islands </option>
                                    <option> Norway </option>
                                    <option> Oman </option>
                                    <option> Pakistan </option>
                                    <option> Palau </option>
                                    <option> Palestine </option>
                                    <option> Panama </option>
                                    <option> Papua New Guinea </option>
                                    <option> Paraguay </option>
                                    <option> Peru </option>
                                    <option> Philippines </option>
                                    <option> Pitcairn Islands </option>
                                    <option> Poland </option>
                                    <option> Portugal </option>
                                    <option> Puerto Rico </option>
                                    <option> Qatar </option>
                                    <option> Republic of the Congo </option>
                                    <option> Romania </option>
                                    <option> Russia </option>
                                    <option> Rwanda </option>
                                    <option> Réunion </option>
                                    <option> Saint Barthélemy </option>
                                    <option> Saint Kitts and Nevis </option>
                                    <option> Saint Lucia </option>
                                    <option> Saint Martin </option>
                                    <option> Saint Pierre and Miquelon </option>
                                    <option> Saint Vincent and the Grenadines </option>
                                    <option> Samoa </option>
                                    <option> San Marino </option>
                                    <option> Saudi Arabia </option>
                                    <option> Senegal </option>
                                    <option> Serbia </option>
                                    <option> Seychelles </option>
                                    <option> Sierra Leone </option>
                                    <option> Singapore </option>
                                    <option> Sint Maarten </option>
                                    <option> Slovakia </option>
                                    <option> Slovenia </option>
                                    <option> Solomon Islands </option>
                                    <option> Somalia </option>
                                    <option> South Africa </option>
                                    <option> South Georgia </option>
                                    <option> South Korea </option>
                                    <option> South Sudan </option>
                                    <option> Spain </option>
                                    <option> Sri Lanka </option>
                                    <option> Sudan </option>
                                    <option> Suriname </option>
                                    <option> Svalbard and Jan Mayen </option>
                                    <option> Swaziland </option>
                                    <option> Sweden </option>
                                    <option> Switzerland </option>
                                    <option> Syria </option>
                                    <option> São Tomé and Príncipe </option>
                                    <option> Taiwan </option>
                                    <option> Tajikistan </option>
                                    <option> Tanzania </option>
                                    <option> Thailand </option>
                                    <option> Timor-Leste </option>
                                    <option> Togo </option>
                                    <option> Tokelau </option>
                                    <option> Tonga </option>
                                    <option> Trinidad and Tobago </option>
                                    <option> Tunisia </option>
                                    <option> Turkey </option>
                                    <option> Turkmenistan </option>
                                    <option> Turks and Caicos Islands </option>
                                    <option> Tuvalu </option>
                                    <option> Uganda </option>
                                    <option> Ukraine </option>
                                    <option> United Arab Emirates </option>
                                    <option> United Kingdom </option>
                                    <option> United States Minor Outlying Islands </option>
                                    <option> United States Virgin Islands </option>
                                    <option> Uruguay </option>
                                    <option> Uzbekistan </option>
                                    <option> Vanuatu </option>
                                    <option> Vatican City </option>
                                    <option> Venezuela </option>
                                    <option> Vietnam </option>
                                    <option> Wallis and Futuna </option>
                                    <option> Western Sahara </option>
                                    <option> Yemen </option>
                                    <option> Zambia </option>
                                    <option> Zimbabwe </option><!---->
                                </select>
                                </div>
                                <div id="guest-postal-code" class="checkout-form-field">
                                <label for="guest-postal-code-input" class="checkout-form-field-label"> Postal Code* </label>
                                <input type="text" name="guest-postal-code" required minlength="5" id="guest-postal-code-input" aria-describedby="guest-postal-code-error" class="checkout-form-field-input mod-postal-code ng-untouched ng-pristine ng-valid" value="<?php echo htmlspecialchars($postal); ?>">

                                </div>
                                <div id="guest-city" class="checkout-form-field">
                                <label for="guest-city-input" class="checkout-form-field-label"> City* </label>
                                <input type="text" name="guest-city" required id="guest-city-input" aria-describedby="guest-city-error" class="checkout-form-field-input ng-untouched ng-pristine ng-valid" value="<?php echo htmlspecialchars($city); ?>">
                                </div>
                                <div id="guest-state" class="checkout-form-field">
                                <label for="guest-state-select" class="checkout-form-field-label"> State* </label>
                                
                                <select name="guest-state" id="guest-state-select" aria-describedby="guest-state-error" class="checkout-form-field-input-dropdown ng-untouched ng-pristine ng-valid">
                                    <option> Alabama </option>
                                    <option> Alaska </option>
                                    <option> American Samoa </option>
                                    <option> Arizona </option>
                                    <option> Arkansas </option>
                                    <option> Armed Forces (AA) </option>
                                    <option> Armed Forces (AE) </option>
                                    <option> Armed Forces (AP) </option>
                                    <option> California </option>
                                    <option> Colorado </option>
                                    <option> Connecticut </option>
                                    <option> Delaware </option>
                                    <option> District of Columbia </option>
                                    <option> Federated States of Micronesia </option>
                                    <option> Florida </option>
                                    <option> Georgia </option>
                                    <option> Guam </option>
                                    <option> Hawaii </option>
                                    <option> Idaho </option>
                                    <option> Illinois </option>
                                    <option> Indiana </option>
                                    <option> Iowa </option>
                                    <option> Kansas </option>
                                    <option> Kentucky </option>
                                    <option> Louisiana </option>
                                    <option> Maine </option>
                                    <option> Marshall Islands </option>
                                    <option> Maryland </option>
                                    <option> Massachusetts </option>
                                    <option> Michigan </option>
                                    <option> Minnesota </option>
                                    <option> Mississippi </option>
                                    <option> Missouri </option>
                                    <option> Montana </option>
                                    <option> Nebraska </option>
                                    <option> Nevada </option>
                                    <option> New Hampshire </option>
                                    <option> New Jersey </option>
                                    <option> New Mexico </option>
                                    <option> New York </option>
                                    <option> North Carolina </option>
                                    <option> North Dakota </option>
                                    <option> Northern Mariana Islands </option>
                                    <option> Ohio </option>
                                    <option> Oklahoma </option>
                                    <option> Oregon </option>
                                    <option> Palau </option>
                                    <option> Pennsylvania </option>
                                    <option> Puerto Rico </option>
                                    <option> Rhode Island </option>
                                    <option> South Carolina </option>
                                    <option> South Dakota </option>
                                    <option> Tennessee </option>
                                    <option> Texas </option>
                                    <option> Utah </option>
                                    <option> Vermont </option>
                                    <option> Virgin Islands </option>
                                    <option> Virginia </option>
                                    <option> Washington </option>
                                    <option> West Virginia </option>
                                    <option> Wisconsin </option>
                                    <option> Wyoming </option><!---->
                                </select>

                                </div>
  
                                <div id="guest-email" class="checkout-form-field mod-email">
                                <label for="guest-email-input" class="checkout-form-field-label"> Email Address* <div class="checkout-form-field-label-note"> Your order confirmation will be sent here </div>
                                </label>
                                <input type="email" name="guest-email" required id="guest-email-input" aria-describedby="guest-email-error" class="checkout-form-field-input ng-untouched ng-pristine ng-valid" value="<?php echo htmlspecialchars($email); ?>">

                                </div>
                                <div id="guest-phone-number" class="checkout-form-field mod-phone">
                                <label for="guest-phone-number-input" class="checkout-form-field-label"> Phone Number* <div class="checkout-form-field-label-note"> (###) ###-#### </div>
                                </label>
                                <input type="tel" name="guest-phone-number" required id="guest-phone-number-input" aria-describedby="guest-phone-number-error" phonenumberinputdirective="" class="checkout-form-field-input ng-untouched ng-pristine ng-valid" value="<?php echo htmlspecialchars($phone); ?>">

                                </div>
                                <div class="checkout-form-field">
                                    <div class="checkout-form-field-checkbox">
                                        <input id="checkout-form-field-text-opt-in" type="checkbox">
                                        <label for="checkout-form-field-text-opt-in" style="margin-top: 3px;">
                                            Receive text alerts about this reservation. 
                                            <button type="button" class="checkout-form-field-texting-toggle" aria-pressed="true"> 
                                                View Details
                                                <svg width="10" height="7" class="checkout-form-field-texting-toggle-icon mod-hide">
                                                    <path fill-rule="nonzero" d="M5 5.004L8.996 1 10 1.997 5 7 0 1.997 1 1z" class="checkout-form-field-texting-toggle-icon-path"></path>
                                                </svg>
                                            </button>
                                        </label>
                                    </div>
                                    <div id="checkout-form-field-texting-detail" style="display: none; margin-top: 15px; font-size: .875rem">
                                        By opting in, you authorize Campspot and its partners to send recurring transactional and promotional text messages. Frequency may vary. Consent is optional, not a condition of purchase. Message and data rates may apply 
                                        <a target="_blank" href="https://www.campspot.com/about/terms-and-conditions" style="font-size: .875rem;">Terms</a> and 
                                        <a target="_blank" href="https://www.campspot.com/about/privacy" style="font-size: .875rem;">Privacy Notice</a> apply.
                                    </div>
                                </div>
                            </div>
                            <div class="checkout-form-section-group">
                                <div class="checkout-form-field">
                                <label for="guest-referral-source" class="checkout-form-field-label"> How did you hear about us? <div class="checkout-form-field-label-note"> Optional </div>
                                </label>
                                <select name="guest-referral-source" id="guest-referral-source" class="checkout-form-field-input-dropdown mod-full ng-untouched ng-pristine ng-valid">
                                    <option value=""></option>
                                    <option value="1: Friend / Family"> Friend / Family </option>
                                    <option value="2: FMCA Rocky Mountain Ramble"> FMCA Rocky Mountain Ramble </option>
                                    <option value="3: Google"> Google </option>
                                    <option value="4: Trip Advisor"> Trip Advisor </option>
                                    <option value="5: Yelp"> Yelp </option>
                                    <option value="6: Good Sam"> Good Sam </option>
                                    <option value="7: Passport America"> Passport America </option>
                                    <option value="8: Coast To Coast"> Coast To Coast </option>
                                    <option value="9: Billboard"> Billboard </option>
                                    <option value="10: Facebook / Instagram"> Facebook / Instagram </option>
                                    <option value="11: RV Business"> RV Business </option>
                                    <option value="12: Woodalls"> Woodalls </option>
                                    <option value="13: FMCA Tucson Intl Convention"> FMCA Tucson Intl Convention </option>
                                    <option value="14: RV Travel"> RV Travel </option>
                                    <option value="15: Airbnb"> Airbnb </option>
                                    <option value="16: RVillage"> RVillage </option>
                                    <option value="17: Home Away VRBO"> Home Away VRBO </option>
                                    <option value="18: Booking.com"> Booking.com </option>
                                    <option value="19: Long Term"> Long Term </option>
                                    <option value="20: Local Business Referral"> Local Business Referral </option>
                                    <option value="21: Rally Group"> Rally Group </option>
                                    <option value="22: Influencer"> Influencer </option>
                                    <option value="23: Repeat Customer"> Repeat Customer </option>
                                    <option value="24: Drive By / Billboard"> Drive By / Billboard </option>
                                    <option value="25: Approved Comped CRR Employee or Guest"> Approved Comped CRR Employee or Guest </option>
                                    <option value="26: Escapades 2022"> Escapades 2022 </option>
                                    <option value="27: Quartzsite RV Show"> Quartzsite RV Show </option>
                                    <option value="28: Campspot"> Campspot </option>
                                    <option value="29: Guest of Another CRR Resort"> Guest of Another CRR Resort </option>
                                    <option value="30: Campendium"> Campendium </option>
                                    <option value="31: RV Parky"> RV Parky </option>
                                    <option value="32: The Dyrt"> The Dyrt </option>
                                    <option value="33: RoverPass"> RoverPass </option>
                                    <option value="34: Promotion Flyer"> Promotion Flyer </option>
                                    <option value="35: Hipcamp"> Hipcamp </option><!---->
                                </select>
                                </div>
                                <div class="checkout-form-field">
                                <label for="guest-reason-for-visit" class="checkout-form-field-label"> Reason for Visit <div class="checkout-form-field-label-note"> Optional </div>
                                </label>
                                <select name="guest-reason-for-visit" id="guest-reason-for-visit" class="checkout-form-field-input-dropdown mod-full ng-untouched ng-pristine ng-valid">
                                    <option value="">
                                    </option>
                                    <option value="1: Vacation"> Vacation </option>
                                    <option value="2: Work"> Work </option>
                                    <option value="3: Overnight Traveler"> Overnight Traveler </option>
                                    <option value="4: Seasonal Guest"> Seasonal Guest </option>
                                    <option value="5: Long Term"> Long Term </option>
                                    <option value="6: Rally Group"> Rally Group </option>
                                    <option value="7: Influencer"> Influencer </option>
                                    <option value="8: Approved Comped CRR Employee or Guest"> Approved Comped CRR Employee or Guest </option>
                                    <option value="9: Travel Through Stop"> Travel Through Stop </option>
                                    <option value="10: Other"> Other </option>
                                    <option value="11: Quartzsite RV Show"> Quartzsite RV Show </option><!---->
                                </select>
                                </div>
                                <div class="checkout-form-field">
                                <label for="guest-reservation-note" class="checkout-form-field-label"> Special Needs or Requests <div class="checkout-form-field-label-note"> Optional </div>
                                </label>
                                <textarea name="guest-reservation-note" id="guest-reservation-note" rows="3" class="checkout-form-field-input ng-untouched ng-pristine ng-valid"></textarea>
                                </div>
                            </div>
                        </section>
                        <section id="app-payment-amount-fragment" class="checkout-form-section">
                            <h1 class="checkout-form-section-title">2. Payment Amount</h1>
                            <div>
                                <div class="checkout-form-payment-amount is-selected">
                                    <label class="checkout-form-payment-amount-selectable is-selected">
                                        <div class="checkout-form-payment-method-selectable-radio">
                                            <input type="radio" id="payment-amount-total" name="payment_amount" class="app-pay-total-balance-radio" value="total" checked>
                                        </div>
                                        <div>
                                            <div class="checkout-form-payment-amount-selectable-label">Pay Total Balance</div>
                                            <div class="checkout-form-payment-amount-selectable-value">
                                                <?php echo htmlspecialchars($amount); ?>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="checkout-form-payment-amount-selectable">
                                        <div class="checkout-form-payment-method-selectable-radio">
                                            <input type="radio" 
                                                id="payment-amount-partial" 
                                                name="payment_amount" 
                                                class="app-pay-total-balance-radio" 
                                                value="partial"
                                                data-partial-value="<?php echo htmlspecialchars($minPayment); ?>">
                                        </div>
                                        <div>
                                            <div class="checkout-form-payment-amount-selectable-label">Pay Partial Balance</div>
                                            <div class="checkout-form-payment-amount-selectable-value" id="payment-amount-partial-value">
                                                <?php echo htmlspecialchars($minPayment); ?>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </section>
                        <section class="checkout-form-section">
                            <h1 class="checkout-form-section-title"> 3. Payment Method </h1>
                            <div>
                                <label id="payment-card-number" class="checkout-form-field">
                                    <div class="checkout-form-field-label"> Card Number* </div>
                                    <iframe title="CardConnect Card Number" id="tokenFrame" name="tokenFrame" frameborder="0" scrolling="no" class="checkout-form-field-cardconnect-iframe app-cc-iframe" src="https://boltgw-uat.cardconnect.com/itoke/ajax-tokenizer.html?tokenizewheninactive=true&amp;inactivityto=3000&amp;css=body%7Bmargin%3A0%3B%7Dinput%7Bborder%3A1px%20solid%20%23bbb%3Bborder-radius%3A3px%3Bbox-sizing%3Aborder-box%3Bfont-family%3Asans-serif%3Bfont-size%3A16px%3Bheight%3A38px%3Bline-height%3A1.5%3Bpadding%3A6px%2010px%3Bwidth%3A100%25%3B%7Dinput%3Afocus%7Bborder-color%3A%2366a3ff%3Boutline%3A0%3B%7D&amp;invalidinputevent=true&amp;formatinput=true"></iframe>
                                    <input type="hidden" required id="mytoken" name="mytoken" />
                                </label>
                                <div id="payment-expiration-date" class="checkout-form-field">
                                    <div class="checkout-form-field-label"> Expiration Date* </div>
                                    <div class="checkout-form-field-cc-exp">
                                        <input type="tel" maxlength="2" name="payment-expiration-date-month" required id="month" placeholder="MM" aria-label="Expiration Month (MM)" aria-describedby="payment-expiration-date-error" class="checkout-form-field-input mod-cc-exp ng-untouched ng-pristine ng-valid">
                                        <div class="checkout-form-field-cc-exp-divider"> / </div>
                                        <input type="tel" maxlength="2" name="payment-expiration-date-year" required id="year" placeholder="YY" aria-label="Expiration Year (YY)" aria-describedby="payment-expiration-date-error" class="checkout-form-field-input mod-cc-exp ng-untouched ng-pristine ng-valid">
                                    </div>
                                </div>
                                <div id="payment-security-code" class="checkout-form-field">
                                    <label for="payment-security-code-input" class="checkout-form-field-label" style="padding-bottom: 5px;"> Security Code (CVV)* </label>
                                    <input type="text" maxlength="4" name="payment-security-code" required id="payment-security-code-input" aria-describedby="payment-security-code-error" class="checkout-form-field-input mod-cc-cvv ng-untouched ng-pristine ng-valid">
                                </div>
                                <input type="hidden" id="mytoken" name="mytoken">
                                <div class="checkout-form-field-checkbox mod-billing-address">
                                    <input type="checkbox" name="payment-billing-info-same-as-guest-info" id="payment-billing-info-same-as-guest-info" class="app-checkout-billing-info-same-as-guest-checkbox" checked>
                                    <label for="payment-billing-info-same-as-guest-info" style="margin-top: 3px;"> Billing information is same as guest information </label>
                                </div>
                            </div>
                        </section>
                        <section class="checkout-form-section app-checkout-policy-component">
                            <checkout-policy-v2>
                                <section class="checkout-policy">
                                    <div class="checkout-form-agreement">
                                        <div id="terms-and-conditions" class="checkout-form-field-checkbox input">
                                            <input aria-label="Checkbox to accept all campground and Campspot policies, Terms & Conditions and Privacy Notice" type="checkbox" id="terms-and-conditions-accept" class="app-terms-and-conditions-accept" style="border: 1px solid black; appearance: auto;">
                                        </div>
                                        <div>
                                            <span class="app-show-checkbox-enabled-dialog"> I acknowledge that I have reviewed and accept the campground's </span>
                                            <a target="_blank" class="app-checkout-policy-park-terms-link" href="/terms-and-conditions-<?php echo ($sId); ?>">Policies, Terms & Conditions </a>, in addition to Campspot platform's <a target="_blank" class="app-checkout-policy-campspot-terms-link" href="https://www.campspot.com/about/terms-and-conditions">Terms & Conditions </a> and <a target="_blank" class="app-checkout-policy-campspot-privacy-link" href="https://www.campspot.com/about/privacy">Privacy Notice </a>.
                                        </div>
                                    </div>
                                </section>
                            </checkout-policy-v2>
                        </section>

                        <!-- Modal Structure (Hidden by Default) -->
                        <div id="termsModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <p>Please accept the terms and conditions to proceed with your order.</p>
                                <button id="closeTermsModalButton">OK</button> 
                            </div>
                        </div>

                        <div id="paymentErrorModal" class="modal" style="display: none;">
                            <div class="modal-content">
                                <span class="close">&times;</span> 
                                <p>There was an error processing your payment. Please try again or try a different card.</p>
                                <button id="closePaymentErrorModalButton">OK</button> 
                            </div>
                        </div>
                        <div class="checkout-form-submit">
                            <button type="button" onclick="submitPaymentForm(cartId, parkId)" class="checkout-form-submit-button mod-place-order app-checkout-submit"> Place Order </button>
                        </div>
                    </card-connect-checkout>
                </form>
            </section>
        </div>
    </div>

    <!-- Add the JavaScript at the bottom of the body -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <!-- Displaying Sites -->
    <script>
        function getStateFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('state');
        }

        const state = getStateFromUrl();
        // Get the select element
        const stateSelectElement = document.getElementById("guest-state-select");

        // Loop through all options to find and select the matching one
        for (let i = 0; i < stateSelectElement.options.length; i++) {
            if (stateSelectElement.options[i].text === state) {
                stateSelectElement.selectedIndex = i;
                break;
            }
        }

        function getCountryFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('country'); 
        }

        const country = getCountryFromUrl();

        // Get the select element
        const countrySelectElement = document.getElementById("guest-country");

        // Loop through all options to find and select the matching one
        for (let i = 0; i < countrySelectElement.options.length; i++) {
            if (countrySelectElement.options[i].text === country) {
                countrySelectElement.selectedIndex = i;
                break;
            }
        }
        
        let cartId, parkId, email;
        
        // Modal close function
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Event listener to close modals when 'OK' button is clicked
        $('#closeTermsModalButton').click(function (event) {
            event.preventDefault(); // Prevent any form submission or page refresh
            closeModal('termsModal');
        });

        $('#closePaymentErrorModalButton').click(function (event) {
            event.preventDefault(); // Prevent any form submission or page refresh
            closeModal('paymentErrorModal');
        });

        // Also allow clicking the 'X' (close) button
        $('.close').click(function () {
            // Find the closest modal and close it
            $(this).closest('.modal').hide();
        });

        // Function to get 'cartId' from the URL
        function getCartIdFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('cartId'); // Adjust 'cartId' to match the query parameter in your URL
        }

        function setLocalStorageWithExpiry(key, value, ttl) {
            const now = new Date().getTime();
            const item = {
                value: value,
                expiry: now + ttl,
            };
            localStorage.setItem(key, JSON.stringify(item));
        } 

        // Use cartId from the URL
        cartId = getCartIdFromUrl();

        function getParkIdFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('parkId'); // Adjust 'parkId' to match the query parameter in your URL
        }

        parkId = getParkIdFromUrl();

        function getEmailFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('email'); // Adjust 'email' to match the query parameter in your URL
        }

        email = getEmailFromUrl();
        console.log('cartId:', cartId, 'parkId:', parkId, 'email:', email);

        $(document).ready(function ($) {
            // Call fetchCart on page load to display available campsites
            fetchCart(cartId, parkId, email);

            async function fetchCart(cartId, parkId, email) {
                console.log('Fetching cart data...');
                console.log('cartId:', cartId, 'parkId:', parkId, 'email:', email);
                // let storedCartData = localStorage.getItem('cartData');
                // let storedSubTotal = localStorage.getItem('subTotal');

                    const baseUrl = "https://insiderperks.com/wp-content/endpoints/campspot/get-cart.php";
                    const params = { cartId: cartId, email: email };
                    const queryString = Object.keys(params).map(key => `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`).join('&');
                    const urlWithParams = `${baseUrl}?${queryString}`;

                    console.log('Fetching cart data from:', urlWithParams);
                    try {
                        const response = await fetch(urlWithParams);
                        let cart = await response.json();

                        console.log('Cart data:', cart);

                        cart = cart.cart;
                        
                        // Check if the cart contains valid data
                        if (!cart || typeof cart !== 'object') {
                            throw new Error('Invalid cart data');
                        }

                        let campsites = cart.parkShoppingCarts[parkId]?.shoppingCartItems || [];
                        const subTotal = cart.parkShoppingCarts[parkId]?.subtotal || 0;
                        let filteredCampsites = [];

                        if (campsites.length) {
                            for (const campsite of campsites) {
                                const filteredCampsite = processCampsite(campsite);
                                filteredCampsites.push(filteredCampsite);
                            }
                        }

                        // Save to localStorage for future reference
                        // localStorage.setItem('cartData', JSON.stringify(filteredCampsites));
                        // localStorage.setItem('subTotal', subTotal);

                        console.log('Cart data:', filteredCampsites, 'Subtotal:', subTotal);
                        // Display available campsites
                        displayAvailableCampsite(filteredCampsites, subTotal);
                    } catch (error) {
                        console.error('Error fetching campground data:', error);
                    }
                
            }

            function processCampsite(campsite) {
                const campsiteTypeId = campsite.campsiteType.id;
                const campsiteName = campsite.campsiteType.name;
                const campName = campsite.campsite.name;
                const campId = campsite.campsite.id;
                const amenities = campsite.campsite.amenities;
                const petFriendly = campsite.campsiteType.isPetFriendly;
                const siteLock = String(campsite.siteLocationLocked);
                const lockFee = campsite.campsiteType.lockingFee;
                const checkin = new Date(campsite.checkinDateInParkTimeZone);
                const checkout = new Date(campsite.checkoutDateInParkTimeZone);
                const children = campsite.guestCategories.ageCategories.find(category => category.name === 'Children')?.count || 0;
                const adults = campsite.guestCategories.ageCategories.find(category => category.name === 'Adults')?.count || 0;
                const pets = campsite.guestCategories.pets || 0;
                const pricePerNight = campsite.pricing.averagePricePerNightBeforeTaxesAndFees;
                const taxes = campsite.pricing.totalTaxes;
                let campFees, totalPrice;

                if (siteLock === 'true') {
                    campFees = campsite.pricing.feeSummary.totalCampgroundFeesWithLockFee + campsite.pricing.feeSummary.lockFeeTaxes;
                    totalPrice = campsite.pricing.tripTotalWithLockSiteFee;
                } else {
                    campFees = campsite.pricing.feeSummary.totalCampgroundFeesWithoutLockFee;
                    totalPrice = campsite.pricing.tripTotal;
                }

                const packageDiscounts = campsite.pricing.packageDiscounts;
                let promoName = "", promoPrice = "";
                if (packageDiscounts.length > 0) {
                    promoName = packageDiscounts[0].name;
                    promoPrice = packageDiscounts[0].price;
                }

                const imageUrl = campsite.campsiteType.images.mainImage.medium.url;
                const siteType = campsite.campsiteType.campsiteCategoryCode;
                const reservationDetailId = campsite.reservationDetailId;

                let dailyRateAddons = [];
                if (campsite.dailyRateAddons.length > 0) {
                    for (const addOn of campsite.dailyRateAddons) {
                        dailyRateAddons.push({
                            addOnName: addOn.name,
                            addOnTypeId: addOn.typeId,
                            addOnCheckin: addOn.checkinDateInParkTimeZone,
                            addOnCheckout: addOn.checkoutDateInParkTimeZone,
                            addOnImg: addOn.images.mainImage.medium.url,
                            addOnTotal: addOn.pricing.tripTotal,
                            dailyRateAddonReservationId: addOn.dailyRateAddonReservationDetailId,
                            reservationDetailId,
                            campsiteTypeId,
                            campsiteName,
                            checkin,
                            checkout
                        });
                    }
                }

                let onlineStoreAddons = [];
                if (campsite.onlineStoreAddons.length > 0) {
                    for (const addOn of campsite.onlineStoreAddons) {
                        onlineStoreAddons.push({
                            addOnName: addOn.name,
                            addOnTypeId: addOn.typeId,
                            addOnQuantity: addOn.quantity,
                            addOnImg: addOn.images.slideshowImages.length > 0 ? addOn.images.mainImage.medium.url : "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/2048px-No_image_available.svg.png",
                            addOnTotal: addOn.pricing.tripTotal,
                            reservationDetailId,
                            campsiteTypeId,
                            campsiteName,
                            checkin,
                            checkout
                        });
                    }
                }

                return {
                    campsiteTypeId,
                    campsiteName,
                    campName,
                    campId,
                    amenities,
                    petFriendly,
                    siteLock,
                    lockFee,
                    checkin,
                    checkout,
                    children,
                    adults,
                    pets,
                    pricePerNight,
                    taxes,
                    campFees,
                    promoName,
                    promoPrice,
                    totalPrice,
                    imageUrl,
                    siteType,
                    dailyRateAddons,
                    onlineStoreAddons,
                    ...(siteType === "rv" ? { rvInfo: campsite.rvInfo } : {}),
                    reservationDetailId
                };
            }

            function displayAvailableCampsite(campsites, subTotal) {
                const tbody = $('#order-summary-table-body');
                tbody.empty();

                campsites.forEach(campsite => {
                    const numberOfNights = Math.ceil((new Date(campsite.checkout) - new Date(campsite.checkin)) / (1000 * 60 * 60 * 24));
                    const row = $(`
                        <table class="checkout-summary-item">
                            <tbody>
                                <tr>
                                    <td class="checkout-summary-item-title app-checkout-summary-site-title">
                                        ${campsite.campsiteName} <span>- ${campsite.campName}</span>
                                        <campsite-name-icons>
                                            <!-- Icons can be added here if needed -->
                                        </campsite-name-icons>
                                    </td>
                                    <td class="checkout-summary-item-price">
                                        <span>$${campsite.totalPrice.toFixed(2)}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="checkout-summary-item-details">
                                        <div>
                                            <span class="app-checkout-summary-site-dates">${formatDateRange(new Date(campsite.checkin), new Date(campsite.checkout))}</span> (${numberOfNights} Nights)
                                        </div>
                                        <div class="app-checkout-summary-site-guests">${formatGuests(campsite.adults, campsite.children, campsite.pets)}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    `);
                    tbody.append(row);

                    // Display daily rate add-ons
                    campsite.dailyRateAddons.forEach(addOn => {
                        const addOnNights = Math.ceil((new Date(addOn.addOnCheckout) - new Date(addOn.addOnCheckin)) / (1000 * 60 * 60 * 24));
                        const addOnRow = $(`
                            <table class="checkout-summary-add-on checkout-summary-item">
                                <tbody>
                                    <tr>
                                        <td class="checkout-summary-item-title">Add-on: ${addOn.addOnName}</td>
                                        <td class="checkout-summary-item-price">$${addOn.addOnTotal.toFixed(2)}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="checkout-summary-item-details">${formatDateRange(new Date(addOn.addOnCheckin), new Date(addOn.addOnCheckout))} (${addOnNights} Nights)</td>
                                    </tr>
                                </tbody>
                            </table>
                        `);
                        tbody.append(addOnRow);
                    });

                    // Display online store add-ons
                    campsite.onlineStoreAddons.forEach(addOn => {
                        const addOnRow = $(`
                            <table class="checkout-summary-add-on checkout-summary-item">
                                <tbody>
                                    <tr>
                                        <td class="checkout-summary-item-title app-checkout-pos-addon-display-name">Add-on: ${addOn.addOnName}</td>
                                        <td class="checkout-summary-item-price app-checkout-pos-addon-trip-total">$${addOn.addOnTotal.toFixed(2)}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="checkout-summary-item-details">Quantity: ${addOn.addOnQuantity}</td>
                                    </tr>
                                </tbody>
                            </table>
                        `);
                        tbody.append(addOnRow);
                    });
                });

                $('#order-total').text(subTotal.toFixed(2));
                $('.checkout-form-payment-amount-selectable-value').text(`$${subTotal.toFixed(2)}`);
            }

            function formatDateRange(startDate, endDate) {
                // Increment the start date by 1 day
                startDate = incrementDate(startDate);
                endDate = incrementDate(endDate);
                const options = { weekday: 'short', month: 'short', day: 'numeric' };
                const start = startDate.toLocaleDateString('en-US', options);
                const end = endDate.toLocaleDateString('en-US', options);
                return `${start} - ${end}`;
            }

            function incrementDate(date) {
                // Create a new Date object based on the input date
                let newDate = new Date(date);
                // Increment the date by 1
                newDate.setDate(newDate.getDate() + 1);
                return newDate;
            }

            function formatGuests(adults, children, pets) {
                let guestText = '';
                if (adults > 0) guestText += `${adults} Adult${adults > 1 ? 's' : ''}`;
                if (children > 0) {
                    if (guestText) guestText += ', ';
                    guestText += `${children} Child${children > 1 ? 'ren' : ''}`;
                }
                if(pets > 0) {
                    if (guestText) guestText += ', ';
                    guestText += `${pets} Pet${pets > 1 ? 's' : ''}`;
                }
                return guestText;
            }
        });

            // Handle Address Line 2 toggle for guest information
            $(document).on('click', '.checkout-form-field-toggle', function (e) {
                e.preventDefault();
                if ($('#guest-address-line-2').length === 0) {
                    $(this).parent().after(`
                        <div class="checkout-form-field">
                            <label for="guest-address-line-2" class="checkout-form-field-label"> Address - Line 2 <div class="checkout-form-field-label-note"> Optional </div>
                            </label>
                            <input type="text" name="guest-address-line-2" id="guest-address-line-2" class="checkout-form-field-input ng-untouched ng-pristine ng-valid">
                        </div><!---->
                    `);
                    $(this).remove();
                }
            });

        // Handle Address Line 2 toggle for billing information
        $(document).on('click', '.billing-form-field-toggle', function (e) {
            e.preventDefault();
            if ($('#billing-address-line-2').length === 0) {
                $(this).parent().after(`
                    <div class="checkout-form-field">
                        <label for="billing-address-line-2" class="checkout-form-field-label"> Address - Line 2 <div class="checkout-form-field-label-note"> Optional </div>
                        </label>
                        <input type="text" name="billing-address-line-2" id="billing-address-line-2" class="checkout-form-field-input ng-untouched ng-pristine ng-valid">
                    </div><!---->
                `);
                $(this).remove();
            }
        });

        // Handle SMS message checkbox
        $('#checkout-form-field-text-opt-in').change(function () {
            const isChecked = $(this).is(':checked');
            $('#smsMessage').val(isChecked);
        });

        // Handle SMS details toggle
        $('.checkout-form-field-texting-toggle').click(function () {
            const details = $('#checkout-form-field-texting-detail');
            details.toggle();

            if (details.is(':visible')) {
                $(this).html('Hide Details<svg width="10" height="7" class="checkout-form-field-texting-toggle-icon mod-hide"><path fill-rule="nonzero" d="M5 5.004L8.996 1 10 1.997 5 7 0 1.997 1 1z" class="checkout-form-field-texting-toggle-icon-path"></path></svg>');
            } else {
                $(this).html('View Details<svg width="10" height="7" class="checkout-form-field-texting-toggle-icon mod-hide"><path fill-rule="nonzero" d="M5 5.004L8.996 1 10 1.997 5 7 0 1.997 1 1z" class="checkout-form-field-texting-toggle-icon-path"></path></svg>');
            }
        });

        // Handle Billing Information checkbox
        $('#payment-billing-info-same-as-guest-info').change(function () {
            if ($(this).is(':checked')) {
                $('.checkout-form-billing-address').hide();
            } else {
                if ($('.checkout-form-billing-address').length === 0) {
                    $(this).closest('.checkout-form-section').append(`
                        <div class="checkout-form-billing-address">
                            <h3 class="checkout-form-billing-address-title"> Billing Information </h3>
                            <div id="billing-name-on-card" class="checkout-form-field">
                                <label for="billing-name-on-card-input" class="checkout-form-field-label"> Name on Card* </label>
                                <input type="text" name="billing-name-on-card" id="billing-name-on-card-input" aria-describedby="billing-name-on-card-error" class="checkout-form-field-input ng-untouched ng-pristine ng-valid">
                            </div>
                            <div id="billing-address-line-1" class="checkout-form-field">
                                <label for="billing-address-line-1-input" class="checkout-form-field-label"> Address - Line 1* </label>
                                <input type="text" name="billing-address-line-1" id="billing-address-line-1-input" aria-describedby="billing-address-line-1-error" class="checkout-form-field-input ng-untouched ng-pristine ng-valid">
                            </div>
                            <div class="checkout-form-field mod-address-2">
                                <button class="billing-form-field-toggle"> Add Address Line 2 </button>
                            </div>
                            <div class="checkout-form-field">
                                <label for="billing-country" class="checkout-form-field-label"> Country* </label>
                                <select name="billing-country" id="billing-country" class="checkout-form-field-input-dropdown app-billing-info-country-select ng-untouched ng-pristine ng-valid">
                                    <option> United States </option>
                                    <option> Canada </option><!---->
                                    <option> Afghanistan </option>
                                    <option> Åland Islands </option>
                                    <option> Albania </option>
                                    <option> Algeria </option>
                                    <option> American Samoa </option>
                                    <option> Andorra </option>
                                    <option> Angola </option>
                                    <option> Anguilla </option>
                                    <option> Antarctica </option>
                                    <option> Antigua and Barbuda </option>
                                    <option> Argentina </option>
                                    <option> Armenia </option>
                                    <option> Aruba </option>
                                    <option> Australia </option>
                                    <option> Austria </option>
                                    <option> Azerbaijan </option>
                                    <option> Bahamas </option>
                                    <option> Bahrain </option>
                                    <option> Bangladesh </option>
                                    <option> Barbados </option>
                                    <option> Belarus </option>
                                    <option> Belgium </option>
                                    <option> Belize </option>
                                    <option> Benin </option>
                                    <option> Bermuda </option>
                                    <option> Bhutan </option>
                                    <option> Bolivia </option>
                                    <option> Bosnia and Herzegovina </option>
                                    <option> Botswana </option>
                                    <option> Bouvet Island </option>
                                    <option> Brazil </option>
                                    <option> British Indian Ocean Territory </option>
                                    <option> British Virgin Islands </option>
                                    <option> Brunei </option>
                                    <option> Bulgaria </option>
                                    <option> Burkina Faso </option>
                                    <option> Burundi </option>
                                    <option> Cambodia </option>
                                    <option> Cameroon </option>
                                    <option> Cape Verde </option>
                                    <option> Cayman Islands </option>
                                    <option> Central African Republic </option>
                                    <option> Chad </option>
                                    <option> Chile </option>
                                    <option> China </option>
                                    <option> Christmas Island </option>
                                    <option> Cocos (Keeling) Islands </option>
                                    <option> Colombia </option>
                                    <option> Comoros </option>
                                    <option> Cook Islands </option>
                                    <option> Costa Rica </option>
                                    <option> Croatia </option>
                                    <option> Cuba </option>
                                    <option> Curaçao </option>
                                    <option> Cyprus </option>
                                    <option> Czechia </option>
                                    <option> Denmark </option>
                                    <option> Djibouti </option>
                                    <option> Dominica </option>
                                    <option> Dominican Republic </option>
                                    <option> DR Congo </option>
                                    <option> Ecuador </option>
                                    <option> Egypt </option>
                                    <option> El Salvador </option>
                                    <option> Equatorial Guinea </option>
                                    <option> Eritrea </option>
                                    <option> Estonia </option>
                                    <option> Ethiopia </option>
                                    <option> Falkland Islands </option>
                                    <option> Faroe Islands </option>
                                    <option> Fiji </option>
                                    <option> Finland </option>
                                    <option> France </option>
                                    <option> French Guiana </option>
                                    <option> French Polynesia </option>
                                    <option> French Southern and Antarctic Lands </option>
                                    <option> Gabon </option>
                                    <option> Gambia </option>
                                    <option> Georgia </option>
                                    <option> Germany </option>
                                    <option> Ghana </option>
                                    <option> Gibraltar </option>
                                    <option> Greece </option>
                                    <option> Greenland </option>
                                    <option> Grenada </option>
                                    <option> Guadeloupe </option>
                                    <option> Guam </option>
                                    <option> Guatemala </option>
                                    <option> Guernsey </option>
                                    <option> Guinea </option>
                                    <option> Guinea-Bissau </option>
                                    <option> Guyana </option>
                                    <option> Haiti </option>
                                    <option> Heard Island and McDonald Islands </option>
                                    <option> Honduras </option>
                                    <option> Hong Kong </option>
                                    <option> Hungary </option>
                                    <option> Iceland </option>
                                    <option> India </option>
                                    <option> Indonesia </option>
                                    <option> Iran </option>
                                    <option> Iraq </option>
                                    <option> Ireland </option>
                                    <option> Isle of Man </option>
                                    <option> Israel </option>
                                    <option> Italy </option>
                                    <option> Ivory Coast </option>
                                    <option> Jamaica </option>
                                    <option> Japan </option>
                                    <option> Jersey </option>
                                    <option> Jordan </option>
                                    <option> Kazakhstan </option>
                                    <option> Kenya </option>
                                    <option> Kiribati </option>
                                    <option> Kosovo </option>
                                    <option> Kuwait </option>
                                    <option> Kyrgyzstan </option>
                                    <option> Laos </option>
                                    <option> Latvia </option>
                                    <option> Lebanon </option>
                                    <option> Lesotho </option>
                                    <option> Liberia </option>
                                    <option> Libya </option>
                                    <option> Liechtenstein </option>
                                    <option> Lithuania </option>
                                    <option> Luxembourg </option>
                                    <option> Macau </option>
                                    <option> Macedonia </option>
                                    <option> Madagascar </option>
                                    <option> Malawi </option>
                                    <option> Malaysia </option>
                                    <option> Maldives </option>
                                    <option> Mali </option>
                                    <option> Malta </option>
                                    <option> Marshall Islands </option>
                                    <option> Martinique </option>
                                    <option> Mauritania </option>
                                    <option> Mauritius </option>
                                    <option> Mayotte </option>
                                    <option> Mexico </option>
                                    <option> Micronesia </option>
                                    <option> Moldova </option>
                                    <option> Monaco </option>
                                    <option> Mongolia </option>
                                    <option> Montenegro </option>
                                    <option> Montserrat </option>
                                    <option> Morocco </option>
                                    <option> Mozambique </option>
                                    <option> Myanmar </option>
                                    <option> Namibia </option>
                                    <option> Nauru </option>
                                    <option> Nepal </option>
                                    <option> Netherlands </option>
                                    <option> New Caledonia </option>
                                    <option> New Zealand </option>
                                    <option> Nicaragua </option>
                                    <option> Niger </option>
                                    <option> Nigeria </option>
                                    <option> Niue </option>
                                    <option> Norfolk Island </option>
                                    <option> North Korea </option>
                                    <option> Northern Mariana Islands </option>
                                    <option> Norway </option>
                                    <option> Oman </option>
                                    <option> Pakistan </option>
                                    <option> Palau </option>
                                    <option> Palestine </option>
                                    <option> Panama </option>
                                    <option> Papua New Guinea </option>
                                    <option> Paraguay </option>
                                    <option> Peru </option>
                                    <option> Philippines </option>
                                    <option> Pitcairn Islands </option>
                                    <option> Poland </option>
                                    <option> Portugal </option>
                                    <option> Puerto Rico </option>
                                    <option> Qatar </option>
                                    <option> Republic of the Congo </option>
                                    <option> Romania </option>
                                    <option> Russia </option>
                                    <option> Rwanda </option>
                                    <option> Réunion </option>
                                    <option> Saint Barthélemy </option>
                                    <option> Saint Kitts and Nevis </option>
                                    <option> Saint Lucia </option>
                                    <option> Saint Martin </option>
                                    <option> Saint Pierre and Miquelon </option>
                                    <option> Saint Vincent and the Grenadines </option>
                                    <option> Samoa </option>
                                    <option> San Marino </option>
                                    <option> Saudi Arabia </option>
                                    <option> Senegal </option>
                                    <option> Serbia </option>
                                    <option> Seychelles </option>
                                    <option> Sierra Leone </option>
                                    <option> Singapore </option>
                                    <option> Sint Maarten </option>
                                    <option> Slovakia </option>
                                    <option> Slovenia </option>
                                    <option> Solomon Islands </option>
                                    <option> Somalia </option>
                                    <option> South Africa </option>
                                    <option> South Georgia </option>
                                    <option> South Korea </option>
                                    <option> South Sudan </option>
                                    <option> Spain </option>
                                    <option> Sri Lanka </option>
                                    <option> Sudan </option>
                                    <option> Suriname </option>
                                    <option> Svalbard and Jan Mayen </option>
                                    <option> Swaziland </option>
                                    <option> Sweden </option>
                                    <option> Switzerland </option>
                                    <option> Syria </option>
                                    <option> São Tomé and Príncipe </option>
                                    <option> Taiwan </option>
                                    <option> Tajikistan </option>
                                    <option> Tanzania </option>
                                    <option> Thailand </option>
                                    <option> Timor-Leste </option>
                                    <option> Togo </option>
                                    <option> Tokelau </option>
                                    <option> Tonga </option>
                                    <option> Trinidad and Tobago </option>
                                    <option> Tunisia </option>
                                    <option> Turkey </option>
                                    <option> Turkmenistan </option>
                                    <option> Turks and Caicos Islands </option>
                                    <option> Tuvalu </option>
                                    <option> Uganda </option>
                                    <option> Ukraine </option>
                                    <option> United Arab Emirates </option>
                                    <option> United Kingdom </option>
                                    <option> United States Minor Outlying Islands </option>
                                    <option> United States Virgin Islands </option>
                                    <option> Uruguay </option>
                                    <option> Uzbekistan </option>
                                    <option> Vanuatu </option>
                                    <option> Vatican City </option>
                                    <option> Venezuela </option>
                                    <option> Vietnam </option>
                                    <option> Wallis and Futuna </option>
                                    <option> Western Sahara </option>
                                    <option> Yemen </option>
                                    <option> Zambia </option>
                                    <option> Zimbabwe </option><!---->
                                </select>
                            </div>
                            <div id="billing-postal-code" class="checkout-form-field">
                                <label for="billing-postal-code-input" class="checkout-form-field-label"> Postal Code* </label>
                                <input type="text" minlength="5" name="billing-postal-code" id="billing-postal-code-input" aria-describedby="billing-postal-code-error" class="checkout-form-field-input mod-postal-code ng-untouched ng-pristine ng-valid">
                            </div>
                            <div id="billing-city" class="checkout-form-field">
                                <label for="billing-city-input" class="checkout-form-field-label"> City* </label>
                                <input type="text" name="billing-city" id="billing-city-input" aria-describedby="billing-city-error" class="checkout-form-field-input ng-untouched ng-pristine ng-valid">
                            </div>
                            <div id="billing-state" class="checkout-form-field">
                                <label for="billing-state-select" class="checkout-form-field-label"> State* </label>
                                <select name="billing-state" id="billing-state-select" aria-describedby="billing-state-error" class="checkout-form-field-input-dropdown ng-untouched ng-pristine ng-valid">
                                    <option> Alabama </option>
                                    <option> Alaska </option>
                                    <option> American Samoa </option>
                                    <option> Arizona </option>
                                    <option> Arkansas </option>
                                    <option> Armed Forces (AA) </option>
                                    <option> Armed Forces (AE) </option>
                                    <option> Armed Forces (AP) </option>
                                    <option> California </option>
                                    <option> Colorado </option>
                                    <option> Connecticut </option>
                                    <option> Delaware </option>
                                    <option> District of Columbia </option>
                                    <option> Federated States of Micronesia </option>
                                    <option> Florida </option>
                                    <option> Georgia </option>
                                    <option> Guam </option>
                                    <option> Hawaii </option>
                                    <option> Idaho </option>
                                    <option> Illinois </option>
                                    <option> Indiana </option>
                                    <option> Iowa </option>
                                    <option> Kansas </option>
                                    <option> Kentucky </option>
                                    <option> Louisiana </option>
                                    <option> Maine </option>
                                    <option> Marshall Islands </option>
                                    <option> Maryland </option>
                                    <option> Massachusetts </option>
                                    <option> Michigan </option>
                                    <option> Minnesota </option>
                                    <option> Mississippi </option>
                                    <option> Missouri </option>
                                    <option> Montana </option>
                                    <option> Nebraska </option>
                                    <option> Nevada </option>
                                    <option> New Hampshire </option>
                                    <option> New Jersey </option>
                                    <option> New Mexico </option>
                                    <option> New York </option>
                                    <option> North Carolina </option>
                                    <option> North Dakota </option>
                                    <option> Northern Mariana Islands </option>
                                    <option> Ohio </option>
                                    <option> Oklahoma </option>
                                    <option> Oregon </option>
                                    <option> Palau </option>
                                    <option> Pennsylvania </option>
                                    <option> Puerto Rico </option>
                                    <option> Rhode Island </option>
                                    <option> South Carolina </option>
                                    <option> South Dakota </option>
                                    <option> Tennessee </option>
                                    <option> Texas </option>
                                    <option> Utah </option>
                                    <option> Vermont </option>
                                    <option> Virgin Islands </option>
                                    <option> Virginia </option>
                                    <option> Washington </option>
                                    <option> West Virginia </option>
                                    <option> Wisconsin </option>
                                    <option> Wyoming </option><!---->
                                </select>
                            </div>
                        </div>
                    `);
                } else {
                    $('.checkout-form-billing-address').show();
                }
            }
        });

        // Initially hide the billing information fields if the checkbox is checked
        if ($('#payment-billing-info-same-as-guest-info').is(':checked')) {
            $('.checkout-form-billing-address').hide();
        }

        // Tokenization event listener
        window.addEventListener('message', function(event) {
            if (event.origin !== "https://boltgw-uat.cardconnect.com") {
                console.error("Message event origin mismatch:", event.origin);
                return;
            }
            try {
                var tokenData = JSON.parse(event.data);
                if (tokenData && tokenData.message) {
                    document.getElementById('mytoken').value = tokenData.message;
                    console.log("Token assigned:", tokenData.message);
                } else {
                    console.error("Token data format unexpected:", tokenData);
                }
            } catch (error) {
                console.error("Error processing tokenization message:", error);
            }
        }, false);
        
        // Submit payment form
        window.submitPaymentForm = async function(cartId, parkId) {
            console.log("Submitting payment form...");
            const overlay = $('.overlay');
            const spinner = $('.spinner');
            var form = document.querySelector('.checkout-form');
        
            // Show spinner when the form is submitted
            overlay.show();
            spinner.show();
        
            // Validate the form fields
            if (!form.checkValidity()) {
                form.reportValidity();
                overlay.hide();
                spinner.hide();
                return;
            }
        
            var token = document.getElementById('mytoken').value;
            if (!token) {
                console.log("Card information not yet tokenized. Please try again.");
                overlay.hide();
                spinner.hide();
                return;
            }
        
            // Get the checkbox element
            var termsCheckbox = document.getElementById('terms-and-conditions-accept');
        
            // Check if the checkbox is checked
            if (!termsCheckbox.checked) {
                // Show the modal if the checkbox is not checked
                document.getElementById('termsModal').style.display = 'block';
                overlay.hide();
                spinner.hide();
                return; // Prevent form submission
            }
            
            // Function to get the selected payment amount
            function getSelectedPaymentAmount() {
                const selectedPaymentOption = $('input[name="payment_amount"]:checked').val();
                
                if (selectedPaymentOption === 'total') {
                    // Get the total amount from the displayed total
                    return parseFloat($('#order-total').text().replace(/[^0-9.]/g, ''));
                } else if (selectedPaymentOption === 'partial') {
                    // Get the partial amount from the data attribute
                    return parseFloat($('#payment-amount-partial').data('partial-value'));
                }
                return 0; // Default fallback
            }

            // Prepare data to submit
            const data = {
                parkId: parkId,
                shoppingCartUuid: cartId,
                guestName: $('#guest-full-name-input').val(),
                guestEmail: $('#guest-email-input').val(),
                guestPhone: $('#guest-phone-number-input').val(),
                shippingName: $('#guest-full-name-input').val(),
                stateProvinceOrRegion: $('#guest-state-select').val(),
                shippingType: "SHIPPING",
                country: $('#guest-country').val(),
                city: $('#guest-city-input').val(),
                address1: $('#guest-address-line-1').val(),
                postalCode: $('#guest-postal-code-input').val(),
                smsMessage: $('#checkout-form-field-text-opt-in').is(':checked'),
                cartId: cartId,
                cvv: $('#payment-security-code-input').val(),
                token: token,
                expiry: $('#month').val() + '/' + $('#year').val(),
                amount: getSelectedPaymentAmount()
            };

            console.log("DEBUG 1 ", data);  
            setLocalStorageWithExpiry('amount', getSelectedPaymentAmount());

            // Include address line 2 if provided
            const address2 = $('#guest-address-line-2').val();
            if (address2) {
                data.address2 = address2;
            }
        
            // If billing info is different
            if (!$('#payment-billing-info-same-as-guest-info').is(':checked')) {
                data.billingName = $('#billing-name-on-card-input').val();
                data.billingAddress1 = $('#billing-address-line-1-input').val();
                data.billingCountry = $('#billing-country').val();
                data.billingPostalCode = $('#billing-postal-code-input').val();
                data.billingCity = $('#billing-city-input').val();
                data.billingState = $('#billing-state-select').val();
        
                const billingAddress2 = $('#billing-address-line-2').val();
                if (billingAddress2) {
                    data.billingAddress2 = billingAddress2;
                }
            }
        
            try {
                // Submit the payment request
                const response = await fetch("https://insiderperks.com/wp-content/endpoints/campspot/vapi-submit-card.php", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                });
        
                const text = await response.text();
                const details = JSON.parse(text);
                console.log("DEBUG 2 ", details);

                if (details.apiResponse2.status != 'APPROVED') {
                    overlay.hide();
                    spinner.hide();
                    document.getElementById('paymentErrorModal').style.display = 'block';
                    return;
                }

                else {
                    document.getElementById('paymentErrorModal').style.display = 'none';
                    // Payment succeeded, hide the spinner and redirect
                    const invoiceUUID = details.apiResponse2.invoiceUUID;
                    setLocalStorageWithExpiry('invoiceUUID', invoiceUUID);
                    setLocalStorageWithExpiry('parkID', parkId);
                    let baseurl = "https://booking-checkoutsummary.onrender.com";
                    window.location.href = baseurl + '/booking-summary.php';
                }
            } catch (error) {
                console.error("Error submitting payment:", error);
                overlay.hide();
                spinner.hide();
                document.getElementById('paymentErrorModal').style.display = 'block';
            }
        };
    </script>
</body>
</html>
