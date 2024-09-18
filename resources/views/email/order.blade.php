<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #000000; /* Black text */
        }
        .email-wrapper {
            width: 100%;
            background-color: #f7f7f7;
            padding: 20px 0;
        }
        .email-container {
            width: 90%;
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff; /* White background */
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #f7ca0d; /* Yellow */
            color: #000000; /* Black text */
            text-align: center;
            padding: 30px 0;
        }
        .header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: 500;
        }
        .order-info {
            padding: 20px;
            background-color: #f4f8fb;
            border-bottom: 1px solid #e6e6e6;
        }
        .order-info h2 {
            font-size: 20px;
            color: #f7ca0d; /* Yellow */
            margin: 0 0 10px 0;
        }
        .order-info p {
            font-size: 16px;
            color: #555555;
        }

        /* Shipping Address Styling */
        .shipping-address {
            padding: 20px;
            background-color: #f4f8fb;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #e6e6e6;
        }
        .shipping-address h3 {
            font-size: 20px;
            color: #333333;
            margin-bottom: 10px;
        }
        .shipping-address address {
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
        }
        .shipping-address strong {
            font-size: 18px;
            color: #333333;
            display: block;
            margin-bottom: 5px;
        }
        .shipping-address span {
            display: block;
            margin-bottom: 5px;
        }

        .order-summary {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .order-summary th {
            background-color: #f4f8fb;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 16px;
            color: #333333;
            border-bottom: 1px solid #e6e6e6;
        }
        .order-summary td {
            padding: 10px;
            font-size: 16px;
            color: #555555;
            border-bottom: 1px solid #e6e6e6;
        }
        .order-summary tfoot th, .order-summary tfoot td {
            padding: 15px 10px;
            font-weight: bold;
            font-size: 18px;
            color: #333333;
        }
        .total-row th, .total-row td {
            background-color: #f7ca0d; /* Yellow */
            color: #000000; /* Black text */
        }

        /* Footer Styling */
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #000000; /* Black background */
            color: #ffffff; /* White text */
            font-size: 14px;
        }
        .footer a {
            color: #f7ca0d; /* Yellow links */
            text-decoration: underline;
        }
        .footer p {
            margin: 5px 0;
        }

        /* Mobile Styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100%;
                padding: 10px;
            }
            .header h1 {
                font-size: 22px;
            }
            .order-summary th, .order-summary td {
                font-size: 14px;
            }
            .order-info h2 {
                font-size: 18px;
            }
            .footer {
                padding: 15px; /* Adjust padding for better visibility */
            }
        }
    </style>
</head>
<body>
        
  
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            @if ($mailData['userType'] == 'customer')

            <div class="header">
                <h1>Thank You for Your Order!</h1>
            </div>

            <!-- Order Information -->
            <div class="order-info">
                <h2>Order ID: #{{$mailData['order']->id}}</h2>
                <p>We are excited to process your order. Below is a summary of the details.</p>
            </div>

            @else
            
            <div class="header">
                <h1>You Have Received An Order</h1>
            </div>

            <!-- Order Information -->
            <div class="order-info">
                <h2>Order ID: #{{$mailData['order']->id}}</h2>
                <p>Order Invoice . Below is a summary of the details.</p>
            </div>
            @endif

            <!-- Shipping Address -->
            <div class="shipping-address">
                <h3>Shipping Address</h3>
                <address>
                    <strong>{{ strtoupper($mailData['order']->first_name) . ' ' . strtoupper($mailData['order']->last_name) }}</strong>
                    <span>{{ $mailData['order']->address }}</span>
                    <span>{{ $mailData['order']->city }}, {{ $mailData['order']->zip }} {{ $mailData['order']->countryName }}</span>
                    <span>Phone: {{ $mailData['order']->mobile }}</span>
                    <span>Email: {{ $mailData['order']->email }}</span>
                </address>
            </div>

            <!-- Order Summary Table -->
            <table class="order-summary" cellpadding="10" cellspacing="0" border="0">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mailData['order']->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>${{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" align="right">Subtotal:</th>
                        <td>${{ number_format($mailData['order']->subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="3" align="right">Discount:</th>
                        <td>-${{ number_format($mailData['order']->discount ?? 0, 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="3" align="right">Shipping:</th>
                        <td>${{ number_format($mailData['order']->shipping, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <th colspan="3" align="right">Grand Total:</th>
                        <td>${{ number_format($mailData['order']->grand_total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Footer -->
            <div class="footer">
                <p>Need help? Contact us at <a href="mailto:support@example.com">support@example.com</a></p>
                <p>&copy; 2024 Tech Shop. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
