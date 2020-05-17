<?php
namespace ArcHive\Dashboard\Actions;

use ArcHive\Api\Database\Tables\ReportsTable;
use ArcHive\Session\OAuth;
use DateTime;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TurboPancake\Actions\Simplifier;
use TurboPancake\Renderer\RendererInterface;
use TurboPancake\Router;
use TurboPancake\Services\Session\SessionInterface;
use function DI\string;

class DashboardAction implements MiddlewareInterface {

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var ReportsTable
     */
    private $reportsTable;

    /**
     * @var SessionInterface
     */
    private $session;

    use Simplifier;
    use Router\RouterAware;

    public function __construct(
        RendererInterface $renderer,
        Router $router,
        ReportsTable $reportsTable,
        SessionInterface $session
    ) {
        $this->renderer = $renderer;
        $this->router = $router;
        $this->reportsTable = $reportsTable;
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $token = $this->session['auth.token'];
        $user = OAuth::getUser($token);
        if ($user === null) {
            return $this->temporaryRedirect('auth.login');
        }

        if ((bool)($request->getQueryParams()['download']??false) === true) {
            $reports = $this->reportsTable->findAll()->fetchAll();
            $content = "Date; Température INT; Température EXT; Humidité INT; Humidité EXT; Présence";
            foreach ($reports as $report) {
                $date = ucwords(strftime('%A %e %B %Y %T', strtotime($report->created_at)));
                $content .= "\n";
                $content .= sprintf(
                    "%s; %f; %f; %f; %f; %d",
                    $date,
                    $report->inside_temperature,
                    $report->outside_temperature,
                    $report->inside_humidity,
                    $report->outside_humidity,
                    $report->someone_present,
                );
            }

            return new Response(200, [
                'Cache-Control' => 'no-cache',
                'Content-Description' => 'File Transfer',
                'Content-Disposition' => 'attachment; filename=data.csv',
                'Content-Type' => 'text/csv',
            ], utf8_decode($content));
        }

        $lastReport = $this->reportsTable->getLastReport();

        $_reports = $this->reportsTable->getLast14Reports();
        $reports = [];
        foreach ($_reports as $report) {
            $reports[] = [
                'date' => strftime('%e %b %Hh%M', strtotime($report->created_at)),
                'datas' => $report
            ];
        }
        $reports = array_reverse($reports);

        return $this->stringResponse($this->renderer->render(
            '@dashboard/home',
            compact('lastReport', 'reports', 'user')
        ));
    }
}