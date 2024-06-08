<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PHP Stripe Payment Gateway Integration - Tutsmake.com</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <style>
   .container{
    padding: 0.5%;
   } 
</style>
</head>
<body>
  <div class="container">
   
  <h1>Tax Refund Calculator</h1>

<label for="country">Country:</label>
<select id="country" onchange="toggleNonGrossIncome()">
  <option value="Mother">Other country</option>
  <option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belgium">Belgium</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Canada">Canada</option>
<option value="China">China</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Egypt">Egypt</option>
<option value="Estonia">Estonia</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="Germany">Germany</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Ireland">Ireland</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Japan">Japan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Latvia">Latvia</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Malta">Malta</option>
<option value="Mexico">Mexico</option>
<option value="Netherlands">Netherlands</option>
<option value="New Zealand">New Zealand</option>
<option value="Norway">Norway</option>
<option value="Pakistan">Pakistan</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Romania">Romania</option>
<option value="Russia">Russia</option>
<option value="Slovak Republic">Slovak Republic</option>
<option value="Slovenia">Slovenia</option>
<option value="South Africa">South Africa</option>
<option value="Spain">Spain</option>
<option value="Sri Lanka">Sri Lanka</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Turkey">Turkey</option>
<option value="Ukraine">Ukraine</option>
<option value="United Kingdom">United Kingdom</option>
<option value="Venezuela">Venezuela</option>
  <!-- Add more countries as needed -->
</select>

<!-- Inputs for 2020 Tax Year -->
<h2>2020</h2>
<label for="grossIncome2020">U.S. Source Income:</label>
<input type="number" id="grossIncome2020" placeholder=""><br>
<div id="nonGrossIncome2020Section" style="display: none;">
  <label for="nonGrossIncome2020">Non U.S. Source Income:</label>
  <input type="number" id="nonGrossIncome2020" placeholder=""><br>
</div>

<label for="taxWithheld2020">Tax Withheld:</label>
<input type="number" id="taxWithheld2020" placeholder=""><br>
<p id="result2020"></p>

<!-- Inputs for 2021 Tax Year -->
<h2>2021</h2>
<label for="grossIncome2021">U.S. Source Income:</label>
<input type="number" id="grossIncome2021" placeholder=""><br>
<div id="nonGrossIncome2021Section" style="display: none;">
  <label for="nonGrossIncome2021">Non U.S. Source Income:</label>
  <input type="number" id="nonGrossIncome2021" placeholder=""><br>
</div>

<label for="taxWithheld2021">Tax Withheld:</label>
<input type="number" id="taxWithheld2021" placeholder=""><br>
<p id="result2021"></p>

<!-- Inputs for 2022 Tax Year -->
<h2>2022</h2>
<label for="grossIncome2022">U.S. Source Income:</label>
<input type="number" id="grossIncome2022" placeholder=""><br>
<div id="nonGrossIncome2022Section" style="display: none;">
  <label for="nonGrossIncome2022">Non U.S. Source Income:</label>
  <input type="number" id="nonGrossIncome2022" placeholder=""><br>
</div>

<label for="taxWithheld2022">Tax Withheld:</label>
<input type="number" id="taxWithheld2022" placeholder=""><br>
<p id="result2022"></p>

<!-- Inputs for 2023 Tax Year -->
<h2>2023</h2>
<label for="grossIncome2023">U.S. Source Income:</label>
<input type="number" id="grossIncome2023" placeholder=""><br>
<div id="nonGrossIncome2023Section" style="display: none;">
  <label for="nonGrossIncome2023">Non U.S. Source Income:</label>
  <input type="number" id="nonGrossIncome2023" placeholder=""><br>
</div>

<label for="taxWithheld2023">Tax Withheld:</label>
<input type="number" id="taxWithheld2023" placeholder=""><br>
<p id="result2023"></p>

<button onclick="calculateRefund()" class="btn btn-success btn-block">Calculate Refund</button>

