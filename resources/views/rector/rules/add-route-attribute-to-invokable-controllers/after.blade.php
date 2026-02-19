@verbatim
use Support\Routing\Attributes\Route;
use Support\Routing\Enums\Method;

class Controller
{
    #[Route(name: 'controller', uri: '/', methods: Method::Get)]
    public function __invoke(): Response
    {
        return response()->noContent();
    }
}
@endverbatim
