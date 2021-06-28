<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use Illuminate\Http\Request;
use Spatie\Fractalistic\Fractal;
use App\Transformer\CarsTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\ApiReturnedErrors;
/**
 *
 * @SWG\Swagger(
 *  basePath="/api",
 *  produces={"application/json"},
 *  consumes={"applicaiton/json"},
 *  addInfo = {
 *      "some-value":"otro más",
 *      "supported-by":"Elexon"
 *  },
 * @OA\Info(
 *   title="Cars Api Lumen-Valet Swagger Edition",
 *   version="1.0.0",
 *   @OA\Contact(
 *     email="luisfernando.parra@techxonn.com",name="Devel Team"
 *   ),
 *  @OA\License(name="MIT")
 * )
 * )
 *
 * @SWG\Tag(
 *      name="Cars",
 *      description="Api que gestiona Coches"
 * )
  * @SWG\Tag(
 *      name="Swagger",
 *      description="Swagger Api que gestiona Coches"
 * )
 *
 *@OA\Server(
 *  description="API Server",
 *  url="http://lumen-valet-swagger.test/"
 * )
 *
 */


 /**
  * Respuestas
  */
  /**
 * @OA\Schema(
 *     schema="PostResponse",
 *     type="object",
 *     title="PostResponse",
 *     properties={
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="attributes", type="object", properties={
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="body", type="string")
 *         }),
 *         @OA\Property(property="relationships", type="array", @OA\Items({
 *
 *         })),
 *     }
 * )
 *
 *  @OA\Schema(
 *      schema = "ResponseNotFound",
 *      type = "object",
 *      title = "ResponseNotFound",
 *      properties= {
 *          @OA\Property(
 *              property="status",type="string",description="Estado devuelto",example="ERROR"
 *          ),
 *          @OA\Property(
 *              property="error",type="string",description="Descripción Error",example="404 Not Found"
 *          )
 *      }
 * )
 */


class CarsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

 /**
 * @OA\Get(
 *     path="/api/cars",
 *      tags={"Cars"},
 *     description="Api que devuelve todos los coches existentes en la BBDD",
 *     @OA\Response(
 *          response="200",
 *          description="Devuelve un Json, donde data es el nivel principal, que incluye un Array con todos los Cars",
 *          @OA\JsonContent(ref="#/components/schemas/CarResponseList")
 *      )
 * )
 */
    public function getAll() {

        $cars = Fractal::create()->collection(Cars::all())->transformWith(CarsTransformer::class)->toArray();
        return response()->json($cars);
    }

