<?php

namespace App\Models;

use App\Enums\Analytics;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * @property string name
 * @property string impressions
 * @property string hovers
 * @property string clicks
 * @property string type
 * @property float hover_percentage
 * @property float click_percentage
 */
class PanAnalytics extends Model
{
    use HasFactory;

    protected $table = 'pan_analytics';

    protected $fillable = [
        'name',
        'impressions',
        'hovers',
        'clicks',
    ];

    public $timestamps = false;

    public array $rules = [
        'name' => 'required|unique:pan_analytics|max:255',
    ];

    public function type(): Attribute
    {
        return Attribute::make(
            get: function () {
                $analyticsFound = false;
                foreach (Analytics::cases() as $analytics) {
                    if( str($this->name)->startsWith($analytics->value . '-') && !$analyticsFound ){
                        $type = $analytics->value;
                        $analyticsFound = true;
                    }
                }
                if ($analyticsFound) {
                    return str($type ?? 'Other')->replace('-', ' ')->title();
                }
                return 'Other';
            });

    }

    protected function hoverPercentage(): Attribute
    {
        return Attribute::make(
            get: function (?string $value, array $attributes): float {
                return $attributes['impressions'] > 0
                    ? round(($attributes['hovers'] / $attributes['impressions']) * 100, 1)
                    : 0;
            }
        );
    }

    protected function clickPercentage(): Attribute
    {
        return Attribute::make(
            get: function (?string $value, array $attributes): float {
                return $attributes['impressions'] > 0
                    ? round(($attributes['clicks'] / $attributes['impressions']) * 100, 1)
                    : 0;
            }
        );
    }

    public static function boot(): void
    {
        parent::boot();

        static::saving(function (self $model): void {
            Validator::make($model->toArray(), $model->rules)->validate();
        });
    }
}
