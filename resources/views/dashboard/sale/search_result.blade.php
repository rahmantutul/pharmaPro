<div class="medicine-grid-container">
    @if($medicines->isNotEmpty())
        <div class="row">
            @foreach($medicines as $medicine)
                @php
                    $stock = $medicine->total_stock ?? 0;
                    $isOutOfStock = $stock == 0;
                    $isLowStock = $stock > 0 && $stock <= 20;
                    $imageUrl = $medicine->image ? asset('uploads/images/medicine/' . $medicine->image) : 'https://placehold.co/200x200/5D9CEC/white?text=' . urlencode($medicine->name);
                @endphp
                
                <div class="col-md-6 col-lg-4">
                    <div class="medicine-card {{ $isOutOfStock ? 'stock-out' : 'stock-available' }}"
                         onclick="{{ !$isOutOfStock ? 'addToCart(' . $medicine->id . ')' : '' }}">
                        <div class="medicine-card-body">
                            <div class="medicine-header">
                                <div class="medicine-image">
                                    <img src="{{ $imageUrl }}"
                                         alt="{{ $medicine->name }}"
                                         onerror="this.src='https://placehold.co/200x200/5D9CEC/white?text=Medicine'">
                                    @if($isOutOfStock)
                                        <div class="stock-badge out-of-stock">
                                            <i class="fa fa-ban"></i> Out
                                        </div>
                                    @elseif($isLowStock)
                                        <div class="stock-badge low-stock">
                                            <i class="fa fa-exclamation-triangle"></i> Low
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="medicine-info">
                                    <h6 class="medicine-name text-truncate" title="{{ $medicine->name }}">
                                        {{ $medicine->name }}
                                    </h6>
                                    <div class="medicine-details">
                                        <span class="badge">
                                            {{ $medicine->strength ?? 'N/A' }}
                                        </span>
                                        <small class="text-muted d-block mt-1">
                                            {{ Str::limit($medicine->generic_name ?? '', 50) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="medicine-footer">
                                <div class="supplier-info">
                                    <small class="text-muted">
                                        <i class="fa fa-building-o"></i>&nbsp;
                                        {{ Str::limit($medicine->supplier->name ?? 'N/A', 50) }}
                                    </small>
                                </div>
                                
                                <div class="price-stock-section">
                                    <div class="price-section">
                                        <span class="price">{{ Helper::getStoreInfo()->currency }}{{ number_format($medicine->sell_price ?? 0, 2) }}</span>
                                        @if($medicine->purchase_price)
                                            <small class="cost-price text-muted">
                                                Cost: {{ Helper::getStoreInfo()->currency }}{{ number_format($medicine->purchase_price, 2) }}
                                            </small>
                                        @endif
                                    </div>
                                    
                                    <div class="stock-section">
                                        @if($stock > 0)
                                            <div class="stock-count">
                                                <i class="fa fa-cubes"></i>&nbsp;
                                                <strong>{{ $stock }}</strong> in stock
                                            </div>
                                        @else
                                            <div class="text-danger">
                                                <i class="fa fa-times-circle"></i>&nbsp;
                                                Out of stock
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fa fa-medkit fa-3x text-muted"></i>
            </div>
            <h5 class="empty-state-title">No Medicines Found</h5>
            <p class="empty-state-text text-muted">
                @if(request('name') || request('supplier') || request('category'))
                    No medicines match your search criteria
                @else
                    No medicines available at the moment
                @endif
            </p>
            @if(request('name') || request('supplier') || request('category'))
                <button type="button" 
                        class="btn btn-primary btn-sm mt-2"
                        onclick="clearFilters()">
                    <i class="fa fa-refresh"></i>&nbsp;{{ __('Clear All Filters') }}
                </button>
            @endif
        </div>
    @endif
</div>

@if($medicines->hasPages())
    <div style="margin-top: 20px;">
        <nav aria-label="Medicine pagination">
            {{ $medicines->links("pagination::bootstrap-4") }}
        </nav>
    </div>
@endif

<style>
.medicine-grid-container {
    padding: 0.5rem;
}

.medicine-grid-container .row {
    display: flex !important;
    flex-wrap: wrap !important;
    align-items: stretch !important;
}

.medicine-grid-container .row:before, 
.medicine-grid-container .row:after {
    display: none !important; /* Hide BS3 float clearers */
}

.medicine-grid-container .row > [class*="col-"] {
    display: flex !important;
    flex-direction: column !important;
    float: none !important; /* Disable BS3 float */
}

.medicine-card {
    display: flex;
    flex-direction: column;
    flex: 1 0 auto;
    background: linear-gradient(135deg, #f0f7ff 0%, #e6f2ff 100%);
    border: 1px solid #cfe2ff;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    height: auto;
    position: relative;
    overflow: hidden;
    margin-bottom: 15px;
}

.medicine-card:hover:not(.stock-out) {
    border-color: #0d6efd;
    box-shadow: 0 5px 15px rgba(13, 110, 253, 0.1);
    transform: translateY(-2px);
}

.medicine-card.stock-out {
    cursor: not-allowed;
    opacity: 0.7;
    background: linear-gradient(45deg, #f8f9fa 25%, #ffffff 25%, #ffffff 50%, #f8f9fa 50%, #f8f9fa 75%, #ffffff 75%, #ffffff);
    background-size: 10px 10px;
}

.medicine-card.stock-out::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(248, 249, 250, 0.7);
}

.medicine-card-body {
    padding: 1rem;
    position: relative;
    z-index: 1;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.medicine-header {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
}

.medicine-image {
    position: relative;
    flex-shrink: 0;
}

.medicine-image img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.stock-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 0.7rem;
    font-weight: 600;
    color: white;
}

.stock-badge.out-of-stock {
    background: #dc3545;
}

.stock-badge.low-stock {
    background: #ffc107;
    color: #000;
}

.medicine-info {
    flex: 1;
    min-width: 0;
}

.medicine-name {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.medicine-details .badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25em 0.6em;
}

.medicine-footer {
    border-top: 1px solid #e9ecef;
    padding-top: 0.75rem;
    margin-top: auto;
}

.supplier-info {
    margin-bottom: 0.5rem;
}

.price-stock-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
}

.price-section .price {
    font-weight: 700;
    font-size: 1.1rem;
    color: #0d6efd;
    display: block;
}

.price-section .cost-price {
    font-size: 0.75rem;
}

.stock-section {
    text-align: right;
}

.stock-count {
    font-size: 0.85rem;
    color: #28a745;
}

.stock-count strong {
    font-weight: 600;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state-icon {
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state-title {
    margin-bottom: 0.5rem;
    color: #6c757d;
}

.empty-state-text {
    max-width: 300px;
    margin: 0 auto;
}

/* Pagination styling */
.pagination {
    justify-content: center;
}

.page-link {
    border: none;
    color: #495057;
    margin: 0 2px;
    border-radius: 6px !important;
}

.page-link:hover {
    background-color: #e9ecef;
    color: #0d6efd;
}

.page-item.active .page-link {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

/* Responsive adjustments */
/* Utilities for BS3 compatibility */
.mt-2 { margin-top: 10px !important; }
.mt-4 { margin-top: 20px !important; }
.mb-1 { margin-bottom: 5px !important; }
.me-1 { margin-right: 5px !important; }
.py-5 { padding-top: 40px !important; padding-bottom: 40px !important; }
.text-center { text-align: center !important; }
.text-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

@media (max-width: 768px) {
    .medicine-card {
        margin-bottom: 0.5rem;
    }
    
    .medicine-header {
        gap: 0.75rem;
    }
    
    .medicine-image img {
        width: 50px;
        height: 50px;
    }
    
    .price-stock-section {
        flex-direction: column;
        align-items: stretch;
        gap: 0.5rem;
    }
    
    .stock-section {
        text-align: left;
    }
}

@media (max-width: 576px) {
    .col-md-6 {
        width: 100%;
    }
}
</style>

<script>
function clearFilters() {
    $('#name').val('');
    $('#supplier').val('').trigger('change');
    $('#category').val('').trigger('change');
    $('#name').trigger('keyup');
}
</script>