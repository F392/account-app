<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlphaRul;

class BillRequest extends FormRequest
{
    public $key;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cash_bill'.$this->key => [new AlphaRul, 'nullable'],
            'credit_bill'.$this->key => [new AlphaRul, 'nullable'],
            'kake_bill'.$this->key => [new AlphaRul, 'nullable'],
            "intax_bill".$this->key => [new AlphaRul, 'required'],
            "notax_bill".$this->key => [new AlphaRul, 'required'],
            'mukei_bill'.$this->key => [new AlphaRul,'nullable'],
        ];
    }

    //バリデーション前にデータを整形
    protected function prepareForValidation()
    {
        //どの階層のボタンを押したか
        $this->key = $this->get('button');
        // データを受け取る
        $inputs = [
            'bill_id'.$this->key => $this->get('bill_id' . $this->key),
            'crew_id'.$this->key => $this->get('crew_id' . $this->key),
            'cash_bill'.$this->key => $this->get('cash_bill' . $this->key),
            'credit_bill'.$this->key => $this->get('credit_bill' . $this->key),
            'kake_bill'.$this->key => $this->get('kake_bill' . $this->key),
            "intax_bill".$this->key => $this->get('intax_bill' . $this->key),
            "notax_bill".$this->key => $this->get('notax_bill' . $this->key),
            'mukei_crew' => $this->get('mukei_crew' . $this->key),
            'mukei_bill' => $this->get('mukei_bill' . $this->key),
            'key'=>$this->key
        ];
        //requestデータを書き換える
        $this->replace($inputs);

    }
}
