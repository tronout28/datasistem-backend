<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paket;

class PaketController extends Controller
{
    public function index()
    {
        $pakets = Paket::all();

        return response()->json([
            'success' => 'success',
            'data' => $pakets,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_paket' => 'required',
        ]);

        $paket = new Paket([
            'name_paket' => $request->name_paket,
        ]);

        $paket->save();

        return response()->json([
            'success' => 'success',
            'data' => $paket,
        ]);
    }


    public function show($id)
    {
        $paket = Paket::find($id);

        if (!$paket) {
            return response()->json([
                'success' => 'false',
                'message' => 'Paket not found',
            ], 404);
        }

        return response()->json([
            'success' => 'success',
            'data' => $paket,
        ]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name_paket' => 'required',
        ]);

        $paket = Paket::find($id);

        if (!$paket) {
            return response()->json([
                'success' => 'false',
                'message' => 'Paket not found',
            ], 404);
        }

        $paket->name_paket = $request->name_paket;
        $paket->save();

        return response()->json([
            'success' => 'success',
            'data' => $paket,
        ]);
    }

    public function destroy($id)
    {
        $paket = Paket::find($id);

        if (!$paket) {
            return response()->json([
                'success' => 'false',
                'message' => 'Paket not found',
            ], 404);
        }

        $paket->delete();

        return response()->json([
            'success' => 'success',
            'message' => 'Paket deleted',
        ]);
    }
}
