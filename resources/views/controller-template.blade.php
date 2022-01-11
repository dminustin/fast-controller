

declare(strict_types=1);

namespace {{ $nameSpace }};

use App\Http\Controllers\BaseFastController as BaseController;
use Illuminate\Http\Request;

class {{ $className }} extends BaseController
{
    protected $middleware = [{!! $middleware  !!}] ;
    protected $rules = [{!! $rules !!}];

    public function handle(Request $request)
    {
        $this->prepare($request);
        //do something with $this->data
        //...
    }
}
