<?php

namespace App\Http\Controllers\Api;

use DB;
use Exception;
use App\Http\Controllers\Controller;
use App\Http\Requests\WidgetCommerce\CreateAlmacenRequest;
use App\Http\Requests\WidgetCommerce\CreateAlmacenSinPrecioRequest;
use App\Http\Requests\WidgetCommerce\CreateHostelRequest;
use App\Http\Requests\WidgetCommerce\CreateRestaurantRequest;
use App\Http\Requests\WidgetCommerce\CreateServiciosRequest;
use App\Http\Requests\WidgetCommerce\CreateTiendaRequest;
use App\Http\Requests\WidgetCommerce\ListRequest;
use App\Models\CommerceWidget;
use App\Models\Widget;
use App\Models\WidgetM;
use Illuminate\Support\Facades\Log;

class CommerceWidgetController extends Controller
{
 
    /**
     * @api {get} /commercewidgets?commerce_id=1
     * @apiVersion 1.0.0
     * @apiGroup CommerceWidgets
     * @apiName /commercewidgets/getList
     * @apiDescription Obtiene el listado de todos widgets de un comercio.
     *
     * @apiParam {Number} commerce_id <code>Id</code> del comercio.
     * 
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     "data": [
     *          {
     *              "id": 1
     *          },
     *          {
     *              "id": 2
     *          }
     *      ]
     */
    public function getList(ListRequest $request)
    {
        $data = $request->all();
        $commerces = CommerceWidget::select('widget_id as id', 'json_process as json')->whereCommerceId($data['commerce_id'])
        ->get();
        return response()->json(['state' => 'success', 'data' => $commerces], 200);
    }

    /**
     * @api {post} /commercewidgets/store/tienda Tienda
     * @apiVersion 1.0.0
     * @apiGroup CommerceWidgets
     * @apiName /commercewidgets/store/tienda
     * @apiDescription Permite asignar un widget de tipo <code>Tienda</code> a un <code>Comercio</code>.
     * 
     * @apiParam {Number} commerce_id <code>Id</code> del comercio.
     * @apiParam {JSON} json Formato json del widget, descrito a continuacion.
     * @apiParam {String} [descatado] Atributo del <code>widget</code>.
     * @apiParam {String} [categoria] Atributo del <code>widget</code>.
     * @apiParam {String} [unidad] Atributo del <code>widget</code>.
     * @apiParam {String} [moneda] Atributo del <code>widget</code>.
     * @apiParam {String} productos Atributo del <code>widget</code> tipo <code>Tienda</code>.
     * @apiParam {String} imagenInterno Atributo del producto de la <code>Tienda</code>  asociada al <code>widget</code>.
     * @apiParam {String} tituloInterno Atributo del producto de la <code>Tienda</code>  asociada al <code>widget</code>.
     * @apiParam {String} [categoriaInterno] Atributo del producto de la <code>Tienda</code> asociada al <code>widget</code>.
     * @apiParam {String} [unidadInterno] Atributo del producto de la <code>Tienda</code> asociada al <code>widget</code>.
     * @apiParam {String} [precioInterno] Atributo del producto de la <code>Tienda</code> asociada al <code>widget</code>.
     * 
     * @apiHeaderExample {json} Header-Example:
     *  {
	 *  "commerce_id": 1,
	 *  "json": [{
	 *		"destacado": "si",
	 *		"categoria": "carnes",
	 *		"unidad": "KG",
	 *		"moneda": "USD",
	 *		"productos": [
	 *			{"categoria": "test",  "imagen": "001.png", "unidad": "KG", "precio": 40, "descripcion": "descripcion", "titulo": "mi producto"},
	 *			{"categoria": "test2",  "imagen": "0012.png", "unidad": "PZ", "precio": 400, "descripcion": "descripcion 2", "titulo": "mi producto 2"}
	 *			]
	 *		}]
     * }
     *
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *      "state": "success",
     *      "msg": "OK"
     *   }
     *
     * @apiError commerce_id No existe.
     * @apiError commerceWidget.unique El <code>Comercio</code> ya tiene un <code>Widget</code> de este tipo. 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "state": "fail",
     *       "error": "commerce.invalid"
     *     }
     */
    public function storeTienda(CreateTiendaRequest $request)
    {
        return $this->store(WidgetM::whereNombre(WidgetM::TIPO_TIENDA)->first(), $request->all());
    }

