<?php

namespace App\Transformer;

use App\Models\Cars;

use League\Fractal\TransformerAbstract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


/**
 * @OA\Schema(
 *      schema="CarResponseList",
 *      type="object",
 *      title="CarResponseList",
 *      properties={
 *          @OA\Property(property="data",type="array",
 *              @OA\Items(ref="#/components/schemas/CarResponse")
 *          )
 *      },
 * )
 */


 /**
  * @OA\Schema(
  *     schema="CarResponseItemFull",
    *   type="object",
    *    title="CarResponseItemFull",
    *   properties={
    *    @OA\Property(property="data",type="object",description="Main Level",
    *           properties={
    *               @OA\Property(property="id",type="integer",description="ID en la BBDD",example="2"),
 *          @OA\Property(property="make",type="string",description="Marca del Coche",example="BMW"),
 *          @OA\Property(property="model",type="string",description="Modelo de coche", example="E91 330d"),
 *          @OA\Property(property="year",type="integer",description="Año de fabricación", example="2005"),
 *          @OA\Property(property="links",type="object",description="Links adicionales",
 *              properties={
 *                  @OA\Property(property="status",type="string",description="Estado del Coche",example="created"),
 *                  @OA\Property(property="url",type="string",description="Url para acceder al coche",example="/api/cars/2")
 *
 *              }),
 *
    *           }
    *   )
    * }
  *)
  */


/**
 * @OA\Schema(
 *      schema="CarResponse",
 *      type = "object",
 *      title="CarResponse",
 *      properties = {
 *          @OA\Property(property="id",type="integer",description="ID en la BBDD",example="2"),
 *          @OA\Property(property="make",type="string",description="Marca del Coche",example="BMW"),
 *          @OA\Property(property="model",type="string",description="Modelo de coche", example="E91 330d"),
 *          @OA\Property(property="year",type="integer",description="Año de fabricación", example="2005"),
 *          @OA\Property(property="links",type="object",description="Links adicionales",
 *              properties={
 *                  @OA\Property(property="status",type="string",description="Estado del Coche",example="created"),
 *                  @OA\Property(property="url",type="string",description="Url para acceder al coche",example="/api/cars/2")
 *
 *              }),
 *
 *
 *      }
 * )
 */


/**
 *  @OA\Schema(
 *      schema="CarInputPut",
 *      type="object",
 *      title="CarInputPut",
 *
 *      properties={
 *          @OA\Property(property="make",type="string",description="Marca del Coche",example="BMW"),
 *          @OA\Property(property="model",type="string",description="Modelo de coche", example="E91 330d"),
 *          @OA\Property(property="year",type="integer",description="Año de fabricación", example="2005")
 *      }
 * )
 */

 /**
 *  @OA\Schema(
 *      schema="Cars/CarInputPutTest",
 *      type="object",
 *      title="Cars/CarInputPutTest",
 *
 *      properties={
 *          @OA\Property(property="make",type="string",description="Marca del Coche",example="BMW"),
 *          @OA\Property(property="model",type="string",description="Modelo de coche", example="E91 330d"),
 *          @OA\Property(property="year",type="integer",description="Año de fabricación", example="2005")
 *      }
 * )
  */


class CarsTransformer extends TransformerAbstract {

    public function transform(Cars $car) {
        return [
            'id' => (int)$car->id,
            'make' => trim($car->make),
            'model' => trim($car->model),
            'year' => (int)$car->year,
            //'created_at' => $book->created_at,
            'created_at' => Str::substr($car->created_at,0,10),
            'links' => [
                'status' => 'created',
                'url' => '/api/cars/'.(int)$car->id
            ]
        ];
    }

}
