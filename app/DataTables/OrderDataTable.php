<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', function($query){
                $showBtn = "<a href='".route('admin.order.show', $query->id)."' class='btn btn-primary'><i class='far fa-eye'></i></a>";
                $deleteBtn = "<a href='".route('admin.order.destroy', $query->id)."' class='btn btn-danger ml-2 mr-2 delete-item'><i class='far fa-trash-alt'></i></a>";

                return $showBtn.$deleteBtn;
            })
            ->addColumn('pembeli', function($query){
                return $query->user->name;
            })
            ->addColumn('jumlah_produk', function($query){
                return $query->product_qty;
            })
            ->addColumn('total', function($query){
                return 'Rp ' . number_format($query->amount, 0, ',', '.');
            })
            ->addColumn('date', function($query){
                return date('d-M-Y', strtotime($query->created_at));
            })
            ->addColumn('pembayaran', function($query){
                $status = $query->status ? $query->status : '-'; // Ambil dari kolom 'status' di tabel orders

                switch ($status) {
                    case 'pending':
                        return "<span class='badge bg-warning'>pending</span>";
                    case 'success':
                    case 'settlement':
                    case 'capture':
                        return "<span class='badge bg-success'>success</span>";
                    case 'expire':
                    case 'cancel':
                    case 'failure':
                        return "<span class='badge bg-danger'>gagal</span>";
                    default:
                        return $status;
                }
            })
            ->addColumn('pengiriman', function($query){
                switch ($query->order_status) {
                    case 'pending':
                        return "<span class='badge bg-warning'>pending</span>";
                    case 'processed_and_ready_to_ship':
                        return "<span class='badge bg-info'>processed</span>";
                    case 'dropped_off':
                        return "<span class='badge bg-info'>dropped off</span>";
                    case 'shipped':
                        return "<span class='badge bg-info'>shipped</span>";
                    case 'out_for_delivery':
                        return "<span class='badge bg-primary'>out for delivery</span>";
                    case 'delivered':
                        return "<span class='badge bg-success'>delivered</span>";
                    case 'canceled':
                        return "<span class='badge bg-danger'>canceled</span>";
                    default:
                        return $query->order_status;
                }
            })
            ->rawColumns(['action', 'pengiriman', 'pembayaran'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()
            ->leftJoin('transactions', 'orders.id', '=', 'transactions.order_id')
            ->select('orders.*', 'transactions.status as payment_status');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('order-table')
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
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [

            Column::make('id'),
            Column::make('date'),
            Column::make('invoice_id'),
            Column::make('pembeli'),
            Column::make('jumlah_produk'),
            Column::make('total')
            ->width(60),
            Column::make('pembayaran'),
            Column::make('pengiriman'),

            Column::computed('action')
            ->exportable(false)
            ->printable(false)
            ->width(100)
            ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Order_' . date('YmdHis');
    }
}
