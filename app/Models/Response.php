<?php

namespace App\Models;


use Illuminate\Support\Str;
use Jenssegers\Mongodb\Eloquent\Model;

class Response extends Model
{
    const PRIVATE_STATUS = 1;
    const PUBLIC_STATUS = 2;

    public function set()
    {
        $this->response = request('json');
        $this->encoded = json_encode(request('json'));
        $this->status = self::PUBLIC_STATUS;
        $this->url = self::urlGenerator();
        $this->uuid = self::uuidGenerator();
        $this->save();
    }

    public function scopeIsDuplicate()
    {
        return Response::where('encoded', json_encode(request('json')))->exists();
    }

    public function scopeOfJson()
    {
        return Response::where('encoded', json_encode(request('json')))->first();
    }

    public function scopeGetWithResponse($response)
    {

    }

    /**
     * @return string
     */
    public static function urlGenerator(): string
    {
        $url = Str::random(15);
        if (Response::where('url', $url)->exists()) {
            return self::urlGenerator();
        }
        return $url;
    }

    /**
     * @return string
     */
    public static function uuidGenerator(): string
    {
        return Str::uuid();
    }

    /**
     * set url for routeKeyName in response model
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'url';
    }
}
