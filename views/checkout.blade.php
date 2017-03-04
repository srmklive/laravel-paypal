<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<div id="paymentMethods"></div>

<script>
    var CREATE_PAYMENT_URL  = 'https://my-store.com/paypal/create-payment';

    paypal.Button.render({

        payment: function(resolve, reject) {
            paypal.request.post(CREATE_PAYMENT_URL)
                .then(function(data) { resolve(data.token); });
            .catch(function(err) { reject(err); });
        },

        onAuthorize: function(data, actions) {
            return actions.redirect();
        },

        onCancel: function(data, actions) {
            return actions.redirect();
        }

    }, '#paymentMethods');
</script>