Route::{{ $method }}('{{ $uri }}', {{ $className }}::class, 'handle')->name('{{ strtolower(join('_', $pathArray)) }}');
