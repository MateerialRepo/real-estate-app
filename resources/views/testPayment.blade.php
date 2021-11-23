<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAystack test</title>
</head>
<body>

    <form id="paymentForm">
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email-address" required />
    </div>
    <div class="form-group">
        <label for="amount">Amount</label>
        <input type="tel" id="amount" required />
    </div>
    <div class="form-group">
        <label for="first-name">First Name</label>
        <input type="text" id="first-name" />
    </div>
    <div class="form-group">
        <label for="last-name">Last Name</label>
        <input type="text" id="last-name" />
    </div>
    <div class="form-submit">
        <button type="submit" onclick="payWithPaystack()"> Pay </button>
    </div>
    </form>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script> 

    <script>
        var paymentForm = document.getElementById('paymentForm');

        paymentForm.addEventListener('submit', payWithPaystack, false);

        function payWithPaystack() {

            var handler = PaystackPop.setup({
                key: 'pk_test_127adf6c9633deee4a91a6410e9683f1b64106bb', // Replace with your public key
                email: document.getElementById('email-address').value,
                amount: document.getElementById('amount').value * 100, // the amount value is multiplied by 100 to convert to the lowest currency unit
                currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
                ref: '633deee4a91a6410e9Reference', // Replace with a reference you generated

                callback: function(response) {
                    //this happens after the payment is completed successfully
                    var reference = response.reference;

                    $.ajax({
                        type: "POST",
                        url: "http://127.0.0.0/payment/callback/"+reference,
                        dataType: "json",
                        success: function (result, status, xhr) {
                            alert("Success!!!")
                        },
                        error: function (xhr, status, error) {
                            alert("Result: " + status + " " + error + " " + xhr.status + " " + xhr.statusText)
                        }
                    });

                    alert('Payment complete! Reference: ' + reference);
                    // Make an AJAX call to your server with the reference to verify the transaction

                },

                onClose: function() {
                    alert('Transaction was not completed, window closed.');
                },
        });
        handler.openIframe();
        }
    </script>
    
</body>
</html>