@if ($paginator->hasPages())
    <nav class="pagination">
        <button class="btn btn-secondary" wire:click="previousPage" @if ($paginator->onFirstPage()) disabled @endif>Previous</button>
        <div>Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</div>
        <button class="btn btn-secondary" wire:click="nextPage" @if (!$paginator->hasMorePages()) disabled @endif>Next</button>
    </nav>
@endif
