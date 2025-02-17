<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Page</title>
    <style>
        .campspot-order-confirmation {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .campspot-order-confirmation h1 {
            /* font-size: 24px; */
            margin-bottom: 5px;
        }

        .order-number {
            color: #666;
            margin-top: 0;
        }

        .success-message {
            background-color: #f8f8d7;
            border: 1px solid #e6e6b8;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }

        .success-message p {
            margin: 5px 0;
        }

        .order-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            background: #fff !important;
            border-radius: 3px !important;
            box-shadow: rgba(0, 0, 0, .08) 0 1px 1px, rgba(0, 0, 0, .12) 0 1px 3px 1px !important;
            flex-wrap: wrap; /* Add this line to allow wrapping */
        }

        .campground-info, .order-info-table {
            flex: 1;
            padding: 15px;
            min-width: 300px; /* Ensure a minimum width */
        }

        .campground-info h3 {
            margin-bottom: 0px;
            margin-top: 0;
        }

        .campground-info p {
            margin: 0px 0; /* Add margin to separate the lines */
            word-break: break-word; /* Ensure long words break to the next line */
            display: block; /* Ensure each p element is on its own line */
            height: 20px;
            font-size: 15px;
        }

        .campground-info a {
            line-height: 0px;
        }

        .order-info-table {
            margin-left: 20px;
            margin-top: 15px;
            width: 100%; /* Ensure full width */
        }

        .order-info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f2f2f2;
        }

        .order-info-label {
            /* font-weight: bold; */
            flex: 1;
        }

        .order-info-value {
            flex: 2;
            text-align: right;
        }

        .order-cart {
            background: #fff !important;
            border-radius: 3px !important;
            box-shadow: rgba(0, 0, 0, .08) 0 1px 1px, rgba(0, 0, 0, .12) 0 1px 3px 1px !important;
            /* background-color: #f9f9f9; */
            padding: 20px;
            /* border-radius: 4px; */
        }

        .order-cart h2 {
            font-size: 20px;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }

        .cart-detail-site-item {
            /* border: 1px solid #e0e0e0; */
            min-height: 110px;
            max-height: 110px;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 20px;
            /* background-color: #f9f9f9; */
        }

        .cart-detail-site-item::after {
            content: "";
            display: table;
            clear: both;
        }

        .cart-detail-site-item a {
            float: left;
            width: 15%;
        }

        .cart-detail-site-item-thumbnail {
            object-position: center;
            width: 130px;
            aspect-ratio: 1/1;
            display: block;
            object-fit: cover;
        }

        .cart-detail-site-item-details {
            justify-content: center;
            float: left;
            width: 55%;
            padding: 5px;
            box-sizing: border-box;
        }

        .cart-detail-site-item-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 2px;
            color: #333;
        }

        .cart-detail-site-item-dates,
        .cart-detail-site-item-guests, .cart-detail-site-item-guests-adult, .cart-detail-site-item-guests-children, .cart-detail-site-item-guests-pet {
            font-size: 14px;
            color: #666;
            margin-bottom: 0.5px;
        }

        .cart-detail-site-item-pricing {
            float: left;
            width: 30%;
            padding: 10px;
            box-sizing: border-box;
        }

        .cart-detail-site-item-pricing-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .cart-detail-site-item-pricing-label {
            color: #333;
        }

        .cart-detail-site-item-pricing-value, .cart-detail-site-order-pricing-label {
            font-weight: bold;
            text-align: right;
        }

        .order-total {
            height: 110px;
            margin-top: 20px;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }

        .order-total .cart-detail-site-item-pricing {
            width: 30%;
            float: right;
            padding: 10px;
            box-sizing: border-box;
        }

        .order-total .cart-detail-site-item-pricing-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .order-total .cart-detail-site-item-pricing-label {
            color: #333;
        }

        .order-total .cart-detail-site-item-pricing-value {
            font-weight: bold;
            text-align: right;
        }

        @media (max-width: 768px) {
            .order-details {
                flex-direction: column;
                align-items: center; /* Center align items */
            }

            .order-info-table {
                margin-left: 0;
                margin-top: 20px;
                width: 100%; /* Ensure full width */
            }

            .order-info-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-info-label, .order-info-value {
                text-align: left;
                width: 100%;
            }

            .order-info-table th, .order-info-table td {
                display: block;
                width: 100%;
                text-align: left;
                padding: 5px 0; /* Reduce padding */
            }

            .order-info-table tr {
                display: contents; /* Keep the table structure */
            }

            .order-info-table th, .order-info-table td {
                padding: 5px 0; /* Reduce padding */
            }

            .order-info-table th {
                background-color: transparent;
            }

            .order-info-table td {
                padding-bottom: 5px; /* Reduce padding */
            }

            .order-info-table tr:nth-child(odd) {
                background-color: transparent;
            }

            .order-info-table tr:nth-child(even) {
                background-color: transparent;
            }

            .order-info-table tr:hover {
                background-color: transparent;
            }

            .order-info-table tr {
                line-height: normal;
            }

            .order-info-table tr:nth-child(3), .order-info-table tr:nth-child(4) {
                grid-column: span 2; /* Make these rows span both columns */
            }
        }

        .spinner {
            display: none;
            width: 48px;
            height: 48px;
            border: 5px solid #FFF;
            border-bottom-color: #FF3D00;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            margin: auto;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation: spin 1s linear infinite;
        }

        /* Gray Overlay CSS */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(128, 128, 128, 0.5); /* Gray color with 50% opacity */
            z-index: 9999;
        }

        /* Spinner Animation */
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        @media (max-width: 768px) {
            .cart-detail-site-item {
                display: block;
                flex-direction: row;
                justify-content: space-between;
                align-items: flex-start;
                width: 100%;
                margin-bottom: 20px;
                min-height: 110px;
                max-height: fit-content;
            }

            .cart-detail-site-item a {
                width: 100%;
                margin-right: 10px;
                margin-bottom: 10px;
            }

            .cart-detail-site-item-thumbnail {
                width: 100%;
                height: auto;
            }

            .cart-detail-site-item-details {
                width: auto;
                text-align: left;
                padding: 0;
                margin-bottom: 5px;
            }

            .cart-detail-site-item-pricing {
                width: 65%;
                text-align: right;
                padding: 0;
                float: right;
            }

            .cart-detail-site-item-pricing-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 5px;
            }

            .cart-detail-site-item-pricing-label,
            .cart-detail-site-item-pricing-value-total,
            .cart-detail-site-item-pricing-value-cfee,
            .cart-detail-site-item-pricing-value-taxes {
                text-align: right;
            }

            .order-total {
                padding-top: 10px;
                width: 100%;
                text-align: center;
            }

            .order-total .cart-detail-site-item-pricing {
                width: 100%;
                text-align: right; /* Align text to the right */
                padding: 0;
                float: none; /* Remove float */
            }

            .order-total .cart-detail-site-item-pricing-row {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-direction: row; /* Ensure row direction */
                margin-bottom: 5px;
            }

            .order-total .cart-detail-site-item-pricing-label,
            .order-total .cart-detail-site-item-pricing-value {
                text-align: right;
            }
        }
    </style>
