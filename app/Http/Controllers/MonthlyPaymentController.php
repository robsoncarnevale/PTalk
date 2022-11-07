<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyPayment;

class MonthlyPaymentController extends Controller
{
    public function index()
    {
        dd("Index MonthlyPaymentController");
    }

    public function create()
    {
        dd("create MonthlyPaymentController");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {       
            if ($request->input('id')) {
                $monthlyPayment = MonthlyPayment::find($request->input('id'));
                $update = $monthlyPayment->update($request->all());

                if ($update) {
                    $msg = "updated";
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Erro ao atualizar a tarifa'
                    ], 500);
                }
            } else {
                if (!$request->input('name')) {
                    abort(404,__('monthlypayment_registration.no_name'));
                } else if (!$request->input('value')) {
                    abort(404,__('monthlypayment_registration.no_value'));
                } else if (!$request->input('class_member')) {
                    abort(404,__('monthlypayment_registration.no_class'));
                } else if (!$request->input('recurrence')) {
                    abort(404,__('monthlypayment_registration.no_recurrence'));
                }
                MonthlyPayment::create($request->all());
                $msg = "created";
            }

            return response()->json([
                'status' => 'success',
                'message' => __('monthlypayment_registration.'.$msg)
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function get($id) {
        $monthlyPayment = MonthlyPayment::find($id);
        return $monthlyPayment;
    }

    public function show()
    {
        dd("show MonthlyPaymentController");
    }

    public function edit()
    {
        dd("edit MonthlyPaymentController");
    }

    public function update()
    {
        dd("update MonthlyPaymentController");
    }

    public function destroy($id)
    {
        try
        {       
            $monthlyPayment = new MonthlyPayment();
            $monthlyPayment = MonthlyPayment::find($id);
            $monthlyPayment->delete();

            return response()->json([
                'status' => 'success',
                'message' => __('monthlypayment_fare.deleted')
            ]);
        }
        catch(\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /*
    Função que busca todas as tarifas
    */
    public function fare()
    {
        $list = MonthlyPayment::all();
        return $list;
    }

    public function parameters()
    {
        dd("parameters MonthlyPaymentController");
    }

    public function pendencies()
    {
        dd("pendencies MonthlyPaymentController");
    }
}