/**
 * @OA\Get(
 *     path="/api/cars/{car_id}",
 *
 *     description="Api que devuelve El coche existente según parámetro de entrada",
 *      tags={"Cars","Swagger"},
 * @OA\Parameter(
 *      name="car_id",
 *      in="path",
 *      required=true,
 *      example=23,
 *      @OA\Schema(
 *          type="integer"
 *      )
 *
 * ),
 *     @OA\Response(
 *          response="200",
 *          description="Devuelve un Json, donde data es el nivel principal, que incluye el objeto Car",
 *          @OA\JsonContent(ref="#/components/schemas/CarResponseItemFull")
 *      ),
 *      @OA\Response(
 *          response="404",
 *          description="Devuelve un Json, de Error: Elemento no encontrado",
 *          @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
 *      )
 * )
 */
    public function getById($car_id) {
        try {
            $data = Cars::findOrFail($car_id);
            $cars = Fractal::create()->item($data)->transformWith(CarsTransformer::class)->toArray();
            return response()->json($cars);
        } catch (ModelNotFoundException $e) {
            return ApiReturnedErrors::returnErrorArray('NOT_FOUND');
        }
    }



    /**
     * @OA\Put(
     *      path = "/api/cars/{car_id}",
     *      description = "Función que modifica un coche específico. Se deben recibir todos los elementos",
     *      tags = {"Cars"},
     *          @OA\Parameter(
     *              name="car_id",
     *              in="path",
     *              required=true,
     *              example=23,
     *                  @OA\Schema(
     *                      type="integer"
     *                  )
     *
     *          ),
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/CarInputPut")
     *          ),
     *     @OA\Response(
     *          response="200",
     *          description="Devuelve un Json, donde data es el nivel principal, que incluye el objeto Car modificado",
     *          @OA\JsonContent(ref="#/components/schemas/CarResponseItemFull")
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Devuelve un Json, de Error: Elemento no encontrado",
     *          @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *      )
     * )
     *
     *
     *
     *
     *
     */
    /**
     * Funcionalidad que modifica un Coche. Recibe todos los parámetros. Para modificar un parámetro utilizar Patch
     *
     * @param integer $car_id Identificador del coche
     * @param Request $request Request que viene del Body
     */
    public function putCar(Request $request, $car_id)
    {

        $request->merge(['car_id' => $car_id]);
        $this->validate($request, [
            'make' => ['required', 'bail', 'string'],
            'model' => ['required', 'bail', 'string','unique:cars,model,'.$car_id.',id'],
            'year' => ['required', 'bail', 'integer', 'min:1970','max:'.date('Y')],
            'car_id' => ['required', 'integer', 'exists:cars,id']
        ]);
        $obj = Cars::find($car_id);
        $obj->make = $request->make;
        $obj->model = $request->model;
        $obj->year = $request->year;
        $obj->save();
        $objOut = Fractal::create()->item($obj)->transformWith(CarsTransformer::class)->toArray();
        return response()->json($objOut);
    }


    /**
     * @OA\Patch(
     *      path = "/api/cars/{car_id}",
     *      description = "Función que modifica un coche específico. Se deben recibir todos los elementos",
     *      tags = {"Cars"},
     *          @OA\Parameter(
     *              name="car_id",
     *              in="path",
     *              required=true,
     *              example=23,
     *                  @OA\Schema(
     *                      type="integer"
     *                  )
     *
     *          ),
     *          @OA\RequestBody(
     *              required=false,
     *              @OA\JsonContent(ref="#/components/schemas/CarInputPut")
     *          ),
     *     @OA\Response(
     *          response="200",
     *          description="Devuelve un Json, donde data es el nivel principal, que incluye el objeto Car modificado",
     *          @OA\JsonContent(ref="#/components/schemas/CarResponseItemFull")
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Devuelve un Json, de Error: Elemento no encontrado",
     *          @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *      )
     * )
     *
     *
     *
     *
     *
     */
    /**
     * Funciónque modifica 1..n datos del Coche
     *
     *
     * @param integer $car_id Identificador del coche
     * @param Request $request Request que viene del Body
     */
    public function patchCar(Request $request, $car_id) {
        $request->merge(['car_id' => $car_id]);
        //dd($request->all());
        // Query parameters
        $this->validate($request, [
            'make' => ['required_without_all:model,year', 'string'],
            'model' => ['required_without_all:make,year', 'string','unique:cars,model,'.$car_id.',id'],
            'year' => ['required_without_all:make,model', 'integer', 'min:1','min:1970','max:'.date('Y')],
            'car_id' => ['required', 'integer', 'min:1', 'exists:cars,id']
        ]);
        $obj = Cars::find($car_id);
        if ($request->has('make')) $obj->make = $request->make;
        if ($request->has('model')) $obj->model = $request->model;
        if ($request->has('pages')) $obj->year = $request->year;
        $obj->save();
        $objOut = Fractal::create()->item($obj)->transformWith(CarsTransformer::class)->toArray();
        return response()->json($objOut);
    }




    /**
 * @OA\Delete(
 *     path="/api/cars/{car_id}",
 *
 *     description="Api que Elimina un coche existente, y devuelve el listado de los existentes",
 *      tags={"Cars","Swagger"},
 * @OA\Parameter(
 *      name="car_id",
 *      in="path",
 *      required=true,
 *      example=23,
 *      @OA\Schema(
 *          type="integer"
 *      )
 *
 * ),
 *
 *     @OA\Response(
 *          response="200",
 *          description="Devuelve un Json, donde data es el nivel principal, que incluye un Array con todos los Cars",
 *          @OA\JsonContent(ref="#/components/schemas/CarResponseList")
 *      ),
 *      @OA\Response(
 *          response="404",
 *          description="Devuelve un Json, de Error: Elemento no encontrado",
 *          @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
 *      )
 * )
 */
    /**
     * Función que dado un car_id lo elimina de la colección
     *
     * @param integer $car_id Identificador del coche
     *
     */
    public function deleteCar($car_id) {
        try {
            $data = Cars::findOrFail($car_id)->delete();


            return $this->getAll();
        } catch (ModelNotFoundException $e) {
            return ApiReturnedErrors::returnErrorArray('NOT_FOUND');
        }
    }

    /**
     * @OA\Post(
     *      path = "/api/cars",
     *      description = "Función POST que crea un Coche en la Colección",
     *      tags = {"Cars"},
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/CarInputPut")
     *          ),
     *     @OA\Response(
     *          response="200",
     *          description="Devuelve un Json, donde data es el nivel principal, que incluye el objeto Car Insertado",
     *          @OA\JsonContent(ref="#/components/schemas/CarResponseItemFull")
     *      ),
     *      @OA\Response(
     *          response="404",
     *          description="Devuelve un Json, de Error: Elemento no encontrado",
     *          @OA\JsonContent(ref="#/components/schemas/ResponseNotFound")
     *      )
     * )
     *
     *
     *
     *
     *
     */
    /**
     * POST Función API que inserta un coche dentro de la colección
     */
    public function postCar(Request $request) {
        $this->validate($request, [
            'make' => ['required', 'bail', 'string'],
            'model' => ['required', 'bail', 'string','unique:cars'],
            'year' => ['required', 'bail', 'integer', 'min:1','min:1970','max:'.date('Y')]
        ]);
        $obj = Cars::create($request->all());
        $objOut = Fractal::create()->item($obj)->transformWith(CarsTransformer::class)->toArray();
        return response()->json($objOut);
    }


}