    /**
     * @api {post} /commercewidgets/store/restaurant Restaurant
     * @apiVersion 1.0.0
     * @apiGroup CommerceWidgets
     * @apiName /commercewidgets/store/restaurant
     * @apiDescription Permite asignar un widget de tipo <code>Restaurant</code> a un <code>Comercio</code>.
     * 
     * @apiParam {Number} commerce_id <code>Id</code> del comercio.
     * @apiParam {JSON} json Formato json del widget, descrito a continuacion.
     * @apiParam {String} [descatado] Atributo del <code>widget</code>.
     * @apiParam {String} [categoria] Atributo del <code>widget</code>.
     * @apiParam {String} [unidad] Atributo del <code>widget</code>.
     * @apiParam {String} [moneda] Atributo del <code>widget</code>.
     * @apiParam {String} productos Atributo del <code>widget</code> tipo <code>Restaurant</code>.
     * @apiParam {String} tituloInterno Atributo del producto del <code>Restaurant</code> asociada al <code>widget</code>.
     * @apiParam {String} [categoriaInterno] Atributo del producto del <code>Restaurant</code> asociada al <code>widget</code>.
     * @apiParam {String} [unidadInterno] Atributo del producto del <code>Restaurant</code> asociada al <code>widget</code>.
     * @apiParam {String} [precioInterno] Atributo del producto del <code>Restaurant</code> asociada al <code>widget</code>.
     * 
     * @apiHeaderExample {json} Header-Example:
     *  {
	 *  "commerce_id": 1,
	 *  "json": [{
	 *		"destacado": "si",
	 *		"categoria": "carnes",
	 *		"unidad": "KG",
	 *		"moneda": "USD",
	 *		"productos": [
	 *			{"categoria": "test",  "unidad": "KG", "precio": 40, "descripcion": "descripcion", "titulo": "mi producto"},
	 *			{"categoria": "test2", "unidad": "PZ", "precio": 400, "descripcion": "descripcion 2", "titulo": "mi producto 2"}
	 *			]
	 *		}]
     * }
     *
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *      "state": "success",
     *      "msg": "OK"
     *   }
     *
     * @apiError commerce_id No existe.
     * @apiError commerceWidget.unique El <code>Comercio</code> ya tiene un <code>Widget</code> de este tipo. 
     * 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "state": "fail",
     *       "error": "commerce.invalid"
     *     }
     */
    public function storeRestaurant(CreateRestaurantRequest $request)
    {
        return $this->store(WidgetM::whereNombre(WidgetM::TIPO_RESTAURANT)->first(), $request->all());
    }

    /**
     * @api {post} /commercewidgets/store/almacen Almacen
     * @apiVersion 1.0.0
     * @apiGroup CommerceWidgets
     * @apiName /commercewidgets/store/almacen
     * @apiDescription Permite asignar un widget de tipo <code>Almacen</code> a un <code>Comercio</code>.
     * 
     * @apiParam {Number} commerce_id <code>Id</code> del comercio.
     * @apiParam {JSON} json Formato json del widget, descrito a continuacion.
     * @apiParam {String} [categoria] Atributo del <code>widget</code>.
     * @apiParam {String} productos Atributo del <code>widget</code> tipo <code>Almacen</code>.
     * @apiParam {String} NombreInterno Atributo del producto del <code>Almacen</code> asociada al <code>widget</code>.
     * @apiParam {String} PrecioInterno Atributo del producto del <code>Almacen</code> asociada al <code>widget</code>.
     * @apiParam {String} DescripcionInterno Atributo del producto del <code>Almacen</code> asociada al <code>widget</code>.
     * @apiParam {String} [categoriaInterno] Atributo del producto del <code>Almacen</code> asociada al <code>widget</code>. 
     * 
     * @apiHeaderExample {json} Header-Example:
     *  {
	 *  "commerce_id": 1,
     *	"categoria": "carnes",
	 *  "json": [{
	 *		"productos": [
	 *			{"categoria": "test",  "nombre": "prueba", "precio": 40, "descripcion": "descripcion"},
	 *			{"categoria": "test2", "nombre": "prueba2", "precio": 400, "descripcion": "descripcion 2"}
	 *			]
	 *		}]
     * }
     *
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *      "state": "success",
     *      "msg": "OK"
     *   }
     *
     * @apiError commerce_id No existe.
     * @apiError commerceWidget.unique El <code>Comercio</code> ya tiene un <code>Widget</code> de este tipo. 
     * 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "state": "fail",
     *       "error": "commerce.invalid"
     *     }
     */
    public function storeAlmacen(CreateAlmacenRequest $request)
    {
        return $this->store(WidgetM::whereNombre(WidgetM::TIPO_ALMACEN)->first(), $request->all());
    }

