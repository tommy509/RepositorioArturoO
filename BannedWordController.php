<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BannedWords\ListRequest;
use App\Http\Requests\BannedWords\CreateRequest;
use App\Models\BannedWord;
use Illuminate\Support\Facades\Log;
use DB;

class BannedWordController extends Controller
{
    /**
     * @api {get} /bannedWords
     * @apiVersion 1.0.0
     * @apiGroup BannedsWords
     * @apiName /bannedWords/getList
     * @apiDescription Obtiene el listado de las palabras prohibidas disponibles.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [{
     *       "id": 1,
     *       "name": "Prueba"
     *     }]
     */
    public function getList(ListRequest $request)
    {
        return response()->json(['state' => 'success', 'data' => BannedWord::all()], 200);
    }

    /**
     * @api {post} /bannedWords/store
     * @apiVersion 1.0.0
     * @apiGroup BannedsWords
     * @apiName /bannedWords/store
     * @apiDescription Permite registrar una nueva palabra prohibida.
     *
     * @apiParam {String} name Nueva palabra prohibida <code>Example</code>.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "state": "success",
     *       "msg": "word.created"
     *     }
     *
     * @apiError unique El <code>atributo</code> debe ser unico.
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "state": "fail",
     *       "error": "nombre.unique"
     *     }
     *
     */
    public function store(CreateRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            BannedWord::create($data);
            DB::commit();        
            return response()->json(['state' => 'success', 'msg' => 'word.created'], 200);
        }
        catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(['state' => 'fail', 'error' => 'No se pudo agregar la palabra prohibida, por favor contacte con el administrador'], 401);
        }

    }

}
