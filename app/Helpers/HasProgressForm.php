<?php

namespace App\Helpers;

use Illuminate\Support\Str;

trait HasProgressForm
{
    private function getData($step){
        return \Arr::mapWithKeys(self::getValidationRules($step), function ($value, $key){
            return [$key => null];
        });
    }

    private function populateValidationRules($step){
        $validation_rules = [];
        foreach (range(0, $step) as $key => $value)
            $validation_rules = array_merge($validation_rules, $this->getValidationRules($key));
        return $validation_rules;
    }
    private function validateForm($step, $steps_count, $model, $web_form, $route_name, $attributes=[]){
        $cache_key = session()->getId() . $model->slug . "-form-data";
        $previous_data = \Cache::get($cache_key, []);
        $data = array_merge(self::getData($step), $previous_data, \request()->all());

        if ($step > 1 && \Validator::make($previous_data,  $this->populateValidationRules($step - 1))->fails())
            return redirect()->route($route_name . '.apply-step-' . $step-1, $attributes);

        if (\request()->method() == "POST"){
            $data = \Validator::make(
                $data,
                $this->populateValidationRules($step)
            )->validate();
            if ($step < $steps_count) {
                \Cache::set($cache_key, $data, 5 * 60);
                return redirect()->route($route_name . '.apply-step-' . $step + 1, $attributes);
            }else {
                $this->saveForm($cache_key, $data, $web_form, $model);
                return redirect()->route($route_name . '.apply-step-1', $attributes)->with('success', __("site.Application Has Been Submitted Successfully"));
            }
        }
        return $this->view(ucfirst($route_name) .'.apply-form-stage-' . $step, array_merge($attributes, $data));
    }

    private function saveForm($cache_key, $data, $web_form, $model){
        \Cache::forget($cache_key);
        unset($data['g-recaptcha-response']);
        foreach ($data as $key => $value) {
            if (request()->hasFile($key))
                $data[$key] = request()->file($key)->store(options: 'forms');
        }
        $data[Str::singular($model->getTable()) . '_id'] = $model->id;
        $form = new $web_form();
        $form->fill($data);
        $form->save();
    }

    private function getValidationRules($step){}
}
