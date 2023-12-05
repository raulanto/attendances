<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'language' => 'es-Es',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            //peneee 
            'cookieValidationKey' => 'ddytytdddtyyfgigggiuguiuigiugiguggpene',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession'   => false,
            'loginUrl'        => null,
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            'on afterLogin' => function ($event) {
                \webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
            }
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            // 'enableStrictParsing' => true,
            //yo no debo mostrar los script de mi proyecto 
            'showScriptName' => false,
            //reglas de mi url para controlador 
            'rules' => [
                //primera regla para el primer controlador 
                ['class' => 'yii\rest\UrlRule', 'controller' => 'answer', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'attendance', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'code', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'degree', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'extracurricular', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'extra-person', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'grade', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'person', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'question', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'tag', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'teacher', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'classroom', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'group', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'listg'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'library', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'subject', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'major', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'subject-major', 'pluralize' => false],
                //reglas para buscar total
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'listg/buscar/<text:[\w\-]+>/<id:\d+>',
                    'route' => 'listg/buscar',
                    'defaults' => ['id' => null],
                ],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'listg/total/<text:[\w\-]+>/<id:\d+>', 'route' => 'listg/total'],
                //buscar total library
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'library/buscar/<text:[\w\-]+>/<id:\d+>',
                    'route' => 'library/buscar',
                    'defaults' => ['id' => null],
                ],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'library/total/<text:[\w\-]+>/<id:\d+>', 'route' => 'library/total'],
                //buscar total question
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'question/buscar/<text:[\w\-]+>/<id:\d+>',
                    'route' => 'question/buscar',
                    'defaults' => ['id' => null],
                ],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'question/total/<text:[\w\-]+>/<id:\d+>', 'route' => 'question/total'],
                //Regla para la funcion que trae la lista de un cierto grupo
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'listg',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>'
                    ],
                    'extraPatterns' => [
                        'GET listas/{id}' => 'listas'
                    ],
                ],
                //Regla que trae el detalle de asistencia de un cierto fklist 
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'attendance',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>'
                    ],
                    'extraPatterns' => [
                        'GET asistencias/{id}' => 'asistencias',
                        'POST guardar' => 'guardar'
                    ],
                ],
                //Regla para traer todos los codigos de un grupo especifico
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'code',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'GET codigos/{id}' => 'codigos',
                        'POST generar' => 'generar'
                    ],
                ],
                //Regla para traer todos los archivos de un grupo especifico
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'library',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>',
                        

                    ],
                    'extraPatterns' => [
                        'GET librarys/{id}' => 'librarys'
                    ],
                ],
                //Regla para buscar en listg
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'listg',
                    'tokens' => [
                        '{id}' => '<id:\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'GET buscar/{text}' => 'buscar',
                        'GET total/{text}' => 'total',
                    ],
                ],
                //Regla para buscar en library
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'library',
                    'tokens' => [
                        '{id}' => '<id:\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'GET buscar/{text}' => 'buscar',
                        'GET total/{text}' => 'total',
                    ],
                ],
                //Regla para buscar en question
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'question',
                    'tokens' => [
                        '{id}' => '<id:\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'GET buscar/{text}' => 'buscar',
                        'GET total/{text}' => 'total',
                    ],
                ],
                //Regla para teacher
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'teacher',
                    'tokens' => [
                        '{id}' => '<id:\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST registrar' => 'registrar',
                    ],
                ],
                //Regla para teacher
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'person',
                    'tokens' => [
                        '{id}' => '<id:\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST registrar' => 'registrar',
                    ],
                ],
                
            ],
        ]
    
    ],
    'modules' => [
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',
            'on beforeAction' => function(yii\base\ActionEvent $event) {
                if ($event->action->uniqueId === 'user-management/auth/login') {
                    $event->action->controller->layout = 'loginLayout.php';
                }
            },
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
