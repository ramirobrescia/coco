<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Http\Requests\StoreProviderRequest;
use App\Http\Requests\UpdateProviderRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ProviderController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return Inertia::render('Providers/Index', [
            'providers' => Inertia::lazy(function () {
                $perPage = request()->integer('perPage', 10);
                $sortBy = request()->input('sortBy', ['key' => 'name', 'order' => 'asc']);

                return Provider::orderBy($sortBy['key'], $sortBy['order'])
                    ->paginate($perPage)
                    ->withQueryString();
            })
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return Inertia::render('Providers/Create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProviderRequest $request)
    {
        $provider = $request->validated();
     
        Provider::create($provider);

        return to_route('proveedores.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        return Inertia::render('Providers/Edit', ['provider' => $provider]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProviderRequest $request, Provider $provider)
    {
        $newData = $request->validated();

        $exist = Provider::where('email', $newData['email'])
                ->where('id', '!=', $provider->id)
                ->count() > 0;
        
        if ($exist){
            $errors = ['email' => 'Ya existe un proveedor con este email'];
            return Inertia::render('Providers/Edit', ['provider' => $provider, 'errors' => $errors]);
        }

        $provider->update($newData);

        return to_route('providers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        //
    }
}
