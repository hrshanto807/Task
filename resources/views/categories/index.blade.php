@extends('layouts.app')

@section('content')
  <section class="py-16 bg-gradient-to-br from-white to-gray-50" x-data="{ showAddModal: false }">
    <div class="max-w-7xl mx-auto px-6">
      <div class="bg-white rounded-2xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-2xl font-bold">{{ $title ?? 'Categories' }}</h2>
          <button @click="showAddModal = true"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded flex items-center gap-2">
            Add Category
          </button>
          <a href="{{ route('products.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Product List</a>
        </div>

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

        <div class="w-full overflow-x-auto block">
          <table id="datatable" class="display nowrap border border-gray-300" style="width:100%">
            <thead class="bg-gray-100">
              <tr>
                <th class="py-3 px-4 border border-gray-300 whitespace-nowrap">Sl</th>
                <th class="py-3 px-4 border border-gray-300 whitespace-nowrap">Name</th>
                <th class="py-3 px-4 border border-gray-300 whitespace-nowrap">Status</th>
                <th class="py-3 px-4 border border-gray-300 whitespace-nowrap">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($categories as $index => $category)
                <tr class="hover:bg-gray-50 @if($category->status == 0) bg-red-50 @endif">
                  <td class="px-4 py-2 border border-gray-300">{{ $index + 1 }}</td>
                  <td class="px-4 py-2 border border-gray-300">{{ $category->name }}</td>
                  <td class="px-4 py-2 border border-gray-300">
                    @if ($category->status == 1)
                      <a href="{{ route('categories.deactivate', $category->id) }}" onclick="return confirm('Are you sure?')"
                        class="bg-green-600 text-white text-xs px-3 py-1 rounded hover:bg-green-700">Active</a>
                    @else
                      <a href="{{ route('categories.activate', $category->id) }}" onclick="return confirm('Are you sure?')"
                        class="bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-600">Inactive</a>
                    @endif
                  </td>
                  <td class="px-4 py-2 space-x-2">
                    <!-- Edit Button -->
                    <button onclick="document.getElementById('editModal-{{ $category->id }}').classList.remove('hidden')"
                      class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                      Edit
                    </button>

                    <!-- Delete Form -->
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('Delete this category?')">
                      @csrf
                      @method('DELETE')
                      <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded">Delete</button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <div class="mt-4">
            {{ $categories->links('pagination::tailwind') }}
          </div>
        </div>
      </div>
    </div>

    <!-- Add Modal -->
    <div x-show="showAddModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" x-transition
      style="display: none;">
      <div class="bg-white p-6 rounded-lg w-full max-w-md" @click.outside.stop>
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-bold">Add Category</h3>
          <button @click="showAddModal = false" class="text-gray-600 hover:text-black text-2xl font-bold">&times;</button>
        </div>
        <form method="POST" action="{{ route('categories.store') }}">
          @csrf
          <label class="block mb-2 text-sm font-medium">Name <span class="text-red-500">*</span></label>
          <input type="text" name="name" class="w-full border rounded p-2 mb-4" placeholder="Enter name" required>
          @error('name')
            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
          @enderror
          <div class="text-right">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Submit</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Modals -->
    @foreach($categories as $category)
      <div id="editModal-{{ $category->id }}"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
          <!-- Close Button -->
          <button onclick="document.getElementById('editModal-{{ $category->id }}').classList.add('hidden')"
            class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl font-bold">&times;</button>

          <h3 class="text-lg font-bold mb-4">Edit Category</h3>

          <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="block mb-2 text-sm font-medium">Category Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ $category->name }}"
              class="w-full border border-gray-300 rounded p-2 mb-4" required>

            <div class="text-right">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
          </form>
        </div>
      </div>
    @endforeach
  </section>
@endsection