@extends('layouts.app')

@section('content')
    <section class="py-16 bg-gradient-to-br from-white to-gray-50" x-data="{ showAddModal: false }">
        <div class="max-w-7xl mx-auto px-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">{{ $title ?? 'Products' }}</h2>
                    <button @click="showAddModal = true"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
                        Add Product
                    </button>
                    <a href="{{ route('categories.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Categories
                        List</a>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        class="bg-green-100 text-green-800 p-4 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Products Table -->
                <div class="w-full overflow-x-auto block">
                    <table class="w-full border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 border border-gray-300">Sl</th>
                                <th class="py-3 px-4 border border-gray-300">Name</th>
                                <th class="py-3 px-4 border border-gray-300">Price</th>
                                <th class="py-3 px-4 border border-gray-300">Categories</th>
                                <th class="py-3 px-4 border border-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $index => $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border border-gray-300">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 border border-gray-300">{{ $product->name }}</td>
                                    <td class="px-4 py-2 border border-gray-300">${{ $product->price }}</td>
                                    <td class="px-4 py-2 border border-gray-300">
                                        @foreach($product->categories as $key => $cat)
                                            <span class="bg-gray-300 py-2">
                                                {{ $cat->name }}@if(!$loop->last),@endif
                                            </span>
                                        @endforeach
                                    </td>

                                    <td class="px-4 py-2 space-x-2">
                                        <!-- Edit Button -->
                                        <button
                                            onclick="document.getElementById('editModal-{{ $product->id }}').classList.remove('hidden')"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                            Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            class="inline-block" onsubmit="return confirm('Delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $products->links() }}
                </div>

            </div>
        </div>

        <!-- Add Product Modal -->
        <div x-show="showAddModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" x-transition
            style="display: none;">
            <div class="bg-white p-6 rounded-lg w-full max-w-md" @click.outside.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Add Product</h3>
                    <button @click="showAddModal = false"
                        class="text-gray-600 hover:text-black text-2xl font-bold">&times;</button>
                </div>
                <form method="POST" action="{{ route('products.store') }}">
                    @csrf
                    <label class="block mb-2 text-sm font-medium">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="w-full border rounded p-2 mb-4" placeholder="Enter name" required>
                    @error('name')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label class="block mb-2 text-sm font-medium">Price <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" class="w-full border rounded p-2 mb-4"
                        placeholder="Enter price" required>
                    @error('price')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror

                    <label class="block mb-2 text-sm font-medium">Categories</label>
                    <div class="grid grid-cols-2 gap-2 mb-4 max-h-48 overflow-y-auto border rounded p-2">
                        @foreach($categories as $category)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-checkbox">
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="text-right">
                        <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Product Modals -->
        @foreach($products as $product)
            <div id="editModal-{{ $product->id }}"
                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
                    <!-- Close Button -->
                    <button onclick="document.getElementById('editModal-{{ $product->id }}').classList.add('hidden')"
                        class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl font-bold">&times;</button>

                    <h3 class="text-lg font-bold mb-4">Edit Product</h3>

                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <label class="block mb-2 text-sm font-medium">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ $product->name }}"
                            class="w-full border border-gray-300 rounded p-2 mb-4" required>

                        <label class="block mb-2 text-sm font-medium">Price <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" value="{{ $product->price }}"
                            class="w-full border border-gray-300 rounded p-2 mb-4" required>

                        <label class="block mb-2 text-sm font-medium">Categories</label>
                        <div class="grid grid-cols-2 gap-2 mb-4 max-h-48 overflow-y-auto border rounded p-2">
                            @foreach($categories as $category)
                                <label class="inline-flex items-center gap-2">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-checkbox"
                                        @if($product->categories->contains($category->id)) checked @endif>
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

    </section>
@endsection