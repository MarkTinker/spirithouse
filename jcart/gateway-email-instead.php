    <?php
    // JCART v1.1
    // http://conceptlogic.com/jcart/

    // THIS FILE IS CALLED WHEN ANY BUTTON ON THE CHECKOUT PAGE (PAYPAL CHECKOUT, UPDATE, OR EMPTY) IS CLICKED
    // WE CAN ONLY DEFINE ONE FORM ACTION, SO THIS FILE ALLOWS US TO FORK THE FORM SUBMISSION DEPENDING ON WHICH BUTTON WAS CLICKED
    // ALSO ALLOWS US TO VERIFY PRICES BEFORE SUBMITTING TO PAYPAL

    // INCLUDE JCART BEFORE SESSION START
    include_once 'jcart.php';

    // START SESSION
    session_start();

    // INITIALIZE JCART AFTER SESSION START
    $cart =& $_SESSION['jcart']; if(!is_object($cart)) $cart = new jcart();

    // WHEN JAVASCRIPT IS DISABLED THE UPDATE AND EMPTY BUTTONS ARE DISPLAYED
    // RE-DISPLAY THE CART IF THE VISITOR CLICKS EITHER BUTTON
    if ($_POST['jcart_update_cart']  || $_POST['jcart_empty'])
       {

       // UPDATE THE CART
       if ($_POST['jcart_update_cart'])
          {
          $cart_updated = $cart->update_cart();
          if ($cart_updated !== true)
             {
             $_SESSION['quantity_error'] = true;
             }
          }

       // EMPTY THE CART
       if ($_POST['jcart_empty'])
          {
          $cart->empty_cart();
          }

       // REDIRECT BACK TO THE CHECKOUT PAGE
       header('Location: ' . $_POST['jcart_checkout_page']);
       exit;
       }

    // THE VISITOR HAS CLICKED THE PAYPAL CHECKOUT BUTTON
    else
       {

       ///////////////////////////////////////////////////////////////////////
       ///////////////////////////////////////////////////////////////////////
       /*

       A malicious visitor may try to change item prices before checking out,
       either via javascript or by posting from an external script.

       Here you can add PHP code that validates the submitted prices against
       your database or validates against hard-coded prices.

       The cart data has already been sanitized and is available thru the
       $cart->get_contents() function. For example:

       foreach ($cart->get_contents() as $item)
          {
          $item_id   = $item['id'];
          $item_name   = $item['name'];
          $item_price   = $item['price'];
          $item_qty   = $item['qty'];
          }

       */
       ///////////////////////////////////////////////////////////////////////
       ///////////////////////////////////////////////////////////////////////

       $valid_prices = true;

       ///////////////////////////////////////////////////////////////////////
       ///////////////////////////////////////////////////////////////////////

       // IF THE SUBMITTED PRICES ARE NOT VALID
       if ($valid_prices !== true)
          {
          // KILL THE SCRIPT
          die($jcart['text']['checkout_error']);
          }

       // PRICE VALIDATION IS COMPLETE
       // SEND CART CONTENTS TO PAYPAL USING THEIR UPLOAD METHOD, FOR DETAILS SEE http://tinyurl.com/djoyoa
       else if ($valid_prices === true)
          {

        // Here we will construct a new email message to be sent to the merchant
        //  upon completion of the checkout process. The example message below is
        //  intentionally simplistic, and meant to be modified to your preferences.
        $message = "New Order:\n\n";

          foreach ($cart->get_contents() as $item)
             {
             $message .= 'Item: ' . $item['name'];
             $message .= "\nPrice: " . $item['price'];
             $message .= "\nQuantity: " . $item['qty'];
             $message .= "\n\n";
             }

        // The following line uses PHP's built-in mail() function to send the email.
        //  Most servers/hosts support this method by default, while others may
        //  require it to be enabled, or configured differently. For more information
        //  about the mail() function see: http://php.net/manual/en/function.mail.php
        //
        // The function call returns a boolean value indicating whether the mail was
        //  successfully accepted by the server's mail daemon for delivery.
        $mailSent = mail('office@spirithouse.com.au', 'EMAIL TITLE', $message);

          // EMPTY THE CART
          $cart->empty_cart();
          
          // Done! You can either output a purchase "reciept" here, or redirect the
          //  user to another page.
          echo "<h1>Order Sent!</h1>";

          }
       }
    ?>