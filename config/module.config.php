<?php
namespace Catcoderphp\CustomConfigProvider;
return [
    'service_manager' => [
        'factories' => [
            "Catcoder\ZendCSHandlers\Service\ResponseHandlerService" => \Catcoderphp\ZendCSHandlers\Factory\Service\ResponseHandlerServiceFactory::class
        ]
    ],
    'invokables' => [

    ],
    'aliases' => [

    ],
    'initializers' => [
        
    ]

];
