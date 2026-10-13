@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Pagination">
        <p class="pagination-summary">
            Affichage de {{ $paginator->firstItem() }} à {{ $paginator->lastItem() }}
            sur {{ $paginator->total() }} {{ $label ?? 'clients' }}
        </p>

        <div class="pagination-controls">
            @if ($paginator->onFirstPage())
                <span class="page-link page-link-direction is-disabled" aria-disabled="true">← Précédent</span>
            @else
                <a class="page-link page-link-direction" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Page précédente">← Précédent</a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="page-link page-link-direction" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Page suivante">Suivant →</a>
            @else
                <span class="page-link page-link-direction is-disabled" aria-disabled="true">Suivant →</span>
            @endif
        </div>
    </nav>
@endif
