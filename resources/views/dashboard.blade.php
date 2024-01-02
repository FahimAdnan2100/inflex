<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"
        integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
        integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <div class="row p-5">

        <div class="col-md-8">
            @php
                $productLists = App\Models\Admin\Product\product::join('categories', 'categories.id', 'products.category_id')
                    ->select('categories.id AS category_id', 'categories.name AS category_name', 'products.id', 'products.name', 'products.price', 'products.quantity')
                    ->paginate(10);
            @endphp

            <h1>Product List</h2>
                <form id="product-form">
                    @csrf
                    <table class="table table-bordered ">
                        <thead>
                            <tr>
                                <th scope="col">Category Name</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productLists as $productList)
                                <tr>
                                    <td>{{ $productList->category_name }}</td>
                                    <td>{{ $productList->name }}</td>
                                    <td>{{ $productList->price }}</td>
                                    <td class="quantity">{{ $productList->quantity }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary purchase-btn"
                                            value="{{ $productList->id }}">Buy
                                            Now</button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $productLists->links('pagination::bootstrap-4') }}
                </form>

        </div>
        <div class="col-md-4">
            <h1>Export Data</h1>
            <a href="/export"><button class="btn btn-primary mb-4">Export</button></a>

    
            <h1>Import Data</h4>
                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3 form-group">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" id="file" name="file" required>
                        <div class="invalid-feedback">
                            <span>File is required</span>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Import</button>
                </form>

                @if (isset($rtr))

                    @if ($rtr['status'] == 1)
                        <p class="text-success mt-2">{{ $rtr['message'] }}</p>
                    @elseif ($rtr['status'] == 502)
                        <p class="text-danger mt-2">{{ $rtr['message'] }}</p>
                    @elseif ($rtr['status'] == 501)
                        <p class="text-danger mt-2">{{ $rtr['message'] }}</p>
                    @endif

                    {{-- @if ($rtr['failedImportData'])
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Category Name</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($rtr['failedImportData'] as $key => $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->category_id }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif --}}
                @endif

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('purchase-btn')) {
                    e.preventDefault();
                    let productId = e.target.value;
                    fetch(`/purchase/${productId}`)
                        .then(response => response.json())
                        .then(data => {
                            let quantityElement = e.target.closest('tr').querySelector('.quantity');
                            quantityElement.textContent = data.quantity;
                            toastr.options = {
                                "progressBar": true,
                                "closeButton": true
                            };
                            toastr.success("{{ Session::get('success') }}",
                                "Successfully Purchased!", {
                                    timeOut: 3000
                                });


                        })
                        .catch(error => {
                            toastr.error('Purchased Error');
                            console.error('Error:', error);
                        });
                }
            });
        });
    </script>

    <script>
        (() => {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            const forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>

</x-app-layout>
