<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VendorProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
{
    return (new EloquentDataTable($query->with('category')))
        ->addColumn('action', function($query){
            $editBtn = "<a href='".route('vendor.produk.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
            $deleteBtn = "<a href='".route('vendor.produk.destroy', $query->id)."' class='btn btn-danger delete-item' ><i class='far fa-trash-alt'></i></a>";
            return $editBtn.$deleteBtn;
        })
        ->addColumn('image', function($query){
            return "<img width='70px' src='".asset($query->thumb_image)."' ></img>";
        })
        ->addColumn('Kategori', function($query){
            return $query->category ? $query->category->name : 'N/A';
        })
        ->addColumn('nama', function($query){ // Pastikan 'nama' ditambahkan jika perlu
            return $query->name;
        })
        ->addColumn('status', function($query){
            if($query->status == 1){
                $button = '<div class="form-check form-switch">
                <input checked class="form-check-input change-status" type="checkbox" id="flexSwitchCheckDefault" data-id="'.$query->id.'"></div>';
            }else {
                $button = '<div class="form-check form-switch">
                <input class="form-check-input change-status" type="checkbox" id="flexSwitchCheckDefault" data-id="'.$query->id.'"></div>';
            }
            return $button;
        })
        ->addColumn('approved', function($query){
            if($query->is_approved === 1){
                return '<i class="badge bg-success">Approved</i>';
            }else {
                return '<i class="badge bg-warning">Pending</i>';
            }
        })
        ->addColumn('price', function($query){
            return 'Rp ' . number_format($query->price, 0, ',', '.');
        })
        ->rawColumns(['image', 'type', 'status', 'action', 'approved'])
        ->setRowId('id');
}


    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->where('vendor_id', Auth::user()->vendor->id)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('vendorproduct-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
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
            Column::make('image')->width(150),
            Column::make('nama'),
            Column::make('Kategori'),
            Column::make('price')->width(150),
            Column::make('approved'),
            Column::make('status'),
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
        return 'VendorProduct_' . date('YmdHis');
    }
}
