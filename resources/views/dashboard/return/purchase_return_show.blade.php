@extends('dashboard.layouts.app')

@push('stylesheet')
<style>
    .return-header {
        background: #f0fdf9;
        color: #115e59;
        padding: 30px;
        border-radius: 10px 10px 0 0;
        border: 1px solid #ccfbf1;
        border-bottom: none;
        margin-bottom: 0;
    }
    .return-body {
        background: #fff;
        padding: 30px;
        border-radius: 0 0 10px 10px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .info-label {
        font-weight: 600;
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }
    .info-value {
        color: #1e293b;
        font-size: 1.125rem;
        margin-top: 6px;
    }
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #dcfce7;
    }
    .table-details {
        margin-top: 30px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
    }
    .table-details th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        padding: 14px 16px;
        font-size: 0.875rem;
    }
    .table-details td {
        padding: 18px 16px;
        border-top: 1px solid #e2e8f0;
        color: #334155;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @include('dashboard.layouts.toolbar')

    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('return.purchase.index') }}">Purchase Returns</a></li>
                <li class="active">Return Details</li>
            </ol>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-10 col-lg-offset-1 col-md-12">
            <div class="return-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h2 class="mt-0" style="color: white; font-weight: 700;">PURCHASE RETURN</h2>
                        <p class="mb-0" style="opacity: 0.9;">Return #: <strong>{{ $return->inv_no }}</strong></p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="status-badge">Completed</div>
                        <p class="mt-2" style="opacity: 0.9;">Date: <strong>{{ date('d M Y', strtotime($return->return_date)) }}</strong></p>
                    </div>
                </div>
            </div>

            <div class="return-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="info-label">Supplier Information</div>
                        <div class="info-value">
                            @if($return->supplier)
                                <strong>{{ $return->supplier->name }}</strong><br>
                                <span style="font-size: 0.9rem; color: #6b7280;">
                                    <i class="fa fa-phone"></i> {{ $return->supplier->phone }}<br>
                                    <i class="fa fa-envelope"></i> {{ $return->supplier->email }}<br>
                                    <i class="fa fa-map-marker"></i> {{ $return->supplier->address }}
                                </span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-8 text-right">
                        <div class="info-label">Return Context</div>
                        <div class="info-value" style="font-style: italic; color: #6b7280;">
                            Stock reduction return to supplier.
                        </div>
                    </div>
                </div>

                <div class="table-details">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Medicine Name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Cost Price</th>
                                <th class="text-right">Total Refund</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #111827;">{{ $return->medicine->name }}</div>
                                    <div style="font-size: 0.85rem; color: #6b7280;">{{ $return->medicine->generic_name }}</div>
                                    <div style="font-size: 0.8rem; color: #9ca3af;">Category: {{ $return->medicine->category->name ?? 'N/A' }}</div>
                                </td>
                                <td class="text-center" style="vertical-align: middle;">
                                    <span style="font-size: 1.1rem; font-weight: 600;">{{ $return->qty }}</span>
                                </td>
                                <td class="text-right" style="vertical-align: middle;">
                                    {{ Helper::getStoreInfo()->currency }}{{ number_format($return->price, 2) }}
                                </td>
                                <td class="text-right" style="vertical-align: middle;">
                                    <span style="font-size: 1.1rem; font-weight: 700; color: #111827;">
                                        {{ Helper::getStoreInfo()->currency }}{{ number_format($return->total, 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="background: #f9fafb;">
                                <td colspan="3" class="text-right" style="font-weight: 700; padding: 20px;">TOTAL DEBIT FROM SUPPLIER</td>
                                <td class="text-right" style="padding: 20px;">
                                    <span style="font-size: 1.5rem; font-weight: 800; color: #059669;">
                                        {{ Helper::getStoreInfo()->currency }}{{ number_format($return->total, 2) }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row mt-4 pt-4" style="border-top: 1px dotted #e5e7eb;">
                    <div class="col-sm-6 text-muted" style="font-size: 0.85rem;">
                        <p>Note: This is a system generated document. Stock has been reduced automatically upon processing this purchase return.</p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <button onclick="window.print()" class="btn btn-default mr-2">
                            <i class="fa fa-print"></i> Print Details
                        </button>
                        <a href="{{ route('return.purchase.index') }}" class="btn btn-success">
                            <i class="fa fa-chevron-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
