<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\ListRequest;
use App\Http\Requests\Tags\CreateRequest;
use App\Models\Tag;
use Illuminate\Support\Facades\Log;

class TagController extends Controller
{
    /**
     * @api {get} /hashtags
     * @apiVersion 1.0.0
     * @apiGroup Hashtags
     * @apiName /hashtags/getList
     * @apiDescription Obtiene el listado de los hashtags disponibles.
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
        return response()->json(['state' => 'success', 'data' => Tag::all()], 200);
    }

    /**
     * @api {post} /hashtags/store
     * @apiVersion 1.0.0
     * @apiGroup Hashtags
     * @apiName /hashtags/store
     * @apiDescription Permite registrar un nuevo hashtag.
     * @apiParam {String} name Nombre del nuevo #hashtag <code>Example</code>.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "state": "success",
     *       "msg": "hashtag.created"
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
            Tag::create($data);
            DB::commit(); 
            return response()->json(['state' => 'success', 'msg' => 'hashtag.created'], 200);
        }
        catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(['state' => 'fail', 'error' => 'No se pudo agregar el hashtag, por favor contacte con el administrador'], 401);
        }

    }

}
