<?php


namespace Catcoderphp\CustomConfigProvider\Bootstrap;

use Catcoderphp\CustomConfigProvider\Service\ResponseHandlerService;
use Laminas\Mvc\Application;
use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\ServiceManager\ServiceManager;

class ErrorHandler
{
    public static function handle(MvcEvent $event,ServiceManager $serviceManager)
    {
        $responseHandler = $serviceManager->get(ResponseHandlerService::class);
        $err = $event->getError();
        if ($exception = $event->getParam("exception")) {
            $responseHandler->exception("Fatal error", $exception);
            return $responseHandler->toJsonModel();
        }
        /**
         * @todo Catch all laminas error
         */
        switch ($err) {
            case "error-router-no-match":
                $responseHandler->notFound("Route not found");
                break;
            default:
                //fix to error more explicit
                $responseHandler->forbidden("unknow error");
        }

        return $responseHandler->toJsonModel();
    }

    /**
     * handling listeners to mvc application
     * @param MvcEvent $mvcEvent
     */
    public static function bootstrap(MvcEvent $mvcEvent)
    {
        $eventManager = $mvcEvent->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sharedManager = $mvcEvent->getApplication()->getEventManager()->getSharedManager();
        $sm = $mvcEvent->getApplication()->getServiceManager();
        $sharedManager->attach(
            Application::class,
            'dispatch.error',
            function ($e) use ($mvcEvent, $sm) {
                $data = ErrorHandler::handle($mvcEvent,$sm);
                $e->setViewModel($data);
            }
        );
    }
}