<p id="totalRefund"></p>
<div id="apply-div">
<input type="hidden" name="refund" id="refund">
<button onclick="pay()" class="btn btn-primary btn-block">Pay now</button>
</div> 
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>
 
<script type="text/javascript">
  $("#apply-div").hide();
  function pay(amount = 0) {

    amount = $("#refund").val()

    var flatFee = 250;

    var calculate  = (amount * 10) / 100;

    amount = flatFee + calculate;

    var handler = StripeCheckout.configure({
      key: 'pk_test_51Oqv91IsLSX0wXZB2PaDDa4CBWibsZXdfx8CrbX9gxXTSpSjuE2QBHJGSbrvHJ9BIWOuIsi0zPdKiwS3aob1iaFI00u2c6wv2U', // your publisher key id
      locale: 'auto',
      token: function (token) {
        console.log('Token Created!!');
        console.log(token)
 
        $.ajax({
          url:"payment.php",
          method: 'post',
          data: { tokenId: token.id, amount: amount },
          dataType: "json",
          success: function( response ) {
            console.log(response.data);
            if(response.success) {
              window.location.href = "success.php";
            } else {
              window.location.href = "failed.php?msg="+ response.data;
            }
          }
        })
      }
    });
  
    handler.open({
      name: 'Demo Site',
      description: '2 widgets',
      amount: amount * 100
    });
  }
</script>

