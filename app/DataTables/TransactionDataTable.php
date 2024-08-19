<?php

namespace App\DataTables;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'transaction.action')
            ->addColumn('invoice_id', function($query) {
                return $query->order ? '#' . $query->order->invoice_id : '-';
            })
            ->addColumn('customer_name', function($query) {
                return $query->order && $query->order->user ? $query->order->user->name : '-';
            })
            ->addColumn('order_id', function($query){
                return $query->order_id ? $query->order_id : '-';
            })
            ->addColumn('Total Harga', function($query){
                $amount = number_format($query->amount, 0, ',', '.');
                return 'Rp. ' . $amount;
            })
            ->addColumn('Status Pembayaran', function($query){
                return $query->status ? $query->status : '-';
            })
            ->filterColumn('invoice_id', function($query, $keyword){
                $query->whereHas('order', function($query) use ($keyword){
                    $query->where('invoice_id', 'like', "%$keyword%");
                });
            })
            ->setRowId('id');
    }

    public function query(Transaction $model): QueryBuilder
    {
        return $model->newQuery()->with('order');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('transaction-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ])
                    ->parameters([
                        'responsive' => true,
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('invoice_id'),
            Column::make('order_id'),
            Column::make('customer_name'),
            Column::make('Total Harga'),
            Column::make('Status Pembayaran'),
        ];
    }

    protected function filename(): string
    {
        return 'Transaction_' . date('YmdHis');
    }
}
