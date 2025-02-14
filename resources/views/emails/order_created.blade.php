<!DOCTYPE html>
<html>
<head>
    <title>Pedido Confirmado</title>
</head>
<body>
    <h2>Olá, {{ $order->client->name }}!</h2>
    <p>Seu pedido #{{ $order->id }} foi criado com sucesso.</p>
    
    <h3>Detalhes do Pedido:</h3>
    <ul>
        @foreach($order->products as $product)
            <li>{{ $product->name }} - R$ {{ number_format($product->price, 2, ',', '.') }}</li>
        @endforeach
    </ul>

    <p>Obrigado por comprar conosco!</p>
</body>
</html>
