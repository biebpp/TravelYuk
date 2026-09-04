<div {{ $attributes->merge(['type' => 'button', 'class' => 'mb-2']) }}>
    <div {{ $attributes->merge(['type' => 'button', 'class' => 'max-w-7xl mx-auto sm:px-6 lg:px-8']) }}>
        <div {{ $attributes->merge(['type' => 'button', 'class' => 'p-4 bg-white overflow-hidden shadow-xl sm:rounded-lg']) }}>
            {{ $slot }}
        </div>
    </div>
</div>