</head>

<body>
    <div class="overlay">
        <div class="spinner"></div>
    </div>

    <div class="campspot-order-confirmation">
        <h1>Order Confirmation</h1>
        <p class="order-number">#000000000</p>

        <div class="success-message">
            <p>Your order has been successfully placed!</p>
            <p>Check your email for a confirmation message.</p>
        </div>

        <div class="order-details">
            <div class="campground-info">
                <h3 class="campground-name">Loading Resort</h3>
                <p class="campground-address">Address: Test</p>
                <p class="campground-phone">Phone: 123456</p>
                <p class="campground-email">Email: test@email.com</p>
            </div>

            <div class="order-info-table">
                <div class="order-info-row">
                    <div class="order-info-label">Order Placed</div>
                    <div class="order-info-value guest-date">Loading</div>
                </div>
                <div class="order-info-row">
                    <div class="order-info-label">Guest Information</div>
                    <div class="order-info-value guest-name">Loading</div>
                </div>
                <div class="order-info-row">
                    <div class="order-info-label">Payment Information</div>
                    <div class="order-info-value guest-visa">Loading</div>
                </div>
                <div class="order-info-row">
                    <div class="order-info-label">Confirmation</div>
                    <div class="order-info-value guest-confirmation">Loading</div>
                </div>
                <div class="order-info-row">
                    <div class="order-info-label">Invoice</div>
                    <div class="order-info-value guest-invoice">Loading</div>
                </div>
            </div>
        </div>

        <div class="order-cart">
            <h2>Order Details</h2>
            <section class="cart-content">
                <div class="cart-summary"></div>
            </section>

            <div class="order-total">
                <div class="cart-detail-site-item-pricing">
                    <div class="cart-detail-site-item-pricing-row">
                        <span class="cart-detail-site-order-pricing-label">Order Total</span>
                        <span class="cart-detail-site-item-pricing-value order-total-value">$1,180.00</span>
                    </div>
                    <div class="cart-detail-site-item-pricing-row">
                        <span class="cart-detail-site-order-pricing-label">VISA Payment</span>
                        <span class="cart-detail-site-item-pricing-value visa-total">($1,180.00)</span>
                    </div>
                    <div class="cart-detail-site-item-pricing-row">
                        <span class="cart-detail-site-order-pricing-label">Outstanding Balance</span>
                        <span class="cart-detail-site-item-pricing-value outstanding-total">$0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add the JavaScript at the bottom of the body -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    var overlay = document.querySelector('.overlay');
    var spinner = document.querySelector('.spinner');
    var loading = document.querySelector('.campspot-order-confirmation');
    overlay.style.display = 'block';
    spinner.style.display = 'block';
    loading.style.display = 'none';
    // Fetch parkId and invoiceUUID from localStorage
    let l_parkid = localStorage.getItem('parkID');
    let l_invoiceUUID = localStorage.getItem('invoiceUUID');
    l_invoiceUUID = JSON.parse(l_invoiceUUID);
    l_parkid = JSON.parse(l_parkid);
    const parkId = l_parkid.value;
    const invoiceUUID = l_invoiceUUID.value;

    

    // Function to adjust margin based on address length and window width
    function adjustAddressMargin() {
        var addressElement = $('.campground-address');
        if (addressElement.text().length > 45 && window.innerWidth <= 768) {
            addressElement.css('margin-bottom', '25px');
        } else {
            addressElement.css('margin-bottom', ''); // Reset margin if conditions are not met
        }
    }

    function formatDate(dateString) {
        var date = new Date(dateString);
        var options = {
            weekday: 'short',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return date.toLocaleDateString('en-US', options);
    }
    

    // Make the API call
    $.ajax({
        url: 'https://insiderperks.com/wp-content/endpoints/crr/order-summary.php',
        method: 'GET',
        data: {
            parkId: parkId,
            invoiceUUID: invoiceUUID
        },
        success: function (response) {
            console.log('API Response:', response); // Log the entire response for debugging

            if (typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    return;
                }
            }

            // Assuming response is a JSON object
            var orderSummary = response.orderSummary;
            var parkMetadata = response.parkMetadata;
            var campsiteConfirmationSummaries = response.orderSummary.campsiteConfirmationSummaries;

            if (orderSummary && parkMetadata) {
                // Trim "N/A" from parkMetadata.name
                var trimmedName = orderSummary.customerName.replace(/N\/A\s*/g, '').trim();
                var formattedPhoneNumber = parkMetadata.phoneNumber.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                var formattedOrderDate = formatDate(orderSummary.orderDate);

                // Update the HTML with the fetched data
                $('.order-number').text('#'+orderSummary.invoiceId);
                $('.campground-name').text(parkMetadata.name);
                $('.campground-address').text(parkMetadata.address);
                $('.campground-phone').html('Phone: <a href="tel:' + parkMetadata.phoneNumber + '">' + formattedPhoneNumber + '</a>');
                $('.campground-email').html('Email: <a href="mailto:' + parkMetadata.email + '">' + parkMetadata.email + '</a>');
                $('.guest-date').text(formattedOrderDate);
                $('.guest-name').text(trimmedName);
                $('.guest-visa').text(orderSummary.cardType +' ending in '+ orderSummary.lastFour);
                $('.guest-confirmation').text('#' + orderSummary.orderConfirmation);
                $('.guest-invoice').text('#' + orderSummary.invoiceId);
                $('.order-total-value').text('$' + orderSummary.grandTotal);
                $('.visa-total').text('($' + orderSummary.payments[0].total + ')');
                $('.outstanding-total').text('$' + orderSummary.remainingBalance);

                // Adjust margin if address length is more than 45 characters and in mobile view
                adjustAddressMargin();
            } else {
                console.error('orderSummary or parkMetadata is undefined');
            }

            // Clear existing cart-detail-site-item elements
            $('.cart-summary').empty();

            // Append new cart-detail-site-item elements
            if (campsiteConfirmationSummaries && campsiteConfirmationSummaries.length > 0) {
                campsiteConfirmationSummaries.forEach(function (campsite) {
                    var checkinDate = new Date(campsite.checkinDateInUTC);
                    var checkoutDate = new Date(campsite.checkoutDateInUTC);
                    var totalNights = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));

                    var childrenElement = campsite.childrenCount > 0 ? `<div class="cart-detail-site-item-guests-children">Children: ${campsite.childrenCount}</div>` : '';
                    var petElement = campsite.petCount > 0 ? `<div class="cart-detail-site-item-guests-pet">Pets: ${campsite.petCount}</div>` : '';

                    var cartItem = `
                        <div class="cart-detail-site-item">
                            <a href="#">
                                <img class="cart-detail-site-item-thumbnail" src="${campsite.images.mainImage.medium.url}" alt="${campsite.campsiteType.name}">
                            </a>
                            <div class="cart-detail-site-item-details">
                                <h3 class="cart-detail-site-item-name">
                                    ${campsite.campsiteType.name} 
                                    ${campsite.campsiteType.isPetFriendly ? 
                                        '<i class="fas fa-paw" title="Pet-Friendly" role="img" aria-label="Pet-Friendly"></i>' : ''}
                                </h3>
                                <div>
                                    ${
                                        campsite.siteLocked === true
                                        ? `Site #${campsite.campsite.name}`
                                        : `<span style="color: red; font-size: 12px;">Site # is subject to change</span>`
                                    }
                                </div>
                                <div class="cart-detail-site-item-dates">${checkinDate.toDateString()} - ${checkoutDate.toDateString()}</div>
                                <div class="cart-detail-site-item-guests-adult">Adults: ${campsite.adultCount}</div>
                                ${childrenElement}
                                ${petElement}
                            </div>
                            <div class="cart-detail-site-item-pricing">
                                <div class="cart-detail-site-item-pricing-row">
                                    <span class="cart-detail-site-item-pricing-label">$${campsite.pricing.dailyRate.rate.toFixed(2)} x ${totalNights} Nights</span>
                                    <span class="cart-detail-site-item-pricing-value-total">$${campsite.pricing.dailyRate.total}</span>
                                </div>
                                <div class="cart-detail-site-item-pricing-row">
                                    <span class="cart-detail-site-item-pricing-label">Campground Fees</span>
                                    <span class="cart-detail-site-item-pricing-value-cfee">$${campsite.pricing.campgroundFeeSummary.totalCampgroundFees}</span>
                                </div>
                                <div class="cart-detail-site-item-pricing-row">
                                    <span class="cart-detail-site-item-pricing-label">Taxes</span>
                                    <span class="cart-detail-site-item-pricing-value-taxes">$${campsite.pricing.totalTaxes}</span>
                                </div>
                            </div>
                        </div>
                    `;

                    $('.cart-summary').append(cartItem);

                    if(campsite.dailyRateAddonConfirmations.length > 0) {
                        campsite.dailyRateAddonConfirmations.forEach(function (addon) {
                            var addOncheckinDate = new Date(addon.checkinDateInUTC);
                            var addOncheckoutDate = new Date(addon.checkoutDateInUTC);
                            var addOntotalNights = Math.ceil((addOncheckoutDate - addOncheckinDate) / (1000 * 60 * 60 * 24));

                            var addonItem = `
                                <div class="cart-detail-site-item">
                                    <a href="#">
                                        <img class="cart-detail-site-item-thumbnail" src="${addon.images.mainImage.small.url}" alt="${addon.name}">
                                    </a>
                                    <div class="cart-detail-site-item-details">
                                        <h3 class="cart-detail-site-item-name">Add On: ${addon.name}</h3>
                                        <div class="cart-detail-site-item-dates">${addOncheckinDate.toDateString()} - ${addOncheckoutDate.toDateString()}</div>
                                    </div>
                                    <div class="cart-detail-site-item-pricing">
                                        <div class="cart-detail-site-item-pricing-row">
                                            <span class="cart-detail-site-item-pricing-label">$${addon.pricing.dailyRate.rate.toFixed(2)} x ${addOntotalNights} Nights</span>
                                            <span class="cart-detail-site-item-pricing-value-total">$${addon.pricing.dailyRate.total}</span>
                                        </div>
                                        <div class="cart-detail-site-item-pricing-row">
                                            <span class="cart-detail-site-item-pricing-label">Campground Fees</span>
                                            <span class="cart-detail-site-item-pricing-value-cfee">$${addon.pricing.campgroundFeeSummary.totalCampgroundFees}</span>
                                        </div>
                                        <div class="cart-detail-site-item-pricing-row">
                                            <span class="cart-detail-site-item-pricing-label">Taxes</span>
                                            <span class="cart-detail-site-item-pricing-value-taxes">$${addon.pricing.totalTaxes}</span>
                                        </div>
                                    </div>
                                </div>
                            `;

                            $('.cart-summary').append(addonItem);
                        });
                    }

                    if(campsite.onlineStoreAddonConfirmations.length > 0) {
                        campsite.onlineStoreAddonConfirmations.forEach(function (addon) {
                            var addOncheckinDate = new Date(addon.checkinDateInUTC);
                            var addOncheckoutDate = new Date(addon.checkoutDateInUTC);
                            var addOntotalNights = Math.ceil((addOncheckoutDate - addOncheckinDate) / (1000 * 60 * 60 * 24));

                            var addonItem = `
                                <div class="cart-detail-site-item">
                                    <a href="#">
                                        <img class="cart-detail-site-item-thumbnail" src="${addon.images.mainImage.small.url}" alt="${addon.name}">
                                    </a>
                                    <div class="cart-detail-site-item-details">
                                        <h3 class="cart-detail-site-item-name">Add On: ${addon.name}</h3>
                                        <div class="cart-detail-site-item-dates">${addOncheckinDate.toDateString()} - ${addOncheckoutDate.toDateString()}</div>
                                    </div>
                                    <div class="cart-detail-site-item-pricing">
                                        <div class="cart-detail-site-item-pricing-row">
                                            <span class="cart-detail-site-item-pricing-label">$${addon.pricing.dailyRate.rate.toFixed(2)} x ${addOntotalNights} Nights</span>
                                            <span class="cart-detail-site-item-pricing-value-total">$${addon.pricing.dailyRate.total}</span>
                                        </div>
                                        <div class="cart-detail-site-item-pricing-row">
                                            <span class="cart-detail-site-item-pricing-label">Campground Fees</span>
                                            <span class="cart-detail-site-item-pricing-value-cfee">$${addon.pricing.campgroundFeeSummary.totalCampgroundFees}</span>
                                        </div>
                                        <div class="cart-detail-site-item-pricing-row">
                                            <span class="cart-detail-site-item-pricing-label">Taxes</span>
                                            <span class="cart-detail-site-item-pricing-value-taxes">$${addon.pricing.totalTaxes}</span>
                                        </div>
                                    </div>
                                </div>
                            `;

                            $('.cart-summary').append(addonItem);
                        });
                    }
                });
            } else {
                console.error('campsiteConfirmationSummaries is undefined or empty');
            }
            overlay.style.display = 'none';
            spinner.style.display = 'none';
            loading.style.display = 'block';
        },
        error: function (error) {
            console.error('Error fetching order summary:', error);
        }
    });

    // Adjust margin on window resize
    $(window).resize(function () {
        adjustAddressMargin();
    });
    </script>
</body>
</html>
