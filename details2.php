<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shantai Lawns - Marriage Hall Decorations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
        .page-title {
            color: #2c3e50;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .decoration-item {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .decoration-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .decoration-item img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            object-fit: cover;
            height: 200px;
            width: 100%;
        }
        .decoration-item .card-body {
            padding: 20px;
        }
        .decoration-item .card-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .decoration-item .card-text {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .decoration-item .btn-success {
            background-color: #2ecc71;
            border: none;
            transition: all 0.3s ease;
            padding: 10px 20px;
            font-size: 16px;
        }
        .decoration-item .btn-success:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }
        .cart-section {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            margin-top: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .cart-section h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .cart-item {
            background-color: #f8f9fa;
            border: none;
            margin-bottom: 10px;
        }
        .total-price {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-top: 20px;
        }
        .btn-invoice {
            background-color: #3498db;
            border: none;
            transition: all 0.3s ease;
            padding: 12px 24px;
            font-size: 18px;
            margin-top: 20px;
        }
        .btn-invoice:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center page-title">Shantai Lawns Decoration Items</h1>
        <div class="row" id="item-container">
            <!-- Items will be dynamically added here -->
        </div>

        <div class="cart-section">
            <h2>Your Cart</h2>
            <ul id="cart-items" class="list-group mb-3">
                <!-- Cart items will be dynamically added here -->
            </ul>
            <p class="total-price">Total Price: Rs. <span id="total-price">0</span></p>
            <button class="btn btn-primary btn-invoice" onclick="generateInvoice()">See Estimated Invoice</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const items = [
            { name: "Balloons Decoration", price: 50, image: "https://cdn.dotpe.in/longtail/store-items/8164292/Q52ei421.jpeg" },
            { name: "Flowers Decoration", price: 100, image: "flowers.jpg" },
            { name: "Lights Decoration", price: 75, image: "lights.jpg" }
        ];

        let cart = [];
        let totalPrice = 0;

        $(document).ready(function() {
            renderItems();
            updateCart();
        });

        function renderItems() {
            const container = $("#item-container");
            items.forEach((item, index) => {
                container.append(`
                    <div class="col-md-4 mb-4">
                        <div class="card decoration-item">
                            <img src="${item.image}" class="card-img-top" alt="${item.name}">
                            <div class="card-body">
                                <h5 class="card-title">${item.name}</h5>
                                <p class="card-text">Price: Rs. ${item.price}</p>
                                <button class="btn btn-success" onclick="addToCart(${index})">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                `);
            });
        }

        function addToCart(index) {
            cart.push(items[index]);
            totalPrice += items[index].price;
            updateCart();
        }

        function updateCart() {
            const cartItems = $("#cart-items");
            cartItems.empty();
            cart.forEach((item, index) => {
                cartItems.append(`
                    <li class="list-group-item cart-item d-flex justify-content-between align-items-center">
                        ${item.name} - $${item.price}
                        <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Remove</button>
                    </li>
                `);
            });
            $("#total-price").text(totalPrice);
        }

        function removeFromCart(index) {
            totalPrice -= cart[index].price;
            cart.splice(index, 1);
            updateCart();
        }

        function generateInvoice() {
            localStorage.setItem('cart', JSON.stringify(cart));
            window.location.href = 'invoice.html';
        }
    </script>
</body>
</html>