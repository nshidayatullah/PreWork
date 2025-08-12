<x-filament-panels::page>
    <div class="space-y-6">

        <!-- Stats Cards (jika ada) -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <!-- Stat Cards bisa ditambahkan di sini -->
        </div>

        <!-- Charts dengan Filters -->
        <div class="space-y-6">
            @foreach ($this->getWidgets() as $widget)
                @livewire($widget, ['filters' => $this->filters])
            @endforeach
        </div>

    </div>
</x-filament-panels::page>
