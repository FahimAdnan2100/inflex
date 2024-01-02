@extends('admin.index')
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 card m-5 p-4">
            <h4 class="text-center ">Product List</h4>
            <hr>
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
                            <td>{{ $productList->product_name }}</td>
                            <td>{{ $productList->price }}</td>
                            <td>{{ $productList->quantity }}</td>
                            <td>
                                <button class="btn btn-success edit-product" type="button"
                                    value="{{ $productList->product_id }}">Edit</button>
                                <button class="btn btn-danger delete-product" type="button"
                                    value="{{ $productList->product_id }}">delete</button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {{ $productLists->links('pagination::bootstrap-4') }}
        </div>
        <div class="col-md-4 card p-4">
            <h4 class="text-center">Add Product</h4>
            <hr>
            <form method="post" action="{{ route('product.store') }}" class="needs-validation" id="product-form"
                novalidate>
                @csrf
                <input type="hidden" id="id" name="id">
                <div class="mb-3 form-group">
                    <label for="category_id" class="form-label w-100">Category *</label>
                    <select class="form-select" name="category_id" id="category_id" required>
                        <option value="" selected="" disabled="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">
                        <span>Category is required</span>
                    </div>
                </div>

                <div class="mb-3 form-group">
                    <label for="name" class="form-label">Name *</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">
                        <span>Name is required</span>
                    </div>
                </div>
                <div class="mb-3 form-group">
                    <label for="price" class="form-label">Price *</label>
                    <input type="number" class="form-control" id="price" name="price" required>
                    <div class="invalid-feedback">
                        <span>Price is required</span>
                    </div>
                </div>
                <div class="mb-3 form-group">
                    <label for="quantity" class="form-label">Quantity *</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                    <div class="invalid-feedback">
                        <span>Quantity is required</span>
                    </div>
                </div>
                <button type="submit" id="save-btn" class="btn btn-primary">Save</button>
                <button type="submit" id="update-btn" class="btn btn-primary d-none">Update</button>
            </form>
        </div>
    </div>

    <script>
        //get info
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('edit-product')) {
                    e.preventDefault();
                    let productId = e.target.value;
                    fetch(`/get-product-info/${productId}`)
                        .then(response => response.json())
                        .then(data => {

                            document.getElementById('id').value = data.id;
                            let selectElement = document.getElementById('category_id');

                            for (let i = 0; i < selectElement.options.length; i++) {
                                if (selectElement.options[i].value == data.category_id) {
                                    selectElement.options[i].selected = true;
                                    break;
                                }
                            }
                            document.getElementById('name').value = data.name;
                            document.getElementById('price').value = data.price;
                            document.getElementById('quantity').value = data.quantity;
                            let saveButton = document.getElementById('save-btn');
                            saveButton.classList.add('d-none');
                            let updateButton = document.getElementById('update-btn');
                            updateButton.classList.remove('d-none');
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });

        //update
        document.addEventListener('DOMContentLoaded', function() {
            const updateButton = document.getElementById('update-btn');

            if (updateButton) {
                updateButton.addEventListener('click', function(e) {
                    e.preventDefault();

                    let formData = new FormData(document.getElementById('product-form'));

                    fetch('/update-product-info', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            toastr.options = {
                                "progressBar": true,
                                "closeButton": true
                            };
                            toastr.success("{{ Session::get('success') }}", "Updated!", {
                                timeOut: 3000
                            });
                            location.reload();
                        })
                        .catch(error => {
                            toastr.error('Validation Error');
                            console.error('Error:', error);
                        });
                });
            }
        });



        //delete
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-product')) {
                    e.preventDefault();
                    let productId = e.target.value;
                    fetch(`/delete-product/${productId}`)
                        .then(response => response.json())
                        .then(data => {
                            toastr.options = {
                                "progressBar": true,
                                "closeButton": true
                            };
                            toastr.success("{{ Session::get('success') }}", "Deleted!", {
                                timeOut: 3000
                            });
                            location.reload();
                        })
                        .catch(error => toastr.error('Validation Error'));
                }
            });
        });
    </script>
@endsection
