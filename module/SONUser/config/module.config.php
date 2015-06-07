<?php

namespace SONUser;

return array(    
    'router' => array(
        'routes' => array(
            'sonuser-register' => array(
                'type' => 'Literal',// Significa que e o caminho real
                'options' => array(
                    'route' => '/register',// Rota padrão ex: http://localhost:8080/register
                    'defaults' => array(
                        '__NAMESPACE__' => 'SONUser\Controller',// Nesse casso e esse mesmo SONUser\Controller por ser literal
                        'controller' => 'Index',// Nome do controller
                        'action' => 'register',//Nome da função dentro da controller
                    ),
                )
            ),
            // Rota criada para a ativação do usuario por email usada na mailer/add-user.phml
            'sonuser-activate' =>array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/register/activate[/:key]',
                    'defaults' => array(
                        'controller' => 'SONUser\Controller\Index',
                        'action' => 'activate'
                    )
                )
            ),
            // Rota administrativa
            'sonuser-admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'SONUser\Controller',
                        'controller' => 'Users',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/:action[/:id]]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '\d+'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'SONUser\Controller',
                                'controller' => 'users'
                            )
                        )
                    ),
                    'paginator' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/[:controller[/page/:page]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'page' => '\d+'
                            ),
                            'defaults' => array(
                                '__NAMESPACE__' => 'SONUser\Controller',
                                'controller' => 'users'
                            )
                        )
                    )
                )
            )            
        )
    ),
    'controllers' => array( // Controllers do modulo
        'invokables' => array( // Controllers que serão invocados para ser utilizado no modulo apelido => caminho real
            'SONUser\Controller\Index' => 'SONUser\Controller\IndexController',            
            'SONUser\Controller\Users' => 'SONUser\Controller\UsersController',            
        )
    ),
////////////////////////////////////////////////////////////////////////////////
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
// Configuração padrao para o doctrine funcionar ///////////////////////////////
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
        // Para inserir dados de teste
        'fixture' => array(
            'SONUser_fixture' => __DIR__ . '/../src/SONUser/Fixture',
        ),
    ),
    
);