<?php

namespace App\Helpers;


use Carbon\Carbon;
use DateTimeInterface;

trait HasTimestamps
{
    public function publishedAt(): string
    {
        $months = [
            1 => 'يناير',
            2 => 'فبراير',
            3 => 'مارس',
            4 => 'أبريل',
            5 => 'مايو',
            6 => 'يونيو',
            7 => 'يوليو',
            8=> 'أغسطس',
            9 => 'سبتمبر',
            10 => 'أكتوبر',
            11 => 'نوفمبر',
            12 => 'ديسمبر'
        ];
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->published_at);
        return preg_replace('/ [a-zA-Z]* /m', ' ' . $months[$date->month] . ' ', $date->format('d M, Y'));
    }

    public static function bootHasTimestamps(){
        static::creating(function ($model) {
            $model->published_at ??= Carbon::now();
        });
    }

    public function formatDate($attribute, $format){
        $months = [
            1 => 'يناير',
            2 => 'فبراير',
            3 => 'مارس',
            4 => 'أبريل',
            5 => 'مايو',
            6 => 'يونيو',
            7 => 'يوليو',
            8=> 'أغسطس',
            9 => 'سبتمبر',
            10 => 'أكتوبر',
            11 => 'نوفمبر',
            12 => 'ديسمبر'
        ];
        $date = Carbon::parse($this->$attribute);
        if (app()->getLocale() == 'ar')
            return preg_replace('/ [a-zA-Z]* /m', ' ' . $months[$date->month] . ' ', $date->format($format));
        return $date->format($format);
    }

    public function publishedAtForHumans(): string
    {
        if (app()->getLocale() == "ar")
            return $this->publishedAt();
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->published_at)->format('d M Y');
    }

    public function publishedAtForHumans2(){
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->published_at)->format('M d, Y');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
