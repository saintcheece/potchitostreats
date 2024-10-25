<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Generation</title>
    <link rel="stylesheet" href="css/admin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9; 
            color: #333; 
            margin: 0;
            padding: 20px; 
        }

        .container {
            max-width: 100%; 
            padding: 20px;
            background-color: #fff; 
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
            color: #4A90E2; 
        }

        .tabs {
            display: flex;
            margin-bottom: 10px;
        }

        .tab {
            flex: 1; 
            cursor: pointer;
            padding: 10px;
            background-color: #e7f1ff; 
            border-radius: 5px;
            margin-right: 10px; 
            transition: background-color 0.3s;
            text-align: center;
        }

        .tab:hover {
            background-color: #d0e3ff; 
        }

        .tab.active {
            background-color: #4A90E2; 
            color: white; 
        }

        .report-section {
            display: none; 
            margin-bottom: 20px;
        }

        .report-section.active {
            display: block; 
        }

        .chart-placeholder {
            height: 300px; 
            margin-top: 15px; 
            position: relative; 
        }

        .button-group {
            margin: 15px 0; 
            display: flex; 
            justify-content: flex-end; 
        }
        .max-width-group{
            display: flex;
            justify-content: space-between;

        }
        .generate-button {
            background-color: #4A90E2; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            padding: 10px 15px; 
            cursor: pointer; 
            transition: background-color 0.3s;
        }

        .generate-button:hover {
            background-color: #357ABD; 
        }

        /* New button styles for sales period selection */
        .sales-period-buttons {
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        .sales-period-button {
            background-color: #e7f1ff; 
            border: none; 
            border-radius: 4px; 
            padding: 8px 12px; 
            margin-right: 5px; 
            cursor: pointer; 
            transition: background-color 0.3s;
        }

        .sales-period-button:hover {
            background-color: #d0e3ff; 
        }

        table {
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        th, td {
            padding: 8px; 
            text-align: left; 
            border-bottom: 1px solid #eee; 
        }

        th {
            background-color: #f2f2f2; 
        }

        /* Pagination Styles */
        .pagination {
            display: flex;
            justify-content: flex-end; 
            margin-top: 10px;
        }

        .pagination button {
            background-color: #e7f1ff; 
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 5px 10px;
            margin-left: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .pagination button:hover {
            background-color: #d0e3ff; 
        }

        /* Totals Section Styling */
        .totals {
            margin-top: 20px;
            padding: 10px;
            background-color: #e7f1ff;
            border-radius: 4px;
            text-align: right;
            display: flex;
            justify-content: flex-end;
            gap: 20px;
        }
       /* Styling for Order Date */
       #order-date {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
            margin-right: 20px;
        }

        /* Checkbox alignment */
        #payment-filter {
            margin-left: 10px;
            margin-right: 5px;
            vertical-align: middle;
        }
        .total-item {
            font-weight: bold;
            color: #4A90E2;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Report Generation</h2>
        <div class="tabs">
            <div class="tab active" onclick="showReport('sales')">Sales Report</div>
            <div class="tab" onclick="showReport('products')">Products Report</div>
            <div class="tab" onclick="showReport('orders')">Orders Report</div>
            
        </div>
      <hr>

        <!-- Sales Report Section -->
        <div class="report-section active" id="sales">
            <div class="sales-period-buttons">
              <div>
              <button class="sales-period-button" onclick="updateSalesChart('week')">Weekly Sales</button>
                <button class="sales-period-button" onclick="updateSalesChart('month')">Monthly Sales</button>
                <button class="sales-period-button" onclick="updateSalesChart('year')">Yearly Sales</button>
              </div>
          
              <button class="generate-button" id="printSalesReport">Print Sales Report</button>

              </div>
            <div class="chart-placeholder contentToCapture">
                <canvas id="salesChart"></canvas> 
            </div>
            <div class="button-group">
                <button class="generate-button">Print Sales Report</button>
            </div>
        </div>

        <!-- Products Report Section -->
        <div class="report-section" id="products">
            <div class="button-group max-width-group">
                <div class="filter-button-container">
                    <button class="sales-period-button" onclick="filterProducts('all')">All Products</button>
                    <button class="sales-period-button" onclick="filterProducts('cakes')">Cakes</button>
                    <button class="sales-period-button" onclick="filterProducts('pastries')">Pastries</button>
                </div>
                <button class="generate-button" id="printProductsReport">Print Report</button>
                </div>
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity Sold</th>
                        <th>Total Sold</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Chocolate Cake</td>
                        <td>150</td>
                        <td>Php 15,000</td>
                    </tr>
                    <tr>
                        <td>Vanilla Pastry</td>
                        <td>120</td>
                        <td>Php 12,000</td>
                    </tr>
                    <tr>
                        <td>Red Velvet Cake</td>
                        <td>90</td>
                        <td>Php 9,000</td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <button>Previous</button>
                <button>Next</button>
            </div>
        </div>

        <!-- Orders Report Section -->
        <div class="report-section" id="orders">
            <div class="button-group">
            <button class="generate-button" id="printOrdersReport">Print Report</button>
            </div>

            <!-- Date picker and Payment Filter -->
            <div class="max-width-group">
            <div>
    <label for="order-date">Order Date:</label>
    <input type="date" id="order-date" max="">
</div>

                <div>
                    <label><input type="checkbox" id="payment-filter" onchange="filterPayment()"> Show Fully Paid</label>
                </div>
            </div>

            <table id="ordersTable">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Products Bought</th>
                        <th>Date and Time Ordered</th>
                        <th>Payment Method</th>
                        <th>Deposit or Fully Paid</th>
                        <th>Total Paid</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-paid="true">
                        <td>001</td>
                        <td>Chocolate Cake</td>
                        <td>2024-10-23 10:00 AM</td>
                        <td>Credit Card</td>
                        <td>Fully Paid</td>
                        <td>Php 1,500</td>
                    </tr>
                    <tr data-paid="false">
                        <td>002</td>
                        <td>Vanilla Pastry</td>
                        <td>2024-10-23 10:05 AM</td>
                        <td>Cash</td>
                        <td>Deposit</td>
                        <td>Php 500</td>
                    </tr>
                </tbody>
            </table>

            <!-- Total Orders Summary -->
            <div class="totals">
                <div class="total-item">Total Orders: 2</div>
                <div class="total-item">Total Fully Paid: Php 1,500</div>
                <div class="total-item">Total Deposits: Php 500</div>
            </div>
            <div class="pagination">
                <button>Previous</button>
                <button>Next</button>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        let salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], 
                datasets: [{
                    label: 'Sales (Php)',
                    data: [120, 150, 180, 100, 220, 160, 300], 
                    backgroundColor: '#4A90E2', 
                    borderColor: '#4A90E2', 
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        function filterProducts(category) {
            console.log('Filtering products by category:', category);
        }

        function filterPayment() {
            const showPaid = document.getElementById('payment-filter').checked;
            const rows = document.querySelectorAll('#ordersTable tbody tr');
            rows.forEach(row => {
                if (showPaid && row.dataset.paid !== "true") {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                }
            });
        }

        document.getElementById('order-date').max = new Date().toISOString().slice(0, 10);

        document.getElementById('order-date').addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const rows = document.querySelectorAll('#ordersTable tbody tr');

            rows.forEach(row => {
                const orderDateCell = row.cells[2]; 
                const orderDate = new Date(orderDateCell.dataset.orderDate); 

                if (orderDate.toDateString() === selectedDate.toDateString()) {
                    row.style.display = ''; 
                } else {
                    row.style.display = 'none'; 
                }
            });
        });

        function formatOrderDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        document.querySelectorAll('#ordersTable tbody tr').forEach(row => {
            const dateCell = row.cells[2];
            const orderDateStr = dateCell.textContent.trim();
            dateCell.dataset.orderDate = orderDateStr; 
            dateCell.textContent = formatOrderDate(orderDateStr); 
        });
        function updateSalesChart(period) {
            let newData = [];
            let newLabels = [];

            switch (period) {
                case 'week':
                    newData = [120, 150, 180, 100, 220, 160, 300];
                    newLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                    break;
                case 'month':
                    newData = [500, 600, 550, 700, 750, 800, 900];
                    newLabels = ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'];
                    break;
                case 'year':
                    newData = [12000, 15000, 18000, 20000, 22000, 25000, 30000];
                    newLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'];
                    break;
            }

            salesChart.data.labels = newLabels;
            salesChart.data.datasets[0].data = newData;
            salesChart.update();
        }

        function showReport(reportType) {
            const reports = document.querySelectorAll('.report-section');
            const tabs = document.querySelectorAll('.tab');

            reports.forEach(report => {
                report.classList.remove('active');
            });

            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            document.getElementById(reportType).classList.add('active');
            document.querySelector(`.tab[onclick*="${reportType}"]`).classList.add('active');
        }
        document.getElementById('printSalesReport').addEventListener('click', function() {
    window.location.href = 'generate_pdf.php?report=sales';
});

document.getElementById('printProductsReport').addEventListener('click', function() {
    window.location.href = 'generate_pdf.php?report=products';
});
document.getElementById('printSalesReport').addEventListener('click', function() {
    html2canvas(document.getElementById('contentToCapture')).then(canvas => {
        const imageData = canvas.toDataURL('image/png');

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'generate_pdf.php?report=sales';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'screenshotImage';
        input.value = imageData;
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
    });
});

document.getElementById('printOrdersReport').addEventListener('click', function() {
    window.location.href = 'generate_pdf.php?report=products';
});


    </script>
</body>
</html>
