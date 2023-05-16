<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      // return parent::toArray($request);
       return [
           'id'=>$this->id ,
           'name' =>$this->name,
           'image'=>$this->image,
           'Ex'=>$this->Ex,
           'type'=>$this->type,
           'number'=>$this->number,
           'qu'=>$this->qu,
           'price'=>$this->price,
           'price_offer'=>$this->price_offer,


           ];
    }
}
