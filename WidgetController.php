<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Widget\ListRequest;
use App\Models\WidgetM;
use DB;

class WidgetController extends Controller
{
    
    /**
     * @api {get} /Widgets/List
     * @apiVersion 1.0.0
     * @apiGroup Widgets
     * @apiName /Widgets/list
     * @apiDescription Listar los Widget.
     * 
     * @apiHeaderExample {json} Header-Example:
     *     {
     *       "Content-Type": "application/json",
     *       "Authorization": "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9wbGFuZXRhLmxvY2FsXC9hcGlcL3YxXC91c2VyXC9sb2dpbiIsImlhdCI6MTU4NjY0MTg2MCwiZXhwIjoxNTg2NjQ1NDYwLCJuYmYiOjE1ODY2NDE4NjAsImp0aSI6IkJqWHlhdHVmZ3ZtRVdYaUciLCJzdWIiOjEwMiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.wOfqbp3tS_zcPB8YrOMNcFTFW5nnnyjYpI9Wnva3bTY"
     *     }
     *
     *
     * @apiSuccessExample Success-Response:
     *   HTTP/1.1 200 OK
     *   {
     *      "state": "success",
     *      "msg": "OK",
     *      "data": [
     *           {
     *               "id": 1,
     *               "name": "Tienda"
     *           },
     *      ]
     *   }
     *
     *
     */
    public function getList(ListRequest $request)
    {
        return response()->json(['state' => 'success', 'msg' => 'OK', 'data' => WidgetM::all()], 200);
    }

}
