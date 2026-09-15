<x-app-layout>
    <x-slot name="header">
        <div class="flex w-full justify-between items-center lg:flex-row">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transaction Dashboard') }}
            </h2>
            <div class="flex justify-end px-12">
                <x-edit-button>
                    {{ __('Export to Excel') }}
                </x-edit-button>
            </div>
        </div>
    </x-slot>

    <x-table-container>
        <div class="flex flex-col md:flex-row m-0 mb-8">
            <x-row-card class="w-full flex flex-col">
                <span class="w-full items-center text-xl font-semibold">Income</span>
                <span>Rp. {{ number_format($total, 2) }}</span>
            </x-row-card>
            <x-row-card class="w-full flex flex-col">
                <span class="w-full items-center text-xl font-semibold">Outcome</span>
                <span>Rp. {{ number_format($outcome, 2) }}</span>
            </x-row-card>
            <x-row-card class="w-full flex flex-col">
                <span class="w-full items-center text-xl font-semibold">Profit</span>
                <span>Rp. {{ number_format($profit, 2) }}</span>
            </x-row-card>
        </div>

        <div class="mx-8 mb-4">
            <span class="text-2xl font-semibold">Transaction Log</span>
        </div>
        @forelse ($transactions as $transaction)
            <x-row-card>
                <div class="w-full flex flex-row items-center gap-4">
                    <div class="bg-indigo-100 p-2 rounded-full shadow-md">
                        <x-lucide-file-text class="h-8 w-auto" />
                    </div>

                    <div class="w-full">
                        <p class="font-semibold text-lg text-gray-900">{{ $transaction->id }}</p>
                        <div class="flex flex-col text-sm text-gray-600 mt-1">
                            <span><strong>Booking:</strong> {{ $transaction->bundle_name }}</span>
                            <span><strong>Client:</strong> {{ $transaction->user->name }}</span>
                            <span><strong>Payment Method:</strong> {{ $transaction->payment }}</span>
                            <span><strong>Price:</strong> Rp. {{ number_format($transaction->nominal, 2) }}</span>
                            <span><strong>Status:</strong> {{ $transaction->status }}</span>
                            <span><strong>Date:</strong> {{ $transaction->updated_at }}</span>
                        </div>
                    </div>
                </div>
            </x-row-card>
        @empty
            <p class="text-gray-500 py-4 text-center">There are no transaction.</p>
        @endforelse
    </x-table-container>
</x-app-layout>