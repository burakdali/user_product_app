<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container">
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            @foreach ($products as $index)
                                <div class="card m-4" style="width: 18rem;">
                                    <div class="card-header">
                                        product id: {{ $index['id'] }}
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">product name: {{ $index['name'] }}</h5>
                                        <p class="card-text">product description: {{ $index['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div class="col-4"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
