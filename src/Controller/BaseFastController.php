<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class BaseFastController extends Controller
{
    protected $rules = [];

    protected $data = [];

    public function prepare(Request $request)
    {
        if (!empty($this->parameters)) {
            $this->data = $this->validate($request, $this->rules);
        }
    }

    abstract public function handle(Request $request);
}
