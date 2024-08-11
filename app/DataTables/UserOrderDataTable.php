<?php

namespace App\DataTables;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserOrderDataTable extends DataTable
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
                $showBtn = "<a href='".route('user.orders.show', $query->id)."' class='btn btn-primary'><i class='far fa-eye'></i></a>";

                return $showBtn;
            })
            ->addColumn('customer', function($query){
                return $query->user->name;
            })
            ->addColumn('Produk Name', function($query){
                return $query->product_name;
            })
            ->addColumn('Harga', function($query) {
                return 'Rp ' . number_format($query->amount, 0, ',', '.');
            })
            ->addColumn('date', function($query){
                return date('d-M-Y', strtotime($query->created_at));
            })
            ->addColumn('Status Pembayaran', function($query) {
                switch ($query->status ? $query->status : '-') {
                    case 'pending':
                        return "<span class='badge bg-warning'>pending</span>";
                    case 'success':
                    case 'settlement':
                    case 'capture':
                        return "<span class='badge bg-success'>success</span>";
                    default:
                        return $query->status;
                }
            })
            ->addColumn('order_status', function($query){
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
            ->rawColumns(['Status Pembayaran', 'order_status', 'action', 'payment_status'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model::where('user_id', Auth::user()->id)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('vendororder-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('Bfrtip')
                    ->responsive(true)
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
            // Column::make('id'),
            // Column::make('customer'),
            Column::make('date')->width(250),
            Column::make('product_qty'),
            Column::make('product_name'),
            Column::make('Harga'),
            Column::make('order_status'),
            Column::make('Status Pembayaran'),
            // Column::make('payment_method'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'VendorOrder_' . date('YmdHis');
    }
}
