@verbatim
class Controller
{
    public function __invoke(): Response
    {
        return response()->noContent();
    }
}
@endverbatim