<script>
  function calculateRefund() {
    var totalRefund = 0;

    // Calculate refund for 2020
    totalRefund += calculateRefundForYear("2020", "grossIncome2020", "taxWithheld2020", "nonGrossIncome2020", "result2020");

    // Calculate refund for 2021
    totalRefund += calculateRefundForYear("2021", "grossIncome2021", "taxWithheld2021", "nonGrossIncome2021", "result2021");

    // Calculate refund for 2022
    totalRefund += calculateRefundForYear("2022", "grossIncome2022", "taxWithheld2022", "nonGrossIncome2022", "result2022");

    // Calculate refund for 2023
    totalRefund += calculateRefundForYear("2023", "grossIncome2023", "taxWithheld2023", "nonGrossIncome2023", "result2023");

    var totalRefundElement = document.getElementById("totalRefund");
    totalRefundElement.innerHTML = "YOUR TOTAL REFUND AMOUNT = $" + totalRefund;

    $("#apply-div").show();
    $("#refund").val(totalRefund);
  }

  function calculateRefundForYear(year, grossIncomeId, taxWithheldId, nonGrossIncomeId, resultId) {
    var grossIncome = parseFloat(document.getElementById(grossIncomeId).value) || 0;
    var taxWithheld = parseFloat(document.getElementById(taxWithheldId).value) || 0;
    var nonGrossIncome = parseFloat(document.getElementById(nonGrossIncomeId).value) || 0;
    var selectedYear = year;

    // Set expenses as a constant 20% of gross salary
    var expenses = 0.2 * grossIncome;

    // Non-gross income set to 0
    var nonGrossIncome = 0;

    // Calculate taxable income
    var taxableIncome = grossIncome - expenses + nonGrossIncome;

    // Tax calculation based on selected tax year
    var tax = 0;
    if (selectedYear === "2020") {
      if (taxableIncome <= 9875) {
        tax = Math.floor(taxableIncome * 0.1);
      } else if (taxableIncome <= 40125) {
        tax = Math.floor(987.5 + (taxableIncome - 9875) * 0.12);
      } else if (taxableIncome <= 85525) {
        tax = Math.floor(4617.5 + (taxableIncome - 40125) * 0.22);
      } else if (taxableIncome <= 163300) {
        tax = Math.floor(14605.5 + (taxableIncome - 85525) * 0.24);
      } else if (taxableIncome <= 207350) {
        tax = Math.floor(33271.5 + (taxableIncome - 163300) * 0.32);
      } else if (taxableIncome <= 518400) {
        tax = Math.floor(47367.5 + (taxableIncome - 207350) * 0.35);
      } else {
        tax = Math.floor(156235 + (taxableIncome - 518400) * 0.37);
      }
    } else if (selectedYear === "2021") {
      if (taxableIncome <= 9950) {
        tax = Math.floor(taxableIncome * 0.1);
      } else if (taxableIncome <= 40525) {
        tax = Math.floor(995 + (taxableIncome - 9950) * 0.12);
      } else if (taxableIncome <= 86375) {
        tax = Math.floor(4664 + (taxableIncome - 40525) * 0.22);
      } else if (taxableIncome <= 164925) {
        tax = Math.floor(14751 + (taxableIncome - 86375) * 0.24);
      } else if (taxableIncome <= 209425) {
        tax = Math.floor(33603.5 + (taxableIncome - 164925) * 0.32);
      } else if (taxableIncome <= 523600) {
        tax = Math.floor(47843.5 + (taxableIncome - 209425) * 0.35);
      } else {
        tax = Math.floor(157804.5 + (taxableIncome - 523600) * 0.37);
      }
    } else if (selectedYear === "2022") {
      if (taxableIncome <= 10275) {
        tax = Math.floor(taxableIncome * 0.1);
      } else if (taxableIncome <= 41775) {
        tax = Math.floor(1027.5 + (taxableIncome - 10275) * 0.12);
      } else if (taxableIncome <= 89075) {
        tax = Math.floor(4807.5 + (taxableIncome - 41775) * 0.22);
      } else if (taxableIncome <= 170050) {
        tax = Math.floor(15213.5 + (taxableIncome - 89075) * 0.24);
      } else if (taxableIncome <= 215950) {
        tax = Math.floor(34647.5 + (taxableIncome - 170050) * 0.32);
      } else if (taxableIncome <= 539900) {
        tax = Math.floor(49335.5 + (taxableIncome - 215950) * 0.35);
      } else {
        tax = Math.floor(162718 + (taxableIncome - 539900) * 0.37);
      }
    } else if (selectedYear === "2023") {
      if (taxableIncome <= 11000) {
        tax = taxableIncome * 0.1;
      } else if (taxableIncome <= 44725) {
        tax = Math.ceil(1100 + (taxableIncome - 11000) * 0.12);
      } else if (taxableIncome <= 95375) {
        tax = Math.ceil(5104 + (taxableIncome - 44725) * 0.22);
      } else if (taxableIncome <= 182100) {
        tax = Math.ceil(19589 + (taxableIncome - 95375) * 0.24);
      } else if (taxableIncome <= 231250) {
        tax = Math.ceil(45189 + (taxableIncome - 182100) * 0.32);
      } else if (taxableIncome <= 578125) {
        tax = Math.ceil(64189 + (taxableIncome - 231250) * 0.35);
      } else {
        tax = Math.ceil(207619 + (taxableIncome - 578125) * 0.37);
      }
    }

    // Calculate refund or amount owed
    var refund = taxWithheld - tax;

    // Display result
    var resultElement = document.getElementById(resultId);
    resultElement.innerHTML = "Your refund or amount owed for " + year + ": $" + parseInt(refund);

    return refund;
  }

  function toggleNonGrossIncome() {
    var country = document.getElementById("country").value;
    var nonGrossIncome2020Section = document.getElementById("nonGrossIncome2020Section");
    var nonGrossIncome2021Section = document.getElementById("nonGrossIncome2021Section");
    var nonGrossIncome2022Section = document.getElementById("nonGrossIncome2022Section");
    var nonGrossIncome2023Section = document.getElementById("nonGrossIncome2023Section");

    if (country !== "Mother") {
      nonGrossIncome2020Section.style.display = "block";
      nonGrossIncome2021Section.style.display = "block";
      nonGrossIncome2022Section.style.display = "block";
      nonGrossIncome2023Section.style.display = "block";
    } else {
      nonGrossIncome2020Section.style.display = "none";
      nonGrossIncome2021Section.style.display = "none";
      nonGrossIncome2022Section.style.display = "none";
      nonGrossIncome2023Section.style.display = "none";
    }
  }
</script>

</body>
</html>