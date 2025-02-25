// resources/views/products/index.blade.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products List</title>
</head>
<body>
    <h1>Lista Proizvoda</h1>

    @if($products->isEmpty())
        <p>Nema proizvoda.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ime proizvoda</th>
                    {{-- <th>Cena</th>
                    <th>Brend</th>
                    <th>Slika</th>
                    <th>Specifikacija</th>         --}}
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id}}</td>
                        <td>{{ $product->name }}</td>
                        {{-- <td>{{ $product->price }}</td>
                        <td>{{ $product->brand }}</td>
                        <td>@foreach(explode(',', $product->all_images) as $image)
                            <img src="{{ $image }}" alt="Product Image" class="product-image" />
                        @endforeach</td>
                        <td>{{ $product->specifications}}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
