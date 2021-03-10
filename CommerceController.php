<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commerce\ListRequest;
use App\Http\Requests\Commerce\CreateRequest;
use App\Models\Commerce;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Str;

class CommerceController extends Controller
{
    /**
     * @api {get} /commerces
     * @apiVersion 1.0.0
     * @apiGroup Commerces
     * @apiName /commerces/getList
     * @apiDescription Obtiene el listado de los comercios disponibles.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [{
     *       "id": 1,
     *       "name": "Jhon Doe",
     *       "url": "a1b2c",
     *       "country": "Brazil",
     *       "phone": "+5812345678901",
     *       "description" : "description",
     *       "form_type": "contenido definido al momento de la creaciòn"",
     *       "logo_100": "image.jpg",
     *       "logo_800": "image2.jpg",
     *       "email": "jhondoe@example.com",
     *       "city": "Sao Paulo",
     *       "direction":  null,
     *       "postal_code": "1234",
     *       "hashtag": "#tag#tag2",
     *       "video": "xgaucT_xl5",
     *       "schedule": "horarios definidos al momento de la creaciòn"
     *     }]
     */
    public function getList(ListRequest $request)
    {
        return response()->json(['state' => 'success', 'data' => Commerce::all()], 200);
    }

    /**
     * @api {post} /commerces/store
     * @apiVersion 1.0.0
     * @apiGroup Commerces
     * @apiName /commerces/store
     * @apiDescription Permite registrar un nuevo comercio.
     *
     * @apiParam {String} name Nombre del Comercio.
     * @apiParam {String} url  Url del Comercio. Minimo 5 caracateres, al menos 1 letra, Ejemplo <code>a1b2c</code>
     * @apiParam {String} country  Pais del Comercio.
     * @apiParam {String} phone  Telefono del Comercio.
     * @apiParam {String} description  Descripcion del Comercio.
     * @apiParam {String} form_type  Tipo del formulario.
     * @apiParam {String} logo_100  Imagen 100 * 100 en formato PNG.
     * @apiParam {String} logo_800  Imagen 800 * 800 en formato PNG.
     * @apiParam {Email} [email]   Correo del responsable del comercio.
     * @apiParam {String} [city]   Ciudad del comercio.
     * @apiParam {String} [direction]   Direcciòn fisica del comercio.
     * @apiParam {String} [postal_code]   Codigo postal del comercio.
     * @apiParam {String} [hashtag]   tags en formato <code>#tag#tag2</code>; solo pueden usarse si existe previamente.
     * @apiParam {String} [video]  Codigo url de youtube / Facebook del video. Ejemplo <code>xgaucT_xl5</code>
     * @apiParam {String} [schedule]  Horarios del comercio.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "state": "success",
     *       "msg": "commerce.created"
     *     }
     *
     * @apiError unique El <code>atributo</code> debe ser unico.
     * @apiError url ya ha sido registrado.
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

            $name = STR::random(1) . date('Y') . STR::random(2) . date('mdH') . STR::random(3). date('is');
            $fileName = $name . '.jpg';
            Storage::disk('local')->putFileAs('public/images/', $request->file('logo_100'), $fileName);
            $data['logo_100'] = $fileName;

            $name2 = STR::random(1) . date('Y') . STR::random(2) . date('mdH') . STR::random(3). date('is');
            $fileName2 = $name2 . '.jpg';
            Storage::disk('local')->putFileAs('public/images/', $request->file('logo_800'), $fileName2);
            $data['logo_800'] = $fileName2;

            Commerce::create($data);

            DB::commit();
            return response()->json(['state' => 'success', 'msg' => 'commerce.created'], 200);
        }
        catch(\Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return response()->json(['state' => 'fail', 'error' => 'No se pudo crear el comercio, por favor contacte con el administrador'], 401);
        }

    }

}