    /**
     * @api {post} /commercewidgets/store/almacen-sin-precio Almacen Sin Precio
     * @apiVersion 1.0.0
     * @apiGroup CommerceWidgets
     * @apiName /commercewidgets/store/almacen-sin-precio
     * @apiDescription Permite asignar un widget de tipo <code>Almacen Sin Precio</code> a un <code>Comercio</code>.
     * 
     * @apiParam {Number} commerce_id <code>Id</code> del comercio.
     * @apiParam {JSON} json Formato json del widget, descrito a continuacion.
     * @apiParam {String} [categoria] Atributo del <code>widget</code>.
     * @apiParam {String} productos Atributo del <code>widget</code> tipo <code>Almacen</code>.
     * @apiParam {String} NombreInterno Atributo del producto del <code>Almacen sin precio</code> asociada al <code>widget</code>.
     * @apiParam {String} DescripcionInterno Atributo del producto del <code>Almacen sin precio</code> asociada al <code>widget</code>.
     * @apiParam {String} [categoriaInterno] Atributo del producto del <code>Almacen sin precio</code> asociada al <code>widget</code>. 
     *
     * @apiHeaderExample {json} Header-Example:
     *  {
	 *  "commerce_id": 1,
	 *  "json": [{
     *	    "categoria": "carnes",
	 *		"productos": [
	 *			{"categoria": "test",  "nombre": "prueba", "descripcion": "descripcion"},
	 *			{"categoria": "test2", "nombre": "prueba2", "descripcion": "descripcion 2"}
	 *			]
	 *		}]
     * }
     *
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *      "state": "success",
     *      "msg": "OK"
     *   }
     *
     * @apiError commerce_id No existe.
     * @apiError commerceWidget.unique El <code>Comercio</code> ya tiene un <code>Widget</code> de este tipo. 
     * 
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "state": "fail",
     *       "error": "commerce.invalid"
     *     }
     */
    public function storeAlmacenSinPrecio(CreateAlmacenSinPrecioRequest $request)
    {
        return $this->store(WidgetM::whereNombre(WidgetM::TIPO_ALMACEN_SIN_PRECIO)->first(), $request->all());
    }

    /**
     * @api {post} /commercewidgets/store/hostel Hostel
     * @apiVersion 1.0.0
     * @apiGroup CommerceWidgets
     * @apiName /commercewidgets/store/hostel
     * @apiDescription Permite asignar un widget de tipo <code>Hostel</code> a un <code>comercio</code>.
     * 
     * @apiParam {Number} commerce_id <code>Id</code> del comercio.
     * @apiParam {JSON} json Formato json del widget, descrito a continuacion.
     * @apiParam {String} tipo_habitacionInterno Atributo del <code>widget</code>.
     * 
     * @apiHeaderExample {json} Header-Example:
     *  {
	 *  "commerce_id": 1,
	 *  "json": [{
	 *		"tipo_habitacion":"tipo "
	 *		}]
     * }
     *
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *      "state": "success",
     *      "msg": "OK"
     *   }
     *
     * @apiError commerce_id No existe.
     * @apiError commerceWidget.unique El <code>Comercio</code> ya tiene un <code>Widget</code> de este tipo. 
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "state": "fail",
     *       "error": "hostel.invalid"
     *     }
     */
    public function storeHostel(CreateHostelRequest $request)
    {
        return $this->store(WidgetM::whereNombre(WidgetM::TIPO_HOSTEL)->first(), $request->all());
    }

    /**
     * @api {post} /commercewidgets/store/servicios Servicios
     * @apiVersion 1.0.0
     * @apiGroup CommerceWidgets
     * @apiName /commercewidgets/store/servicios
     * @apiDescription Permite asignar un widget de tipo <code>Servicios</code> a un <code>comercio</code>.
     * 
     * @apiParam {Number} commerce_id <code>Id</code> del comercio.
     * @apiParam {JSON} json Formato json del widget, descrito a continuacion.
     * @apiParam {String} tipo_servicioInterno Atributo del <code>widget</code>.
     * 
     * @apiHeaderExample {json} Header-Example:
     *  {
	 *  "commerce_id": 1,
	 *  "json": [{
	 *		"tipo_servicio":"tipo "
	 *		}]
     * }
     *
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *      "state": "success",
     *      "msg": "OK"
     *   }
     *
     * @apiError commerce_id No existe.
     * @apiError commerceWidget.unique El <code>Comercio</code> ya tiene un <code>Widget</code> de este tipo. 
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *       "state": "fail",
     *       "error": "tipo_servicio.invalid"
     *     }
     */
    public function storeServicios(CreateServiciosRequest $request)
    {
        return $this->store(WidgetM::whereNombre(WidgetM::TIPO_SERVICIO)->first(), $request->all());
    }

    private function store($widget, $data) 
    {
        DB::beginTransaction();
        try {

            if(CommerceWidget::whereCommerceId($data['commerce_id'])->whereWidgetId($widget->id)->count() > 0) {
                return response()->json(['state' => 'fail', 'msg' => 'commerceWidget.unique'], 401);
            }

            CommerceWidget::create(['commerce_id' => $data['commerce_id'], 'widget_id' => $widget->id, 'json' => json_encode($data['json']) ]);
            DB::commit();
            return response()->json(['state' => 'success', 'msg' => 'widget.created'], 200);
        }
        catch(Exception $e) {
            Log::error('CommerceWidgetController@store' . $widget['nombre'] . ': ' . $e->getMessage());
            DB::rollback();
            return response()->json(['state' => 'fail', 'error' => 'No se pudo agregar el widget al comercio, por favor contacte con el administrador'], 401);
        }
    }

}
