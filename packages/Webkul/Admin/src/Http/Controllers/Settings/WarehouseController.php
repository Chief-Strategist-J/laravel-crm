<?php

namespace Webkul\Admin\Http\Controllers\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use Illuminate\View\View;
use Prettus\Repository\Criteria\RequestCriteria;
use Webkul\Admin\DataGrids\Settings\WarehouseDataGrid;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Attribute\Http\Requests\AttributeForm;
use Webkul\Warehouse\Repositories\WarehouseRepository;

class WarehouseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected WarehouseRepository $warehouseRepository)
    {
        request()->request->add(['entity_type' => 'warehouses']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(WarehouseDataGrid::class)->process();
        }

        return view('admin::settings.warehouses.index');
    }

    /**
     * Search location results
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        $results = $this->warehouseRepository
            ->pushCriteria(app(RequestCriteria::class))
            ->all();

        return response()->json($results);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function products($id)
    {
        if (request()->ajax()) {
            return app(\Webkul\Admin\DataGrids\Product\ProductDataGrid::class)->toJson();
        }

        $warehouse = $this->warehouseRepository->findOrFail($id);

        return view('admin::settings.warehouses.products', compact('warehouse'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin::settings.warehouses.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeForm $request)
    {
        Event::dispatch('settings.warehouse.create.before');

        $warehouse = $this->warehouseRepository->create($request->all());

        Event::dispatch('settings.warehouse.create.after', $warehouse);

        session()->flash('success', trans('admin::app.settings.warehouses.index.create-success'));

        return redirect()->route('admin.settings.warehouses.index');
    }

    /**
     * Show the form for viewing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $warehouse = $this->warehouseRepository->findOrFail($id);

        return view('admin::settings.warehouses.view', compact('warehouse'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $warehouse = $this->warehouseRepository->findOrFail($id);

        return view('admin::settings.warehouses.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeForm $request, int $id)
    {
        Event::dispatch('settings.warehouse.update.before', $id);

        $warehouse = $this->warehouseRepository->update($request->all(), $id);

        Event::dispatch('settings.warehouse.update.after', $warehouse);

        session()->flash('success', trans('admin::app.settings.warehouses.index.update-success'));

        return redirect()->route('admin.settings.warehouses.index');
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->warehouseRepository->findOrFail($id);

        try {
            Event::dispatch('settings.warehouse.delete.before', $id);

            $this->warehouseRepository->delete($id);

            Event::dispatch('settings.warehouse.delete.after', $id);

            return response()->json([
                'message' => trans('admin::app.settings.warehouses.index.destroy-success'),
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => trans('admin::app.settings.warehouses.index.destroy-success'),
            ], 400);
        }
    }
}
