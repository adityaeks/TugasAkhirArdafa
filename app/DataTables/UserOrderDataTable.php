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
        $dataTable = new EloquentDataTable($query);
        $dataTable
            ->addColumn('action', function($query){
                $showBtn = "<a href='".route('user.orders.show', $query->id)."' class='btn btn-primary'><i class='far fa-eye'></i></a>";
                return $showBtn;
            })
            ->addColumn('customer', function($query){
                return $query->user->name;
            })
            ->addColumn('nama_produk', function($order){
                return $order->product_name;
            })
            ->addColumn('jumlah', function($order){
                return $order->product_qty;
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
            ->rawColumns(['pembayaran', 'pengiriman', 'action', 'payment_status'])
            ->setRowId('id');

        // Enable searching for custom columns
        $dataTable->filterColumn('nama_produk', function($query, $keyword) {
            $query->where('orders.product_name', 'like', "%{$keyword}%");
        });
        $dataTable->filterColumn('jumlah', function($query, $keyword) {
            $query->where('orders.product_qty', 'like', "%{$keyword}%");
        });
        $dataTable->filterColumn('Harga', function($query, $keyword) {
            $query->where('orders.amount', 'like', "%{$keyword}%");
        });
        $dataTable->filterColumn('date', function($query, $keyword) {
            $query->whereRaw('DATE_FORMAT(orders.created_at, "%d-%b-%Y") like ?', ["%{$keyword}%"]);
        });
        $dataTable->filterColumn('pembayaran', function($query, $keyword) {
            $query->where('transactions.status', 'like', "%{$keyword}%");
        });
        $dataTable->filterColumn('pengiriman', function($query, $keyword) {
            $query->where('orders.order_status', 'like', "%{$keyword}%");
        });

        return $dataTable;
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery()
            ->where('user_id', Auth::user()->id) // Filter berdasarkan user yang sedang login
            ->leftJoin('transactions', 'orders.id', '=', 'transactions.order_id')
            ->select('orders.*', 'transactions.status as payment_status'); // Memilih status pembayaran dari tabel transactions
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
            Column::make('date')->width(100),
            Column::make('nama_produk')->width(150),
            Column::make('jumlah'),
            Column::make('Harga'),
            Column::make('pengiriman'),
            Column::make('pembayaran'),
            // Column::make('payment_method'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
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
