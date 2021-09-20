<?php
namespace App\DataTables;
use Yajra\DataTables\Services\DataTable;
use DB;
use Auth;
use Helpers;
use Session;
use App\Models\Supplier;

class SupplierListDataTable extends DataTable
{
    public function ajax()
    {
        $suppliers = $this->query();
        return datatables()
            ->of($suppliers)
            ->addColumn('supp_name', function ($suppliers) {
                $name = "<a href='".url("edit-supplier/$suppliers->id")."'>$suppliers->name</a>";
                return $name;
            })
            ->addColumn('inactive', function ($suppliers) {
                if($suppliers->is_active == 1){
                    $status = '<div class="switch d-inline m-r-10">
                            <input class="status" type="checkbox" data-supplier_id="'.$suppliers->id.'"  id="switch-'.$suppliers->id.'" checked="">
                            <label for="switch-'.$suppliers->id.'" class="cr"></label>
                        </div>';
                }else{
                    $status = '<div class="switch d-inline m-r-10">
                            <input class="status" type="checkbox" data-supplier_id="'.$suppliers->id.'"  id="switch-'.$suppliers->id.'">
                            <label for="switch-'.$suppliers->id.'" class="cr"></label>
                        </div>';
                }
                return $status;
            })
             ->addColumn('address', function ($suppliers) {
                $address= '<p>'.(isset($suppliers->street)? $suppliers->street:'').''.(isset($suppliers->city)?', '.$suppliers->city:'').(isset($suppliers->state) ? ', '.$suppliers->state:'').''.(isset($suppliers->zipcode)?', '.$suppliers->zipcode:'').''.(isset($suppliers->countryName) ?', '.$suppliers->countryName:'').'</p>';
                return $address;

            })

            ->addColumn('created_at', function ($suppliers) {

                $startDate = timeZoneformatDate($suppliers->created_at);
                $startTime = timeZonegetTime($suppliers->created_at);
                return $startDate.'<br>'.$startTime;
            })


            ->rawColumns(['supp_name','inactive', 'address', 'created_at'])
            ->make(true);
    }
 
    public function query()
    {
        $supplier = isset($_GET['supplier']) ? $_GET['supplier'] : null;
        $suppliers = Supplier::select();
        if (!empty($supplier) && $supplier == "inactive") {
            $suppliers->where('is_active', 0);
        } else if (!empty($supplier) && $supplier == "total") {
            $suppliers;
        } else {
            $suppliers->where('is_active', 1);
        }
       
        return $this->applyScopes($suppliers);
    }
    
    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'id', "visible" => false])

            ->addColumn(['data' => 'supp_name', 'name' => 'name', 'title' => 'Name'])

            ->addColumn(['data' => 'email', 'name' => 'email', 'title' => __('Email')])
            
            ->addColumn(['data' => 'contact', 'name' => 'contact', 'title' => __('Phone')])

            ->addColumn(['data'=> 'address', 'name' => 'address', 'title' =>__('Address')])

            ->addColumn(['data' => 'inactive', 'name' => 'is_active', 'title' => __('Status'), 'orderable' => false])
           
            ->addColumn(['data' => 'created_at', 'name' => 'suppliers.created_at', 'title' => __('Created at')])
           
            
            ->parameters([
                'pageLength' => $this->row_per_page,
                'language' => [
                        'url' => url('/resources/lang/'.config('app.locale').'.json'),
                    ],
                'order' => [0, 'DESC']
            ]);
    }

    protected function getColumns()
    {
        return [
            'id',
            'created_at',
            'updated_at',
        ];
    }

    protected function filename()
    {
        return 'customers_' . time();
    }
}
