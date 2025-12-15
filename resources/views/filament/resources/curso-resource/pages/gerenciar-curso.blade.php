<x-filament-panels::page>
    @php
        $viewData = $this->getViewData();
        $curso = $viewData['curso'] ?? null;
    @endphp
    @if($curso)
        @livewire('admin.gerenciar-curso', ['curso' => $curso->id])
    @else
        <div class="p-4 text-red-600">Curso não encontrado.</div>
    @endif
</x-filament-panels::page>
