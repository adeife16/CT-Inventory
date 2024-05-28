<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Inventory</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
<div class="container">
  <h1 class="mt-5">Product Inventory</h1>
  <form id="product-form" class="mt-4">
    <div class="form-group">
      <label for="name">Product Name</label>
      <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="form-group">
      <label for="quantity">Quantity in Stock</label>
      <input type="number" class="form-control" id="quantity" name="quantity" required>
    </div>
    <div class="form-group">
      <label for="price">Price per Item</label>
      <input type="number" class="form-control" id="price" name="price" required>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
  <h2 class="mt-5">Submitted Data</h2>
  <div id="data-container">
    <h2 class="text-center mt-5">No data available</h2>
  </div>
</div>
<!-- The following script handles fetching and rendering product data from the server -->
<script>
$(document).ready(function() {
  // Function to fetch data from the server
  function fetchData() {
    $.ajax({
      url: '/products', // URL for the server API endpoint
      method: 'GET', // HTTP method
      dataType: 'json', // Expected data type from the server
    })
    .done(
      function(response) {
        // If the request is successful and the response status is 'success'
        if (response.status === 'success') {
          renderData(response.products); // Render the fetched data
        }
      })
    .fail(function() {
      console.log('Failed to fetch data');
    });
  }

  // Function to render the data in HTML
  function renderData(data) {
    let html = '<table class="table table-striped">';
    html += '<thead><tr><th>Product Name</th><th>Quantity in Stock</th><th>Price per Item</th><th>Date and Time</th><th>Total Value</th></tr></thead>';
    html += '<tbody>';
    let totalSum = 0;
    data.forEach((item, index) => {
      let totalValue = item.total_value;
      html += `<tr>
                <td>${item.name}</td>
                <td>${item.quantity}</td>
                <td>$${item.price}</td>
                <td>${item.created_at}</td>
                <td>$${totalValue}</td>
              </tr>`;
      totalSum += totalValue;
    });
    html += `<tr>
              <td colspan="4"><strong>Total</strong></td>
              <td><strong>${totalSum}</strong></td>
              <td></td>
            </tr>`;
    html += '</tbody></table>';
    $('#data-container').html(html);
  }

  // Event handler for the form submission
  $('#product-form').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/products', // URL for the server API endpoint
      method: 'POST', // HTTP method
      data: $(this).serialize(), // Form data to be sent
      dataType: 'json', // Expected data type from the server
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for security
      },
      success: function(response) {
        if (response.status === 'success') {
          fetchData(); // Fetch and render the updated data
          $('#product-form')[0].reset(); // Reset the form
        }
      }
    });
  });

  fetchData(); // Initial data fetch and render
});
</script>
</body>
</html>
