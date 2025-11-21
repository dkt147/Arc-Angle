<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8" />

<title>Stripe Checkout Frontend Only</title>

<script src="https://js.stripe.com/v3/"></script>

</head>

<body>



<button id="checkout-button">Pay $75</button>



<script>

  const stripe = Stripe('pk_test_51QY4ZQCxi29dqjfazBDMoBwnQK7Z83LOm1FoNFANYOANIm81xNQqXK9Z6JTHSUvImy0Wp5RliSZQgPRsQmjl9YKt00AJKhT1H6'); // Replace with your test publishable key



  const checkoutButton = document.getElementById('checkout-button');

  checkoutButton.addEventListener('click', () => {

    stripe.redirectToCheckout({

      lineItems: [{price: 'price_YourPriceIdHere', quantity: 1}], // Replace with your price ID

      mode: 'payment',

      successUrl: window.location.origin + '/success.html?session_id={CHECKOUT_SESSION_ID}',

      cancelUrl: window.location.origin + '/cancel.html',

    }).then((result) => {

      if (result.error) {

        alert(result.error.message);

      }

    });

  });

</script>



</body>

</html>

