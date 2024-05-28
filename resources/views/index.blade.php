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
<script>
$(document).ready(function() {
  function fetchData() {
    $.ajax({
      url: '/products',
      method: 'GET',
      dataType: 'json',
    })
    .done(
      function(response) {
        if (response.status === 'success') {
          renderData(response.products);
        }
      })
    .fail(function() {
      console.log('Failed to fetch data');
    });
  }

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

  $('#product-form').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: '/products',
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.status === 'success') {
          fetchData();
          $('#product-form')[0].reset();
        }
      }
    });
  });

  fetchData();
});
</script>
</body>
</html>
