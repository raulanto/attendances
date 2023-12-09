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
                ['class' => 'yii\rest\UrlRule', 'controller' => 'extra-group', 'pluralize' => false],

                ['class' => 'yii\rest\UrlRule', 'controller' => 'grade', 'pluralize' => false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'grade-person', 'pluralize' => false],
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

                //REGLAS DE KRYSS
                ['class' => 'yii\web\UrlRule', 'pattern' => 'extracurricular/buscar/<text:.*>', 'route' => 'extracurricular/buscar'],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'extracurricular/total/<text:.*>', 'route' => 'extracurricular/total'],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'extracurricular/buscar-todos/<text:.*>', 'route' => 'extracurricular/buscar-todos'],

                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'extra-group/extragroups/<id:\d+>',
                    'route' => 'extra-group/extragroups',
                    'defaults' => ['text' => null],
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'extra-group/buscar/<text:\w+>',
                    'route' => 'extra-group/buscar',
                ],
                [
                    'class' => 'yii\web\UrlRule', 
                    'pattern' => 'grade/buscar', 
                    'route' => 'grade/buscar'
                ],                
                ['class' => 'yii\web\UrlRule', 'pattern' => 'grade/total/<text:.*>', 'route' => 'grade/total'],

                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'extragroup',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>'
                    ],
                    'extraPatterns' => [
                        'GET extragroups/{id}' => 'extragroups'
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'grade',
                    'tokens' => [
                        '{id}' => '<id:\\d[\\d,]*>',
                    ],
                    'extraPatterns' => [
                        'GET grades/{id}' => 'grades',
                    ],
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'grade',
                    'tokens' => [
                        '{id}' => '<id:\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'GET buscar/{text}' => 'buscar',
                        'GET total/{text}' => 'total',
                    ],
                ],
                //MOD----------
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'extracurricular',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>',
                        '{text}' => '<text:\\w+>'
                    ],
                    'extraPatterns' => [
                        'GET buscar/{text}' => 'buscar',
                        'GET total' => 'id',
                        'GET buscar-todos' => 'buscar-todos',
                    ],
                ],
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'grade-person',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>'
                    ],
                    'extraPatterns' => [
                        'GET gradesp/{id}' => 'gradesp'
                    ],
                ],

                //REGLAS DE MONICA
                //buscar total classroom
                [
                    'class' => 'yii\web\UrlRule', 
                    'pattern' => 'classroom/buscar/<text:.*>', 
                    'route' => 'classroom/buscar'
                ],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'classroom/total/<text:.*>', 'route' => 'classroom/total'],

                //buscar total library
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'library/buscar/<text:[\w\-]+>/<id:\d+>',
                    'route' => 'library/buscar',
                    'defaults' => ['id' => null],
                ],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'library/total/<text:[\w\-]+>/<id:\d+>', 'route' => 'library/total'],
                //Regla para traer todos los archivos de un grupo especifico
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'library',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>'
                    ],
                    'extraPatterns' => [
                        'GET librarys/{id}' => 'librarys'
                    ],
                ],
                //Regla para buscar en classroom
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'classroom',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>',
                        '{text}'      => '<text:\\w+>'
                    ],
                    'extraPatterns' => [
                        'GET buscar/{text}' => 'buscar',
                        'GET total' => 'id',
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
                //Regla para traer todos los grupo de una persona especifico
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'group',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'GET grupos/{id}' => 'grupos'
                    ],
                ],                                   

                //REGLAS RAUL
                //reglas para buscar total
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'listg/buscar/<text:[\w\-]+>/<id:\d+>',
                    'route' => 'listg/buscar',
                    'defaults' => ['id' => null],
                ],
                ['class' => 'yii\web\UrlRule', 'pattern' => 'listg/total/<text:[\w\-]+>/<id:\d+>', 'route' => 'listg/total'],

                //Regla para la funcion que trae la lista de un cierto grupo
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'listg',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>'
                    ],
                    'extraPatterns' => [
                        'GET listas/{id}' => 'listas',
                        'GET grupop/{id}' => 'grupop',
                        'GET contar/{id}' => 'contar'
                    ],
                ],
                //Regla que trae el detalle de asistencia de un cierto fklist 
                [
                    'class'      => 'yii\rest\UrlRule',
                    'controller' => 'attendance',
                    'tokens' => [
                        '{id}'        => '<id:\\d[\\d,]*>',
                        '{text}' => '<text:\w+>',
                        '{text}' => '<text:\w+>',
                    ],
                    'extraPatterns' => [
                        'GET asistencias/{id}' => 'asistencias',
                        'POST guardar/{text}/{id}/{text}' => 'guardar',
                        'GET total/{id}' => 'total'
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
                        'POST generar' => 'generar',
                        'PUT update' => 'update'
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
                        'GET gruposp/{text}' => 'gruposp',
                        'GET listatodos/{text}' => 'listatodos'
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


                //REGLAS ARMANDO
                //buscar total question
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'question/total/<text:[\w\-]+>/<id:\d+>',
                    'route' => 'question/total'
                ],
                [
                    'class' => 'yii\web\UrlRule',
                    'pattern' => 'question/buscar/<text:[\w\-]+>/<id:\d+>',
                    'route' => 'question/buscar',
                    'defaults' => ['id' => null],
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
                        'GET qmaestro/{text}' => 'qmaestro',
                        'GET qperson/{text}' => 'qperson',

                    ],
                ],
                //Regla para gradeperson
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'grade-person',
                    'tokens' => [
                        '{id}' => '<id:\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'POST guardar' => 'guardar',

                    ],
                ],
                //Regla para grade
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'grade',
                    'tokens' => [
                        '{id}' => '<id:\d[\\d,]*>',
                        '{text}' => '<text:\w+>'
                    ],
                    'extraPatterns' => [
                        'POST guardar' => 'guardar',

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
